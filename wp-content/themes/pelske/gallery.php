<?php
/**
 * Template Name: Gallery
 *
 * @package Pelske
 */

function get_all_gallery_img_cats() {

	$categories = get_terms( array(
			'taxonomy' => 'gallery_img_tax',
			'hide_empty' => false,
		)
	);
	error_log( print_r( $categories, true ) );

	return $categories;
}

get_header();
?>

	<main id="main" class="site-main">

		<h1><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></h1>
		<form action="" method="POST">
			<fieldset>
				<legend>Filters:</legend>
				<ol class="list-inline">
					<li class="list-inline-item">
						<input type="checkbox" name="galleryFilterAll" id="galleryFilterAll" checked>
						<label for="galleryFilterAll">Alles</label>
					</li>
					<?php
						$categories = get_all_gallery_img_cats();
						foreach($categories as $category) {
							$label = ucfirst( $category->name );
					?>
					<li class="list-inline-item">
						<input type="checkbox" name="galleryFilter<?php echo $label; ?>" id="galleryFilter<?php echo $label; ?>">
						<label for="galleryFilter<?php echo $label; ?>"><?php echo $label; ?></label>
					</li>
					<?php } ?>
				</ol>
			</fieldset>
		</form>

	</main>

<?php
get_sidebar();
get_footer();
