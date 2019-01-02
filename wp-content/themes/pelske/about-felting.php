<?php
/**
 * Template Name: About - Felting
 *
 * @package Pelske
 */

get_header();
?>

	<main id="main" class="site-main">

		<?php

		$aboutfeltingposts = get_posts( array(
			'tag_id' => '32,66',
			'posts_per_page' => -1
		) );

		if ( $aboutfeltingposts ) :
	    foreach ( $aboutfeltingposts as $post ) : setup_postdata($post);
	    	get_template_part( 'template-parts/content', 'page' );
			endforeach;
			wp_reset_postdata();
		endif;

		?>


	</main>

<?php
get_footer();
