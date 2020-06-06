<?php
/**
 * Template Name: Contact - Guestbook
 *
 * @package Pelske
 */

$response = '';

// Response messages
$invalid_nonce    = __( 'Sorry, your nonce did not verify. Please try again.', 'pelske' );
$not_human        = __( 'Human verification incorrect.', 'pelske' );
$missing_content  = __( 'Please supply all information.', 'pelske' );
$gb_entry_success = __( 'Thanks! Your entry was added to the Guestbook.', 'pelske' );
$gb_entry_error   = __( 'Something went wrong with your entry. Please try again.', 'pelske' );


if( ! empty( $_POST ) ){
	$editing = true;

	// User posted variables
	$gb_name = $_POST['gb-name'];
	$gb_location = $_POST['gb-location'];
	$gb_message = $_POST['gb-msg'];

	// Validate and add entry to guestbook
	// Check honey pot
	$honeypot = false;
	if ( ! empty( $_REQUEST['address'] ) && $_REQUEST['address'] !== '') {

    $honeypot = true;
    error_log( print_r( $_REQUEST, true ), 3, $_SERVER['DOCUMENT_ROOT'] . '/wp-pelskes-vilt/spambots.log' );
    wp_die(__('No bots please!', 'pelske'));

	} else {

		// Check nonce
		if ( ! isset( $_POST['pelske_gb_entry_submit_nonce'] ) || ! wp_verify_nonce( $_POST['pelske_gb_entry_submit_nonce'], 'pelske_gb_entry_submit' ) ) {
			pelske_form_generate_response( 'error', $invalid_nonce );
		} else {

			// Check recaptcha (defined in pelskes-vilt.php)
			if( recaptcha_is_valid() === false ){
				pelske_form_generate_response( 'error', $not_human );
			} else {

				// Check if all fields are set
				if( empty( $gb_name ) || empty( $gb_location ) || empty( $gb_message ) ){
				  pelske_form_generate_response( 'error', $missing_content );
				} else {

	  			// Add entry to the guestbook
					$new_gb_entry = array(
						'post_type'       => 'pelske_gb_entry',
						'post_title'      => sprintf( esc_html__( 'Guestbook entry of %1$s from %2$s.', 'pelske' ), sanitize_text_field( $gb_name ), sanitize_text_field( $gb_location ) ),
						'post_content'    => sanitize_textarea_field( $gb_message ),
						'post_status'     => 'publish',
						'comment_status'  => 'open',
						'meta_input'      => array(
							'gb_author'   => sanitize_text_field( $gb_name ),
							'gb_location' => sanitize_text_field( $gb_location ),
						),
					);
					$pid = wp_insert_post( $new_gb_entry );

					if( $pid === 0 ) {
						pelske_form_generate_response( 'error', $gb_entry_error );
					} else {
						pelske_form_generate_response( 'success', $gb_entry_success );
						$editing = false;
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
		<div id="guestbook-form-wrapper">
			<?php echo $response; ?>
			<form id="guestbook-form" class="js-validate" action="<?php echo the_permalink(); ?>" method="post" accept-charset="utf-8">
				<ol class="form-list">
					<li class="form-list-item">
						<label for="gb-name"><?php _e( 'Name', 'pelske' ) ?>*</label>
						<input type="text" name="gb-name" id="gb-name" value="<?php if( $editing ) echo esc_attr( $_POST['gb-name'] ); ?>" required>
					</li>
					<li class="form-list-item">
						<label for="gb-location"><?php _e( 'Location', 'pelske' ) ?>*</label>
						<input type="text" name="gb-location" id="gb-location" value="<?php if( $editing ) echo esc_attr( $_POST['gb-location'] ); ?>" required>
					</li>
					<li class="form-list-item">
						<label for="gb-msg"><?php _e( 'Message', 'pelske' ) ?>*</label>
						<textarea name="gb-msg" id="gb-msg" required><?php if( $editing ) echo esc_textarea( $_POST['gb-msg'] ); ?></textarea>
						<p class="disclaimer"><small><?php printf( __('This form is protected by reCAPTCHA and the Google <a href="%1$s">Privacy Policy</a> and <a href="%2$s">Terms of Service</a> apply.', 'pelske'), 'https://policies.google.com/privacy', 'https://policies.google.com/terms' ); ?></small></p>
					</li>
					<li class="form-list-item">
						<input type="text" name="address" class="js-validate-hp" tabindex="-1" autocomplete="nope">
						<input class="anim-gradient-flash" type="submit" name="gb-submit" value="<?php _e( 'Submit', 'pelske' ) ?>">
					</li>
				</ol>
				<div class="g-recaptcha" data-size="invisible" data-sitekey="6Lcc-2AUAAAAAG9dl0_KBPYKacjmQMOVZKLKe_lM" data-callback="gbEntrySubmitCallback" data-badge="inline"></div>
				<?php wp_nonce_field( 'pelske_gb_entry_submit', 'pelske_gb_entry_submit_nonce' ); ?>
			</form>
		</div>

		<?php

			$args = [
		    'post_type'      => 'pelske_gb_entry',
		    'posts_per_page' => 10,
		    'orderby'			   => 'date',
		    'order'					 => 'DESC',
			];

			$gb_entries = get_posts( $args );

			if ( $gb_entries ) :
		    echo '<ol class="gb-entry-list">';
		    foreach ( $gb_entries as $post ) {
		    	setup_postdata($post);
					get_template_part( 'template-parts/content', 'guestbook-entry' );
				};
				echo '</ol>';
				wp_reset_postdata();
			else:
				echo '<p>' . __('Be the first to leave a comment!', 'pelske') . '</p>';
			endif;

		 ?>

	</main>

<?php
get_footer();
