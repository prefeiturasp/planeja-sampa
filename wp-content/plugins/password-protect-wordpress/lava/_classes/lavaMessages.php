<?php
/**
 * The lava Messages class
 * 
 * 
 * @package Lava
 * @subpackage lavaMessages
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaMessages
 * 
 * @package Lava
 * @subpackage lavaMessages
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaMessages extends lavaBase
{
    protected $messages = array();
    protected $messageIndexes = array();
    protected $statuses = array();
    
    /**
     * lavaSMessages::lavaConstruct()
     * 
     * This method is called by the __construct method of lavaBase and handles the construction
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function lavaConstruct()
    {

    }

    function newMessage( $message = "ERROR", $type = "warning", $location = "preHead" )
    {
        $messageId = rand( 100000, 999999 );
        $messageArray = array(
            "body" => $message,
            "type" => $type,
            "location" => $location
        );

        $this->messages['messages'][$messageId] = $messageArray;

        $this->messageIndexes['messages']["location"][$messageId] = $messageArray;
        $this->messageIndexes['messages']["type"][$type] = $messageArray;
    }

    function newStatus( $slug, $status)
    {
        $this->statuses[ $slug ] = $status;
    }

    function fetchStatus( $slug )
    {
        return $this->statuses[ $slug ];
    }
   
}
?>