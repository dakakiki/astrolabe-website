<?php
/**
 * Hero: the promise, and the client profile as it looks in the app.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s  = astrolabe_section( 'hero' );
$vs = '&#xFE0E;'; // text form of a symbol, never an emoji
?>
<section class="hero">
	<div class="wrap">
		<div class="copy">
			<div class="eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></div>
			<h1><?php echo astrolabe_inline( $s['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h1>
			<p class="lede"><?php echo esc_html( $s['lede'] ); ?></p>
			<div class="hero-cta">
				<a class="btn btn-primary" href="#beta"><?php echo esc_html( $s['cta_primary'] ); ?></a>
				<a class="btn" href="#features"><?php echo esc_html( $s['cta_secondary'] ); ?></a>
			</div>
			<ul class="hero-notes">
				<?php foreach ( astrolabe_lines( $s['notes'] ) as $note ) : ?>
					<li><?php echo esc_html( $note ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<div class="hero-visual">
			<svg class="wheel-art" viewBox="0 0 400 400" aria-hidden="true" data-wheel-art></svg>
			<figure class="mock" aria-label="<?php esc_attr_e( 'AstroLabe client profile, with the natal chart and what is coming up', 'astrolabe' ); ?>">
				<div class="mock-bar">
					<?php echo astrolabe_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span class="crumb">Clients / <b>Emma Clarke</b></span>
					<span class="tz">Europe/London · 10:24</span>
				</div>
				<div class="mock-head">
					<div class="avatar" aria-hidden="true">EC</div>
					<div class="who">
						<b>Emma Clarke</b>
						<span>Born May 1991 · 16:20 · Bristol</span>
					</div>
					<div class="chips">
						<span class="chip"><span class="glyph">☉<?php echo $vs; // phpcs:ignore ?></span> 23° Taurus</span>
						<span class="chip"><span class="glyph">☽<?php echo $vs; // phpcs:ignore ?></span> 8° Cancer</span>
						<span class="chip">ASC 18° Libra</span>
					</div>
				</div>
				<div class="mock-tabs" aria-hidden="true">
					<span>Overview</span><span class="on">Natal chart</span><span>Transits</span><span>Consultations</span><span>Notes</span><span>Payments</span>
				</div>
				<div class="mock-body">
					<div class="mock-wheel">
						<svg class="wheel" id="hero-wheel" viewBox="0 0 400 400" role="img" aria-label="<?php esc_attr_e( 'Natal chart wheel', 'astrolabe' ); ?>"></svg>
					</div>
					<div class="mock-side">
						<div class="mini">
							<div class="k">Next consultation</div>
							<div class="v">Thu 15 Oct · 14:00</div>
							<div class="s">Follow-up reading · 60 min · video</div>
						</div>
						<div class="mini">
							<div class="k">Transits now</div>
							<div class="v"><span class="glyph">♇<?php echo $vs; // phpcs:ignore ?></span> conjunct natal <span class="glyph">♄<?php echo $vs; // phpcs:ignore ?></span></div>
							<div class="s">orb 1°52′ · applying</div>
						</div>
						<div class="mini">
							<div class="k">Balance</div>
							<div class="v owed">€70 owed</div>
							<div class="s">Natal reading, 28 Aug</div>
						</div>
						<div class="mini">
							<div class="k">Last note</div>
							<div class="v note">“Career change — look again when Pluto settles on her Saturn.”</div>
						</div>
					</div>
				</div>
			</figure>
		</div>
	</div>
</section>

<div class="wrap divider" aria-hidden="true"><?php echo astrolabe_icon( 'sparkle' ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
