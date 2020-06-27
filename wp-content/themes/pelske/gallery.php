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

get_header();
?>

	<main id="main" class="site-main">

		<?php the_title( '<h1 class="entry-title"><span>', '</span></h1>' ); ?>

		<?php
			if( $gallery_imgs ):
		?>
		<ul class="grid-list grid-list__square gallery" id="gallery">
		<?php
			foreach( $gallery_imgs as $post ):
				setup_postdata( $post );
				$full_img = wp_get_attachment_image_src( get_field('gallery_img_large'), 'full' );
				$url = $full_img[0];
				$image = get_field('gallery_img_small');
		?>
			<li class="grid-list-item gallery-item">
				<a class="grid-list-link gallery-link" href="<?php echo $url; ?>">
					<?php
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
