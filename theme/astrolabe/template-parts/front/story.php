<?php
/**
 * One client, the whole story: the client timeline.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s = astrolabe_section( 'story' );
?>
<section class="section section-after">
	<div class="wrap story">
		<div class="copy">
			<div class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
			<h2 class="h-section"><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<p class="section-text"><?php echo esc_html( $s['text'] ); ?></p>
		</div>
		<ol class="timeline">
			<?php foreach ( astrolabe_defaults()['timeline'] as $entry ) : ?>
				<li<?php echo empty( $entry['now'] ) ? '' : ' class="t-now"'; ?>>
					<span class="dot"><?php echo astrolabe_icon( $entry['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
					<div class="card-t">
						<div class="when"><?php echo esc_html( $entry['when'] ); ?></div>
						<div class="what"><?php echo esc_html( $entry['what'] ); ?></div>
						<div class="more<?php echo empty( $entry['note'] ) ? '' : ' note'; ?>"><?php echo esc_html( $entry['more'] ); ?></div>
					</div>
				</li>
			<?php endforeach; ?>
		</ol>
	</div>
</section>
