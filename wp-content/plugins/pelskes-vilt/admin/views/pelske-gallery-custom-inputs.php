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

<div class="pelske-gallery-meta">
	<ol class="form-list">
		<li class="pelske-gallery-img">
			<label for="async-upload" class="label-bold">Upload .jpg</label>
			<input type="file" id="gallery-img-file" name="async-upload" size="25" accept="image/jpg, image/jpeg" />
			<input type="hidden" name="image_id">
			<p id="gallery-img-notice"></p>
			<div id="gallery-img-preview">
				<?php
					if( has_post_thumbnail( $post->ID ) ){
						$url = esc_url( get_the_post_thumbnail_url( $post, 'medium' ) );
						echo '<img src="' . $url . '" alt="Gallery Image" id="gallery-img-preview-img" >';
					}
				?>
			</div>
		</li>
		<li class="pelske-gallery-img-cat">
			<label for="gallery_img_cat" class="label-bold">Kies categorie</label>
			<?php

				$categories = get_terms( array(
					'taxonomy' => 'gallery_img_tax',
					'hide_empty' => false,
				) );

				foreach( $categories as $cat ) {

					$cat_checked_attr = in_array( $cat->name, $tax_terms ) ? 'checked' : '';

					echo '<input type="checkbox" name="gallery_img_cat_' . $cat->name . '"' . $cat_checked_attr . '>' .
							 '<label for="gallery_img_cat_' . $cat->name . '">' . ucfirst( $cat->name ) . '</label>';

				}

			?>
		</li>
	</ol>
</div>
