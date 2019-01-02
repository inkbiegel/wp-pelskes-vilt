<?php
/**
 * Template part for displaying page content in events.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Pelske
 */

	$post_id = get_the_ID();
	$location = get_post_meta( $post_id, 'event-location', true );
	$street = get_post_meta( $post_id, 'event-street', true );
	$street_nr = get_post_meta( $post_id, 'event-street-nr', true );

?>


	<li class="event-list-item">
		<article class="event">
			<div class="event-info">
				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_post_thumbnail_url(); ?>" target="_blank">
						<?php the_post_thumbnail( 'thumbnail' ); ?>
					</a>
				<?php endif; ?>
				<?php esc_html( the_title( '<h2 class="entry-title">', '</h2>' ) ); ?>
				<p>
					<?php
						echo esc_html( $location );
						if( get_post_meta( $post_id, 'event-location-extra', true ) !== '' ) {
							echo ', ' . esc_html( get_post_meta( $post_id, 'event-location-extra', true ) );
						}
					?>
					<br>
					<?php echo esc_html( $street ) . ' ' . esc_html( $street_nr ); ?>
					<br>
					<a href="https://www.google.com/maps/search/?api=1&query=<?php echo urlencode( $street ) . '+' . urlencode( $street_nr ) . '+' . urlencode( $location ) ?>" target="_blank" rel="noopener">Google Maps</a>
				</p>
				<table class="event-time-table">

				<?php

					$dates = get_post_meta( $post_id, 'event-dates', true );
					$i = 0;

					foreach ($dates as $date) {
						echo  '<tr>' .
								 		'<td>' . $dates[$i] . '</td>' .
								 		'<td>' . get_post_meta( $post_id, 'event-start-times', true )[$i] . ' - ' . get_post_meta( $post_id, 'event-end-times', true )[$i] . '</td>' .
								  '</tr>';
						$i++;
					}

				?>

				</table>
			</div>
		</article>
	</li>