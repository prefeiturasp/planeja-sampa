<?php
/**
 * The lavaPage class
 * 
 * This class is the base class for all admin pages
 * 
 * @package Lava
 * @subpackage lavaPage
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaPage
 * 
 * @package Lava
 * @subpackage LavaPlugin
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaPage extends lavaBase
{
    public $multisiteSupport = false;//Whether the page should appear in the network sidebar
    public $styles = array(), $scripts = array();
    
    /**
    * lavaPage::lavaConstruct()
    * 
    * @return void
    *
    * @since 1.0.0
    */
    function lavaConstruct( $slug )
    {
        $this->setSlug( $slug, false );
        $this->setTitle( $slug );
        $this->setCapability( "manage_options" );
        $this->lavaCallReturn = $this->_pages( false );//prevents the parent losing control
        
    }

    function _registerActions()
    {
        $pageHook = $this->pageHook;
        if( is_callable( array( $this, "loadPage" ) ) )
        {
            add_action( "load-{$pageHook}", array( $this, "loadPage" ) );
        }
        if( method_exists( $this, "registerActions" ) )
        {
            $this->registerActions();
        }
    }

    function loadPage() {
        
    }

	function get( $what )
	{
		return $this->$what;
	}

	
	function getUrl()
	{
		$slug = $this->get( "slug" );
		if( defined( 'WP_NETWORK_ADMIN' ) and WP_NETWORK_ADMIN == true )
		{
			//if we are in the network admin then make sure it is a network link
			return network_admin_url( "admin.php?page={$slug}");
		}
		return admin_url( "admin.php?page={$slug}");
	}
    
    function setCapability( $capability )
    {
        $this->capability = $capability;
        return $this->_pages( false );
    }
    
    function setSlug( $slug, $slugify = true )
    {
        $this->slug = $slug;

        if( $slugify == true )
        {
            $this->slug = $this->_slug( $slug );
        }
        return $this->_pages( false );
    }
    
    function setTitle( $title )
    {
        $this->title = $title;
        return $this->_pages( false );
    }
    
    function registerPage( $parentSlug )
    {
        $this->pageHook = add_submenu_page( 
            $parentSlug,
            $this->get( "title" ), 
            $this->get( "title" ), 
            $this->get( "capability" ),  
            $this->get( "slug" ), 
            array( $this, "doPage") 
        );
        $hook_suffix = $this->pageHook;
        add_action( "admin_print_styles-$hook_suffix", array( $this, "enqueueIncludes" ) );
        $this->_registerActions();
    }


    
    function enqueueIncludes()
    {
        foreach( $this->_pages()->styles as $name => $notNeeded )
        {
            wp_enqueue_style( $name );
        }
        foreach( $this->_pages()->scripts as $name => $notNeeded )
        {
            wp_enqueue_script( $name );
        }
    }
    
    function doPage()
    {
        $this->displayHeader();
        $this->displayNotifications();
        $this->displayPage();
        $this->displayFooter();
    }
    
    function displayHeader()
    {
        $pluginSlug = $this->_slug();
        $pluginName = $this->_name();
        $pluginVersion = $this->_version();

        $page_hook = $_GET['page'];
        $lavaPageClass = apply_filters( "admin_page_class-{$pluginSlug}", "" );
        $lavaPageClass = apply_filters( "admin_page_class-{$page_hook}", $lavaPageClass );
        
        ?>
		<script type="text/javascript">

		  var _gaq = _gaq || [];
		  _gaq.push(['_setAccount', 'UA-29306585-1']);
		  _gaq.push(['_setDomainName', 'example.com']);
		  _gaq.push(['_setAllowLinker', true]);
		  _gaq.push(['_trackPageview']);

		  (function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		  })();

		</script>
        <script type="text/javascript">
  if (typeof(Zenbox) !== "undefined") {
    Zenbox.init({
      dropboxID:   "20219253",
      url:         "https://platinummirror.zendesk.com",
      tabTooltip:  "Support",
      tabImageURL: "https://assets.zendesk.com/external/zenbox/images/tab_support_right.png",
      tabColor:    "#CE3D2B",
      tabPosition: "Right"
    });
  }
</script>
        <script type="text/javascript">
      var analytics=analytics||[];(function(){var e=["identify","track","trackLink","trackForm","trackClick","trackSubmit","page","pageview","ab","alias","ready","group"],t=function(e){return function(){analytics.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var n=0;n<e.length;n++)analytics[e[n]]=t(e[n])})(),analytics.load=function(e){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src=("https:"===document.location.protocol?"https://":"http://")+"d2dq2ahtl5zl1z.cloudfront.net/analytics.js/v1/"+e+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(t,n)};
      analytics.load("xzis081zuy");
    </script>
        <div class="lava-full-screen-loader">
            <div class="lava-loader loading">
                <span class="child1"></span>
                <span class="child2"></span>
                <span class="child3"></span>
                <span class="child4"></span>
                <span class="child5"></span>
            </div>
        </div>
        <div class="wrap">
            <div class="lava-header" style="margin-bottom:10px;">
                <div id="icon-options-general" class="icon32"></div>
                <h2>
                    <?php echo $pluginName; ?> <span class="version"><?php echo $pluginVersion; ?></span>
                    <span class="lava-ajax-checks">
                        <?php $this->runActions( "ajaxChecks" ); ?>
                    </span>
                </h2>
                
            <!--.lava-header END-->
            </div>
            <div id="lava-nav" class="lava-nav bleed-left bleed-right with-padding lava-sticky-top">
                <div class="sticky-toggle tiptip" title="Toggle whether this bar should stick to the top of the screen."></div>
                <div class="left-grad"></div>
                <ul class="nav nav-horizontal clearfix">
                    <?php foreach( $this->_pages( false )->adminPages() as $page ): ?>
                   <li class="clearfix <?php echo $page->get( "slug" ); ?> <?php if( $page_hook == $page->get( "slug" ) ){ echo "active"; } ?>"><a href="<?php echo $page->getUrl(); ?>"><?php echo $page->get( "title" ); ?></a></li>
                   <?php endforeach; ?>
                </ul>
                <?php $this->runActions( "lavaNav" ); ?>
            </div>
            <noscript>
                <div class="lava-message warning">
                    <span class="message"><?php _e( "You don't have JavaScript enabled. Many features will not work without JavaScript.", $this->_framework()) ?></span>
                </div>
            </noscript>
            <?php $this->runActions( "pageHiddenStuff" ); ?>

			<div class="lava-content-cntr bleed-left bleed-right with-padding">
				<div class="lava-underground texture texture-woven bleed-left bleed-right with-padding underground-hidden" style="">
				<?php
                    $this->runActions( "displayUnderground" );
					$this->displayUnderground();
				?>
				</div>
				<div class="lava-overground">
					<div class="torn-paper bleed-left bleed-right bleed-abs"></div>
					<div class="lava-btn-hide-underground underground-cancel-bar lava-btn lava-btn-block" style="display:none"><?php $this->cancelText() ?></div>
					<div class="content">
        <?php
    }

	function displayUnderground()
	{
		//sub classes should overload this method or rely on js to move things around (if they have to)
	}

    function displayFooter()
    {
        ?>
					<!--.content END-->
					</div>
				<!--.lava-overground END-->
				</div>
				<?php $this->displayToolbar() ?>
			<!--.lava-content-cntr END-->
			</div>
        <!--.wrap END-->
        </div>
        <?php
    }

    function displayNotifications()
    {
        $notifications = array();
        if( isset( $_GET[ 'messagesnonce' ] ) )
        {
            $storedNotifications = get_option( "lavaNotifications" );

            if( is_array( $storedNotifications ) and isset( $storedNotifications[ $_GET[ 'messagesnonce' ] ] ) )
            {
                $storedNotifications = $storedNotifications[ $_GET[ 'messagesnonce' ] ];
    
                if( is_array( $storedNotifications ) )
                {
                    foreach( $storedNotifications as $notification )
                    {
                        $notifications[] = $notification;
                    }
                }
            }
        }
        $page_hook = $this->pageHook;
        $notifications = apply_filters( "lava_notifications-{$page_hook}", $notifications );
        
        foreach( $notifications as $notification )
        {
            ?>
            <div class="lava-notification lava-notification-"><?php echo $notification['message'];?></div>
            <?php
        }
    }

    function displayPage()
    {
        ?>
        <div class="lava-notification lava-notification-error"><?php _e( "It looks like this page has gone walk-abouts.", $this->_framework() ) ?></div>
        <?php
    }

    function displayToolbar()
    {
		?>
		<div class="lava-toolbar lava-sticky-bottom <?php echo $this->runFilters( "toolbarClass" ) ?>">
			<div class="inner">
				<?php $this->runActions( "toolbarButtons" ) ?>
			</div>
		</div>
		<?php
    }

    function dieWith( $message = "" ) {
        echo "$message";
        die;
    }
 
	function cancelText()
	{
		_e( "Cancel", $this->_framework() );
	}

    function hookTags()
    {
        $hooks = array(
            " ",
            "slug/{$this->slug}",
            "multisiteSupport/{$this->multisiteSupport}"
        );
        return $hooks;
    }
}
?>