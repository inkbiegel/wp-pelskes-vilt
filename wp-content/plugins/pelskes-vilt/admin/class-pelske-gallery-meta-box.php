<?php

/**
 * Represents the Pelske Gallery Meta Box.
 *
 * Registers the meta box with the WordPress API, sets its properties, and renders the content
 * by including the markup from its associated view. Handles all functionality to display, save
 * and edit custom gallery_img posts in admin.
 *
 * @package    Pelskes_Vilt
 * @subpackage Pelskes_Vilt/admin
 */
class Pelske_Gallery_Meta_Box {

	/**
	 * Register this class with the WordPress API and set up other hooks
	 *
	 * @access 			public
	 */
	public function initialize_hooks() {
		add_action( 'post_edit_form_tag', array( $this, 'update_edit_form' ) );
		add_action( 'save_post_gallery_img', array( $this, 'save_meta_box' ) );
	}

	/**
	 * Makes sure the Wordpress post form can accept file uploads
	 *
	 * @access 			public
	 */
	public function update_edit_form(){

		echo ' enctype="multipart/form-data"';

	}

	/**
	 * Saves the meta information associated with this post.
	 *
	 * @access 			public
	 * @param    		int    $post_id   The ID of the post that's currently being edited.
	 */
	public function save_meta_box( $post_id ) {

		$terms = array();

    // If the user doesn't have permission to save, we exit the function.
    if ( ! $this->user_can_save( $post_id, 'gallery_img_save', 'gallery_img_nonce' ) ) {
      return;
    }

		// Loop through all $_POST data
    foreach ( $_POST as $key => $value ){

    	// Filter out our metadata inputs
    	if( strpos( $key, 'gallery_img_cat' ) === 0 ){

    		// Get all non empty inputs
    		if ( $this->value_exists( $key ) ) {

					// Add value of checked category checkboxes to array
					$key = str_replace( 'gallery_img_cat_', '', $key );

					$terms[] = $key;

    		}

    	}

		}

		// Save checked categories as post terms
		wp_set_post_terms( $post_id, $terms, 'gallery_img_tax' );

	}

	/**
	 * Verifies that the post type that's being saved is actually a gallery_img
	 *
	 * @access      private
	 * @param 			int 			Optional post_id if we're too early for get_current_screen
	 * @return      bool      Return true if the current post type is a gallery_img; false, otherwise.
	 */
	private function is_valid_post_type( $post_id = false ) {

	  if( get_current_screen() && get_current_screen()->post_type ){

	  	return get_current_screen()->post_type == 'gallery_img';

	  } elseif ( $post_id ) {

	  	return get_post( $post_id )->post_type == 'gallery_img';

	  }

	}

	/**
	 * Determines whether or not the current user has the ability to publish a gallery_img,
	 * the correct nonce is set and it's not an autosave
	 *
	 * @access      private
	 * @param       int     $post_id      The ID of the post being saved.
	 * @param       string  $nonce_action The name of the action associated with the nonce.
	 * @param       string  $nonce_name   The name of the nonce field.
	 * @return      bool                  Whether or not the user has the ability to save this post.
	 */
	private function user_can_save( $post_id, $nonce_action, $nonce_name ) {

		$post_status = get_post_status( $post_id );
		$user_has_permission = current_user_can( 'publish_posts', $post_id );
    $is_valid_nonce = ( isset( $_POST[ $nonce_name ] ) && wp_verify_nonce( $_POST[ $nonce_name ], $nonce_action ) );

    // Return true if the user is able to save; otherwise, false.
    return $user_has_permission && $this->is_valid_post_type( $post_id ) && $is_valid_nonce && $post_status !== 'auto-draft';

	}

	/**
	 * Determines whether or not a value exists in the $_POST collection
	 * identified by the specified key.
	 *
	 * @access  			private
	 * @param   			string    $key    The key of the value in the $_POST collection.
	 * @return  			bool              True if the value exists; otherwise, false.
	 */
	private function value_exists( $key ) {

	  return ! empty( $_POST[ $key ] );

	}

	/**
	 * Sanitizes the data in the $_POST collection identified by the specified key
	 * based on whether or not the data is text or is an array.
	 *
	 * @access   		private
	 * @param    		string        $key                      The key used to retrieve the data from the $_POST collection.
	 * @param    		bool          $is_array    Optional.    True if the incoming data is an array.
	 * @return  		array|string                            The sanitized data.
	 */
	private function sanitize_data( $key, $is_array = false ) {

    $sanitized_data = null;

    if ( $is_array ) {

      $array_items = $_POST[ $key ];
      $sanitized_data = array();

      foreach ( $array_items as $array_item ) {

        $array_item = sanitize_text_field( $array_item );
        if ( ! empty( $array_item ) ) {

          $sanitized_data[] = $array_item;

        }

      }

    } else {

      $sanitized_data = '';
      $sanitized_data = sanitize_text_field( $_POST[ $key ] );

    }

    return $sanitized_data;

	}

	/**
	 * Updates the post meta with the specified value for the specified key.
	 *
	 * @access   		private
	 * @param    		int        		$post_id                  ID of the post to be updated
	 * @param    		string        $meta_key                 The key used to retrieve the meta data from the post.
	 * @param    		array|string  $meta_value								The value of the meta key.
	 */
	private function update_post_meta( $post_id, $meta_key, $meta_value ) {

    if ( isset( $_POST[ $meta_key ] ) && is_array( $_POST[ $meta_key ] ) ) {
      $meta_value = array_filter( $_POST[ $meta_key ] );
    }

    update_post_meta( $post_id, $meta_key, $meta_value );
	}

}

?>