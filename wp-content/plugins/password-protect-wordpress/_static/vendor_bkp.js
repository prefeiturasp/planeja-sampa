var lava_api_version = 1;
var lava_vendor_uri = "http://www.volcanicpixels.com/api/" + lava_api_version;

if( location.href.indexOf('localhost') != -1 ){
	lava_vendor_uri = "http://localhost:8082/api/" + lava_api_version;
}
var install_id = "DEVELOPMENTINSTALL2";
var install_url = encodeURIComponent('http://localhost:31786/');
var install_name = "WordPress Beta";
var install_version = "4.0 beta";
var package_slug = "private_blog";
//lava_vendor_uri = 'http://localhost:8082/api/' + lava_api_version;

jQuery(document).ready(function(){
	//do register
	

	var the_url = lava_vendor_uri + '/register/'
				+ '?install_id=' + install_id
				+ '&install_version=' + install_version
				+ '&package_slug=' + package_slug
				+ '&install_name='+ install_name
				+ '&install_url=' + install_url
	;
	jQuery.getJSON( the_url, function(data){
		if( data.status == "complete" ) {
			jQuery('.ajax-check.type-register').removeClass( "loading" ).addClass( "complete" ).attr("title", "Registered").tipTip({'delay':0});
		} else {
			jQuery('.ajax-check.type-register').removeClass( "loading" ).addClass( "error" ).attr("title", "An error occured").tipTip({'delay':0});
		}
	});



	showPremiumUI()
})

function showPremiumUI() {
	jQuery('.setting.tag-is-premium .premium-notice').attr("title", "This is a premium feature, either enter trial mode or purchase a license to use.").tipTip({'delay':0});

	jQuery('.start-trial').click(function(){
		enterTrialMode();
	})


	jQuery('.get-premium-link').attr("href", "#unlock").click(function(){
		showUnderground( "get-premium" );
		var the_url = lava_vendor_uri + '/get_license_options/'
				+ '?package_slug=' + package_slug
		;
		jQuery.getJSON( the_url, function(data){
			jQuery('.underground-context-get-premium').removeClass('loading');
			var license_types = data.licenses;
			jQuery('.underground-context-get-premium .license-options').html('');
			for( license_type in license_types) {
				the_license = license_types[license_type];
				var the_license = '<div class="license-option " data-price="' + the_license.price + '" data-product="' + license_type + '"><h3>' + the_license.name + '</h3><div class="description">' + the_license.description + '</div></div>';
				jQuery('.underground-context-get-premium .license-options').append(the_license)
			}
			jQuery('.underground-context-get-premium .license-options .license-option:first-child').addClass('selected');
			jQuery('.license-option').click(function(){
				jQuery('.license-option').removeClass('selected');
				jQuery(this).addClass( 'selected');
			});
		});

	});
	

	jQuery('.redeem-code-link').attr("href", "#verify").click(function(){
		current_key = jQuery('.vendor-input.license-public').val();
		userInput = prompt('Enter License key', current_key );
		if (userInput != null) {
			jQuery('.vendor-input.license-public').val(userInput);
			checkLicense(false);
		}
	})


	checkLicense(true);
	

	jQuery('.lava-btn.purchase-premium-button').click(function(){
		jQuery(this).html(jQuery(this).attr("data-clicked-text") );

		the_url = lava_vendor_uri + '/setup_payment/'
				+ '?package_slug=' + package_slug
				+ '&purchase_id=' + jQuery('.license-option.selected').attr('data-product');
		jQuery.getJSON( the_url, function(data){
			location.href = data.checkout_url;
		});
	});
}

function enterTrialMode() {
	jQuery('.remove-for-trial').remove()
	jQuery('.setting.tag-is-premium').removeClass( 'tag-is-premium' );
	jQuery('.started-trial').removeClass('hidden');
	jQuery('.lava-form-purpose').val('trial');
}

function checkLicense( routine ) {
	var license_pub = jQuery('.vendor-input.license-public').val();
	jQuery('.ajax-check.type-licensing').show().removeClass( "complete" ).removeClass( "error" ).addClass('loading').attr("title", "Checking License...").tipTip({'delay':0});
	if( license_pub.length == 0 ) {
		jQuery('.ajax-check.type-licensing').hide();
		if( routine ) {
			console.log('just routine')
			return;//no license
		}
	}
	var installation_to_unlicense = jQuery(this).attr('data-installation_to_unlicense');
	the_url = lava_vendor_uri + '/is_licensed/'
				+ '?install_id=' + install_id
				+ '&license_public=' + license_pub
				+ '&installation_to_unlicense=' + installation_to_unlicense
	;

	if(routine){
		//this is a routine license check (no changes were made) - if the license is alive then that is fine
		jQuery.getJSON( the_url, function(data){
			if( data.license_status == "alive" ) {
				jQuery('.ajax-check.type-licensing').removeClass( "loading" ).addClass( "complete" ).attr("title", "License approved").tipTip({'delay':0});
			} else if(data.license_status == "dead") {
				jQuery('.ajax-check.type-licensing').removeClass( "loading" ).addClass( "error" ).attr("title", "License rejected").tipTip({'delay':0});
				var action = jQuery('.vendor-input.ajax-action').val();
				var nonce = jQuery('.vendor-input.ajax-nonce').val();
				var the_url = ajaxurl + '?action=' + action + '&nonce=' + nonce 
							+ '?private_key=' + ''
							+ '&public_key=' + jQuery('.vendor-input.license-public').val();
				;
				alert( data.license_error_message );
				var private_key = jQuery('.vendor-input.license-private').val();

				if( private_key.length != 0 ) {//don't need to remove it if it isn't there
					jQuery.getJSON( the_url, function(data){
						location.reload();
					});
				}
			}
		});
	} else {
		jQuery('body').addClass( 'lava-loading' );
		jQuery.getJSON( the_url, function(data){
			if( data.license_status == "alive" ) {
				jQuery('.ajax-check.type-licensing').removeClass( "loading" ).addClass( "complete" ).attr("title", "License approved").tipTip({'delay':0});
				action = jQuery('.vendor-input.ajax-action').val();
				nonce = jQuery('.vendor-input.ajax-nonce').val();
				the_url = ajaxurl + '?action=' + action + '&nonce=' + nonce 
							+ '&private_key=' + data.license_private
							+ '&public_key=' + data.license_public
				;

				jQuery.getJSON( the_url, function(data){
					location.reload();
				});
			} else if(data.license_status == "dead") {
				jQuery('.ajax-check.type-licensing').removeClass( "loading" ).addClass( "error" ).attr("title", "License rejected").tipTip({'delay':0});
				action = jQuery('.vendor-input.ajax-action').val();
				nonce = jQuery('.vendor-input.ajax-nonce').val();
				the_url = ajaxurl + '?action=' + action + '&nonce=' + nonce 
							+ '&private_key=' + ''
							+ '&public_key=' + jQuery('.vendor-input.license-public').val();
				;
				alert( data.license_error_message );
				jQuery.getJSON( the_url, function(data){
					location.reload();
				});
			}
		});
	}
}