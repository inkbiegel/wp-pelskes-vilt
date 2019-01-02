(function( $ ) {
	'use strict';

	$(document).ready(function() {

		fileUploadPreviewHandler();

	});

	function fileUploadPreviewHandler(){

		$('#gallery-img-file').on('change', function(e){

			e.preventDefault();

	    var $imgPreview 	= $('#gallery-img-preview');
	    var $imgNotice  	= $('#gallery-img-notice');
	    var $imgFile    	= $('#gallery-img-file');
	    var $imgId      	= $('.pelske-gallery-img [name="image_id"]');
	    var fileExtension = ['jpeg', 'jpg'];

	    if ( $.inArray( $(this).val().split('.').pop().toLowerCase(), fileExtension ) == -1) {
	      $imgNotice.html( "Only formats are allowed : " + fileExtension.join(', ') );
	      return;
	    }

	    var formData = new FormData();

	    formData.append( 'action', 'upload-attachment' );
	    formData.append( 'async-upload', $imgFile[0].files[0] );
	    formData.append( 'name', $imgFile[0].files[0].name );
	    formData.append( '_wpnonce', gallery_img_ajax_config.nonce );

	    $.ajax({
	      url: gallery_img_ajax_config.upload_url,
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

		        if( $('#gallery-img-preview-img').length > 0 ){

		        	$('#gallery-img-preview-img').attr('src', resp.data.url);

		        } else {

			        var img = $('<img>', {
			          src: resp.data.url,
			          id: 'gallery-img-preview-img',
			          alt: 'Event flyer'
			        });
		        	$imgPreview.html( img ).show();

		        }

		        $imgId.val( resp.data.id );

				    var galleryImgData = new FormData();

				    galleryImgData.append( '_wpnonce', gallery_img_ajax_config.nonce );
				    galleryImgData.append( 'action', 'image_submission' );
				    galleryImgData.append( 'id', resp.data.id );
				    galleryImgData.append( 'filename', resp.data.filename );
				    galleryImgData.append( 'post_id', $('#post_ID').val() );

				    $.ajax({
				      url: gallery_img_ajax_config.ajax_url,
				      data: galleryImgData,
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