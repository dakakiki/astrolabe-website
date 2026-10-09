<?php
/**
 * Features grid (Site content → Features).
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s = astrolabe_section( 'features' );
?>
<section class="section section-features" id="features">
	<div class="wrap">
		<div class="section-head center">
			<div class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
			<h2><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<?php if ( $s['lede'] ) : ?>
				<p><?php echo esc_html( $s['lede'] ); ?></p>
			<?php endif; ?>
		</div>
		<div class="features">
			<?php foreach ( astrolabe_items( 'feature' ) as $item ) : ?>
				<article class="feature">
					<div class="ico"><?php echo astrolabe_icon( $item['icon'] ? $item['icon'] : 'chart' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<p><?php echo esc_html( $item['text'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
