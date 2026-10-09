<?php
/**
 * Privacy & security (Site content → Privacy points).
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s = astrolabe_section( 'privacy' );
?>
<section class="section" id="privacy">
	<div class="wrap">
		<div class="section-head">
			<div class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
			<h2><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<?php if ( $s['lede'] ) : ?>
				<p><?php echo esc_html( $s['lede'] ); ?></p>
			<?php endif; ?>
		</div>
		<div class="trust">
			<?php foreach ( astrolabe_items( 'trust' ) as $item ) : ?>
				<div class="trust-item">
					<?php echo astrolabe_icon( $item['icon'] ? $item['icon'] : 'shield' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<h3><?php echo esc_html( $item['title'] ); ?></h3>
					<p><?php echo esc_html( $item['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
