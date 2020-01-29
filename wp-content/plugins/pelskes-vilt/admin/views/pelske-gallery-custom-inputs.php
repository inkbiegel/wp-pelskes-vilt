<?php
/**
 * @package Pelske's Vilt
 *
 * Loads in the html of the custom inputs for the gallery-img meta box in the backend
 *
 */

	$tax_terms = array();

	// If we're editing, show correct category
	if ( ! empty( $_GET['post'] ) ) {

		$terms = wp_get_post_terms( $post->ID, 'gallery_img_tax' );

		foreach( $terms as $term ) {

			$tax_terms[] = $term->name;

		}

	}

?>