jQuery(document).ready(function($) {
		
	// click print button
	$('.print-preview-button').on('click', function(event) {
		
		var tab = null;
		var iframe = null;
		var button = $(this);
		var url = $(this).attr('href');
		
		// show the print settings preview in thickbox
		if(show_print_preview == 'yes') {
			tb_show('', url + '&TB_iframe=true&width=720&height=460');
					
			return false;	
		}
		
		// remove old iframe
		$('#printIFrame').remove();
				
		// open tab or iframe
		if($.browser.opera) {
			tab = window.open('about:blank');
            tab.document.location = url; 
        } else {
        	iframe = $('<iframe id="printIFrame" name="printIFrame" src="about:blank" style="position:absolute;top:-9999px;left:-9999px;border:0px;overfow:none; z-index:-1"></iframe>');
        	$('body').append(iframe);
        	
        	// show loader
        	$('#woocommerce-delivery-notes-box .loading').show();
			button.parent().find('.loading').show();
        	
        	// load content
	       	iframe.attr('src', url);	       
        }
		
		// use a timeout to make it work cross-browser, kind of a hack
		setTimeout(function() {
			
			// check browser
			if ($.browser.opera) {
				var doc = tab.document;
				var win = tab;
			} else {
				var doc = iframe.contents();
				var win = iframe.get(0).contentWindow;
			}
				        
	        // focus window
	        win.focus();
	    
	        setTimeout(function() { 
	        	// print window
	        	win.print();
	        	
	        	// close tab if it exists
		        if(tab) { 
			        tab.close(); 
		        } 
		        
		        // hide the loader
				$('#woocommerce-delivery-notes-box .loading').hide();
				button.parent().find('.loading').hide();
		    }, 1000);
	         
	        //removed iframe after 60 seconds
	        setTimeout(function() {
		    	iframe.remove();
		    }, (30 * 1000));
	    }, 333);
		
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

