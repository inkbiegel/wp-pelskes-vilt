<?php
/**
 * Template Name: Gallery
 *
 * @package Pelske
 */

get_header();
?>

	<main id="main" class="site-main">

		<h1><?php the_title( '<h1 class="entry-title">', '</h1>' ); ?></h1>
		<p>This is the gallery template</p>

	</main>

<?php
get_sidebar();
get_footer();
