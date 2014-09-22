<?php
/**
 * The Volcanic Pixels licensing ajax class
 * 
 * 
 * @package Lava
 * @subpackage lavaVolcanicPixelsLicensingAjax
 * 
 * @author Daniel Chatfield
 * @copyright 2012
 * @version 1.0.0
 */
class lavaVolcanicPixelsLicensingAjax extends lavaAjax {
	public $targetAction = "licensing";

	function doAjax() {
		$private_key = $_REQUEST['private_key'];
		$public_key = $_REQUEST['public_key'];
		$this->_settings()->fetchSetting('license_public', 'vendor')->updateValue( $public_key );
		$this->_settings()->fetchSetting('license_private', 'vendor')->updateValue( $private_key );

		$return = array(
			"status" => "complete"
		);

		$this->returnData( $return );
		$this->doReturn();
	}
}
?>