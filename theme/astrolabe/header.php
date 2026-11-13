<?php
/**
 * Site header.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php get_template_part( 'template-parts/sprites' ); ?>
<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'astrolabe' ); ?></a>

<div class="site">
	<header class="site-header" id="top">
		<div class="wrap">
			<a class="lockup" href="<?php echo esc_url( home_url( '/' ) ); ?>" aria-label="<?php esc_attr_e( 'AstroLabe home', 'astrolabe' ); ?>">
				<?php echo astrolabe_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="word">AstroLabe</span>
			</a>
			<nav class="site-nav" id="site-nav" aria-label="<?php esc_attr_e( 'Main', 'astrolabe' ); ?>">
				<?php astrolabe_primary_links(); ?>
			</nav>
			<div class="header-actions">
				<button type="button" class="icon-btn" data-theme-toggle aria-label="<?php esc_attr_e( 'Switch between night and day', 'astrolabe' ); ?>">
					<?php echo astrolabe_icon( 'sun', 'theme-night-icon' ) . astrolabe_icon( 'moon', 'theme-day-icon' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
				<a class="btn btn-ghost btn-sm signin" href="<?php echo esc_url( astrolabe_app_url() ); ?>"><?php esc_html_e( 'Sign in', 'astrolabe' ); ?></a>
				<a class="btn btn-primary btn-sm" href="<?php echo esc_url( astrolabe_signup_url() ); ?>"><?php esc_html_e( 'Start free trial', 'astrolabe' ); ?></a>
				<button type="button" class="icon-btn menu-btn" aria-label="<?php esc_attr_e( 'Open menu', 'astrolabe' ); ?>" aria-controls="site-nav" aria-expanded="false" data-menu>
					<?php echo astrolabe_icon( 'menu' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</button>
			</div>
		</div>
	</header>

	<main id="content">
