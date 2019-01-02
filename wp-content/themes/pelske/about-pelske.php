<?php
/**
 * Template Name: About - Pelske
 *
 * @package Pelske
 */

get_header();
?>

	<main id="main" class="site-main">

		<?php

		$aboutpelskeposts = get_posts( array(
			'tag_id' => '62,64',
			'posts_per_page' => -1
		) );

		if ( $aboutpelskeposts ) :
	    foreach ( $aboutpelskeposts as $post ) : setup_postdata($post);
	    	get_template_part( 'template-parts/content', 'page' );
			endforeach;
			wp_reset_postdata();
		endif;

		?>


	</main>

<?php
get_footer();
