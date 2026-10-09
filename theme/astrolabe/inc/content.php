<?php
/**
 * Editable lists of the front page, under the "Site content" menu.
 *
 * ACF free has no Repeater, so each repeating item (a feature, a privacy point,
 * a plan, a question) is its own post; the order is the "Order" field. The post
 * types are ACF post types (acf-json/post_type_astro_*.json) whose admin menu
 * parent is this page. Until any item exists, the front page shows the
 * defaults from inc/defaults.php.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

/** The editable lists: key => [plural, singular]. Post type = "astro_{key}". */
function astrolabe_list_types(): array {
	return array(
		'feature' => array( __( 'Features', 'astrolabe' ), __( 'Feature', 'astrolabe' ) ),
		'trust'   => array( __( 'Privacy points', 'astrolabe' ), __( 'Privacy point', 'astrolabe' ) ),
		'plan'    => array( __( 'Plans', 'astrolabe' ), __( 'Plan', 'astrolabe' ) ),
		'faq'     => array( __( 'Questions', 'astrolabe' ), __( 'Question', 'astrolabe' ) ),
	);
}

/* ---------------- "Site content" menu ---------------- */

add_action(
	'admin_menu',
	static function (): void {
		add_menu_page(
			__( 'Site content', 'astrolabe' ),
			__( 'Site content', 'astrolabe' ),
			'edit_pages',
			'astrolabe-content',
			'astrolabe_content_page',
			'dashicons-star-filled',
			21
		);
	},
	9 // before the post types add themselves under it
);

function astrolabe_content_page(): void {
	$front = (int) get_option( 'page_on_front' );
	echo '<div class="wrap"><h1>' . esc_html__( 'Site content', 'astrolabe' ) . '</h1>';

	if ( isset( $_GET['seeded'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		echo '<div class="notice notice-success"><p>' . esc_html__( 'Editable copies created. Edit them in the lists below.', 'astrolabe' ) . '</p></div>';
	}

	echo '<p>' . esc_html__( 'Texts of the front-page sections are edited on the front page itself (Pages → the page set as homepage, fields below the editor). The lists below hold the repeating items; drag order with the "Order" field.', 'astrolabe' ) . '</p>';

	if ( ! $front ) {
		echo '<div class="notice notice-warning inline"><p>' . esc_html__( 'No static homepage is set (Settings → Reading). The front page shows the default texts until one is set.', 'astrolabe' ) . '</p></div>';
	} elseif ( current_user_can( 'edit_post', $front ) ) {
		printf( '<p><a class="button" href="%s">%s</a></p>', esc_url( get_edit_post_link( $front ) ), esc_html__( 'Edit front-page texts', 'astrolabe' ) );
	}

	if ( ! post_type_exists( 'astro_feature' ) ) {
		echo '<div class="notice notice-warning inline"><p>' . esc_html__( 'Advanced Custom Fields is not active, so the lists and the extra fields cannot be edited. The site still shows everything with the default texts.', 'astrolabe' ) . '</p></div></div>';
		return;
	}

	echo '<table class="widefat striped" style="max-width:720px"><tbody>';
	$missing = false;
	foreach ( astrolabe_list_types() as $key => list( $plural ) ) {
		$count    = (int) ( wp_count_posts( 'astro_' . $key )->publish ?? 0 );
		$missing  = $missing || 0 === $count;
		$state    = $count
			? sprintf( /* translators: %d: number of items */ _n( '%d item', '%d items', $count, 'astrolabe' ), $count )
			: __( 'showing the defaults', 'astrolabe' );
		printf(
			'<tr><td><a href="%s"><strong>%s</strong></a></td><td>%s</td></tr>',
			esc_url( admin_url( 'edit.php?post_type=astro_' . $key ) ),
			esc_html( $plural ),
			esc_html( $state )
		);
	}
	echo '</tbody></table>';

	if ( $missing && current_user_can( 'manage_options' ) ) {
		echo '<form method="post" action="' . esc_url( admin_url( 'admin-post.php' ) ) . '" style="margin-top:16px">';
		echo '<input type="hidden" name="action" value="astrolabe_seed">';
		wp_nonce_field( 'astrolabe_seed' );
		submit_button( __( 'Create editable copies of the defaults', 'astrolabe' ), 'primary', 'submit', false );
		echo '<p class="description">' . esc_html__( 'Only for lists that are still empty. Afterwards the front page shows the posts, not the defaults.', 'astrolabe' ) . '</p>';
		echo '</form>';
	}
	echo '</div>';
}

add_action(
	'admin_post_astrolabe_seed',
	static function (): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Not allowed.', 'astrolabe' ), 403 );
		}
		check_admin_referer( 'astrolabe_seed' );
		astrolabe_seed_lists();
		wp_safe_redirect( admin_url( 'admin.php?page=astrolabe-content&seeded=1' ) );
		exit;
	}
);

/**
 * Creates the default items as posts, for every list that is still empty.
 * Returns how many posts were created.
 */
function astrolabe_seed_lists(): int {
	$created  = 0;
	$defaults = astrolabe_defaults();
	foreach ( array_keys( astrolabe_list_types() ) as $key ) {
		if ( ! post_type_exists( 'astro_' . $key ) || (int) ( wp_count_posts( 'astro_' . $key )->publish ?? 0 ) > 0 ) {
			continue;
		}
		foreach ( $defaults[ $key ] as $order => $item ) {
			$id = wp_insert_post(
				array(
					'post_type'    => 'astro_' . $key,
					'post_status'  => 'publish',
					'post_title'   => $item['title'],
					'post_content' => $item['answer'] ?? '',
					'menu_order'   => $order + 1,
				),
				true
			);
			if ( is_wp_error( $id ) ) {
				continue;
			}
			++$created;
			foreach ( $item as $field => $value ) {
				if ( in_array( $field, array( 'title', 'answer' ), true ) ) {
					continue;
				}
				update_post_meta( $id, $field, is_bool( $value ) ? (int) $value : $value );
			}
		}
	}
	return $created;
}

/* Show the order in the lists, and sort by it. */
foreach ( array( 'feature', 'trust', 'plan', 'faq' ) as $astrolabe_key ) {
	add_filter(
		"manage_astro_{$astrolabe_key}_posts_columns",
		static function ( array $columns ): array {
			$columns['menu_order'] = __( 'Order', 'astrolabe' );
			return $columns;
		}
	);
	add_action(
		"manage_astro_{$astrolabe_key}_posts_custom_column",
		static function ( string $column, int $post_id ): void {
			if ( 'menu_order' === $column ) {
				echo (int) get_post_field( 'menu_order', $post_id );
			}
		},
		10,
		2
	);
}

add_action(
	'pre_get_posts',
	static function ( WP_Query $query ): void {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}
		$type = (string) $query->get( 'post_type' );
		if ( str_starts_with( $type, 'astro_' ) && 'astro_beta_request' !== $type && ! $query->get( 'orderby' ) ) {
			$query->set( 'orderby', 'menu_order title' );
			$query->set( 'order', 'ASC' );
		}
	}
);
