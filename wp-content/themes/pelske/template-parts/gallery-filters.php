<?php
	function get_all_gallery_img_cats() {

		$categories = get_terms( array(
				'taxonomy' => 'gallery_img_tax',
				'hide_empty' => false,
			)
		);

		return $categories;
	}
?>
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