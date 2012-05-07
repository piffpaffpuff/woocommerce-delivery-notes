jQuery(document).ready(function($) {

	// remove taxonomy image button
	removeImage = function() {
		jQuery('#company-logo-image-id').val('');		
		jQuery("#company-logo-placeholder").empty();
		jQuery("#company-logo-add-button").show();
		jQuery("#company-logo-remove-button").hide();
	}
		
	// this javascript is called from the button inside the media manager
	// remove the media uploader and set the image
	window.sendImageToContent = function(id, image) {
		tb_remove();
		
		jQuery('#company-logo-image-id').val(id);		
		jQuery("#company-logo-placeholder").empty();
		
		// build the image
		var img = new Image();
			
		// loading of image has completed
		jQuery(img).load(function() {
			jQuery(this).show();
			jQuery("#company-logo-placeholder").append(this);
			jQuery("#company-logo-add-button").hide();
			jQuery("#company-logo-remove-button").show();
		}).error(function() {
			jQuery(this).hide();
			removeImage();
		}).attr("src", image);
	}
	
	// remove taxonomy image button
	jQuery("#company-logo-remove-button").click(function() {
		removeImage();
		return false;
	});
	
	// remove the image on the quick edit form once the tax is saved
	if(jQuery("#company-logo-add-button").length > 0) {
		jQuery('#submit').ajaxComplete(function(event, request, settings) {
			removeImage();
		});
	}

});

