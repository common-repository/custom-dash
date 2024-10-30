jQuery(document).on( 'click', '#iascd_upload_btn', ias_upload_login_logo );

/**
 * Used to open media popup and replace image url in text field.
 */
function ias_upload_login_logo(e) {
	e.preventDefault();
	var logo_image = wp.media({ 
		title: 'Select Logo',
		multiple: false
	}).open()
	.on( 'select', function(e) {
		var uploaded_image = logo_image.state().get('selection').first();
		var logo_image_url = uploaded_image.toJSON().url;
		jQuery( '#iascd_general_login_logo' ).val( logo_image_url );
	});
}
