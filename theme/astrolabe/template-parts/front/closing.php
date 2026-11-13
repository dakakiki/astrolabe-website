<?php
/**
 * Closing call to action.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s = astrolabe_section( 'closing' );
?>
<section class="closing">
	<svg class="wheel-art" viewBox="0 0 400 400" aria-hidden="true" data-wheel-art></svg>
	<div class="wrap">
		<h2><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<p><?php echo esc_html( $s['text'] ); ?></p>
		<div class="hero-cta">
			<a class="btn btn-primary" href="<?php echo esc_url( astrolabe_signup_url() ); ?>"><?php echo esc_html( $s['cta'] ); ?></a>
		</div>
	</div>
</section>
