<?php
/**
 * @package Pelske's Vilt
 *
 * Loads in the html of the custom inputs for the pelske-event meta box in the backend
 *
 */

	// If we're editing, show correct number of days
	if (! empty($_GET['post']) ) {

		echo '<script> jQuery(document).ready(function($){ setNumberOfDaysOnEdit($); }); </script>';

	}

?>

<div class="pelske-event-meta">
	<fieldset class="pelske-event-location">
		<legend>Locatie</legend>
		<ol>
			<li>
				<label for="event-location">Plaats</label>
				<input type="text" name="event-location" placeholder="bvb. Leuven" required value="<?php echo esc_attr( get_post_meta( $post->ID, 'event-location', true ) ) ?>">
			</li>
			<li>
				<label for="event-location-extra">Naam</label>
				<input type="text" name="event-location-extra" placeholder="bvb. Brabanthallen" value="<?php echo esc_attr( get_post_meta( $post->ID, 'event-location-extra', true ) ) ?>">
			</li>
			<li>
				<label for="event-street">Straat</label>
				<input type="text" name="event-street" placeholder="bvb. Bondgenotenlaan" required value="<?php echo esc_attr( get_post_meta( $post->ID, 'event-street', true ) ) ?>">
			</li>
			<li>
				<label for="event-street-nr">Nummer</label>
				<input type="number" name="event-street-nr" placeholder="bvb. 124" required value="<?php echo esc_attr( get_post_meta( $post->ID, 'event-street-nr', true ) ) ?>">
			</li>
		</ol>
	</fieldset>
	<fieldset class="pelske-event-datetime">
		<legend>Datum en Tijdstip</legend>
		<ol id="pelske-event-date-list" data-number-of-days="<?php echo $this->get_number_of_days(); ?>">
			<li>
				<fieldset>
					<legend>Dag 1</legend>
					<ol>
						<li>
							<label for="event-day-0-date">Datum</label>
							<input type="date" name="event-dates[0]" id="event-day-0-date" required>
						</li>
						<li>
							<label for="event-day-0-time-start">Start Tijd</label>
							<input type="time" name="event-start-times[0]" id="event-day-0-time-start" step="600" required pattern="[0-9]{2}:[0-9]{2}">
						</li>
						<li>
							<label for="event-day-0-time-end">Eind Tijd</label>
							<input type="time" name="event-end-times[0]" id="event-day-0-time-end" step="600" required pattern="[0-9]{2}:[0-9]{2}">
						</li>
					</ol>
				</fieldset>
			</li>
		</ol>
		<input type="submit" id="pelske-event-add-day" value="Voeg dag toe" class="button button-large">
	</fieldset>
</div>
