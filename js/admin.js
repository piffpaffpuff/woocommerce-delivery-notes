jQuery(document).ready(function($) {
	
	/*
	 * Print button
	 */	 
	
	// Button on list and edit screen
	$('.print-preview-button').printLink();
	$('.print-preview-button').on('printLinkInit', function(event) {
		$(this).parent().find('.print-preview-loading').addClass('is-active');
	});
	$('.print-preview-button').on('printLinkComplete', function(event) {
		$('.print-preview-loading').removeClass('is-active');
	});
	$('.print-preview-button').on('printLinkError', function(event) {
		$('.print-preview-loading').removeClass('is-active');
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
	$('.wcdn-image-select-add-button, .wcdn-image-select-attachment').on('click', function(event) {
		event.preventDefault();
		
		// If the modal already exists, reopen it.
		if(media_modal) {
			media_modal.open();
			return;
		}
		
		// Create the modal.
		media_modal = wp.media.frames.media_modal = wp.media({
			title: $('.wcdn-image-select-add-button').data( 'uploader-title' ),
			button: {
				text: $('.wcdn-image-select-add-button').data( 'uploader-button-title' ),
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
	$('.wcdn-image-select-remove-button').on('click', function(event) {
		event.preventDefault();
		removeImage();
	});
	
	// add media 
	function addImage(id) {
		removeImage();
		$('.wcdn-image-select-spinner').addClass('is-active');

		// load the image		
		var data = {
			attachment_id: id,
			action: 'wcdn_settings_load_image',
			nonce: $('.submit #_wpnonce').val()
		}
		
		$.post(ajaxurl, data, function(response) {
			$('.wcdn-image-select-image-id').val(data.attachment_id);		
			$('.wcdn-image-select-attachment .thumbnail').html(response);
			$('.wcdn-image-select-spinner').removeClass('is-active');
			$('.wcdn-image-select-add-button').addClass('hidden');
			$('.wcdn-image-select-remove-button').removeClass('hidden');
		}).error(function() {
			removeImage();
		});

	}
	
	// remove media 
	function removeImage() {
		$('.wcdn-image-select-image-id').val('');		
		$('.wcdn-image-select-attachment .thumbnail').empty();
		$('.wcdn-image-select-spinner').removeClass('is-active');
		$('.wcdn-image-select-add-button').removeClass('hidden');
		$('.wcdn-image-select-remove-button').addClass('hidden');
	}
	
	$('input#woocommerce_demo_store').change(function() {
		if ($(this).is(':checked')) {
			$('#woocommerce_demo_store_notice').closest('tr').show();
		} else {
			$('#woocommerce_demo_store_notice').closest('tr').hide();
		}
	}).change();
	
	// Toggle invoice number fields
	$('input#wcdn_create_invoice_number').on('change', function(event) {
		if ($(this).is(':checked')) {
			$('.create-invoice').closest('tr').removeClass('hidden');
		} else {
			$('.create-invoice').closest('tr').addClass('hidden');
		}
	}).trigger('change');
/*
	// Toggle invoice number fields
	$('#create-invoice-number').on('change', function(event) {
		$('.invoice-number-row').toggle();
		event.preventDefault();
	});
	
	// Button to reset the invoice counter 
	$('#reset-invoice-counter').on('click', function(event) {
		event.preventDefault();
		
		// Text strings are pulled from wp_localize_script
		var reset = window.confirm(WCDNText.resetCounter);
		
		// Reset the counter
		if(reset) {
			var data = {
				action: 'wcdn_reset_counter',
				reset: true,
				nonce: $('#mainform #settings-nonce').val()
			}

			$.post(ajaxurl, data, function(response) {
				$('#invoice-counter-value').text('0');
			});
		}
	});
*/
	
});

