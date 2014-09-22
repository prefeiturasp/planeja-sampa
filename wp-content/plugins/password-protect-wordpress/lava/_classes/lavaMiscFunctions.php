<?php
class lavaMiscFunctions extends lavaBase
{

	function lavaConstruct() {
		$this->addAutoMethods();
	}

    function current_context_url( $path )
    {
        if( is_multisite() and defined( 'WP_NETWORK_ADMIN' ) and WP_NETWORK_ADMIN == true )
        {
            return network_admin_url( $path );
        }
        return admin_url( $path );
    }

    function addAutoMethods() {
    	$objects = array(
    		$this,
    		$this->_this()->pluginCallbacks,
    		$this->_ajax(),
    		$this->_skins()
    	);

		foreach( $objects as $object ) {
			$this->_addAutoMethods( $object );
		}
    }

    function _addAutoMethods( $object ) {
        $autoHooks = array(
            "init" => "init",
            "admin_init" => "adminInit"
        );
        foreach( $autoHooks as $hookTag => $actions ) {
                if( !is_array( $actions ) ) {
                    $actions = array( $actions );
                }
                foreach( $actions as $action ) {
                    if( method_exists( $object, $action ) ) {
                        $callback = array( $object, $action ); 
                        add_action( $hookTag, $callback );
                    }
                }
            }
    }

    function _registerActions() {
    	$hooks = array();

    	foreach( $hooks as $hook ) {
    		add_action( $hook, array( $this, $hook ) );
    	}
    }

    function versionMatch( $ver1, $ver2 = null ) {
        if( is_null( $ver2 ) ) {
            $ver2 = $this->_version();
        }
        if( strpos( $ver2, "beta" ) ) {
            return false;//this is a beta plugin so we should assume run update hooks all the time
        }
        if( $ver1 == $ver2 ) {
            return false;
            return true;
        }
        return false;
    }

    function userAgentInfo() {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        $info = array(
            'device' => 'pc',
            'os' => 'unknown',
            'browser' => 'unknown'
        );
        if( strpos( $ua, "iPad" ) ) {
            $info['device'] = "iPad";
            $info['os'] = 'ios';
            $info['browser'] = 'Mobile Safari';
            return $info;
        } else if( strpos( $ua, "iPod" ) ) {
            $info['device'] = "iPod";
            $info['os'] = 'ios';
            $info['browser'] = 'Mobile Safari';
            return $info;
        } else if( strpos( $ua, "iPhone" ) ) {
            $info['device'] = "iPhone";
            $info['os'] = 'ios';
            $info['browser'] = 'Mobile Safari';
            return $info;
        }

        //not an ios device
         if( strpos( $ua, "Windows NT 6.2" ) ) {
            $info['os'] = 'Windows 8';
         } else if( strpos( $ua, "Windows NT 6.1" ) ) {
            $info['os'] = 'Windows 7';
         } else if( strpos( $ua, "Windows NT 6.0" ) ) {
            $info['os'] = 'Windows Vista';
         } else if( strpos( $ua, "Windows NT 5.1" ) ) {
            $info['os'] = 'Windows XP';
         } else if( strpos( $ua, "Macintosh" ) ) {
            $info['os'] = "OSX";
         }
         //do the browser
         if( strpos( $ua, "Chrome" ) ) {
            $info['browser'] = 'Chrome';
         } else if( strpos( $ua, "Safari" ) ) {
            $info['browser'] = 'Safari';
         } else if( strpos( $ua, "MSIE" ) ) {
            $info['browser'] = 'Internet Explorer';
         } else if( strpos( $ua, "Firefox" ) ) {
            $info['browser'] = 'Firefox';
         } else if( strpos( $ua, "Opera" ) ) {
            $info['browser'] = 'Opera';
         }
         return $info;
    }

    
}
?>