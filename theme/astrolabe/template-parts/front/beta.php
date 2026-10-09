<?php
/**
 * Closed beta: the request form (received by inc/beta-requests.php).
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

$s       = astrolabe_section( 'beta' );
$choices = astrolabe_defaults();
$state   = isset( $_GET['beta'] ) ? sanitize_key( wp_unslash( $_GET['beta'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$done    = 'thanks' === $state;
$errors  = array(
	'invalid' => __( 'Please add your name, a valid email and your consent to be emailed.', 'astrolabe' ),
	'limit'   => __( 'Too many requests from your connection — please try again in an hour.', 'astrolabe' ),
	'error'   => sprintf( /* translators: %s: contact email */ __( 'Something went wrong on our side. Please write to us at %s.', 'astrolabe' ), astrolabe_setting( 'contact_email' ) ),
);
$error = $errors[ $state ] ?? '';
?>
<section class="section" id="beta">
	<div class="wrap beta">
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
		<div class="form-card">
			<form id="beta-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate<?php echo $done ? ' hidden' : ''; ?>>
				<input type="hidden" name="action" value="astrolabe_beta">
				<input type="hidden" name="astro_started" value="<?php echo esc_attr( (string) time() ); ?>">
				<div class="hp" aria-hidden="true">
					<label for="f-website">Website</label>
					<input id="f-website" name="website" type="text" tabindex="-1" autocomplete="off">
				</div>
				<div class="form-grid">
					<div class="field">
						<label for="f-name"><?php esc_html_e( 'Your name', 'astrolabe' ); ?></label>
						<input class="input" id="f-name" name="name" autocomplete="name" maxlength="120" required>
					</div>
					<div class="field">
						<label for="f-email"><?php esc_html_e( 'Email', 'astrolabe' ); ?></label>
						<input class="input" id="f-email" name="email" type="email" autocomplete="email" required>
					</div>
					<div class="field">
						<label for="f-practice"><?php esc_html_e( 'How do you work?', 'astrolabe' ); ?></label>
						<select class="input" id="f-practice" name="practice">
							<?php foreach ( $choices['practice'] as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>"><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="field">
						<label for="f-volume"><?php esc_html_e( 'Consultations a month', 'astrolabe' ); ?></label>
						<select class="input" id="f-volume" name="volume">
							<?php foreach ( $choices['volume'] as $value => $label ) : ?>
								<option value="<?php echo esc_attr( $value ); ?>"<?php selected( $value, '6-20' ); ?>><?php echo esc_html( $label ); ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<fieldset class="field full">
						<legend><?php esc_html_e( 'Your tradition', 'astrolabe' ); ?> <span class="hint">— <?php esc_html_e( 'pick any', 'astrolabe' ); ?></span></legend>
						<div class="pills">
							<?php foreach ( $choices['tradition'] as $value => $label ) : ?>
								<label class="pill"><input type="checkbox" name="tradition[]" value="<?php echo esc_attr( $value ); ?>"><span><?php echo esc_html( $label ); ?></span></label>
							<?php endforeach; ?>
						</div>
					</fieldset>
					<div class="field full">
						<label for="f-tools"><?php esc_html_e( 'What do you use today?', 'astrolabe' ); ?> <span class="hint">— <?php esc_html_e( 'optional', 'astrolabe' ); ?></span></label>
						<textarea class="input" id="f-tools" name="tools" maxlength="2000" placeholder="<?php esc_attr_e( 'For example: a calendar app, a notes file, desktop chart software…', 'astrolabe' ); ?>"></textarea>
					</div>
					<label class="check full">
						<input type="checkbox" name="call" value="1" checked>
						<span><?php esc_html_e( 'I am happy to have a 30-minute video call about how I work.', 'astrolabe' ); ?></span>
					</label>
					<label class="check full">
						<input type="checkbox" name="consent" value="1" required>
						<span>
							<?php
							printf(
								/* translators: %s: link to the privacy notice */
								esc_html__( 'You may email me about the beta. My details are used only for that — see the %s.', 'astrolabe' ),
								'<a href="' . esc_url( astrolabe_privacy_url() ) . '">' . esc_html__( 'privacy notice', 'astrolabe' ) . '</a>'
							);
							?>
						</span>
					</label>
				</div>
				<div class="form-foot">
					<button class="btn btn-primary" type="submit"><?php echo esc_html( $s['submit'] ); ?></button>
					<span class="form-error" role="alert"<?php echo $error ? '' : ' hidden'; ?>><?php echo esc_html( $error ); ?></span>
				</div>
			</form>
			<div class="form-done"<?php echo $done ? '' : ' hidden'; ?> tabindex="-1">
				<?php echo astrolabe_mark(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<h3><?php echo esc_html( $s['done_title'] ); ?></h3>
				<p><?php echo esc_html( $s['done_text'] ); ?></p>
			</div>
		</div>
	</div>
</section>
