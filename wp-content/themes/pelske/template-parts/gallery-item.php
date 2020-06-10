<?php
	if( is_home() ) {
		$url = get_site_url() .  __('/en/gallery', 'pelske');
	} else {
		$full_img = wp_get_attachment_image_src( get_field('gallery_img_large'), 'full' );
		$url = $full_img[0];
	}
?>
<li class="grid-list-item gallery-item">
	<a class="grid-list-link gallery-link" href="<?php echo $url; ?>">
		<?php
			$image = get_field('gallery_img_small');
			echo wp_get_attachment_image( $image, 'medium', false, array( 'class' => 'grid-list-img gallery-img-small' ) );
		?>
	</a>
</li>