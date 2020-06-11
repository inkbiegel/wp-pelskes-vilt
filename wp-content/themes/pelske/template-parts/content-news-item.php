<?php
/**
 * Template part for displaying page content in home.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pelske
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_post_thumbnail('medium'); ?>
		<?php the_title( '<h3 class="entry-title">', '</h3>' ); ?>
	</header>

	<div class="entry-content">
		<?php
		the_content();
		?>
	</div>
</article>
