<?php
/**
 * Template Name: Contact - Form
 *
 * @package Pelske
 */

$response = '';

// Response messages
$not_human       = __( 'Human verification incorrect.', 'pelske' );
$missing_content = __( 'Please supply all information.', 'pelske' );
$email_invalid   = __( 'Email address invalid.', 'pelske' );
$message_unsent  = __( 'Message was not sent. Please try again.', 'pelske' );
$message_sent    = __( 'Thanks! Your message has been sent.', 'pelske' );


if( ! empty( $_POST ) ){
	$editing = true;

	// User posted variables
	$name = $_POST['contact_name'];
	$email = $_POST['contact_email'];
	$message = $_POST['contact_msg'];

	// Php mailer variables
	$to = 'jellevuylsteke@gmail.com';
	$subject = __( 'Someone sent a message from ', 'pelske' ) . get_bloginfo('name');
	$headers[] = 'From: ' . sanitize_text_field( $name ) . ' <' . sanitize_email( $email ) . '>';
	$headers[] = 'Reply-To: ' . sanitize_text_field( $name ) . ' <' . sanitize_email( $email ) . '>';

	// Validate and send mail
	// Check honey pot
	$honeypot = false;
	if ( ! empty( $_REQUEST['address'] ) && $_REQUEST['address'] !== '') {

    $honeypot = true;
    error_log( print_r( $_REQUEST, true ), 3, $_SERVER['DOCUMENT_ROOT'] . '/wp-pelskes-vilt/spambots.log' );
    wp_die(__('No bots please!', 'pelske'));

	} else {

		// Check recaptcha (defined in pelskes-vilt.php)
		if( recaptcha_is_valid() === false ){
			pelske_form_generate_response( 'error', $not_human );
		} else {

	  	// Validate email
			if( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ){
			  pelske_form_generate_response( 'error', $email_invalid );
			} else {

				// Validate presence of name and message
				if( empty( $name ) || empty( $message ) ){
				  pelske_form_generate_response( 'error', $missing_content );
				} else {

	  			// Send email
					$sent = wp_mail( $to, $subject, sanitize_textarea_field( $message ), $headers );
					if ( $sent ) {

						pelske_form_generate_response( 'success', $message_sent );
						$editing = false;

					} else {
						pelske_form_generate_response( 'error', $message_unsent );
					}

				}
			}
		}
	}

} else {
	$editing = false;
}

get_header();
?>

	<main id="main" class="site-main">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php echo $response; ?>
		<form id="contact-form" class="js-validate" action="<?php echo the_permalink(); ?>" method="post" accept-charset="utf-8">
			<ol class="form-list">
				<li class="form-list-item">
					<label for="contact_name"><?php _e( 'Name', 'pelske' ) ?>*</label>
					<input type="text" name="contact_name" id="contact_name" value="<?php if( $editing ) echo esc_attr( $_POST['contact_name'] ); ?>" required>
				</li>
				<li class="form-list-item">
					<label for="contact_email"><?php _e( 'Email address', 'pelske' ) ?>*</label>
					<input type="email" name="contact_email" id="contact_email" value="<?php if( $editing ) echo esc_attr( $_POST['contact_email'] ); ?>" pattern="^([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x22([^\x0d\x22\x5c\x80-\xff]|\x5c[\x00-\x7f])*\x22))*\x40([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d)(\x2e([^\x00-\x20\x22\x28\x29\x2c\x2e\x3a-\x3c\x3e\x40\x5b-\x5d\x7f-\xff]+|\x5b([^\x0d\x5b-\x5d\x80-\xff]|\x5c[\x00-\x7f])*\x5d))*(\.\w{2,})+$" title="<?php _e( 'The domain portion of the email address is invalid (the portion after the @).', 'pelske' ) ?>" required>
				</li>
				<li class="form-list-item">
					<label for="contact_msg"><?php _e( 'Message', 'pelske' ) ?>*</label>
					<textarea name="contact_msg" id="contact_msg" required><?php if( $editing ) echo esc_textarea( $_POST['contact_msg'] ); ?></textarea>
					<p class="disclaimer"><small><?php printf( __('This form is protected by reCAPTCHA and the Google <a href="%1$s">Privacy Policy</a> and <a href="%2$s">Terms of Service</a> apply.', 'pelske'), 'https://policies.google.com/privacy', 'https://policies.google.com/terms' ); ?></small></p>
				</li>
				<li class="form-list-item">
					<input type="text" name="address" class="js-validate-hp" tabindex="-1" autocomplete="nope">
					<input class="anim-gradient-flash" type="submit" name="contact_submit" value="<?php _e( 'Submit', 'pelske' ) ?>">
				</li>
			</ol>
			<div class="g-recaptcha" data-size="invisible" data-sitekey="6Lcc-2AUAAAAAG9dl0_KBPYKacjmQMOVZKLKe_lM" data-callback="contactSubmitCallback" data-badge="inline"></div>
		</form>

	</main>

<?php
get_footer();
