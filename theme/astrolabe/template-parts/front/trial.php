<?php
/**
 * Free trial: how it works, and the way into the app's registration.
 * (Replaced the closed-beta request form on 13 Nov 2026.)
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s = astrolabe_section( 'trial' );
?>
<section class="section" id="trial">
	<div class="wrap trial">
		<div class="copy">
			<div class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
			<h2><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<p><?php echo esc_html( $s['text'] ); ?></p>
			<ol class="steps">
				<?php foreach ( astrolabe_lines( $s['steps'] ) as $step ) : ?>
					<li><?php echo esc_html( $step ); ?></li>
				<?php endforeach; ?>
			</ol>
		</div>
		<div class="trial-card">
			<?php echo astrolabe_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<h3><?php echo esc_html( $s['card_title'] ); ?></h3>
			<ul class="trial-facts">
				<?php foreach ( astrolabe_lines( $s['facts'] ) as $fact ) : ?>
					<li><?php echo esc_html( $fact ); ?></li>
				<?php endforeach; ?>
			</ul>
			<a class="btn btn-primary" href="<?php echo esc_url( astrolabe_signup_url() ); ?>"><?php echo esc_html( $s['cta'] ); ?></a>
			<p class="trial-signin">
				<?php esc_html_e( 'Already have an account?', 'astrolabe' ); ?>
				<a href="<?php echo esc_url( astrolabe_app_url() ); ?>"><?php esc_html_e( 'Sign in', 'astrolabe' ); ?></a>
			</p>
		</div>
	</div>
</section>
