<?php
/**
 * Beta requests: the front-page form, stored as private posts that only
 * administrators can see, with an email to the operator for each one.
 *
 * No form plugin and no third party: the request stays in this site's
 * database. Spam is kept out with a hidden field, a minimum fill time and a
 * per-connection limit. The WordPress privacy tools (Tools → Export / Erase
 * Personal Data) find requests by email address.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

const ASTROLABE_BETA_TYPE  = 'astro_beta_request';
const ASTROLABE_BETA_LIMIT = 5; // requests per connection per hour

add_action(
	'init',
	static function (): void {
		$admin_only = array_fill_keys(
			array( 'edit_post', 'read_post', 'delete_post', 'edit_posts', 'edit_others_posts', 'read_private_posts', 'edit_private_posts', 'edit_published_posts', 'delete_posts', 'delete_private_posts', 'delete_others_posts', 'delete_published_posts' ),
			'manage_options'
		);
		register_post_type(
			ASTROLABE_BETA_TYPE,
			array(
				'labels'          => array(
					'name'          => __( 'Beta requests', 'astrolabe' ),
					'singular_name' => __( 'Beta request', 'astrolabe' ),
					'edit_item'     => __( 'Beta request', 'astrolabe' ),
					'not_found'     => __( 'No requests yet.', 'astrolabe' ),
				),
				'public'          => false,
				'show_ui'         => true,
				'show_in_rest'    => false,
				'menu_icon'       => 'dashicons-email-alt',
				'menu_position'   => 22,
				'supports'        => false,
				'capability_type' => ASTROLABE_BETA_TYPE,
				'map_meta_cap'    => false,
				'capabilities'    => $admin_only + array(
					'create_posts'  => 'do_not_allow',
					'publish_posts' => 'do_not_allow',
				),
			)
		);
	}
);

/** Request statuses an administrator can set. */
function astrolabe_beta_statuses(): array {
	return array(
		'new'      => __( 'New', 'astrolabe' ),
		'invited'  => __( 'Invited', 'astrolabe' ),
		'waiting'  => __( 'Later', 'astrolabe' ),
		'declined' => __( 'Not now', 'astrolabe' ),
	);
}

/* ---------------- Receiving the form ---------------- */

add_action( 'admin_post_nopriv_astrolabe_beta', 'astrolabe_beta_receive' );
add_action( 'admin_post_astrolabe_beta', 'astrolabe_beta_receive' );

function astrolabe_beta_receive(): void {
	$back = static function ( string $result ): void {
		wp_safe_redirect( add_query_arg( 'beta', $result, home_url( '/' ) ) . '#beta' );
		exit;
	};

	// phpcs:disable WordPress.Security.NonceVerification.Missing -- public form; a nonce in a cached page would expire.
	$post = wp_unslash( $_POST );

	// Bots fill the hidden field, or post faster than a person can type.
	$started = (int) ( $post['astro_started'] ?? 0 );
	if ( ! empty( $post['website'] ) || ( $started && time() - $started < 3 ) ) {
		$back( 'thanks' );
	}

	$ip      = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$counter = 'astro_beta_' . substr( wp_hash( $ip ), 0, 24 );
	$count   = (int) get_transient( $counter );
	if ( $count >= ASTROLABE_BETA_LIMIT ) {
		$back( 'limit' );
	}

	$choices = astrolabe_defaults();
	$name    = mb_substr( sanitize_text_field( $post['name'] ?? '' ), 0, 120 );
	$email   = sanitize_email( $post['email'] ?? '' );
	$consent = ! empty( $post['consent'] );
	if ( '' === $name || ! is_email( $email ) || ! $consent ) {
		$back( 'invalid' );
	}

	$practice   = sanitize_key( $post['practice'] ?? '' );
	$volume     = sanitize_text_field( $post['volume'] ?? '' );
	$traditions = array_values( array_intersect( array_map( 'sanitize_key', (array) ( $post['tradition'] ?? array() ) ), array_keys( $choices['tradition'] ) ) );
	$tools      = mb_substr( sanitize_textarea_field( $post['tools'] ?? '' ), 0, 2000 );
	$call       = ! empty( $post['call'] );
	// phpcs:enable

	$id = wp_insert_post(
		array(
			'post_type'   => ASTROLABE_BETA_TYPE,
			'post_status' => 'private',
			'post_title'  => $name,
		),
		true
	);
	if ( is_wp_error( $id ) ) {
		$back( 'error' );
	}

	$meta = array(
		'_astro_email'      => $email,
		'_astro_practice'   => isset( $choices['practice'][ $practice ] ) ? $practice : '',
		'_astro_volume'     => isset( $choices['volume'][ $volume ] ) ? $volume : '',
		'_astro_traditions' => $traditions,
		'_astro_tools'      => $tools,
		'_astro_call'       => $call ? 1 : 0,
		'_astro_consent_at' => gmdate( 'c' ),
		'_astro_status'     => 'new',
	);
	foreach ( $meta as $key => $value ) {
		update_post_meta( $id, $key, $value );
	}

	set_transient( $counter, $count + 1, HOUR_IN_SECONDS );

	astrolabe_beta_notify( $id );
	$back( 'thanks' );
}

/** Email to the operator (Customizer → AstroLabe → "Beta requests go to"). */
function astrolabe_beta_notify( int $id ): void {
	$to = astrolabe_setting( 'beta_recipient' );
	$to = is_email( $to ) ? $to : get_option( 'admin_email' );

	$r     = astrolabe_beta_read( $id );
	$lines = array(
		__( 'A new request for the AstroLabe closed beta.', 'astrolabe' ),
		'',
		__( 'Name', 'astrolabe' ) . ': ' . $r['name'],
		__( 'Email', 'astrolabe' ) . ': ' . $r['email'],
		__( 'Works', 'astrolabe' ) . ': ' . $r['practice'],
		__( 'Consultations a month', 'astrolabe' ) . ': ' . $r['volume'],
		__( 'Tradition', 'astrolabe' ) . ': ' . $r['traditions'],
		__( 'Happy to have a call', 'astrolabe' ) . ': ' . $r['call'],
		'',
		__( 'Uses today', 'astrolabe' ) . ':',
		'' !== $r['tools'] ? $r['tools'] : '—',
		'',
		admin_url( 'post.php?post=' . $id . '&action=edit' ),
	);

	wp_mail(
		$to,
		sprintf( /* translators: %s: name */ __( '[AstroLabe] Beta request from %s', 'astrolabe' ), $r['name'] ),
		implode( "\n", $lines ),
		array( 'Reply-To: ' . $r['email'] )
	);
}

/** A request as display strings. */
function astrolabe_beta_read( int $id ): array {
	$choices    = astrolabe_defaults();
	$practice   = (string) get_post_meta( $id, '_astro_practice', true );
	$volume     = (string) get_post_meta( $id, '_astro_volume', true );
	$traditions = (array) get_post_meta( $id, '_astro_traditions', true );
	$status     = (string) get_post_meta( $id, '_astro_status', true );

	return array(
		'name'       => get_post_field( 'post_title', $id ),
		'email'      => (string) get_post_meta( $id, '_astro_email', true ),
		'practice'   => $choices['practice'][ $practice ] ?? '—',
		'volume'     => $choices['volume'][ $volume ] ?? '—',
		'traditions' => $traditions ? implode( ', ', array_map( static fn( $t ) => $choices['tradition'][ $t ] ?? $t, $traditions ) ) : '—',
		'tools'      => (string) get_post_meta( $id, '_astro_tools', true ),
		'call'       => get_post_meta( $id, '_astro_call', true ) ? __( 'Yes', 'astrolabe' ) : __( 'No', 'astrolabe' ),
		'consent_at' => (string) get_post_meta( $id, '_astro_consent_at', true ),
		'status'     => $status ? $status : 'new',
	);
}

/* ---------------- Admin: list and detail ---------------- */

add_filter(
	'manage_' . ASTROLABE_BETA_TYPE . '_posts_columns',
	static function (): array {
		return array(
			'cb'         => '<input type="checkbox">',
			'title'      => __( 'Name', 'astrolabe' ),
			'email'      => __( 'Email', 'astrolabe' ),
			'practice'   => __( 'Works', 'astrolabe' ),
			'volume'     => __( 'A month', 'astrolabe' ),
			'traditions' => __( 'Tradition', 'astrolabe' ),
			'call'       => __( 'Call', 'astrolabe' ),
			'status'     => __( 'Status', 'astrolabe' ),
			'date'       => __( 'Received', 'astrolabe' ),
		);
	}
);

add_action(
	'manage_' . ASTROLABE_BETA_TYPE . '_posts_custom_column',
	static function ( string $column, int $id ): void {
		$r = astrolabe_beta_read( $id );
		if ( 'email' === $column ) {
			printf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $r['email'] ) );
		} elseif ( 'status' === $column ) {
			echo esc_html( astrolabe_beta_statuses()[ $r['status'] ] ?? $r['status'] );
		} elseif ( isset( $r[ $column ] ) ) {
			echo esc_html( $r[ $column ] );
		}
	},
	10,
	2
);

add_action(
	'add_meta_boxes_' . ASTROLABE_BETA_TYPE,
	static function (): void {
		add_meta_box( 'astro-beta-request', __( 'Request', 'astrolabe' ), 'astrolabe_beta_box', ASTROLABE_BETA_TYPE, 'normal', 'high' );
	}
);

function astrolabe_beta_box( WP_Post $post ): void {
	$r    = astrolabe_beta_read( $post->ID );
	$rows = array(
		__( 'Name', 'astrolabe' )                  => esc_html( $r['name'] ),
		__( 'Email', 'astrolabe' )                 => sprintf( '<a href="mailto:%1$s">%1$s</a>', esc_attr( $r['email'] ) ),
		__( 'Works', 'astrolabe' )                 => esc_html( $r['practice'] ),
		__( 'Consultations a month', 'astrolabe' ) => esc_html( $r['volume'] ),
		__( 'Tradition', 'astrolabe' )             => esc_html( $r['traditions'] ),
		__( 'Uses today', 'astrolabe' )            => nl2br( esc_html( '' !== $r['tools'] ? $r['tools'] : '—' ) ),
		__( 'Happy to have a call', 'astrolabe' )  => esc_html( $r['call'] ),
		__( 'Consent given', 'astrolabe' )         => esc_html( $r['consent_at'] ),
	);
	echo '<table class="form-table" role="presentation"><tbody>';
	foreach ( $rows as $label => $value ) {
		printf( '<tr><th scope="row">%s</th><td>%s</td></tr>', esc_html( $label ), $value ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above
	}
	echo '<tr><th scope="row"><label for="astro-status">' . esc_html__( 'Status', 'astrolabe' ) . '</label></th><td><select id="astro-status" name="astro_status">';
	foreach ( astrolabe_beta_statuses() as $value => $label ) {
		printf( '<option value="%s"%s>%s</option>', esc_attr( $value ), selected( $r['status'], $value, false ), esc_html( $label ) );
	}
	echo '</select></td></tr></tbody></table>';
	wp_nonce_field( 'astro_beta_status', 'astro_beta_nonce' );
	echo '<p class="description">' . esc_html__( 'The invitation itself is sent from the app (admin → Invitations, or invitations:send). Delete a request here when it is no longer needed.', 'astrolabe' ) . '</p>';
}

add_action(
	'save_post_' . ASTROLABE_BETA_TYPE,
	static function ( int $id ): void {
		if ( ! isset( $_POST['astro_beta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['astro_beta_nonce'] ), 'astro_beta_status' ) ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$status = sanitize_key( $_POST['astro_status'] ?? '' );
		if ( isset( astrolabe_beta_statuses()[ $status ] ) ) {
			update_post_meta( $id, '_astro_status', $status );
		}
	}
);

// Saving the detail screen must not publish a request.
add_filter(
	'wp_insert_post_data',
	static function ( array $data ): array {
		if ( ASTROLABE_BETA_TYPE === $data['post_type'] && ! in_array( $data['post_status'], array( 'private', 'trash' ), true ) ) {
			$data['post_status'] = 'private';
		}
		return $data;
	}
);

/* ---------------- WordPress privacy tools ---------------- */

/** Requests sent from an email address. */
function astrolabe_beta_find( string $email ): array {
	return get_posts(
		array(
			'post_type'   => ASTROLABE_BETA_TYPE,
			'post_status' => array( 'private', 'trash' ),
			'numberposts' => -1,
			'fields'      => 'ids',
			'meta_key'    => '_astro_email', // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
			'meta_value'  => $email, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
		)
	);
}

add_filter(
	'wp_privacy_personal_data_exporters',
	static function ( array $exporters ): array {
		$exporters['astrolabe-beta'] = array(
			'exporter_friendly_name' => __( 'AstroLabe beta requests', 'astrolabe' ),
			'callback'               => static function ( string $email ): array {
				$data = array();
				foreach ( astrolabe_beta_find( $email ) as $id ) {
					$r      = astrolabe_beta_read( $id );
					$data[] = array(
						'group_id'    => 'astrolabe-beta',
						'group_label' => __( 'Beta requests', 'astrolabe' ),
						'item_id'     => 'astro-beta-' . $id,
						'data'        => array(
							array( 'name' => __( 'Name', 'astrolabe' ), 'value' => $r['name'] ),
							array( 'name' => __( 'Email', 'astrolabe' ), 'value' => $r['email'] ),
							array( 'name' => __( 'Works', 'astrolabe' ), 'value' => $r['practice'] ),
							array( 'name' => __( 'Consultations a month', 'astrolabe' ), 'value' => $r['volume'] ),
							array( 'name' => __( 'Tradition', 'astrolabe' ), 'value' => $r['traditions'] ),
							array( 'name' => __( 'Uses today', 'astrolabe' ), 'value' => $r['tools'] ),
							array( 'name' => __( 'Happy to have a call', 'astrolabe' ), 'value' => $r['call'] ),
							array( 'name' => __( 'Consent given', 'astrolabe' ), 'value' => $r['consent_at'] ),
						),
					);
				}
				return array(
					'data' => $data,
					'done' => true,
				);
			},
		);
		return $exporters;
	}
);

add_filter(
	'wp_privacy_personal_data_erasers',
	static function ( array $erasers ): array {
		$erasers['astrolabe-beta'] = array(
			'eraser_friendly_name' => __( 'AstroLabe beta requests', 'astrolabe' ),
			'callback'             => static function ( string $email ): array {
				$removed = false;
				foreach ( astrolabe_beta_find( $email ) as $id ) {
					$removed = (bool) wp_delete_post( $id, true ) || $removed;
				}
				return array(
					'items_removed'  => $removed,
					'items_retained' => false,
					'messages'       => array(),
					'done'           => true,
				);
			},
		);
		return $erasers;
	}
);

// Names in the list without the "Private:" prefix.
add_filter(
	'private_title_format',
	static function ( string $format, WP_Post $post ): string {
		return ASTROLABE_BETA_TYPE === $post->post_type ? '%s' : $format;
	},
	10,
	2
);
