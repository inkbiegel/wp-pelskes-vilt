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
			get_template_part( 'template-parts/content', 'home' );
		endforeach;
		wp_reset_postdata();
	endif;

	?>

	</main>

<?php
get_sidebar();
get_footer();
