<?php
/**
 * Template Name: Gallery
 *
 * @package Pelske
 */
$args = array(
	'post_type' => 'gallery_img',
	'numberposts' => -1,
);
$gallery_imgs = get_posts( $args );

function get_all_gallery_img_cats() {

	$categories = get_terms( array(
			'taxonomy' => 'gallery_img_tax',
			'hide_empty' => false,
		)
	);

	return $categories;
}

get_header();
?>

	<main id="main" class="site-main">

		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<form action="" method="POST" class="gallery-filters" id="galleryFilters">
			<fieldset>
				<legend><?php _e('Show', 'pelske'); ?>:</legend>
				<ol class="list-inline">
					<li class="list-inline-item">
						<input type="checkbox" name="galleryFilterAll" id="galleryFilterAll" checked>
						<label for="galleryFilterAll"><?php _e('Everything', 'pelske'); ?></label>
					</li>
					<?php
						$categories = get_all_gallery_img_cats();
						foreach($categories as $category) {
							$cat_name = ucfirst( $category->name );
					?>
					<li class="list-inline-item">
						<input type="checkbox" name="galleryFilter<?php echo $cat_name; ?>" id="galleryFilter<?php echo $cat_name; ?>">
						<label for="galleryFilter<?php echo $cat_name; ?>"><?php echo $cat_name; ?></label>
					</li>
					<?php } ?>
				</ol>
			</fieldset>
		</form>
		<?php
			if( $gallery_imgs ):
		?>
		<ul class="grid-list grid-list__square gallery" id="gallery">
		<?php
			foreach( $gallery_imgs as $post ):
				setup_postdata( $post );
				$full_img = wp_get_attachment_image_src( get_field('gallery_img_large'), 'full' );
		?>
			<li class="grid-list-item gallery-item">
				<a class="grid-list-link gallery-link" href="<?php echo $full_img[0]; ?>">
					<?php
						$image = get_field('gallery_img_small');
						echo wp_get_attachment_image( $image, 'medium', false, array( 'class' => 'grid-list-img gallery-img-small' ) );
					?>
				</a>
			</li>
		<?php
			endforeach;
		?>
		</ul>
		<?php
			endif;
		?>
	</main>

<?php
get_footer();
