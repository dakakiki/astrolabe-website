<?php
/**
 * Why AstroLabe: seven places for one client, gathered into one.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s     = astrolabe_section( 'problem' );
$tools = astrolabe_defaults()['tools'];
?>
<section class="section">
	<div class="wrap scatter-grid">
		<div class="copy">
			<div class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
			<h2><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<p><?php echo astrolabe_inline( $s['text_1'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
			<p><?php echo astrolabe_inline( $s['text_2'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		</div>
		<div class="scatter" aria-hidden="true">
			<svg class="links" viewBox="0 0 100 100" preserveAspectRatio="none">
				<?php foreach ( $tools as $tool ) : ?>
					<path d="<?php echo esc_attr( sprintf( 'M%d %d Q%d %d 50 50', $tool['x'], $tool['y'], ( $tool['x'] + 50 ) / 2 + ( $tool['y'] > 50 ? -6 : 6 ), ( $tool['y'] + 50 ) / 2 ) ); ?>" />
				<?php endforeach; ?>
			</svg>
			<?php foreach ( $tools as $tool ) : ?>
				<span class="tool" style="<?php echo esc_attr( sprintf( 'left: %d%%; top: %d%%; --tilt: %ddeg', $tool['x'], $tool['y'], $tool['tilt'] ) ); ?>">
					<?php echo astrolabe_icon( $tool['icon'] ) . esc_html( $tool['label'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</span>
			<?php endforeach; ?>
			<div class="hub"><?php echo astrolabe_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
		</div>
	</div>
</section>
