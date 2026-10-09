<?php
/**
 * Charts: the wheel with today's transits, and what the calculations cover.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s = astrolabe_section( 'charts' );
?>
<section class="section" id="charts">
	<div class="wrap spotlight">
		<div class="spot-visual">
			<svg class="wheel" id="spot-wheel" viewBox="0 0 440 440" role="img" aria-label="<?php esc_attr_e( "Natal chart with today's transits on the outer ring", 'astrolabe' ); ?>"></svg>
			<div class="caption"><span>Natal · Placidus · Tropical</span><span>Outer ring: transits today</span></div>
		</div>
		<div class="copy">
			<div class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
			<h2 class="h-section"><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<ul class="facts">
				<?php foreach ( astrolabe_defaults()['facts'] as $fact ) : ?>
					<li>
						<?php echo astrolabe_icon( 'sparkle', 'star' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						<div>
							<b><?php echo esc_html( $fact['title'] ); ?></b>
							<span><?php echo esc_html( $fact['text'] ); ?></span>
							<?php if ( $fact['list'] ) : ?>
								<div class="list"><?php echo esc_html( $fact['list'] ); ?></div>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
