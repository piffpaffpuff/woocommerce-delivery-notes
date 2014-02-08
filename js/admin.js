jQuery(document).ready(function($) {
	
	/*
	 * Print button
	 */	 
	
	// button on list and edit screen
	$('.print-preview-button').printLink();
	$('.print-preview-button').on('printLinkClick', function() {
		$('#woocommerce-delivery-notes-box .loading').show();
		$(this).parent().find('.loading').show();
	});
	$('.print-preview-button').on('printLinkLoad', function() {
		$('#woocommerce-delivery-notes-box .loading').hide();
		$(this).parent().find('.loading').hide();
	});
	
	/*
	 * Settings
	 */	 
	 
	// button to open the media uploader
	$('#company-logo-add-button, #company-logo-placeholder').on('click', function(event) {
		tb_show('', 'media-upload.php?post_id=0&company_logo_image=true&type=image&TB_iframe=true');
		event.preventDefault();
	});
	
	// button to remove the media 
	$('#company-logo-remove-button').on('click', function(event) {
		removeImage();
		event.preventDefault();
	});
	
	// called when the "Insert into post" button is clicked
	window.send_to_editor = function(html) {
		removeImage();
		tb_remove();
		
		// find the attachment id
		var tag = $('<div></div>');
		tag.append(html);
		var imgClass = $('img', tag).attr('class');		
		var imgID = parseInt(imgClass.replace(/\D/g, ''), 10);
		
		// load the image		
		var data = {
			attachment_id: imgID,
			action: 'load_thumbnail'
		}
		
		$.post(ajaxurl, data, function(response) {
			$('#company-logo-image-id').val(data.attachment_id);		
			$('#company-logo-placeholder').removeClass('loading').html(response);
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

