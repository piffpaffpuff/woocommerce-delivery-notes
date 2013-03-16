jQuery(document).ready(function($) {
		
	// click print button
	$('.print-preview-button').on('click', function(event) {
		
		var iframe = null;
		var url = $(this).attr('href');
		
		// just open the href link when iframe printing is not supported			
		if($.browser.opera || ($.browser.msie && parseInt($.browser.version) > 9)) {				
			return;
		}
		
		// otherwise continue with the cool iframe printing
		if(show_print_preview == 'yes') {
			// show the print settings preview in thickbox
			tb_show('', url + '&TB_iframe=true&width=720&height=460');
					
			return false;	
		} else {
			// create the iframe and print dialogue
			$('#woocommerce-delivery-notes-box .loading').show();
			$(this).parent().find('.loading').show();
	
			// print the page with a hidden preview window
			if(!$('#printPreview')[0]) {
				// create a new iframe
				var iframe = '<iframe id="printPreview" name="printPreview" src=' + url + ' style="position:absolute;top:-9999px;left:-9999px;border:0px;overfow:none; z-index:-1"></iframe>';
				$('body').append(iframe);
	
				// print when the iframe is loaded
				$('#printPreview').on('load',function() {  
					$('#woocommerce-delivery-notes-box .loading').hide();
					$(this).parent().find('.loading').hide();
					frames['printPreview'].focus();
					frames['printPreview'].print();
				});
			} else {
				// change the iframe src when the iframe is already appended
				$('#printPreview').attr('src', url);
			}
			
			return false;	
		}
		
		return false;
	});
		
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

