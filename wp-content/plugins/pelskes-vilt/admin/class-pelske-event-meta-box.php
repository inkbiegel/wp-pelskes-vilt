<?php

/**
 * Represents the Pelske Event Meta Box.
 *
 * Registers the meta box with the WordPress API, sets its properties, and renders the content
 * by including the markup from its associated view. Handles all functionality to display, save
 * and edit custom pelske_event posts in admin.
 *
 * @package    Pelskes_Vilt
 * @subpackage Pelskes_Vilt/admin
 */
class Pelske_Event_Meta_Box {

	/**
	 * Register this class with the WordPress API and set up other hooks
	 *
	 * @access 			public
	 */
	public function initialize_hooks() {
		add_action( 'post_edit_form_tag', array( $this, 'update_edit_form' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post_pelske_event', array( $this, 'save_meta_box' ) );
		add_action( 'load-post.php', array( $this, 'get_date_time_post_meta' ) );
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
	 * The function responsible for creating the actual meta box.
	 *
	 * @access 			public
	 */
	public function add_meta_box() {

		add_meta_box(
			'pelske-event',
			'Event Details',
			array( $this, 'display_meta_box' ),
			'pelske_event',
			'advanced',
			'default',
			array('__block_editor_compatible_meta_box' => true) // Hide Gutenberg incompatibility error
		);

	}

	/**
	 * Renders the content of the meta box.
	 *
	 * @access 			public
	 * @param    		int    		$post    	The post that's currently being edited.
	 */
	public function display_meta_box( $post ) {

		wp_nonce_field( 'pelske_event_save', 'pelske_event_nonce' );

		require_once( PELSKE__PLUGIN_DIR . 'admin/views/pelske-event-custom-inputs.php' );

	}

	/**
	 * Saves the meta information associated with this post.
	 *
	 * @access 			public
	 * @param    		int    $post_id   The ID of the post that's currently being edited.
	 */
	public function save_meta_box( $post_id ) {

    // If the user doesn't have permission to save, we exit the function.
    if ( ! $this->user_can_save( $post_id, 'pelske_event_save', 'pelske_event_nonce' ) ) {
      return;
    }

    // Get current metadata
    $current_metadata = get_post_meta( $post_id, '', true );

    // Loop through all $_POST data
    foreach ( $_POST as $key => $value ){

    	// Filter out our metadata inputs
    	if( strpos( $key, 'event' ) === 0 ){

    		// Get all non empty inputs
    		if ( $this->value_exists( $key ) ) {

    			$sanitized_value = $this->sanitize_data( $key, is_array( $value ) );

    			// No need to overwrite metadata if it's the same
  				if( isset( $current_metadata[ $key ] ) ){

  					if( $sanitized_value === maybe_unserialize( $current_metadata[ $key ][0] ) ) {
  						continue;
  					}

  				}

    			$this->update_post_meta(
    				$post_id,
    				$key,
    				$sanitized_value
    			);

    		} else {

    			// If input is empty, delete the postmeta if it was previously set (non required fields)
    			if ( isset( $current_metadata[ $key ] ) ) {
    				delete_post_meta( $post_id, $key );
    			}

    		}

    	}

    }

    // Save the last date of the event for display/sorting purposes
		$last_date = sanitize_text_field( end( $_POST['event-dates'] ) );

		$this->update_post_meta(
			$post_id,
			'last-date',
			$last_date
		);

	}

	/**
	 * On post edit, gets the meta data for dates and times and puts them in an array to pass
	 * to pelske-event-meta.js. Returns the array so it can be called in class-pelske-event to
	 * localize script.
	 *
	 * @access 			public
	 * @return 			array 		Associative array with arrays for 'dates', 'start_times' and 'end_times'
	 */
	public function get_date_time_post_meta() {

		// Check if we're editing a pelske_event
		if( empty( $_GET['post'] ) || ! $this->is_valid_post_type() ) {
			return;
		}

		// Get the date/time post meta values
		$post = get_post( $_GET['post'] );
		$post_meta = get_post_meta( $post->ID, '', true );
		$dates = maybe_unserialize( $post_meta['event-dates'][0] );
		$start_times = maybe_unserialize( $post_meta['event-start-times'][0] );
		$end_times = maybe_unserialize( $post_meta['event-end-times'][0] );

		// Stick them in an associative array of arrays
		$date_time_post_meta = array(
			'dates' => $dates,
			'start_times' => $start_times,
			'end_times' => $end_times
		);

		// Return the array
		return $date_time_post_meta;


	}

	/**
	 * Returns the number of days present in the post meta on post edit
	 *
	 * @access      public
	 * @return      int      Number of days present in the post meta
	 */
	public function get_number_of_days() {

		// Check if we're editing a pelske_event
		if( empty( $_GET['post'] ) || ! $this->is_valid_post_type() ) {
			return;
		}

		$post = get_post( $_GET['post'] );
		return esc_attr( count( get_post_meta( $post->ID, 'event-dates', true ) ) );

	}

	/**
	 * Verifies that the post type that's being saved is actually a pelske_event
	 *
	 * @access      private
	 * @param 			int 			Optional post_id if we're too early for get_current_screen
	 * @return      bool      Return true if the current post type is a pelske_event; false, otherwise.
	 */
	private function is_valid_post_type( $post_id = false ) {

	  if( get_current_screen() && get_current_screen()->post_type ){

	  	return get_current_screen()->post_type == 'pelske_event';

	  } elseif ( $post_id ) {

	  	return get_post( $post_id )->post_type == 'pelske_event';

	  }

	}

	/**
	 * Determines whether or not the current user has the ability to publish a pelske_event,
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