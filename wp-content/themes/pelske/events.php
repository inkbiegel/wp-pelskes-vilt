<?php
/**
 * Template Name: Events
 *
 * @package Pelske
 */

get_header();
?>

	<main id="main" class="site-main">

		<?php

			$args = [
		    'post_type'      => 'pelske_event',
		    'posts_per_page' => -1,
		    'meta_key'			 => 'last-date',
		    'orderby'			   => 'meta_value',
		    'order'					 => 'ASC',
		    'meta_query'     => array(
		    	'key'     => 'last-date',
		    	'value'   => date( 'Y-m-d' ),
		    	'compare' => '>=',
		    ),
			];

			$events = get_posts( $args );

			if ( $events ) :
		    echo '<ol class="event-list">';
		    foreach ( $events as $post ) :
					get_template_part( 'template-parts/content', 'events' );
				endforeach;
				echo '</ol>';
				wp_reset_postdata();
			endif;

		?>

	</main>

<?php
get_footer();
