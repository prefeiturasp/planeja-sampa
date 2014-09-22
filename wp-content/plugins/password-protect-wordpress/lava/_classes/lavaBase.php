<?php
/**
 * The lava base class
 * 
 * This class is the base class for all lava classes - it adds chaining and config.
 * 
 * @package Lava
 * @subpackage lavaBase
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaBase
 * 
 * @package Lava
 * @subpackage LavaBase
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaBase
{
    protected $pluginInstance;
    protected $chain = array();
    protected $memory = array();
    public $suffixes = array( "/pre", "", "/post" );
    public $allowMethodMiss = false;
    public $autoMethods = false;
    
    
    /**
     * __construct function.
     * 
     * This method stores the plugin instance into a property so that chaining can be implemented.
     * 
     * @magic
     * @param lavaPlugin $pluginInstance
     * @param array $arguments
     * @return void
     * 
     * @since 1.0.0
     */
    function __construct( $pluginInstance, $arguments = array() )
    {
        $this->pluginInstance = $pluginInstance;

        if( method_exists( $this, "lavaConstruct" ) )//call the sub classes construct argument
        {
			$callback = array( $this, "lavaConstruct" );
            call_user_func_array( $callback, $arguments );
        }
        
        $this->addAutoMethods();
    }

    /**
     * __call function.
     * 
     * This method implements chaining (allows lavaPlugin method calls to be called from any class)
     * 
     * @magic
     * @param lavaPlugin $pluginInstance
     * @param array $arguments
     * @return void
     * 
     * @since 1.0.0
     */
    function __call( $methodName, $arguments )
    {
        // lavaPlugin chainable methods start with "_" - so this is checking to see whether we should try a lavaPlugin method
        if( substr( $methodName, 0, 1 ) == "_" )
        {
            if( method_exists( $this->pluginInstance, $methodName ) )
            {
                $callback = array( $this->pluginInstance, $methodName );
                return call_user_func_array( $callback, $arguments );
            }
        }
        elseif( !is_null( $this->lavaContext() ) )
        {
            //lets see if the class that is the current context has this method
            if( method_exists( $this->lavaContext(), $methodName ) )
            {
                $callback = array( $this->lavaContext(), $methodName );
                return call_user_func_array( $callback, $arguments );
            }
        }

        $parent = $this->getContext( "parent" );
        if( !is_null( $parent ) )
        {
            $object = $this->lavaContext( null, "parent" );
            if( method_exists( $object, $methodName ) )
            {
                $callback = array( $object, $methodName );
                return call_user_func_array( $callback, $arguments );
            }
        }
        //some classes have methods with same name on parent and child. To get around this the parent method is prepended "parent_". Since no child exists with this method we should now check to see if parent has this method.

        if( method_exists( $this, "parent_$methodName" ) )
        {
            $callback = array( $this, "parent_$methodName" );
            return call_user_func_array( $callback, $arguments );
        }

        if( ! $this->allowMethodMiss )
        {

            echo "<h2>LavaError thrown on line 110 of lavaBase.php</h2> <br/>";
            echo "Could not find method '{$methodName}' on object of class '" . get_class( $this ) . "'. We also tried the current child which has class '" . get_class( $this->getContext() ) . "' and the parent which has class '" . get_class( $this->getContext() ) . "'.";

            exit;
        }
        //to prevent a dummy method call from returning a child parents set an "if lost return to" property on the children - we should check to see if it exists
        if( isset( $this->lavaCallReturn ) )
        {
            return $this->lavaCallReturn;
        }
        return $this;//couldn't find anything to call so return this object so chaining doesn't break
    }

    function addAutoMethods() {
        if( $this->autoMethods == true ) {
            $this->_misc()->_addAutoMethods( $this );
        }
    }

    //meant to be overridden - so a class can forward a request to something else
    function getThis() {
        return $this;
    }
    
    /**
     * lavaReset function.
     * 
     * Resets the chain (prevents unexpected chaining to occur)
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    function lavaReset()
    {
        $this->chain = array();
        return $this;
    }
    
    /**
     * lavaContext function.
     * 
     * adds/removes context
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    final function lavaContext( $context = null, $handle = "current" )
    {
        if( null != $context)
        {
            $this->chain[ $handle ] = $context;
        }
        if( array_key_exists($handle, $this->chain) ) {
            return $this->chain[ $handle ];
        } else {
            return $this;
        }
    }

    /**
     * lavaContext function.
     * 
     * adds/removes context
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    final function setContext( $context = null, $handle = "current" )
    {
        $this->chain[ $handle ] = $context;
    }

    /**
     * lavaContext function.
     * 
     * adds/removes context
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    final function getContext( $handle = "current" )
    {
        if( array_key_exists( $handle, $this->chain ) )
        {
            return $this->chain[ $handle ];
        }
        return null;
    }

    /**
     * withinContext function.
     * 
     * Sets the parent handler (adds to chain for method lookups)
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    final function withinContext( $context )
    {
        $this->setContext( $context, "parent" );

        return $this;
    }

    /**
     * clearLavaContext function.
     * 
     * adds/removes context
     * 
     * @return $this
     * 
     * @since 1.0.0
     */
    final function clearContext( $handle = "current" )
    {
        $this->chain[ $handle ] = null;
    }
    
    /**
     * lavaRemember function.
     * 
     * The lavaRemember function stores data as a key>value pair as a protected property to a class
     * 
     * @param string $key
     * @param $value (default: null)
     * @return $this || $value || false
     * 
     * @since 1.0.0
     */
    function lavaRemember( $key, $value = null )
    {
        if( isset( $value ) )
        {//value has been given - so lets set it
            $this->memory[ $key ] = $value;
            return $this;
        }
        if( isset( $this->memory[ $key ] ) )
        {
            return $this->memory[ $key ];
        }
        return false;
    }

	function addWPAction( $hookTags, $methodNames = "", $priority = 10, $debug = false ) {
		if( !is_array( $hookTags ) ) {
			$hookTags = array( $hookTags );
		}
		if( !is_array( $methodNames ) ) {
			$methodNames = array( $methodNames );
		}
		foreach( $hookTags as $hookTag ) {

			foreach( $methodNames as $methodName ) {
				$_methodName = $methodName;
				if( empty( $_methodName) ) {
					$_methodName = $hookTag;
				}
				//if( $debug) { echo $hookTag; echo "<br>"; echo $_methodName;echo "<br>"; }
				add_action( $hookTag, array( $this, $_methodName ), $priority );
			}
		}
		//if( $debug ) exit;
	}

	function addWPFilter( $hookTags, $methodNames = "", $priority = 10, $args = 1 ) {
		if( !is_array( $hookTags ) ) {
			$hookTags = array( $hookTags );
		}
		if( !is_array( $methodNames ) ) {
			$methodNames = array( $methodNames );
		}
		foreach( $hookTags as $hookTag ) {

			foreach( $methodNames as $methodName ) {
				$_methodName = $methodName;
				if( empty( $_methodName) ) {
					$_methodName = $hookTag;
				}
				//if( $debug) { echo $hookTag; echo "<br>"; echo $_methodName;echo "<br>"; }
				add_filter( $hookTag, array( $this, $_methodName ), $priority, $args );
			}
		}
		//if( $debug ) exit;
	}

	function addAction( $hookTags, $methodNames = "", $priority = 10 ) {
		if( !is_array( $hookTags ) ) {
			$hookTags = array( $hookTags );
		}
		if( !is_array( $methodNames ) ) {
			$methodNames = array( $methodNames );
		}
		foreach( $hookTags as $hookTag ) {

			foreach( $methodNames as $methodName ) {
				$_methodName = $methodName;
				if( empty( $_methodName) ) {
					$_methodName = $hookTag;
				}
				add_action( $this->_slug( $hookTag ), array( $this, $_methodName ), $priority );
			}
		}
	}


	function addFilter( $hookTags, $methodNames = "", $priority = 10, $args = 1 ) {

		if( !is_array( $hookTags ) ) {
			$hookTags = array( $hookTags );
		}
		if( !is_array( $methodNames ) ) {
			$methodNames = array( $methodNames );
		}
		foreach( $hookTags as $hookTag ) {

			foreach( $methodNames as $methodName ) {
				$_methodName = $methodName;
				if( empty( $_methodName) ) {
					$_methodName = $hookTag;
				}
				add_filter( $this->_slug( $hookTag ), array( $this, $_methodName ), $priority, $args );
			}
		}
	}

    /**
     * runActions function.
     * 
     * Runs the actions with all the parameters
     *
     * @param string $key
     * @param $value (default: null)
     * 
     * @since 1.0.0
     */
    function runActions( $hookTag, $debug = false )
    {
        $hooks = array_unique( $this->hookTags() );
        $suffixes = array_unique( $this->suffixes );

        foreach( $suffixes as $suffix)
        {
            foreach( $hooks as $hook )
            {
                if( $hook == " " ) {
                    $hook = "";
                } else {
                    $hook = "-".$hook;
                }
				if( $debug )
				{
					echo $this->_slug( "{$hookTag}{$hook}{$suffix}" ) . "\n";
				}
                do_action( $this->_slug( "{$hookTag}{$hook}{$suffix}" ), $this );
            }
        }
    }

     /**
     * runActions function.
     * 
     * Runs the filters with all the parameters
     * 
     * @param string $hookTag
     * @param $args (default: null)
     * 
     * @since 1.0.0
     */
    function runFilters( $hookTag, $argument = "", $args = null, $debug = false )
    {
        if( is_null( $args ) ) {
            $args = $this;
        }
        
        $hooks = array_unique( $this->hookTags() );
        $suffixes = array_unique( $this->suffixes );

        foreach( $suffixes as $suffix)
        {
            foreach( $hooks as $hook )
            {
                if( $hook == " " ) {
                    $hook = "";
                } else {
                    $hook = "-".$hook;
                }
                //echo( $this->_slug( "{$hookTag}{$hook}{$suffix}" ). "<br/>" );
                $theHook = $this->_slug( "{$hookTag}{$hook}{$suffix}" );
				if( $debug ){ echo( "$theHook<br>" ); }
                $argument = apply_filters( $theHook, $argument, $args );
            }
        }

        return $argument;
    }

    function hookTags()
    {
        return array( " " );
    }
}
?>