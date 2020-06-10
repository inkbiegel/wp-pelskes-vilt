<?php
/**
 * The template for displaying the homepage
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pelske
 */

get_header();
?>

	<main id="main" class="site-main">

	<?php

	$homeposts = get_posts( array(
		'cat' => '46,48'
	) );

	if ( $homeposts ) :
		foreach ( $homeposts as $post ) :
			setup_postdata( $post );
			get_template_part( 'template-parts/content', 'home' );
		endforeach;
		wp_reset_postdata();
	endif;

	?>
		<section class="news">
			<h2><?php _e('News', 'pelske'); ?></h2>
			<?php
				$newsposts = get_posts( array(
					'cat' 						=> '71,73',
					'posts_per_page' 	=> 3,
				) );

				if ( $newsposts ) :
					foreach ( $newsposts as $post ) :
						setup_postdata( $post );
						get_template_part( 'template-parts/content', 'news-item' );
					endforeach;
					wp_reset_postdata();
				endif;
			?>
		</section>
		<aside class="latest-pics">
			<h2><?php _e('Latest work', 'pelske'); ?></h2>
			<?php
				$pics = get_posts( array(
					'post_type'				=> 'gallery_img',
					'posts_per_page' 	=> 6,
				) );

				if ( $pics ) :
			?>
				<ul class="grid-list grid-list__square gallery">
			<?php
					foreach ( $pics as $post ) :
						setup_postdata( $post );
						get_template_part( 'template-parts/gallery-item' );
					endforeach;
					wp_reset_postdata();
			?>
				</ul>
			<?php
				endif;
			?>
		</aside>
	</main>

<?php
get_sidebar();
get_footer();
