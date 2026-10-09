<?php
/**
 * Helpers used by the templates.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

/**
 * A front-page section: the defaults, with every non-empty field from the
 * front page laid over them. ACF stores group sub-fields as "{group}_{field}"
 * post meta, so this reads them with or without ACF active.
 */
function astrolabe_section( string $key ): array {
	$section = astrolabe_defaults()['sections'][ $key ] ?? array();
	$front   = (int) get_option( 'page_on_front' );
	if ( ! $front ) {
		return $section;
	}
	foreach ( $section as $name => $default ) {
		$value = get_post_meta( $front, "{$key}_{$name}", true );
		if ( is_string( $value ) && '' !== trim( $value ) ) {
			$section[ $name ] = $value;
		}
	}
	return $section;
}

/** Inline markup allowed in editable headings and short texts. */
function astrolabe_inline( string $text ): string {
	return wp_kses(
		$text,
		array(
			'em'     => array(),
			'strong' => array(),
			'br'     => array(),
			'a'      => array( 'href' => true ),
		)
	);
}

/** Non-empty, trimmed lines of a textarea. */
function astrolabe_lines( string $text ): array {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\R/', $text ) ) ) );
}

/** One of the line icons in template-parts/sprites.php. */
function astrolabe_icon( string $name, string $class = '' ): string {
	return sprintf(
		'<svg%s aria-hidden="true" focusable="false"><use href="#i-%s"></use></svg>',
		$class ? ' class="' . esc_attr( $class ) . '"' : '',
		esc_attr( $name )
	);
}

/** The AstroLabe mark (concept A, "Rete"). */
function astrolabe_mark( string $class = 'mark', string $label = '' ): string {
	return sprintf(
		'<svg class="%s"%s><use href="#mark-a"></use></svg>',
		esc_attr( $class ),
		$label ? ' role="img" aria-label="' . esc_attr( $label ) . '"' : ' aria-hidden="true" focusable="false"'
	);
}

/** A theme setting from the Customizer, with its default. */
function astrolabe_setting( string $key ): string {
	$defaults = array(
		'app_url'        => 'https://app.astrolabe.online',
		'contact_email'  => 'hello@astrolabe.online',
		'business'       => '[Business name], Serbia',
		'beta_recipient' => '',
		'footer_note'    => 'No ads. No tracking. No selling data.',
	);
	$value = get_theme_mod( 'astrolabe_' . $key, $defaults[ $key ] ?? '' );
	return is_string( $value ) ? $value : '';
}

/** A page of the app, e.g. astrolabe_app_url( '/legal/terms' ). */
function astrolabe_app_url( string $path = '' ): string {
	return untrailingslashit( astrolabe_setting( 'app_url' ) ) . $path;
}

/** The site's own privacy page if one is set, otherwise the app's. */
function astrolabe_privacy_url(): string {
	$url = get_privacy_policy_url();
	return $url ? $url : astrolabe_app_url( '/legal/privacy' );
}

/** A link to a section of the front page, from any page. */
function astrolabe_anchor( string $id ): string {
	return is_front_page() ? '#' . $id : home_url( '/#' . $id );
}

/**
 * Items of an editable list (feature, trust, plan, faq): the published posts
 * in their order, or the defaults while none exist.
 */
function astrolabe_items( string $type ): array {
	$posts = get_posts(
		array(
			'post_type'        => 'astro_' . $type,
			'post_status'      => 'publish',
			'numberposts'      => 50,
			'orderby'          => array(
				'menu_order' => 'ASC',
				'title'      => 'ASC',
			),
			'suppress_filters' => false,
		)
	);
	if ( ! $posts ) {
		return astrolabe_defaults()[ $type ] ?? array();
	}

	$fields = array(
		'feature' => array( 'icon', 'text' ),
		'trust'   => array( 'icon', 'text' ),
		'plan'    => array( 'tag', 'for', 'price', 'period', 'yearly', 'featured' ),
		'faq'     => array(),
	);

	return array_map(
		static function ( WP_Post $post ) use ( $type, $fields ): array {
			// Raw title: templates escape it once on output.
			$item = array( 'title' => $post->post_title );
			foreach ( $fields[ $type ] ?? array() as $field ) {
				$item[ $field ] = get_post_meta( $post->ID, $field, true );
			}
			if ( 'faq' === $type ) {
				$item['answer'] = apply_filters( 'the_content', $post->post_content );
			}
			if ( 'plan' === $type ) {
				$item['featured'] = (bool) $item['featured'];
			}
			return $item;
		},
		$posts
	);
}
