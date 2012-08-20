jQuery(document).ready(function($) {
	
	// click print button
	$('.print-preview-button').on('click', function(event) {
		var url = $(this).attr('href');
	
		if(show_print_preview == 'yes') {
			// print the page with a preview
			tb_show('', url + '&TB_iframe=true&width=720&height=460');
			
			$('#TB_iframeContent').on('load', function(event) {
				var name = $('#TB_iframeContent').attr('name');
				frames[name].focus();
				frames[name].print();
			});
		} else {
			// print the page with a hidden preview window
			if(!$('#printPreview')[0]) {
				// create a new iframe
				var iframe = '<iframe id="printPreview" name="printPreview" src=' + url + ' style="position:absolute;top:-9999px;left:-9999px;border:0px;overfow:none; z-index:-1"></iframe>';
				$('body').append(iframe);
				
				// print when the iframe is loaded
				$('#printPreview').on('load',function() {  
					frames['printPreview'].focus();
					frames['printPreview'].print();
				});
			} else {
				// change the iframe src when the iframe is already appended
				$('#printPreview').attr('src', url);
			}		
		}
		
		event.preventDefault();
	});
		
	// button to open the media uploader
	$('#company-logo-add-button, #company-logo-placeholder img').on('click', function(event) {
		tb_show('', 'media-upload.php?post_id=0&amp;custom_uploader_page=true&amp;type=image&amp;TB_iframe=true');
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

