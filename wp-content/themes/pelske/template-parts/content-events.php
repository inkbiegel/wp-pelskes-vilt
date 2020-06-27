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


	<li class="event-list-item flex-list-item card-list-item">
		<article class="event card">
			<div class="card-body">
				<figure class="event-figure event-front card-front">
					<figcaption>
						<?php
							esc_html( the_title( '<h2 class="event-title card-title">', '</h2>' ) );
							$dates = get_post_meta( $post_id, 'event-dates', true );
							$last_date = get_post_meta( $post_id, 'last-date', true );
						?>
						<div class="event-dates card-subtitle">
							<time class="event-time" datetime="<?php echo $dates[0]; ?>"><?php echo strtolower(date( 'j M', strtotime($dates[0]) )); ?></time>
							<?php if( $dates[0] !== $last_date ) : ?>
							- <time class="event-time" datetime="<?php echo $last_date; ?>"><?php echo strtolower(date( 'j M', strtotime($last_date) )); ?></time>
							<?php endif; ?>
						</div>
					</figcaption>
					<?php
					$image = get_field('event_flyer');
					if ( $image ) :
						echo wp_get_attachment_image( $image, 'medium', '', ['class'=>'event-img card-img'] );
					endif; ?>
				</figure>
				<div class="event-back card-back">
					<p class="event-name"><?php the_title('<strong>', '</strong>') ?></p>
					<p class="event-location">
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
											'<td>' . strtolower( date( 'j M', strtotime($dates[$i]) ) ) . '</td>' .
											'<td>' . get_post_meta( $post_id, 'event-start-times', true )[$i] . ' - ' . get_post_meta( $post_id, 'event-end-times', true )[$i] . '</td>' .
										'</tr>';
							$i++;
						}

					?>

					</table>
				</div>
			</div>
		</article>
	</li>