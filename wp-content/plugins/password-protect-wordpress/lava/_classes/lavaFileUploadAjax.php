<?php
/**
 * The File Upload ajax class (handles ajax file upload requests)
 * 
 * 
 * @package Lava
 * @subpackage lavaFileUploadAjax
 * 
 * @author Daniel Chatfield
 * @copyright 2012
 * @version 1.0.0
 */
class lavaFileUploadAjax extends lavaAjax {
	public $targetAction = "file_upload";
	public $formatResponse = false;

	function doAjax() {
		$uploadArray = array();
		if( array_key_exists($this->_slug( "upload" ), $_REQUEST) ) {
			$uploadArray = $_REQUEST[ $this->_slug( "upload" ) ];
		}
		$defaults = array(
			"upload_key" => "error",
			"callback_tag" => ""
		);
		$return = array();
		foreach( $uploadArray as $upload ) {
			$args = wp_parse_args($upload, $defaults);
			extract($args);
			$upload_overrides = array( 'test_form' => false ); 
			$theUpload = wp_handle_upload( $_FILES[$upload_key], $upload_overrides );
			$return[] = array(
				"name" => $_FILES[$upload_key]['name'],
				"size" => $_FILES[$upload_key]['size'],
				"url" => $theUpload['url']
			);

		}

		$this->returnData( $return );
		$this->doReturn();
	}
}
?>