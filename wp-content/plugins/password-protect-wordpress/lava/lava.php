<?php
/**
 * The main lava class.
 *
 * @package Lava
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 * 
 */
 
 
if( !class_exists( "lava" ) ):

/**
 * lava
 * 
 * @package Lava
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lava
{
    
    private static $instances = array();
    private static $currentPlugin;
    
    /**
     * newPlugin function.
     * 
     * @static
     * @param string $pluginFile (default: __file__) The filepath to the plugin file. Required in cases where the lava framework is defined in in another plugin.
     * @param string $pluginName (default: "Some Plugin")
     * @param float $pluginVersion (default: 1)
     * @return lavaPlugin
     * 
     * @since 1.0.0
     */
    static function newPlugin( $pluginFile = __file__, $pluginName = "Some Plugin", $pluginVersion = 1 )
    {
        if( !class_exists( "lavaPlugin" ) )
        {
            require_once( dirname( __FILE__ ) . "/_classes/lavaPlugin.php" );
        }
        
        
        $pluginSlug = strtolower( str_replace( " ", "_", $pluginName ) );
        
        if( !isset( self::$instances[ $pluginSlug ] ) )
        {
            self::$instances[ $pluginSlug ] = new lavaPlugin( $pluginFile, $pluginName, $pluginVersion );
        }
        
        return self::$instances[ $pluginSlug ];
        
    }
    
    /**
     * fetchPlugin function.
     * 
     * The fetchPlugin function returns the specified plugin instance or false if it has not been declared. This function should be used within a callback to ensure all plugins have been defined.
     * 
     * @access public
     * @static
     * @param mixed $pluginName
     * @return lavaPlugin
     * 
     * @since 1.0.0
     */
    static function fetchPlugin( $pluginName )
    {
        $pluginSlug = strtolower( str_replace( " ", "_", $pluginName ) );
        
        if( isset( self::$instances[ $pluginSlug ] ) )
        {
            return self::$instances[ $pluginSlug ];
        }
        
        return false;
    }
    
    /**
     * pluginExists function.
     * 
     * Checks the existence of a plugin and returns a boolean.
     * 
     * @access public
     * @static
     * @param mixed $pluginName
     * @return bool
     * 
     * @since 1.0.0
     */
    static function pluginExists( $pluginName )
    {
        $pluginSlug = strtolower( str_replace( " ", "_", $pluginName ) );
        
        if( isset( self::$instances[ $pluginSlug ] ) )
        {
            return true;
        }
        
        return false;
    }

    static function currentPlugin( $thePlugin = null )
    {
        if( !is_null( $thePlugin ) )
        {
            self::$currentPlugin = $thePlugin;
        }

        return self::$currentPlugin;
    }
}

endif;

?>