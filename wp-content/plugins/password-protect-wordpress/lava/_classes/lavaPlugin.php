<?php
/**
 * The lava plugin class
 * 
 * This class is the main plugin class and the only class that doesn't extend lavaBase
 * 
 * @package Lava
 * @subpackage lavaPlugin
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaPlugin
 * 
 * @package Lava
 * @subpackage LavaPlugin
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaPlugin
{
    /**
     * __construct function.
     * 
     * @access public
     * @param mixed $pluginName
     * @param mixed $pluginVersion
     * @return void
     * 
     * @since 1.0.0
     */
    function __construct( $pluginFile, $pluginName, $pluginVersion, $loadVendor = true )
    {
        $this->pluginFile = apply_filters( "junction_link_fix", $pluginFile );
        $this->pluginName = $pluginName;
        $this->pluginVersion = $pluginVersion;
        $this->pluginSlug = strtolower( str_replace( " ", "_", $pluginName ) );
        $this->pluginCallbacks = null;
        
        spl_autoload_register( array( $this, "__autoload" ) );
        $filename = dirname( $pluginFile ).'/pluginCallbacks.php';
        if( file_exists( $filename ) )
        {
            include( $filename );
            $className = $this->_slug( "callbacks" );
            $this->pluginCallbacks = $this->_new( $className );
        }

        $this->_misc();//initialise this class
        if( $loadVendor ) {
            require_once( dirname( $pluginFile ) .  "/vendor.php" );
            $className = $this->_slug( "vendor" );
            $this->pluginVendor = $this->_new( $className );
        }
    }
    
    /**
     * __autoload function.
     * 
     * The __autoload function defines what to do when a non-declared class is referenced
     * 
     * @access public
     * @param mixed $className
     * @return void
     * 
     * @since 1.0.0
     */
    function __autoload( $className )
    {    
        if( file_exists( dirname( __FILE__ ) . "/{$className}.php" ) AND !class_exists( $className ) )//don't want to include the file if it doesn't exist
        {
        	include_once( dirname( __FILE__ ) . "/{$className}.php" );
        }

        if( file_exists( dirname( $this->_file() ) . "/_classes/{$className}.php" ) AND !class_exists( $className ) )//don't want to include the file if it doesn't exist
        {
            include_once( dirname( $this->_file() ) . "/_classes/{$className}.php" );
        }
    }
    
    
    
    
    
    
    /**
     * _name function.
     * 
     * @return ->pluginName
     * 
     * @since 1.0.0
     */
    function _name()
    {
        return $this->pluginName;
    }

    /**
     * _this function.
     * 
     * @return lavaPlugin
     * 
     * @since 1.0.0
     */
    function _this()
    {
        return $this;
    }

	/**
     * _request function.
	 *	Determines whether the current request matches the argument
     * 
     * @return lavaPlugin
     * 
     * @since 1.0.0
     */
    function _request( $request )
    {
		switch( $request )
		{
			case "admin":
				return is_admin();
			break;
			default:
				return true;
		}
    }
    
    /**
     * _slug function.
     * 
     * @return ->pluginSlug
     * 
     * @since 1.0.0
     */
    function _slug( $append = null )
    {
        $append = empty( $append )? "" : "_{$append}";
        return $this->pluginSlug . $append;
    }
    
    /**
     * _version function.
     * 
     * @return ->pluginVersion
     * 
     * @since 1.0.0
     */
    function _version()
    {
        return $this->pluginVersion;
    }
    
    /**
     * _file function.
     * 
     * @return ->pluginFile
     * 
     * @since 1.0.0
     */
    function _file()
    {
        return $this->pluginFile;
    }
    
    
    
    
    
    
    /**
     * _new function.
     * 
     * The _new function is used for instantiating new classes - it is needed for chaining to work
     * 
     * @access private
     * @param mixed $className
     * @param array $arguments
     * 
     * @return new class
     * 
     * @since 1.0.0
     */
    function _new( $className, $arguments = array() )
    {
        return new $className( $this, $arguments );
    }
    
    /**
     * _framework function
     * 
     * Function used for translation purposes
     * 
     * @return framework version
     * 
     * @since 1.0.0
     */
    function _framework()
    {
        return "lavaPlugin";
    }
    
    /**
     * _handle function.
     * 
     * 
     * 
     * @access private
     * @param mixed $what
     * @param bool $reset
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function _handle( $what, $reset )
    {
        $pointer = "_" . strtolower( $what );
        if( !isset( $this->$pointer ) )
        {
            $this->$pointer = $this->_new( "lava$what" );
        }
        if( $reset == true )
        {
            return $this->$pointer->lavaReset();
        }
        else
        {
            return $this->$pointer->getThis();
        }
    }

    /**
     * _ajax function.
     * 
     * @return lavaAjaxHandlers
     * 
     * @since 1.0.0
     */
    function _ajax( $reset = true )
    {
        return $this->_handle( "AjaxHandlers", $reset );
    }
    
    /**
     * _settings function.
     * 
     * @return lavaSettings
     * 
     * @since 1.0.0
     */
    function _settings( $reset = true )
    {
        return $this->_handle( "Settings", $reset );
    }

    /**
     * _skins function.
     * 
     * @return lavaSkins
     * 
     * @since 1.0.0
     */
    function _skins( $reset = true )
    {
        return $this->_handle( "Skins", $reset );
    }
    
    /**
     * _pages function.
     * 
     * @return lavaPages
     * 
     * @since 1.0.0
     */
    function _pages( $reset = true)
    {
        return $this->_handle( "Pages", $reset );
    }
    
    /**
     * _messages function.
     * 
     * @return lavaPages
     * 
     * @since 1.0.0
     */
    function _messages( $reset = true)
    {
        return $this->_handle( "Messages", $reset );
    }
    
    /**
     * _tables function.
     * 
     * @return lavaTables
     * 
     * @since 1.0.0
     */
    function _tables( $reset = true)
    {
        return $this->_handle( "Tables", $reset );
    }

    function _misc( $reset = true )
    {
        return $this->_handle( "MiscFunctions", $reset);
    }
    
}
?>