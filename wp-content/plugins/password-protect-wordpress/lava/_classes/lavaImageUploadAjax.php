<?php
/**
 * The image Upload ajax class (handles ajax file upload requests)
 * 
 * 
 * @package Lava
 * @subpackage lavaImageUploadAjax
 * 
 * @author Daniel Chatfield
 * @copyright 2012
 * @version 1.0.0
 */
class lavaImageUploadAjax extends lavaFileUploadAjax {
	public $targetAction = "image_upload";
	public $allowedExtensions = array( "png", "gif", "jpg", "bmp" );
}
?>