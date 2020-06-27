<?php
/**
 * Template Name: Static
 *
 * @package Pelske
 */

get_header();
?>
	<main id="main" class="site-main">
		<?php
		 the_title('<h1><span>','</span></h1>');
		 the_content();
		?>
	</main>
<?php
get_footer();