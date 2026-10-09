<?php
/**
 * Not found.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<section class="not-found">
	<div class="wrap wrap-narrow">
		<?php echo astrolabe_mark( 'mark not-found-mark' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		<div class="eyebrow">404</div>
		<h1><?php echo wp_kses( __( 'This star is <em>not on our chart.</em>', 'astrolabe' ), array( 'em' => array() ) ); ?></h1>
		<p><?php esc_html_e( 'The page you were looking for has moved or never existed.', 'astrolabe' ); ?></p>
		<div class="hero-cta">
			<a class="btn btn-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to the start', 'astrolabe' ); ?></a>
		</div>
	</div>
</section>
<?php
get_footer();
