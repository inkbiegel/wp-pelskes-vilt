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
			the_title('<h1 class="entry-title"><span>','</span></h1>');

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
		    echo '<ol class="event-list flex-list card-list">';
		    foreach ( $events as $post ) :
					get_template_part( 'template-parts/content', 'events' );
				endforeach;
				echo '</ol>';
				wp_reset_postdata();
			else :
				_e('There are no planned events at the moment.', 'pelske');
			endif;

		?>

	</main>

<?php
get_footer();
