jQuery(document).ready(function($) {
	
	/*
	 * Print button
	 */	 
	
	// Button on list and edit screen
	$('.print-preview-button').printLink();
	$('.print-preview-button').on('printLinkInit', function(event) {
		$(this).parent().find('.print-preview-loading').show();
	});
	$('.print-preview-button').on('printLinkComplete', function(event) {
		$('.print-preview-loading').hide();
	});

	/*
	 * Bulk actions
	 */	
	/*
var bulkLink = $('#woocommerce-delivery-notes-bulk-print-link');
	if( bulkLink.length > 0 ) {
		var url = bulkLink.attr('href');
		console.log(url);
		window.open(url, 'name');
	}
*/

	/*
	 * Settings
	 */	 
	 
	// Media managment
	var file_frame;
 
	// Button to open the media uploader
	$('#company-logo-add-button, #company-logo-placeholder').on('click', function(event) {
		event.preventDefault();
		
		// If the media frame already exists, reopen it.
		if(file_frame) {
			file_frame.open();
			return;
		}
		
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader-title' ),
			button: {
				text: jQuery( this ).data( 'uploader-button-title' ),
			},
			multiple: false 
		});
		
		// Open the modal
		file_frame.open();
		
		// When an image is selected, run a callback.
		file_frame.on( 'select', function(event) {
			// We set multiple to false so only get one image from the uploader
			var attachment = file_frame.state().get('selection').first().toJSON();
			
			// Do something with attachment.id and/or attachment.url here
			addImage(attachment.id);
		});
	});
	
	// Button to remove the media 
	$('#company-logo-remove-button').on('click', function(event) {
		event.preventDefault();
		removeImage();
	});
	
	// add media 
	function addImage(id) {
		removeImage();
		$('#company-logo-loader').addClass('loading');

		// load the image		
		var data = {
			attachment_id: id,
			action: 'load_thumbnail'
		}
		
		$.post(ajaxurl, data, function(response) {
			$('#company-logo-image-id').val(data.attachment_id);		
			$('#company-logo-placeholder').html(response);
			$('#company-logo-loader').removeClass('loading');
			$('#company-logo-add-button').hide();
			$('#company-logo-remove-button').show();
		}).error(function() {
			removeImage();
		});

	}
	
	// remove media 
	function removeImage() {
		$('#company-logo-image-id').val('');		
		$('#company-logo-placeholder').empty();
		$('#company-logo-loader').removeClass('loading');
		$('#company-logo-add-button').show();
		$('#company-logo-remove-button').hide();
	}
	
});

