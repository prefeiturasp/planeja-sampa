<?php
/*
Add premium feature labels
Add premium feature warning
Add premium feature trial load
Add licensing to bar

*/
class private_blog_vendor extends lavaExtension {

	public $apiVersion = 1;

	function init() {
		$this->registerLicensingSettings();
	}

	function adminInit(){
		$this->addAction( "ajaxChecks" );
		$this->addAction( "lavaNav" );
		$this->addAction( "displayUnderground" );
		$this->addAction( 'pageHiddenStuff' );
		$this->_pages()
				->addScript( $this->_slug( "vendor_js" ), "_static/vendor.js" )
				->addStyle( $this->_slug( "vendor_css" ), "_static/vendor.css" )
		;
		$this->_ajax()->addHandler( 'lavaVolcanicPixelsLicensingAjax' );

	}


	function doLicensingHooks() {
		if( md5( $this->privateKey() ) != $this->publicKey() ) {
			$this->addFilter( "settingAbsElements-tag/is-premium", "settingAbsElements" );
		} else {
			$this->addFilter( "settingClasses-tag/is-premium", "removePremiumBlock" );
		}
	}

	function removePremiumBlock( $classes ) {
		unset( $classes['tag-is-premium'] );

		return $classes;
	}

	function registerLicensingSettings() {
		$this->_settings()
			->addSetting('license_public', 'vendor')
			->addSetting('license_private', 'vendor')
		;
		$this->doLicensingHooks();
	}

	function ajaxChecks(){
		//these are now handled client side
	}

	function lavaNav(){
		$code_link_text = 'Redeem key';
		if( $this->publicKey() != '' ) {
			$code_link_text = 'Change key';
		}
		?>
		<a href="#unlock" title="Click to purchase a code to unlock premium features" class="tiptip vendor-link get-premium-link">Get premium</a>
		<a href="#redeem" title="Click to redeem a previously purchased code to unlock premium features" class="tiptip vendor-link redeem-code-link"><?php echo $code_link_text ?></a>
		<?php
	}

	function settingAbsElements( $current ) {
		$current .= '
		<div class="premium-notice remove-for-trial">
			<div class="premium-notice-inner">
				<div class="premium-line">
					<div class="lava-btn vendor-link get-premium-link tiptip" title="Click to purchase a license to permanently unlock premium features">Get premium</div>
				</div>
			</div>
		</div>';

		return $current;
	}

	function displayUnderground() {
		$this->getPremiumUi();
		$this->redeemPremiumUi();
	}

	function getPremiumUi() {
		?>
		<div class="underground-section underground-hidden underground-context-get-premium loading">
			<h2>Get Premium</h2>
			<div class="lava-new-message lava-message-notice" style="background: white">Licenses can be transferred between websites but excesively doing this (doing it over 10 times in a week for example) may cause the license to be blacklisted</div>

			<div class="lava-new-message lava-message-notice" style="background: white">The price will be converted into your local currency before the transaction completes</div>
			<div class="license-options clearfix">
				<div class="lava-loader loading">
			        <span class="child1"></span>
			        <span class="child2"></span>
			        <span class="child3"></span>
			        <span class="child4"></span>
			        <span class="child5"></span>
			    </div>
			</div>

			<button data-clicked-text="Please wait ..." class="lava-btn lava-btn-action  lava-btn-block lava-btn-action-red purchase-premium-button" style="display: inline; margin-top: 30px">Purchase with PayPal</button>
		</div>
		<?php
	}

	function redeemPremiumUi(){
		//currently we offer no diagnostics

	}

	function pageHiddenStuff() {
		$this->licensingFields();
	}

	function licensingFields() {
		$license_status = "free";
		if( md5( $this->privateKey() ) == $this->publicKey() ) {
			$license_status = "premium";
		}
		$lava_variables = array(
			'package_slug' => $this->_slug(),
			'package_version' => $this->_version(),
			'install_id' => $this->getInstallId(),
			'install_url' => get_home_url(),
			'install_name' => get_bloginfo( 'name' ),
			'private_key' => $this->getPrivateKey(),
			'public_key' => $this->getPublicKey(),
			'license_status' => $license_status,
			'licensing_nonce' => wp_create_nonce( $this->_slug( "licensing" ) ),
			'ajax_action' => $this->_slug('licensing'),
			'vendor_url' => $this->getVendorUrl( 'api/' . $this->apiVersion . '/' )
		);
		foreach( $lava_variables as $variable_name => $variable_key ): 
			?>
				<input type="hidden" class="vendor-input" data-variable-name="<?php echo $variable_name ?>" value="<?php  echo $variable_key ?>"/>
			<?php
		endforeach;
	}

	function getPublickey() {
		return $this->_settings()->fetchSetting('license_public', 'vendor')->getValue();
	}

	function getPrivateKey() {
		return $this->_settings()->fetchSetting('license_private', 'vendor')->getValue();
	}

	function publickey() {
		return $this->_settings()->fetchSetting('license_public', 'vendor')->getValue();
	}

	function privateKey() {
		return $this->_settings()->fetchSetting('license_private', 'vendor')->getValue();
	}

	function getInstallId() {
		return md5( AUTH_SALT . get_home_url() . 'private_blog' );
	}

	function getVendorUrl( $append = "" ) {
		if( ! defined( 'LAVA_API_IS_LOCAL' ) ) {
			define( 'LAVA_API_IS_LOCAL', false );
		}
		if( LAVA_API_IS_LOCAL ) {
			return "http://localhost:11080/" . $append;
		} else {
			return 'http://legacy.volcanicpixels.com/' . $append;
		}
	}
}
?>
