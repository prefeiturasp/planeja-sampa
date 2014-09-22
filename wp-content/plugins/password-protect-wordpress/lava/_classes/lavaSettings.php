<?php
/**
 * The lava Settings class
 * 
 * This class is the class that controls the settings
 * 
 * @package Lava
 * @subpackage lavaSettings
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaSettings
 * 
 * @package Lava
 * @subpackage LavaSettings
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaSettings extends lavaBase
{
    protected $settings = array();
    protected $settingsIndexes = array();

    protected $settingCache = array();
    protected $useGlobals = false;
    
    /**
     * lavaSettings::lavaConstruct()
     * 
     * This method is called by the __construct method of lavaBase and handles the construction
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function lavaConstruct()
    {
        $callbacks = $this->_new( "lavaSettingsCallback" );
        add_option( $this->_slug( "settings" ), array() );//add the option if it doesn't exist

        $default = array(
            "use_globals" => false
        );
        $networkOptions = get_site_option( "config", $default );

        if( $networkOptions['use_globals'] == true)
        {
            $this->useGlobals = true;
        }
    }
   
    /**
     * lavaSettings::addSetting( $name, $who )
     * 
     * This method adds a plugin setting.
     * 
     * @param $name The name of the setting - should be unique within the section
     * @param $who - Who is adding the setting:
     *      "settings" - This is a plugin setting like "enabled"
     *      "skins" - This is a skin setting
     *      "keyholder" - This is a licensing setting (usually used to prevent file tamper)
     * 
     * @return lavaSetting
     * 
     * @since 1.0.0
     */
    function addSetting( $key, $who = "settings" )
    {
        
        if( !isset( $this->settings[ $who ][ $key ] ) )
        {
            $arguments = array(
                $key,
                $who
            );
            $this->settings[ $who ][ $key ] = $this->_new( "lavaSetting", $arguments );
        }
        $this->lavaContext( $this->settings[ $who ][ $key ] );
        return $this;
    }
    
    /**
     * lavaSettings::fetchSetting( $key, $who )
     * 
     * This method fetches a plugin setting.
     * 
     * @param $key 
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    function fetchSetting( $key, $who = "settings" )
    {
        unset( $this->chain[ "current" ] );//unset it so if the fetch fails then any subsequent chained actions aren't accidentally applied to another setting
        if( isset( $this->settings[ $who ][ $key] ) )
        {
            $this->lavaContext( $this->settings[ $who ][ $key] );
        }
        return $this;
    }


    /**
     * lavaSettings::returnSetting()
     * 
     * This method returns what is in lavaContext
     * 
     * @return lavaSetting
     * 
     * @since 1.0.0
     */
    function returnSetting()
    {
        return $this->lavaContext();
    }

    /**
     * lavaSettings::fetchSettings( $who = "settings" )
     * 
     * This method fetches a plugin setting.
     * 
     * @param $who 
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    function fetchSettings( $who = "settings" )
    {
        if( !array_key_exists( $who, $this->settings ) )
        {
            return array();
        }
        return $this->settings[ $who ];
    }

    function fetchSettingsWithTag( $tag, $who = "settings" ) {
        if(array_key_exists($who, $this->settingsIndexes) and array_key_exists("tags", $this->settingsIndexes[ $who ]) and array_key_exists($tag, $this->settingsIndexes[ $who ][ "tags" ]) ) {
            return $this->settingsIndexes[ $who ][ "tags" ][ $tag ];
        }
        return array();
    }

    /**
     * lavaSettings::addTag( $tag)
     * 
     * This method tags a setting.
     * 
     * @param $tag
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    function _addTag( $tag, $key, $who )
    {
        $this->settingsIndexes[ $who ][ "tags" ][ $tag ][ $key ] = $this->settings[ $who ][ $key ];
    }

    /**
     * lavaSettings::removeTag( $tag)
     * 
     * This method removes a tag from a setting.
     * 
     * @param $tag
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    function _removeTag( $tag, $key, $who )
    {
        unset( $this->settingsIndexes[ $who ][ "tags" ][ $tag ][ $key ]);
    }
    
    /**
     * lavaSettings::settingExists( $key )
     * 
     * This method determines whether a setting exists
     * 
     * @param $key 
     * 
     * @return boolean
     * 
     * @since 1.0.0
     */
    function settingExists( $key, $who = "settings" )
    {
        if( array_key_exists( $who, $this->settings ) and array_key_exists( $key, $this->settings[ $who ] ) ) {
            return true;
        }
        return false;
    }

    function getCache( $who )
    {
        if( !isset( $this->settingCache[ $who ] ) )
        {
            $this->settingCache[ $who ] = $this->getOption( $this->_slug( $who ), array() );
        }

        
        return $this->settingCache[ $who ];
    }

    function putCache( $who, $cache)
    {
        $this->settingCache[ $who ] = $cache;

        return $this;
    }

    function updateCache( $who = "*" )
    {
        
        if( $who == "*" )
        {
            foreach( $this->settingCache as $who => $cache)
            {
                $this->updateOption( $this->_slug( $who ), $cache );
            }
        }
        else
        {
            $this->updateOption( $this->_slug( $who ), $this->settingCache[ $who ] );
        }

        return $this;
    }
    
    function config( $key, $default = null )
    {

        if( isset( $this->config[ $key ] ) )
        {
            return $this->config[ $key ];
        }
        return $default;
    }

    function getOption( $option, $default = null )
    {
        if( $this->useGlobals )
        {
            return get_site_option( $option, $default );
        }
        return get_option( $option, $default );
    }

    function updateOption( $option, $value )
    {
        if( $this->useGlobals )
        {
            return update_site_option( $option, $value );
        }
        return update_option( $option, $value );
    }
}
?>