jQuery(document).ready(function($) {
	
	// button to open the media uploader
	$('#company-logo-add-button, #company-logo-placeholder img').on('click', function(event) {
		tb_show('', 'media-upload.php?post_id=0$amp;type=image&amp;TB_iframe=true');
		event.preventDefault();
	});
	
	// button to remove the media 
	$('#company-logo-remove-button').on('click', function(event) {
		removeImage();
		event.preventDefault();
	});
	
	// this javascript is called from the custom 
	// button inside the media manager. see the
	// script-media-uploader.js for details.
	// close the media uploader and set the media 
	window.sendImageToContent = function(id, image) {
		console.log(this);
		tb_remove();
		
		$('#company-logo-image-id').val(id);		
		$('#company-logo-placeholder').addClass('loading').empty();

		// load the image		
		var data = {
			attachment_id: id,
			action: 'load_thumbnail'
		}
		
		$.post(ajaxurl, data, function(response) {
			$('#company-logo-placeholder').removeClass('loading').append(response);
			$('#company-logo-add-button').hide();
			$('#company-logo-remove-button').show();
		}).error(function() {
			removeImage();
		});
	}
	
	// remove media 
	function removeImage() {
		$('#company-logo-image-id').val('');		
		$('#company-logo-placeholder').removeClass('loading').empty();
		$('#company-logo-add-button').show();
		$('#company-logo-remove-button').hide();
	}

});

