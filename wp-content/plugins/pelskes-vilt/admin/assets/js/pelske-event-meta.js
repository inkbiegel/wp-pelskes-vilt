(function( $ ) {
	'use strict';

	$(document).ready(function() {

		$('#pelske-event-add-day').on('click', function(e){

			e.preventDefault();

			$('#pelske-event-date-list').append( createNewDay() );

		});

		$('input[name=post_title]').prop('required',true);

	});

	function createNewDay(){

		var $newDay, iDayCount;

		iDayCount = $('#pelske-event-date-list').children().length - 1; // -1 for easier array management
		iDayCount++;

		$newDay = '<li>' +
								'<fieldset>' +
								 	'<legend>Dag ' + (iDayCount + 1) + '</legend>' +
							 		'<ol>' +
								 		'<li>' +
									 		'<label for="event-day-' + iDayCount + '-date">Datum</label>' +
											'<input type="date" name="event-dates[' + iDayCount + ']" id="event-day-' + iDayCount + '-date">' +
									 	'</li>' +
										'<li>' +
											'<label for="event-day-' + iDayCount + '-time-start">Start Tijd</label>' +
											'<input type="time" name="event-start-times[' + iDayCount + ']" id="event-day-' + iDayCount + '-time-start" step="600" pattern="[0-9]{2}:[0-9]{2}">' +
										'</li>' +
										'<li>' +
											'<label for="event-day-' + iDayCount + '-time-end">Eind Tijd</label>' +
											'<input type="time" name="event-end-times[' + iDayCount + ']" id="event-day-' + iDayCount + '-time-end" step="600" pattern="[0-9]{2}:[0-9]{2}">' +
										'</li>' +
									'</ol>' +
								'</fieldset>' +
							'</li>';

		return $newDay;

	}

})( jQuery );

// Functions that need to be publicly callable
/**
 * Show correct number of days and set the values as passed from
 * get_date_time_post_meta() in class-pelske-event-meta-box.php
 */
function setNumberOfDaysOnEdit($){

	var nrOfDays = $('#pelske-event-date-list').data('number-of-days');

	for (var i = 0; i < nrOfDays; i++) {
		// No need to trigger the first time because first date is hardcoded in pelske-event-custom-inputs.php
		if( i > 0 ) { $('#pelske-event-add-day').trigger('click'); }
		$('#event-day-' + i + '-date').val(date_time_post_meta.dates[i]);
		$('#event-day-' + i + '-time-start').val(date_time_post_meta.start_times[i]);
		$('#event-day-' + i + '-time-end').val(date_time_post_meta.end_times[i]);
	}

}