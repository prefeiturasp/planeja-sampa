<?php
/**
 * The lavaAjaxHandlers class
 * 
 * This class is the controller for the plugin admin pages
 * 
 * @package Lava
 * @subpackage lavaAjaxHandlers
 * 
 * @author Daniel Chatfield
 * @copyright 2012
 * @version 1.0.0
 */
class lavaAjaxHandlers extends lavaBase
{
    protected $handlers = array();
    
    function adminInit() {
        $this->addAdminAjaxHandlers();
    }

    function addAdminAjaxHandlers() {
        $handlers = array(
            "FileUpload",
            "ImageUpload",
            "DataSource"
        );

        foreach( $handlers as $handler ) {
            $fullHandler = "lava" . $handler . "Ajax";
            $this->handlers[$handler] = $this->_new( $fullHandler );
        }
    }

    function addHandler( $handler ) {
        $this->handlers[$handler] = $this->_new( $handler );
    }
}
?>