jQuery(document).ready(function(){
	bindChangeKey();
	bindGetKey();
	bindPurchaseKey();

	doRegister();
	
})

/* accessor methods (get) */

function getLavaVariable( variableName ) {
	return jQuery('input.vendor-input[data-variable-name="' + variableName + '"]').val();
}

function getPublicKey() {
	return getLavaVariable( 'public_key' );
}

function getPrivateKey() {
	return getLavaVariable( 'private_key' );
}

function getVendorUrl() {
	return getLavaVariable( 'vendor_url' );
}

function getInstallId() {
	return getLavaVariable( 'install_id' );
}

function getInstallVersion() {
	return getLavaVariable( 'package_version' );
}

function getInstallUrl() {
	return getLavaVariable( 'install_url' );
}

function getInstallName() {
	return getLavaVariable( 'install_name' );
}

function getPackageSlug() {
	return getLavaVariable( 'package_slug' );
}

/* accessor methods (set) */


function setLavaVariable( variableName, variableValue ) {
	return jQuery('input.vendor-input[data-variable-name="' + variableName + '"]').val( variableValue );
}

function setPublicKey( public_key ) {
	if (public_key == undefined) {
		public_key = '';
	} 
    public_key = public_key.replace(/(^\s+|\s+$)/g,'');
	return setLavaVariable( 'public_key', public_key );
}

function setPrivateKey( private_key ) {
	return setLavaVariable( 'private_key', private_key );
}


/* Dom manipulation */

function createAjaxBlinker( method, title ) {
	//remove any existing blinkers
	jQuery('.lava-ajax-checks .ajax-blinker[data-method="' + method + '"]').remove();

	jQuery('<span></span>').addClass('ajax-blinker').attr( 'title', title ).attr( 'data-method', method ).attr( 'data-status', 'loading' ).appendTo( '.lava-ajax-checks' ).tipTip({'delay':0});
}

function setAjaxBlinkerStatus( method, status, title ) {
	var blinker = jQuery('.lava-ajax-checks .ajax-blinker[data-method="' + method + '"]').attr( 'data-status', status );

	if( typeof(title) != "undefined" ) {
		jQuery( blinker ).attr( "title", title ).tipTip( {'delay': 0} );
	}
}

function showLavaLoader() {
	jQuery('body').addClass('lava-loading');
}

function hideLavaLoader() {
	jQuery('body').removeClass('lava-loading');
}

/* Event binders */

function bindChangeKey() {
	jQuery('.vendor-link.redeem-code-link').attr('href', '#redeem').click(function(){
		var current_key = getPublicKey();

		var userInput = prompt( 'Enter license key:', current_key );

		if (userInput !== null) {
			setPublicKey(userInput);
			doLicensePush();
		}
	});
}

function bindGetKey() {
	jQuery('.vendor-link.get-premium-link').attr('href', '#purchase').click(function(){
		showUnderground( 'get-premium' );
		var method = 'get_license_options';
		var request = doApiRequest( method ).success(function(data){
			var license_types = data.licenses;
			jQuery('.underground-context-get-premium .license-options').html('');
			var keys = [];
			for (var key in license_types) {
				keys.push(key);
			}
			keys.sort();

			len = keys.length;

			for(var i=0; i < len; i++) {
				license_type = keys[i];
				var the_license = license_types[license_type];
				the_license = '<div class="license-option " data-price="' + the_license.price + '" data-product="' + license_type + '"><h3>' + the_license.name + '</h3><div class="description">' + the_license.description + '</div></div>';
				jQuery('.underground-context-get-premium .license-options').append(the_license);
				jQuery('.underground-context-get-premium .license-options .license-option:first-child').addClass('selected');
				jQuery('.license-option').click(function(){
					jQuery('.license-option').removeClass('selected');
					jQuery(this).addClass( 'selected');
				});
			}
		});
	});
}

function bindPurchaseKey() {
	jQuery('.lava-btn.purchase-premium-button').click(function(){
		jQuery(this).html(jQuery(this).attr("data-clicked-text") );

		var method = "setup_payment";
		var args = {
			'purchase_id' : jQuery('.license-option.selected').attr('data-product')
		};
		var request = doApiRequest( method, args ).success(function(data){
			location.href = data.checkout_url;
		})
	});
}




/* Api Request functions  */

function addDefaultArgs( args ) {
	if( typeof( args ) == "undefined" ) {
		args = {};
	}

	var default_args = {
		'install_version': getInstallVersion(),
		'package_slug': getPackageSlug()
	}
	for( arg_name in default_args ) {
		if( ! args.hasOwnProperty( arg_name ) ) {
			args[arg_name] = default_args[arg_name];
		}
	}

	return args;
}

function doRegister() {
	var method = 'register';
	var title = 'Registering ...'
	//show status indicator
	createAjaxBlinker( method, title );

	var args = {
		'install_url': getInstallUrl(),
		'install_name': getInstallName()
	};

	var request = doApiRequest( method, args ).success(function(data){
		setAjaxBlinkerStatus( method, "success", "Registered" );
		doLicenseCheck();
	})
}

function doLicenseCheck() {
	var method = 'is_licensed';
	var title = 'Checking license status ...'
	//show status indicator
	createAjaxBlinker( method, title );

	var args = {
		'public_key': getPublicKey(),
		'private_key': getPrivateKey()
	};

	var request = doApiRequest( method, args ).success(function(data){
		if( data.license_status == 'alive' || data.license_status == 'dead' ) {
			if( data.public_key != getPublicKey() || data.private_key != getPrivateKey() ) {
				setPublicKey( data.public_key );
				setPrivateKey( data.private_key );
				if( data.license_message != undefined ) {
					if( data.license_message.length > 0) {
						alert( data.license_message );
					}
				}
				doLicensePush();
			}

			if( data.license_status == 'alive' ) {
				setAjaxBlinkerStatus( method, 'success', 'License accepted' );
			} else if( data.license_status == 'dead' ) {
				setAjaxBlinkerStatus( method, 'error', 'This installation is not licensed for premium features' )
			} else if( data.license_status == 'old' ) {
				alert( data.old_message );
			}
		}

		if (data.license_status === 'engaged') {
			setAjaxBlinkerStatus( method, 'error', 'Licensed already being used' );
			setPublicKey('');
			alert("This license key is already being used on the maximum number of sites, to unlicense a site enter 'unlicense' as the key in the site you wish to unlicense.");
			doLicensePush();
		}
	})
}

function doLicensePush() {
	showLavaLoader();
	var request_url = ajaxurl 	+ '?action='+ getLavaVariable( 'ajax_action' )
								+ '&nonce=' + getLavaVariable( 'licensing_nonce' )
								+ '&public_key=' + getPublicKey()
								+ '&private_key=' + getPrivateKey();

	jQuery.getJSON( request_url ).success(function(){
		location.reload();
	}).error(function(){
		alert('An error occured whilst pushing license to database');
		location.reload();
	});
}


function doApiRequest( method, args ) {
	args = addDefaultArgs( args );
	var request_url = getVendorUrl() + method + '/?jsoncallback=?&install_id=' + getInstallId();

	if( typeof(args) == "undefined" ) {
		args = {};
	}
	return jQuery.getJSON( request_url, args );
}
