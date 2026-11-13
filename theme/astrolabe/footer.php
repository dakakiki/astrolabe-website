<?php
/**
 * Site footer.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$astrolabe_email = astrolabe_setting( 'contact_email' );
?>
	</main>

	<footer class="site-footer">
		<div class="wrap">
			<div class="footer-grid">
				<div>
					<a class="lockup" href="<?php echo esc_url( home_url( '/' ) ); ?>">
						<?php echo astrolabe_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<span class="word">AstroLabe</span>
					</a>
					<p><?php esc_html_e( 'Practice management for professional astrologers.', 'astrolabe' ); ?></p>
				</div>
				<div>
					<h4><?php esc_html_e( 'Product', 'astrolabe' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( astrolabe_anchor( 'features' ) ); ?>"><?php esc_html_e( 'Features', 'astrolabe' ); ?></a></li>
						<li><a href="<?php echo esc_url( astrolabe_anchor( 'charts' ) ); ?>"><?php esc_html_e( 'Charts', 'astrolabe' ); ?></a></li>
						<li><a href="<?php echo esc_url( astrolabe_anchor( 'pricing' ) ); ?>"><?php esc_html_e( 'Pricing', 'astrolabe' ); ?></a></li>
						<li><a href="<?php echo esc_url( astrolabe_anchor( 'faq' ) ); ?>"><?php esc_html_e( 'FAQ', 'astrolabe' ); ?></a></li>
					</ul>
				</div>
				<div>
					<h4><?php esc_html_e( 'Account', 'astrolabe' ); ?></h4>
					<ul>
						<li><a href="<?php echo esc_url( astrolabe_app_url() ); ?>"><?php esc_html_e( 'Sign in', 'astrolabe' ); ?></a></li>
						<li><a href="<?php echo esc_url( astrolabe_signup_url() ); ?>"><?php esc_html_e( 'Start free trial', 'astrolabe' ); ?></a></li>
						<?php if ( $astrolabe_email ) : ?>
							<li><a href="<?php echo esc_url( 'mailto:' . $astrolabe_email ); ?>"><?php echo esc_html( $astrolabe_email ); ?></a></li>
						<?php endif; ?>
					</ul>
				</div>
				<div>
					<h4><?php esc_html_e( 'Legal', 'astrolabe' ); ?></h4>
					<?php
					wp_nav_menu(
						array(
							'theme_location' => 'footer_legal',
							'container'      => false,
							'depth'          => 1,
							'fallback_cb'    => 'astrolabe_legal_fallback',
						)
					);
					?>
				</div>
			</div>
			<div class="footer-base">
				<span>© <?php echo esc_html( wp_date( 'Y' ) ); ?> AstroLabe · <?php echo esc_html( astrolabe_setting( 'business' ) ); ?></span>
				<span><?php echo esc_html( astrolabe_setting( 'footer_note' ) ); ?></span>
			</div>
		</div>
	</footer>
</div>
<?php wp_footer(); ?>
</body>
</html>
