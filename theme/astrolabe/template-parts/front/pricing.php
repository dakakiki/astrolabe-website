<?php
/**
 * Pricing: the closed beta, and the planned plans (Site content → Plans).
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s     = astrolabe_section( 'pricing' );
$plans = astrolabe_items( 'plan' );
?>
<section class="section section-after" id="pricing">
	<div class="wrap">
		<div class="section-head center">
			<div class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
			<h2><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<?php if ( $s['lede'] ) : ?>
				<p><?php echo esc_html( $s['lede'] ); ?></p>
			<?php endif; ?>
		</div>
		<div class="beta-banner">
			<?php echo astrolabe_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div>
				<h3><?php echo esc_html( $s['banner_title'] ); ?></h3>
				<p><?php echo esc_html( $s['banner_text'] ); ?></p>
			</div>
			<a class="btn btn-primary" href="#beta"><?php echo esc_html( $s['banner_cta'] ); ?></a>
		</div>
		<?php if ( $plans ) : ?>
			<div class="plans">
				<?php foreach ( $plans as $plan ) : ?>
					<div class="plan<?php echo $plan['featured'] ? ' featured' : ''; ?>">
						<?php if ( $plan['tag'] ) : ?>
							<span class="tag"><?php echo esc_html( $plan['tag'] ); ?></span>
						<?php endif; ?>
						<h3><?php echo esc_html( $plan['title'] ); ?></h3>
						<p class="for"><?php echo esc_html( $plan['for'] ); ?></p>
						<div class="price"><b><?php echo esc_html( $plan['price'] ); ?></b><span><?php echo esc_html( $plan['period'] ); ?></span></div>
						<?php if ( $plan['yearly'] ) : ?>
							<div class="yearly"><?php echo esc_html( $plan['yearly'] ); ?></div>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( $s['note'] ) : ?>
			<p class="pricing-note"><?php echo esc_html( $s['note'] ); ?></p>
		<?php endif; ?>
	</div>
</section>
