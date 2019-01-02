<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, sets up the metabox and enqueues styles and scripts
 *
 * @package    Pelskes_Vilt
 * @subpackage Pelskes_Vilt/admin
 */

class Pelske_Gallery_Admin {

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
	 * @var      Pelske_Gallery_Meta_Box    $meta_box    A reference to the meta box for the plugin.
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

		$this->meta_box = new Pelske_Gallery_Meta_Box();

	}

	/**
	 * Sets up all hooks for the gallery-img metabox, including child classes'
	 *
	 * @access 	 public
	 *
	 */
	public function initialize_hooks(){

		$this->meta_box->initialize_hooks();

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

	}

	/**
	 * Enqueues all css files for the gallery-img metabox in the backend.
	 *
	 * @access 				public
	 *
	 */
	public function enqueue_admin_styles() {

    if ( ! get_current_screen()->id === 'gallery-img' ) {
			return;
		}

		wp_enqueue_style(
			$this->name . '-admin',
			plugins_url( 'pelskes-vilt/admin/assets/css/admin.css' ),
			false
		);

	}

	/**
	 * Enqueues all js files for the gallery-img metabox in the backend.
	 * Localizes pelske-event-meta.js to receive data from the metabox.
	 *
	 * @access 				public
	 *
	 */
	public function enqueue_admin_scripts() {

    if ( ! get_current_screen()->id === 'gallery-img' ) {
			return;
		}

		wp_enqueue_script(
			$this->name . '-meta',
			plugins_url( 'pelskes-vilt/admin/assets/js/pelske-gallery-meta.js' ),
			array( 'jquery' )
		);

		// Localize to set up ajax call for updating gallery img preview on upload
		$media_form_nonce = wp_create_nonce('media-form');
		$data = array(
			'upload_url' => admin_url('async-upload.php'),
			'ajax_url'   => admin_url('admin-ajax.php'),
			'nonce'      => $media_form_nonce
		);
		wp_localize_script( $this->name . '-meta', 'gallery_img_ajax_config', $data );

	}

}