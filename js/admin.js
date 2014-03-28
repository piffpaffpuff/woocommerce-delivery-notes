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
	$('.print-preview-button').on('printLinkError', function(event) {
		$('.print-preview-loading').hide();
		tb_show('', $(this).attr('href') + '&amp;TB_iframe=true&amp;width=800&amp;height=500');
	});

	/*
	 * Bulk actions print button in the confirm message
	 */	
	$(window).on('load', function(event) {
		var bulkButton = $('#woocommerce-delivery-notes-bulk-print-button');
		if( bulkButton.length > 0 ) {
			bulkButton.trigger('click');
		}
	});

	/*
	 * Settings
	 */	 
	 
	// Media managment
	var media_modal;
 
	// Button to open the media uploader
	$('#company-logo-add-button, #company-logo-placeholder').on('click', function(event) {
		event.preventDefault();
		
		// If the modal already exists, reopen it.
		if(media_modal) {
			media_modal.open();
			return;
		}
		
		// Create the modal.
		media_modal = wp.media.frames.media_modal = wp.media({
			title: $('#company-logo-add-button').data( 'uploader-title' ),
			button: {
				text: $('#company-logo-add-button').data( 'uploader-button-title' ),
			},
			multiple: false 
		});
		
		// Open the modal.
		media_modal.open();
		
		// When an image is selected, run a callback.
		media_modal.on( 'select', function(event) {
			// We set multiple to false so only get one image from the uploader
			var attachment = media_modal.state().get('selection').first().toJSON();
			
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

