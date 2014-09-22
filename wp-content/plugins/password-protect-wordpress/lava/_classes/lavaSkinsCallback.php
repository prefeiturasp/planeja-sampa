<?php
/**
 * The lava Skins Callback class
 * 
 * 
 * @package Lava
 * @subpackage lavaSkinsCallback
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaSkinsCallback
 * 
 * @package Lava
 * @subpackage LavaSkinsCallback
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaSkinsCallback extends lavaSettingsCallback
{
    /**
     * lavaSkinsCallback::lavaConstruct()
     * 
     * This method is called by the __construct method of lavaBase and handles the construction
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function lavaConstruct()
    {
        //settingActions
        $hookTag = "settingActions";
        add_filter( $this->_slug( "{$hookTag}-type/skin" ), array( $this, "removeActions" ), 20, 2 );

        //settingControl
        $hookTag = "settingControl";
        add_filter( $this->_slug( "{$hookTag}-type/skin" ), array( $this, "addSkinsUx" ), 10, 2 );

		//skinRibbons
        $hookTag = "skinRibbons";
        add_action( $this->_slug( "{$hookTag}" ), array( $this, "addActiveRibbon" ), 10, 2 );

		$hookTag = "_templateVars_bodyClass";
		$this->addAction( $hookTag );

		$hookTag = "_templateVars";
		$this->addAction( $hookTag );

		$hookTag = "_templateVars_env";
		$this->addAction( $hookTag );
    }

	function addSkinsUx( $settingControl, $theSetting )
    {
        extract( $theSetting->getVars() );
        $settingControl = '<div class="js-fallback">' . $settingControl . ' </div>';
        
        $settingControl .= '<div class="js-only skin-selector">';

        $skins = $this->_skins()->fetchSkins();

        foreach( $skins as $skin ){
        	$settingControl .= '
        	<figure class="skin"  data-slug="' . $skin->getSlug() . '">  
				<a class="select-skin tiptip clearfix" title="Click to select this skin" href="#select">
					<img class="preview-img" width="400" height="200" src="' . $skin->skinUrl( "thumbnail.png" ) .'" alt="Skin Thumbnail" />
					<span class="skin-overlay">Select Skin</span>
				    <div class="skin-meta">
					    <h3 class="skin-name">' . $skin->getName() . '</h3> 
					</div>
				</a>			    				    
			</figure>';
        }

        $settingControl .= "</div>";
        
        return $settingControl;
    }

	function addActiveRibbon()
	{
		?>
		<div class="ribbon ribbon-active ribbon-green">
			<span class="ribbon-fold ribbon-fold-left"></span>
			<span class="ribbon-fold ribbon-fold-right"></span>
			<?php _e( "Selected", $this->_framework() ) ?>
		</div>
		<?php
	}

	function _templateVars( $templateVars ) {
		$envVars = apply_filters( $this->_slug( "_templateVars_env" ), array() );
		$bodyClass = apply_filters( $this->_slug( "_templateVars_bodyClass" ), "" );
		$pluginVars = apply_filters( $this->_slug( "_templateVars_pluginVars" ), array() );
		$skinSettings = $this->_templateVars_skinSettings();
		$pluginTranslation = $this->_skins()->getTranslations();


		$templateVars = array(
			"environment" => $envVars,
			"body_class" => $bodyClass,
			"plugin_vars" => $pluginVars,
			"plugin_translation" => $pluginTranslation,
			"settings" => $skinSettings,
		);

		return $templateVars;
	}

	function _templateVars_env( $envVars )
	{
		$currentSkin = $this->_skins()->currentSkin();

		$envVars = array(
			"blog_name" => get_bloginfo('name'),
			"static_url" => plugins_url( "/skins/{$currentSkin}/static", $this->_file() )
		);

		return $envVars;
	}

	function _templateVars_bodyClass( $current ) {
		if( is_array( $_GET ) ) {
			foreach( $_GET as $class => $ignore ) {
				$current .= " {$class}";
			}
		}
		return $current;
	}

	function _templateVars_skinSettings() {
		$settings = $this->_skins()->fetchCurrentSkin()->fetchSettings();
		$currentSkinSlug = $this->_skins()->currentSkin();
		$truncateLength = strlen($currentSkinSlug) + 1;
		$settingsArray = array();
		foreach( $settings as $setting ) {
			$key = $setting->getKey();
			$key = substr($key, $truncateLength);
			$value = $setting->getValue();
			$settingsArray[$key] = $value;
		}
		return $settingsArray;
	}
}
?>