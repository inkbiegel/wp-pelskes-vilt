(function( $ ) {
	'use strict';

	$(document).ready(function() {

		$('#pelske-event-add-day').on('click', function(e){

			e.preventDefault();

			$('#pelske-event-date-list').append( createNewDay() );

		});

		$('input[name=post_title]').prop('required',true);

		fileUploadPreviewHandler();

	});

	function createNewDay(){

		var $newDay, iDayCount;

		iDayCount = $('#pelske-event-date-list').children().length - 1; // -1 for easier array management
		iDayCount++;

		$newDay = '<li>' +
								'<fieldset>' +
								 	'<legend>Day ' + (iDayCount + 1) + '</legend>' +
							 		'<ol>' +
								 		'<li>' +
									 		'<label for="event-day-' + iDayCount + '-date">Date</label>' +
											'<input type="date" name="event-dates[' + iDayCount + ']" id="event-day-' + iDayCount + '-date">' +
									 	'</li>' +
										'<li>' +
											'<label for="event-day-' + iDayCount + '-time-start">Start Time</label>' +
											'<input type="time" name="event-start-times[' + iDayCount + ']" id="event-day-' + iDayCount + '-time-start" step="600" pattern="[0-9]{2}:[0-9]{2}">' +
										'</li>' +
										'<li>' +
											'<label for="event-day-' + iDayCount + '-time-end">End Time</label>' +
											'<input type="time" name="event-end-times[' + iDayCount + ']" id="event-day-' + iDayCount + '-time-end" step="600" pattern="[0-9]{2}:[0-9]{2}">' +
										'</li>' +
									'</ol>' +
								'</fieldset>' +
							'</li>';

		return $newDay;

	}

	function fileUploadPreviewHandler(){

		$('#event-flyer-file').on('change', function(e){

			e.preventDefault();

	    var $imgPreview 	= $('#event-flyer-preview');
	    var $imgNotice  	= $('#event-flyer-notice');
	    var $imgFile    	= $('#event-flyer-file');
	    var $imgId      	= $('.pelske-event-flyer [name="image_id"]');
	    var fileExtension = ['jpeg', 'jpg'];

	    if ( $.inArray( $(this).val().split('.').pop().toLowerCase(), fileExtension ) == -1) {
	      $imgNotice.html( "Only formats are allowed : " + fileExtension.join(', ') );
	      return;
	    }

	    var formData = new FormData();

	    formData.append( 'action', 'upload-attachment' );
	    formData.append( 'async-upload', $imgFile[0].files[0] );
	    formData.append( 'name', $imgFile[0].files[0].name );
	    formData.append( '_wpnonce', flyer_ajax_config.nonce );

	    $.ajax({
	      url: flyer_ajax_config.upload_url,
	      data: formData,
	      processData: false,
	      contentType: false,
	      dataType: 'json',
	      type: 'POST',
	      beforeSend: function() {
			    $imgFile.hide();
			    $imgNotice.html('Uploading&hellip;').show();
				},
				xhr: function() {
			    var myXhr = $.ajaxSettings.xhr();

			    if ( myXhr.upload ) {
		        myXhr.upload.addEventListener( 'progress', function(e) {
	            if ( e.lengthComputable ) {
	              var perc = ( e.loaded / e.total ) * 100;
	              perc = perc.toFixed(2);
	              $imgNotice.html('Uploading&hellip;(' + perc + '%)');
	            }
		        }, false );
			    }

			    return myXhr;
				},
	      success: function(resp) {
	    		if ( resp.success ) {

		        $imgNotice.html('Successfully uploaded.');

		        if( $('#event-flyer-preview-img').length > 0 ){

		        	$('#event-flyer-preview-img').attr('src', resp.data.url);

		        } else {

			        var img = $('<img>', {
			          src: resp.data.url,
			          id: 'event-flyer-preview-img',
			          alt: 'Event flyer'
			        });
		        	$imgPreview.html( img ).show();

		        }

		        $imgId.val( resp.data.id );

				    var flyerData = new FormData();

				    flyerData.append( '_wpnonce', flyer_ajax_config.nonce );
				    flyerData.append( 'action', 'image_submission' );
				    flyerData.append( 'id', resp.data.id );
				    flyerData.append( 'filename', resp.data.filename );
				    flyerData.append( 'post_id', $('#post_ID').val() );

				    $.ajax({
				      url: flyer_ajax_config.ajax_url,
				      data: flyerData,
							processData: false,
	      			contentType: false,
				      dataType: 'json',
				      type: 'POST'
				    });

			    } else {
		        $imgNotice.html('Failed to upload image. Please try again.');
		        $imgId.val('');
			    }

		      $imgFile.show();

				},
	      error: function(resp) {
	      	$imgNotice.html('Failed to upload image. Please try again.');
	      }
	    });

		});

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