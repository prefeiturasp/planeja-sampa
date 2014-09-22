<?php
/**
 * The lavaPages class
 * 
 * This class is the controller for the plugin admin pages
 * 
 * @package Lava
 * @subpackage lavaPages
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaPages
 * 
 * @package Lava
 * @subpackage LavaPages
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaPages extends lavaBase
{
    protected $adminPages = array();
    protected $defaultPage;
    public $styles = array(), $scripts = array();
    
    /**
    * lavaPages::lavaConstruct()
    * 
    * @return void
    *
    * @since 1.0.0
    */
    function lavaConstruct()
    {
        $this->addStyle( $this->_slug( "lavaStyles" ), "lava/_static/styles.css" );
        $this->addStyle( $this->_slug( "dropkick" ), "lava/_static/dropkick.css" );
        $this->addStyle( $this->_slug( "codemirror" ), "lava/_static/codemirror/codemirror.css" );
        $this->addStyle( $this->_slug( "codemirror-theme" ), "lava/_static/codemirror/codemirror.theme.css" );
        $this->addStyle( $this->_slug( "lobster" ), "http://fonts.googleapis.com/css?family=Lobster" );
        //$this->addStyle( $this->_slug( "colorpicker-theme" ), "lava/_static/colorpicker/colorpicker.css" );

        $this->addScript( $this->_slug( "lavaScripts" ), "lava/_static/scripts.js", array( "jquery" ) );
        $this->addScript( $this->_slug( "lavaScriptsNew" ), "lava/_static/scripts_new.js", array( "jquery" ) );
        $this->addScript( $this->_slug( "dropkick" ), "lava/_static/dropkick.js", array( "jquery" ) );
        $this->addScript( $this->_slug( "modernizr" ), "lava/_static/modernizr.js" );
        $this->addScript( $this->_slug( "tiptip" ), "lava/_static/tiptip.js", array( "jquery" ) );
        $this->addScript( $this->_slug( "jquery-color" ), "lava/_static/jquery.color.js", array( "jquery" ) );
        $this->addScript( $this->_slug( "jquery-resize" ), "lava/_static/jquery.resize.js", array( "jquery" ) );
        $this->addScript( $this->_slug( "jquery-fileupload") , "lava/_static/jquery.fileupload.js", array( "jquery", "jquery-ui-core", "jquery-ui-widget", $this->_slug( "jquery-iframe-transport" ) ) );
        $this->addScript( $this->_slug( "jquery-iframe-transport" ), "lava/_static/jquery.iframe-transport.js", array( "jquery" ) );
        $this->addScript( $this->_slug( "codemirror" ), "lava/_static/codemirror/codemirror.js" );
        $this->addScript( $this->_slug( "codemirror-css" ), "lava/_static/codemirror/codemirror.css.js" );
        $this->addScript( $this->_slug( "colorpicker" ), "lava/_static/autoResize.js", array( "jquery" ) );
        //$this->addScript( $this->_slug( "colorpicker" ), "lava/_static/colorpicker/colorpicker.js" );

        
        add_action( "admin_enqueue_scripts", array( $this, "registerIncludes" ) );

        add_action( "admin_menu", array( $this, "registerPages") );

        if( is_multisite() )
            add_action( "network_admin_menu", array( $this, "registerNetworkPages" ) );
    }
    
    
    /**
     * addPage function.
     * 
     * This function adds an admin page
     * 
     * @param mixed $slug
     * @param string $type (default: "")
     * @return void
     *
     * @since 1.0.0
     */
    function addPage( $slug, $type = "", $slugify = true )
    {
        if( true == $slugify )
        {
            $slug = $this->_slug( $slug );
        }


        if( !isset( $this->adminPages[ $slug] ) )
        {
            $arguments = array( $slug );
            $this->adminPages[ $slug ] = $this->_new( "lava{$type}Page", $arguments );
        }
        $this->chain[ "current" ] = $this->adminPages[ $slug ];
        
        if( empty( $this->defaultPage ) )// If a default page (the page that displays when the main page is clicked) hasn't been set then set it (otherwise a blank page will be displayed).
        {
            $this->defaultPage = $this->adminPages[ $slug ];
        }

        return $this;
    }
    
    /**
     * fetchPage function.
     * 
     * @access public
     * @param mixed $slug
     * @return void
     *
     * @since 1.0.0
     */
    function fetchPage( $slug )
    {
        unset( $this->chain[ "current" ] );
        if( isset( $this->adminPages[ $slug ] ) )
        {
            $this->chain[ "current" ] = $this->adminPages[ $slug ];
        }
        $slug = $this->_slug( $slug );
        if( isset( $this->adminPages[ $slug ] ) )
        {
            $this->chain[ "current" ] = $this->adminPages[ $slug ];
        }
        return $this;
    }

    function pageExists( $slug )
    {
        if( isset( $this->adminPages[ $slug ] ) )
        {
            return true;
        }
        $slug = $this->_slug( $slug );
        if( isset( $this->adminPages[ $slug ] ) )
        {
            return true;
        }
        return false;
    }

    function adminPages( $filter = true )
    {
        $adminPages = $this->adminPages;
        if( true == $filter and defined( 'WP_NETWORK_ADMIN') and WP_NETWORK_ADMIN == true )
        {
            $adminPages = array();
            foreach( $this->adminPages as $slug=>$page )
            {
                if( true == $page->multisiteSupport )
                {
                    $adminPages[$slug] = $page;
                }
            }
        }
        return apply_filters( "admin_pages_order-".$this->_slug(), $adminPages );
    }
    
    
    function addPageFromTemplate( $slug, $template )
    {
        return $this->addPage( $slug );
    }
    
    
    /**
     * addAboutPage function.
     * 
     * @access public
     * @return void
     */
    function addAboutPage( $slug = "about" )
    {
        $this   ->addPage( $slug, "About" )
                    ->setTitle( sprintf( __( "About %s", $this->_framework() ), $this->_name() ) );
        return $this;
    }

    /**
     * addSettingsPage function.
     * 
     * @access public
     * @return void
     */
    function addSettingsPage( $slug = "settings" )
    {
        $this   ->addPage( $slug, "Settings" )
                    /* translators: This is the title of the settings page */
                    ->setTitle( __( "Plugin Settings", $this->_framework() ) );
                    
        return $this;
    }
    
    /**
     * addSkinsPage function.
     * 
     * @param string $slug (default: "skins") - to be appended to the plugin slug to make the url
     * @return void
     */
    function addSkinsPage( $slug = "skins" )
    {
        $this->_skins( false );

        $this   ->addPage( $slug, "Skins" )
                    /* translators: This is the title of the settings page */
                    ->setTitle( __( "Skins", $this->_framework() ) )
        ;
                    
        return $this;
    }

    
    /**
     * addTablePage function.
     * 
     * @access public
     * @param mixed $slug (default: "table") - to be appended to the plugin slug to make the url
     * @return void
     * @since 1.0.0
     */
    function addTablePage( $slug = "table" )
    {
        $this   ->addPage( $slug, "Table" )
                    ->setTitle( __( "Table", $this->_framework() ) )
        ;
        return $this;
    }
    




    /**
     * defaultPage function.
     *  Sets the currently chained page as the one to be displayed when the top-level page is clicked.
     * 
     * @return void
     * @since 1.0.0
     */
    function defaultPage()
    {
        if( isset( $this->chain[ "current" ] ) )
        {
            $this->defaultPage = $this->chain[ "current" ];
        }

        return $this;
    }

    /**
     * registerPages function.
     *  Registers each of the admin pages
     * 
     * @return void
     * @since 1.0.0
     */
    function registerPages()
    {
        $defaultPage = $this->defaultPage;
        //register the main page
        add_menu_page( $defaultPage->get( "title" ),  $this->_name(), $defaultPage->get( "capability" ), $defaultPage->get( "slug" ), array( $defaultPage, "doPage" ) );

        $parentSlug = $defaultPage->get( "slug" );

        //register each subpage
        foreach( $this->adminPages as $page )
        {
            $page->registerPage( $parentSlug );
        }
        
    }

    /**
     * registerNetworkPages function.
     *  Registers each of the admin pages
     * 
     * @return void
     * @since 1.0.0
     */
    function registerNetworkPages()
    {
        
    }





	function addStyle( $name, $path = "" )
	{
		$include = array(
			'path' => $path
		);

		$this->styles[ $name ] = $include;
		return $this;
	}

	function addScript( $name, $path = "", $dependencies = array() )
	{
		$include = array(
			'path' => $path,
			'dependencies' => $dependencies
		);

		$this->scripts[ $name ] = $include;
		return $this;
	}

    /**
     * lavaPages::registerIncludes()
     * 
     * @return void
     */
    function registerIncludes()
    {
		foreach( $this->scripts as $name => $include )
		{
			$path         = $include['path'];
			$dependencies = $include['dependencies'];

			if( !empty( $path ) )
			{
				if( strpos( $path, 'http' ) === false ) {
					$path = plugins_url( $path, $this->_file() );
				}
				wp_register_script( $name, $path, $dependencies );
			}
		}
		foreach( $this->styles as $name => $include )
		{
			$path = $include['path'];

			if( !empty( $path ) )
			{
				if( strpos( $path, "http" ) === false ) {
					$path = plugins_url( $path, $this->_file() );
				}
				wp_register_style( $name, $path );
			}
		}
	}




    function addCustomStyles()
    {
        $this->addStyle( $this->_slug( "Pluginstyles" ), "_static/styles.css" );
        return $this;
    }

    function addCustomScripts()
    {
        $this->addScript($this->_slug( "pluginScripts" ), "_static/scripts.js");
        return $this;
    }

}
?>