jQuery(document).ready(function($) {
	

	
	// this javascript is called from the custom 
	// button inside the media manager.
	// close the media uploader and set the media 
	window.sendMediaToContent = function(id, media) {
		tb_remove();
		
		jQuery('#company-logo-image-id').val(id);		
		jQuery('#company-logo-placeholder').empty();
		
		// build the image
		var img = new Image();
			
		// loading of image has completed
		jQuery(img).load(function() {
			jQuery(this).show();
			jQuery('#company-logo-placeholder').append(this);
			jQuery('#company-logo-add-button').hide();
			jQuery('#company-logo-remove-button').show();
		}).error(function() {
			jQuery(this).hide();
			removeImage();
		}).attr('src', media);
	}
	
	// remove media 
	function removeImage() {
		jQuery('#company-logo-image-id').val('');		
		jQuery('#company-logo-placeholder').empty();
		jQuery('#company-logo-add-button').show();
		jQuery('#company-logo-remove-button').hide();
	}

	// button to open the media uploader
	jQuery('#company-logo-add-button').click(function() {
		tb_show('', 'media-upload.php?post_id=0$amp;type=image&amp;TB_iframe=true');
		return false;
	});
	
	// button to remove the media 
	jQuery('#company-logo-remove-button').click(function() {
		removeImage();
		return false;
	});

});

