<?php
/**
 * Theme setup, assets and Customizer settings.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

const ASTROLABE_VERSION = '0.1.0';

const ASTROLABE_FONTS = 'https://fonts.bunny.net/css?family=ibm-plex-mono:400,500|inter-tight:400,500,600|newsreader:400,400i,500,500i&display=swap';

add_action(
	'after_setup_theme',
	static function (): void {
		load_theme_textdomain( 'astrolabe', get_template_directory() . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'responsive-embeds' );
		add_post_type_support( 'page', 'excerpt' );
		add_theme_support( 'editor-styles' );
		add_editor_style( array( ASTROLABE_FONTS, 'assets/css/editor.css' ) );

		register_nav_menus(
			array(
				'primary'      => __( 'Header', 'astrolabe' ),
				'footer_legal' => __( 'Footer — legal', 'astrolabe' ),
			)
		);
	}
);

/* ---------------- Assets ---------------- */

add_action(
	'wp_enqueue_scripts',
	static function (): void {
		$uri  = get_template_directory_uri();
		$path = get_template_directory();
		$ver  = static fn( string $file ): string => ASTROLABE_VERSION . '.' . (string) filemtime( $path . $file );

		wp_enqueue_style( 'astrolabe-fonts', ASTROLABE_FONTS, array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_enqueue_style( 'astrolabe', $uri . '/assets/css/site.css', array( 'astrolabe-fonts' ), $ver( '/assets/css/site.css' ) );
		wp_enqueue_script( 'astrolabe', $uri . '/assets/js/site.js', array(), $ver( '/assets/js/site.js' ), array( 'strategy' => 'defer', 'in_footer' => true ) );
	}
);

add_filter(
	'wp_resource_hints',
	static function ( array $urls, string $relation ): array {
		if ( 'preconnect' === $relation ) {
			$urls[] = array( 'href' => 'https://fonts.bunny.net', 'crossorigin' );
		}
		return $urls;
	},
	10,
	2
);

// The visitor's night/day choice, applied before first paint (same key as the prototype).
add_action(
	'wp_head',
	static function (): void {
		wp_print_inline_script_tag( "try{if(localStorage.getItem('astrolabe.site.theme')==='day'){document.documentElement.dataset.theme='day';}}catch(e){}" );
		echo '<meta name="theme-color" content="#090d1b">' . "\n";
	},
	1
);

// The mark's icons (same files as the app) until a Site Icon is chosen in the Customizer.
add_action(
	'wp_head',
	static function (): void {
		if ( has_site_icon() ) {
			return;
		}
		printf( '<link rel="icon" href="%s" sizes="48x48">' . "\n", esc_url( get_theme_file_uri( 'assets/logo/favicon.ico' ) ) );
		printf( '<link rel="icon" href="%s" type="image/svg+xml">' . "\n", esc_url( get_theme_file_uri( 'assets/logo/favicon.svg' ) ) );
		printf( '<link rel="apple-touch-icon" href="%s">' . "\n", esc_url( get_theme_file_uri( 'assets/logo/apple-touch-icon.png' ) ) );
	}
);

// A plain meta description when no SEO plugin writes one.
add_action(
	'wp_head',
	static function (): void {
		if ( defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'SEOPRESS_VERSION' ) ) {
			return;
		}
		$description = is_front_page()
			? 'Clients, birth data, natal charts, transits, consultations, notes, appointments and payments in one workspace built for professional astrologers.'
			: get_bloginfo( 'description' );
		if ( $description ) {
			printf( '<meta name="description" content="%s">' . "\n", esc_attr( $description ) );
		}
	}
);

/* ---------------- Customizer ---------------- */

add_action(
	'customize_register',
	static function ( WP_Customize_Manager $wp_customize ): void {
		$wp_customize->add_section(
			'astrolabe',
			array(
				'title'    => __( 'AstroLabe', 'astrolabe' ),
				'priority' => 30,
			)
		);

		$fields = array(
			'app_url'        => array( __( 'App address', 'astrolabe' ), 'url', 'esc_url_raw', 'https://app.astrolabe.online', __( 'Sign in and the Terms / DPA links point here.', 'astrolabe' ) ),
			'contact_email'  => array( __( 'Contact email', 'astrolabe' ), 'email', 'sanitize_email', 'hello@astrolabe.online', __( 'Shown in the footer.', 'astrolabe' ) ),
			'business'       => array( __( 'Business name (footer)', 'astrolabe' ), 'text', 'sanitize_text_field', '[Business name], Serbia', '' ),
			'beta_recipient' => array( __( 'Beta requests go to', 'astrolabe' ), 'email', 'sanitize_email', '', __( 'Email that receives each beta request. Empty: the site administrator address.', 'astrolabe' ) ),
			'footer_note'    => array( __( 'Footer note', 'astrolabe' ), 'text', 'sanitize_text_field', 'No ads. No tracking. No selling data.', __( 'Keep it true: change it if analytics with cookies is ever added.', 'astrolabe' ) ),
		);

		foreach ( $fields as $key => list( $label, $type, $sanitize, $default, $description ) ) {
			$wp_customize->add_setting(
				'astrolabe_' . $key,
				array(
					'default'           => $default,
					'sanitize_callback' => $sanitize,
				)
			);
			$wp_customize->add_control(
				'astrolabe_' . $key,
				array(
					'label'       => $label,
					'description' => $description,
					'section'     => 'astrolabe',
					'type'        => $type,
				)
			);
		}
	}
);

/* ---------------- Menus without a menu ---------------- */

/** Header links used until a "Header" menu is assigned. */
function astrolabe_primary_fallback(): void {
	$links = array(
		'features' => __( 'Features', 'astrolabe' ),
		'charts'   => __( 'Charts', 'astrolabe' ),
		'privacy'  => __( 'Privacy', 'astrolabe' ),
		'pricing'  => __( 'Pricing', 'astrolabe' ),
		'faq'      => __( 'FAQ', 'astrolabe' ),
	);
	foreach ( $links as $id => $label ) {
		printf( '<a href="%s">%s</a>', esc_url( astrolabe_anchor( $id ) ), esc_html( $label ) );
	}
}

/** Legal links used until a "Footer — legal" menu is assigned. */
function astrolabe_legal_fallback(): void {
	$links = array(
		astrolabe_privacy_url()              => __( 'Privacy', 'astrolabe' ),
		astrolabe_app_url( '/legal/terms' ) => __( 'Terms', 'astrolabe' ),
		astrolabe_app_url( '/legal/dpa' )   => __( 'Data processing', 'astrolabe' ),
	);
	echo '<ul>';
	foreach ( $links as $url => $label ) {
		printf( '<li><a href="%s">%s</a></li>', esc_url( $url ), esc_html( $label ) );
	}
	echo '</ul>';
}

/** Header links: the items of the Header menu as bare links, or the section anchors. */
function astrolabe_primary_links(): void {
	$locations = get_nav_menu_locations();
	$items     = empty( $locations['primary'] ) ? array() : wp_get_nav_menu_items( $locations['primary'] );
	if ( ! $items ) {
		astrolabe_primary_fallback();
		return;
	}
	foreach ( $items as $item ) {
		if ( (int) $item->menu_item_parent ) {
			continue;
		}
		printf( '<a href="%s">%s</a>', esc_url( $item->url ), esc_html( $item->title ) );
	}
}
