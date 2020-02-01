<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, sets up the metabox and enqueues styles and scripts
 *
 * @package    Pelskes_Vilt
 * @subpackage Pelskes_Vilt/admin
 */

class Pelske_Event_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string    $name    The ID of this plugin.
	 */
	private $name;

	/**
	 * A reference to the meta box.
	 *
	 * @access   private
	 * @var      Pelske_Event_Meta_Box    $meta_box    A reference to the meta box for the plugin.
	 */
	private $meta_box;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @access 	 public
	 * @var      string    $name       The name of this plugin.
	 */
	public function __construct( $name ) {

		$this->name = $name;

		$this->meta_box = new Pelske_Event_Meta_Box();

	}

	/**
	 * Sets up all hooks for the pelske_event metabox, including child classes'
	 *
	 * @access 	 public
	 *
	 */
	public function initialize_hooks(){

		$this->meta_box->initialize_hooks();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		add_filter( 'manage_pelske_event_posts_columns', array( $this, 'customize_backend_columns' ) );
		add_action( 'manage_pelske_event_posts_custom_column' , array( $this, 'customize_backend_columns_data' ), 10, 2 );
		add_filter( 'manage_edit-pelske_event_sortable_columns', array( $this, 'customize_backend_sortable_columns' ), 10, 2 );

	}

	/**
	 * Enqueues all css files for the pelske_event metabox in the backend.
	 *
	 * @access 				public
	 *
	 */
	public function enqueue_admin_styles() {

    if ( ! get_current_screen()->id === 'pelske_event' ) {
			return;
		}

		wp_enqueue_style(
			$this->name . '-admin',
			plugins_url( 'pelskes-vilt/admin/assets/css/admin.css' ),
			false
		);

	}

	/**
	 * Enqueues all js files for the pelske_event metabox in the backend.
	 * Localizes pelske-event-meta.js to receive data from the metabox.
	 *
	 * @access 				public
	 *
	 */
	public function enqueue_admin_scripts() {

    if ( ! get_current_screen()->id === 'pelske_event' ) {
			return;
		}

		wp_enqueue_script(
			$this->name . '-meta',
			plugins_url( 'pelskes-vilt/admin/assets/js/pelske-event-meta.js' ),
			array( 'jquery' )
		);

		// Localize, if we're editing a pelske_event, to pass the date/time post meta to the script
		if ( empty( $_GET['post'] ) ) {
			return;
		}

		wp_localize_script( $this->name . '-meta', 'date_time_post_meta', $this->meta_box->get_date_time_post_meta() );

	}

	/**
	 * Adds Event Date to the post list in the backend
	 *
	 * @access				public
	 */
	public function customize_backend_columns( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'title' => 'Titel',
			'event_date' => 'Datum Evenement',
			'date' => 'Datum'
		);
		return $columns;

	}

	/**
	 * Insert content into newly created columns for the post list in the backend
	 *
	 * @access 					public
	 */
	public function customize_backend_columns_data( $column, $post_id ) {

		switch ( $column ) {

			case 'event_date':
				$event_dates = maybe_unserialize( get_post_meta( $post_id, '', true )['event-dates'][0] );
				echo date_i18n( 'd-m-yy', strtotime( $event_dates[0] ) );
				break;

		}

	}

	public function customize_backend_sortable_columns( $columns ) {
		$columns['event_date'] = 'event_date';
		return $columns;
	}


}