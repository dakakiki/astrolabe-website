<?php
/**
 * Questions (Site content → Questions).
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s = astrolabe_section( 'faq' );
?>
<section class="section section-after" id="faq">
	<div class="wrap">
		<div class="section-head center">
			<div class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
			<h2><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		</div>
		<div class="faq">
			<?php foreach ( astrolabe_items( 'faq' ) as $item ) : ?>
				<details>
					<summary><?php echo esc_html( $item['title'] ); ?></summary>
					<div class="a"><?php echo wp_kses_post( $item['answer'] ); ?></div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>
