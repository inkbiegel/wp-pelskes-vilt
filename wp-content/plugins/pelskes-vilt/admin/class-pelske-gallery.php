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
	 * Initialize the class and set its properties.
	 *
	 * @access 	 public
	 * @var      string    $name       The name of this plugin.
	 */
	public function __construct( $name ) {

		$this->name = $name;

	}

	/**
	 * Sets up all hooks for the gallery-img, including child classes'
	 *
	 * @access 	 public
	 */
	public function initialize_hooks(){

		add_filter( 'manage_gallery_img_posts_columns', array( $this, 'customize_backend_columns' ) );
		add_action( 'manage_gallery_img_posts_custom_column' , array( $this, 'customize_backend_columns_data' ), 10, 2 );

	}

	/**
	 * Removes Title and adds Thumbnail and tax Category columns to the post list in the backend
	 *
	 * @access				public
	 */
	public function customize_backend_columns( $columns ) {

		$columns = array(
			'cb' => '<input type="checkbox" />',
			'featured_image' => 'Foto',
			'img_detail' => 'Detail',
			'img_category' => 'Categorie',
			'date' => 'Date'
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

			case 'featured_image':
				echo wp_get_attachment_image( get_field( 'gallery_img_full', $post_id ), 'medium' );
				break;

			case 'img_detail':
				echo wp_get_attachment_image( get_field( 'gallery_img_detail', $post_id ), 'medium' );
				break;

			case 'img_category':
				echo $this->get_post_terms_list_by_id( $post_id, 'gallery_img_cat' );
				break;

		}

	}

	/**
	 * Removes the picture from the uploads folder when gallery_img is deleted
	 *
	 * @access				public
	 */
	public function remove_thumbnail_on_post_deletion( $post_id ) {

    global $post_type;
		if ( $post_type != 'gallery_img' ) return;

		delete_post_thumbnail( $post_id );

	}

	/**
	 * Gets all tax terms for Foto and returns them in a comma separated list
	 *
	 * @access				private
	 * @return				string 					Comma separated list of tax terms
	*/
	private function get_post_terms_list_by_id( $post_id, $field_name ) {

		$categories = '';
		$terms = get_field( $field_name, $post_id );

		for ( $i = 0; $i < count( $terms ); $i++) {

			if( $i == 0 ){
				$categories .= ucfirst( $terms[$i] );
			} else {
				$categories .= ', ' . ucfirst( $terms[$i] );
			}

		}

		return $categories;

	}

}