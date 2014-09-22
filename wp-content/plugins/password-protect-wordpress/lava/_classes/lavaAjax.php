<?php
/**
 * The lava Ajax class
 * 
 * 
 * @package Lava
 * @subpackage lavaAjax
 * 
 * @author Daniel Chatfield
 * @copyright 2012
 * @version 1.0.0
 */
 
/**
 * lavaAjax
 * 
 * @package Lava
 * @subpackage LavaAjax
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaAjax extends lavaBase {
	public $targetAction = "undefined";//the action part of the hook to bind to
	public $slugifyAction = true;//if true the plugin slug is prepended to the action to prevent conflict with other plugins
	public $priveledgedOnly = true;//whether to bind to nopriv or not
	public $checkNonce = true;//whether to check the nonce field
	public $formatResponse = true;
	public $responseStatus = "ok";
	public $responseData = "";

	function lavaConstruct() {
		if( $this->slugifyAction ) {
			$this->targetAction = $this->_slug( $this->targetAction );
		}

		$this->_registerActions();
	}

	function _registerActions() {
		add_action('wp_ajax_' . $this->targetAction, array( $this, "_doAjax" ));
	}

	function _doAjax() {
		if($this->checkNonce) {
			if( $this->nonceError() ) {
				$this->returnError( "An error has occured - no valid nonce was supplied so we can't authenticate intention" );
				$this->doReturn();
			}
		}

		$this->doAjax();

		//hmmm it shouldn't still be running
		$this->returnError( "No Terminations" );
		$this->doReturn();
	}

	function doAjax() {

	}

	function nonceError() {
		if( !array_key_exists("nonce", $_GET ) ) {
			if( !array_key_exists("nonce", $_POST) ) {
				return true;//no nonce
			} else {
				$nonce = $_POST['nonce'];
			}
		} else {
			$nonce = $_GET['nonce'];
		}
		if( !wp_verify_nonce($nonce, $this->targetAction) ) {
			return true;
		}
		return false;
	}

	function returnError( $error = "Unknown Error" ) {
		$this->responseStatus = "error";
		$this->responseData = $error;
	}

	function doReturn() {
		if( !$this->formatResponse ) {
			echo json_encode($this->responseData);
		} else {
			$response = array(
				"status" => $this->responseStatus,
				"data" => $this->responseData
			);

			echo json_encode($response);
		}
		die();
	}

	function returnData( $data = "" ) {
		$this->responseData = $data;
	}


}
?>