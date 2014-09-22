<?php
/**
 * The lava Setting class
 * 
 * This class is the class base for all settings
 * 
 * @package Lava
 * @subpackage lavaSetting
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaSetting
 * 
 * @package Lava
 * @subpackage LavaSetting
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaSetting extends lavaBase
{
    /**
     * @property $type - text, colour, password, image, file, checkbox, selectbox
     * @property $who - Who set the setting? Is it a "plugin" setting or a "theme" setting.
     * @property $key - A lowercase, string with no spaces that is used to identify the setting
     * @property $properties - an array of properties that may be type specific (like: maxsize for uploads)
     * @property $validation - an array of key=>value pairs where the key is the name of the validation (e.g. email) and the value is a callback that will process the validation upon submission
     * 
     */

    public $name;
    public $help = '';
    public $inlineHelp = '';
    
    /**
     * lavaSetting::lavaConstruct( $name, $who )
     * 
     * @param $name - The kname of the setting
     * @param $who - The ID of which section the setting is for
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function lavaConstruct( $key, $who )
    {
        $this->type = "text";
        $this->who = $who;
        $this->key = $key;
        $this->status = "default";
        
        $this->properties = array();
        $this->validation = array();
        $this->tags = array();
        $this->dataTags = array();
    }

    /**
     * lavaSetting::setType( $type )
     *  Sets the type of the setting (text, password, timeperiod etc.)
     * 
     * @param $type 
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function setType( $type )
    {
        $this->type = $type;
        switch( $type )
        {
            case "checkbox":
                $this->setDefault( "on" , false );
                break;
            case "color"://bloody American spelling - polluting the world
            case "colour"://that's more like it
                //no support for alpha channels at this time
                $this->setDefault( "#FFFFFF", false );
                $this->addTag( "reset-to-default" );
                break;
            case "password":
                $this->setDefault( "password" );
                $this->addTag( "reset-to-default" );
                break;
            case "timeperiod":
                $this->addTag( "reset-to-default" );
                break;
            case "image":
                $this->addTag( "reset-to-default" );
                break;
            case "textarea":
                $this->addTag( "reset-to-default" );
                break;
        }
        return $this->_settings( false );
    }

    

    /**
     * lavaSetting::setName( $name )
     *  Sets the name that appears to the user
     * 
     * @param $name - translated name
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function setName( $name )
    {
        $this->name = $name;
        return $this->_settings( false );
    }

    /**
     * lavaSetting::setDefault( $value, $overwrite )
     * 
     * @param $value - The value to be used as the default value for the setting
     * @param $overwrite (default:true) - should we overwrite an existing value (false when used internally)
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function setDefault( $value, $overwrite = true )
    {
        $old = $this->getProperty( "default" );
        if( $overwrite == true )
        {
            $this->setProperty( "default", $value );
        }
        else if( is_null( $old ) )
        {
            $this->setProperty( "default", $value );
        }
        
        //To allow chaining return the lavaSettings instance and don't reset the chain
        return $this->_settings( false );
    }

    
    /**
     * lavaSetting::setHelp( $help )
     *  Sets the contextual help
     * 
     * @param $help - translated help
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */    
    function setHelp( $help )
    {
        $this->help = $help;
        return $this->_settings( false );
    }

    function setInlineHelp( $help )
    {
        $this->inlineHelp = $help;
        return $this->_settings( false );
    }

    /**
     * lavaSetting::setVisibility( $visibility )
     *  Hides the HTML from view (still printed though)
     * 
     * @param $visibility - boolean value of whether to show or hide the setting
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function setVisibility( $visibility = true )
    {
        //hides the setting from the admin panel (still deciding whether it should generate HTML and just hide it or not generate at all)
        if( $visibility == false )
        {
            $this->addTag( "hidden" );
        }
        else
        {
            $this->removeTag( "hidden" );
        }
        return $this->_settings( false );
    }

    
    /**
     * lavaSetting::setProperty( $property, $value )
     * 
     * @param $property
     * @param $value
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function setProperty( $property, $value)
    {
        $this->properties[ $property ] = $value;
        return $this->_settings( false );
    }

    /**
     * lavaSetting::addPropertyValue( $property, $value, $duplicate )
     *  Adds value as element in array to property. if duplicate is false then only adds if doesn't exist
     *
     * @param $property
     * @param $value
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function addPropertyValue( $property, $value, $duplicate = false )
    {
        $current = $this->getProperty( $property );
        if( !is_array( $current ) )
        {
            $current = array();
        }
        if( $duplicate == false and in_array( $value, $current ) )
        {
            return $this->_settings( false );
        }
        $current[] = $value;
        $this->setProperty( $property, $current );

        return $this->_settings( false );
    }

	function addSettingOption( $value, $name = "", $class = "" ) {
		if( empty($name) ) {
			$name = $value;
		}
		$option = array(
			"value" => $value,
			"name" => $name,
			"class" => $class,
		);
		$this->addTag( "options-available" );
		$this->addPropertyValue( "setting-options", $option );

		return $this->_settings( false );
	}
    
    

    /**
     * lavaSetting::addTag( $tag )
     *  Adds a tag to the setting that is used for hooks and printed in the html to allow easy customizations
     * 
     * @param $tag - The name of the tag to add to the setting. Should be lowercase letters and hyphen only
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function addTag( $tag )
    {
        if( !empty( $tag ) )
        {
            $this->tags[ $tag ] = $tag;

            $this->_settings( false )->_addTag( $tag, $this->key, $this->who );
        }
        return $this->_settings( false );
    }

    /**
     * lavaSetting::removeTag( $tag )
     *  Removes a previously added tag
     * 
     * @param $tag - The name of the tag to remove to the setting. 
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function removeTag( $tag )
    {
        if( !empty( $tag ) ) {
            unset( $this->tags[ $tag ] );

            $this->_settings( false )->_removeTag( $tag, $this->key, $this->who );
        }
        return $this->_settings( false );
    }
    

    /**
     * lavaSetting::addValidation( $validation )
     *  Adds client side and server side validation to the data
     * 
     * @param $validation - the slug of the validation function to apply
     * 
     * @return chain
     * 
     * @since 1.0.0
     */
    function addValidation( $validation = "notnull" )
    {
        $validationCallbacks = $this->_settings( false )->validationCallback;
        
        $callback = $this->_settings( false )->validationCallback( $validation );
        
        if( null != $callback )
        {
            $this->validation[ $validation ] = $callback;
        }
        
        
        return $this->_settings( false );
    }
    
    /**
     * lavaSetting::multisiteOnly()
     *  Makes the setting only appear to network admins
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function multisiteOnly()
    {
        $this->addTag( "multisite-only" );
        
        return $this->_settings( false );
    }

    function settingToggle( $settingToToggle ) {
        if( $this->hasTag( "skin-setting" ) ) {
            $skinName = $this->getData( "skin" );
            $settingToToggle = "{$skinName}-{$settingToToggle}";
        }
        return $this->addTag( "setting-toggle" )->bindData( "setting-toggle", $settingToToggle );
    }

    function settingToggledBy( $settingToggledBy ) {
        $this->_settings()->fetchSetting( $settingToggledBy )->addTag("setting-toggle");
        $this->bindData('setting-toggle', $settingToggledBy );
        return $this->_settings( false );
    }
    
    

    


    /**
     * lavaSetting::bindData( $key, $value )
     *  Binds a data tag to the setting
     * 
     * @param $key
     * @param $value
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function bindData( $key, $value )
    {
        $this->dataTags[ $key ] = $value;

        return $this->_settings( false );
    }

    function getData( $key = null ) {
        if( $key == null ) {
            return $this->dataTags;
        } else if( array_key_exists($key, $this->dataTags ) ) {
            return $this->dataTags[ $key ];
        }
        return "";
    }

    /**
     * lavaSetting::updateValue( $value, $doStatus = false, $suppressSave = false )
     *  Updates the setting value
     * 
     * @param $value - what to update to
     * @param $doStatus (false) - whether to check if changed and update the status
     * @param $suppressSave (false) - whether to supporess the saving of the option (to prevent unneccessary database calls) - this is used during a loop of multiple calls and then the save is called after
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function updateValue( $value, $doStatus = false, $suppressSave = false )
    {
        
        //$value = htmlentities($value, ENT_QUOTES);
        $value = utf8_encode( $value );
        $cache = $this->getCache();
        
        if( $doStatus )
        {
            
        }
        $cache[ $this->key ] = $value;

        $this->_settings( false )->putCache( $this->who, $cache );
        if( !$suppressSave )
        {
            $this->_settings( false )->updateCache( $this->who );
        }
        return $this->_settings( false );
    }

    function getVars() {
        $settingKey = $this->getKey();
        $settingWho = $this->who;
        $pluginSlug =  $this->_slug();
        $settingInputName = "{$pluginSlug}[{$settingWho}/{$settingKey}]";
        $settingInputID = "{$pluginSlug}-{$settingWho}-{$settingKey}";
        $settingOptions = $this->getProperty( "setting-options" );
        $settingValue = $this->getValue( false );

        return array(
            "settingKey" => $settingKey,
            "settingWho" => $settingWho,
            "pluginSlug" => $pluginSlug,
            "settingInputName" => $settingInputName,
            "settingInputID" => $settingInputID,
            "settingValue" => $settingValue,
            "settingOptions" => $settingOptions
        );
    }
    
    /**
     * lavaSetting::getValue( $format = false )
     *  Retrieves the value of the setting
     * 
     * @param $format - whether to format as HTML entitity so it can be used as input value
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function getValue( $format = false)
    {
        $cache = $this->getCache();
        if( !array_key_exists( $this->key, $cache ) )
        {
            return $this->getDefault();
        }
        $value = $cache[ $this->key ];
        if( $format == false)
        {
            //$value = html_entity_decode( $value );
            $value = utf8_decode($value);
            return $value;
        }
        return $value;
    }


    /**
     * lavaSetting::getCache()
     *  Retrieves the setting cache from the singleton instance
     * 
     * @return #chain
     * 
     * @since 1.0.0
     */
    function getCache()
    {
        return $cache = $this->_settings( false )->getCache( $this->who );
    }





    /**
     * lavaSetting::getClasses( $format )
     *  Gets the classes for the setting and either returns them as a formatted string or as an array
     * 
     * @param $format
     * 
     * @return chain
     * 
     * @since 1.0.0
     */
    function getClasses( $format = false )
    {
        $classes = array();

        $classes["setting"] = "setting";
        $classes["clearfix"] = "clearfix";

        foreach( $this->tags as $tag )
        {
            $classes["tag-{$tag}"] = "tag-{$tag}";
        }

        $type = $this->getType();
        $classes["type-{$type}"] = "type-{$type}";

        $classes = $this->runFilters( "settingClasses", $classes );

        if( $format == false )
        {
            return $classes;
        }

        $classesFormatted = "";
        foreach( $classes as $class)
        {
            $classesFormatted .= " $class";
        }

        return $classesFormatted;
    }


    /**
     * lavaSetting::getTags( $format )
     *  Gets the tags for the setting and either returns them as a formatted string or as an array
     * 
     * @param $format
     * 
     * @return chain
     * 
     * @since 1.0.0
     */
    function getTags( $format = false )
    {

        if( $format == false )
        {
            return $this->tags;
        }

        $formatted = "";
        foreach( $this->tags as $tag)
        {
            $formatted .= " $tag";
        }

        return $formatted;
    }

    function hasTag( $tag ) {
        $tags = $this->getTags();

        if( array_key_exists($tag, $tags) ) {
            return true;
        }
        return false;
    }

    /**
     * lavaSetting::getType()
     *
     * @return #chain
     *
     * @since 1.0.0
     */
    function getType()
    {
        return $this->type;
    }

    /**
     * lavaSetting::getName()
     *
     * @return #chain
     *
     * @since 1.0.0
     */
    function getName()
    {
        return $this->name;
    }
    
    /**
     * lavaSetting::getHelp()
     *
     * @return #chain
     *
     * @since 1.0.0
     */
    function getHelp()
    {
        return $this->help;
    }

    /**
     * lavaSetting::getKey()
     *
     * @return #chain
     *
     * @since 1.0.0
     */
    function getKey()
    {
        return $this->key;
    }

    /**
     * lavaSetting::getStatus()
     *
     * @return #chain
     * 
     * @since 1.0.0
     */
    function getStatus()
    {
        return $this->status;
    }

    /**
     * lavaSetting::getDefault()
     *
     * @return #chain
     * 
     * @since 1.0.0
     */
    function getDefault()
    {
        return $this->getProperty( "default" );
    }

    /**
     * lavaSetting::getProperty( $key )
     *
     * @return #chain
     * 
     * @since 1.0.0
     */
    function getProperty( $key )
    {
        if( !array_key_exists( $key, $this->properties ) )
        {
            return null;
        }
        return $this->properties[ $key ];
    }




    /**
     * lavaSetting::doSetting()
     *  prints the setting HTML
     *
     * @return chain
     * 
     * @since 1.0.0
     */
    function doSetting()
    {
        
        if( array_key_exists( "no-display", $this->tags ) )//This setting shouldn't be displayed
        {
            return;
        }

        $dataTags = "";
        foreach( $this->dataTags as $tag => $value )
        {
            $dataTags .= " data-{$tag}=\"{$value}\"";
        }

        $settingKey = $this->getKey();
        $settingWho = $this->who;
        $pluginSlug =  $this->_slug();
        $classes = $this->getClasses( true );
        $tags = $this->getTags( true );
        $type = $this->getType();
        $name = $this->getName();
        $key = $this->getKey();
        $help = $this->getHelp();
        $inlineHelp = $this->inlineHelp;
        if( !empty( $help ) )
        {
            $help = "<span class=\"tiptip-right help\" title=\"$help\" >&#63;</span>";
        }
        $help .= "<div class=\"inline-help\">$inlineHelp</div>";
        $status = $this->getStatus();
        $defaultValue = $this->getDefault();
        $settingID = "setting-cntr_{$pluginSlug}-{$settingWho}-{$settingKey}";

        $settingStart = "<div class=\"{$classes}\" $dataTags data-tags=\"{$tags}\" data-status=\"{$status}\" data-type=\"{$type}\" data-setting-key=\"{$key}\" data-default-value=\"{$defaultValue}\" id=\"$settingID\" >";
            
            $settingAbsElements = $this->runFilters( "settingAbsElements", '' );
            $statusIndicator = '<span class="status-indicator show-status"></span>';
            $preSettingStart = '<div class="pre-setting">';
                $settingName = "<span class=\"setting-name\">$name</span>$help";
            $preSettingEnd = '</div>';


            $settingInnerStart = '<div class="setting-inner clearfix">';
                $settingInnerPre = $this->runFilters( "settingInnerPre", '' );
                $settingControlStart = '<div class="setting-control">';
                    $settingControl = $this->doSettingControl();
                $settingControlEnd = '</div>';
                $settingActionsStart = '<div class="setting-actions clearfix">';
                    $settingActions = $this->getActions();
                $settingActionsEnd = '</div>';
                $settingInnerPost = $this->runFilters( "settingInnerPost", '' );
            $settingInnerEnd ='</div>';

            $postSettingStart = '<div class="post-setting clearfix">';
            $postSettingEnd ='</div>';
        $settingEnd = '<div style="clear:both;"></div></div>';
        
        $settingFull = 
            "
            $settingStart
                $settingAbsElements
                $statusIndicator

                $preSettingStart
                    $settingName
                $preSettingEnd
            
                $settingInnerStart
                    $settingInnerPre
                    $settingControlStart
                        $settingControl
                    $settingControlEnd
                    $settingActionsStart
                        $settingActions
                    $settingActionsEnd
                    $settingInnerPost
                $settingInnerEnd

                $postSettingStart
                $postSettingEnd
            $settingEnd
            ";
        $settingFull = $this->runFilters( "settingFull", $settingFull );

        return $settingFull;
    }

    /**
     * lavaSetting::getActions()
     *  Returns the actions for the setting
     *
     * @return HTML string of actions
     * 
     * @since 1.0.0
     */
    function getActions()
    {
        $settingActions = $this->runFilters( "settingActions" );

        return $settingActions;
    }

    /**
     * lavaSetting::doSettingControl()
     *  Returns the setting control HTML
     *
     * @return HTML string of actions
     * 
     * @since 1.0.0
     */
    function doSettingControl( $type = "default" )
    {
        $settingKey = $this->getKey();
        $settingWho = $this->who;
        $pluginSlug =  $this->_slug();
        $settingInputName = "{$pluginSlug}[{$settingWho}/{$settingKey}]";
        $settingInputID = "{$pluginSlug}-{$settingWho}-{$settingKey}";
        $settingValue = $this->getValue( true );
        $settingPlaceholder = $this->getProperty( "placeholder" );
        if( "default" == $type )
        {
            $type = $this->getType();
        }
        
        switch( $type )
        {
            
            case "hidden":
                $settingControl = "<input data-actual='true' id='{$settingInputID}' type='hidden' name='{$settingInputName}' value='{$settingValue}' />";
            break;
            case "radio":
                $theOptions = $this->getProperty( "radio-values" );
                if( !is_array( $theOptions ) )
                {
                    $theOptions = array();
                }
                $settingControl = "";
                foreach( $theOptions as $option )
                {
                    $settingControl .= "<input type='radio' name='{$settingInputName}' value='{$option}' />";
                }
            break;
            case "color"://Bloody American spelling
            case "colour":
                $settingControl = "<input class='color-input' data-actual='true' id='{$settingInputID}' type='text' name='{$settingInputName}' value='{$settingValue}' />";
            break;
            case "checkbox":
                $checked = "";
                if( "on" == $settingValue )
                {
                    $checked = 'checked="checked"';
                }
                $settingControl = "<input id='{$settingInputID}-backup' type='hidden' name='{$settingInputName}' value='off' />";
                $settingControl .= "<input data-actual='true' {$checked} id='{$settingInputID}' type='checkbox' name='{$settingInputName}' value='on' />";
            break;

            case "password":
                $settingControl = "<input class='lava-focus-inner lava-auto-resize' placeholder='{$settingPlaceholder}' data-actual='true' id='{$settingInputID}' type='password' name='{$settingInputName}' value='{$settingValue}' />";
            break;

            case "timeperiod":
                $settingControl = "<input data-actual='true' id='{$settingInputID}' type='text' name='{$settingInputName}' value='{$settingValue}' />";
			case "skin":
            case "text":
            default:
                $settingControl = "<input data-actual='true' id='{$settingInputID}' type='text' name='{$settingInputName}' value='{$settingValue}' />";
        }

        $settingControl = $this->runFilters( "settingControl", $settingControl );

        return $settingControl;
    }


    function hookTags()
    {
        $settingWho = $this->who;
        $settingKey = $this->getKey();
        $settingType = $this->getType();

        $hooks = array( " ");
        $hooks[] = "who/{$settingWho}";
        $hooks[] = "type/{$settingType}";
        $hooks[] = "key/{$settingKey}";
        $hooks[] = "who/{$settingWho}-key/{$settingKey}";

        foreach( $this->tags as $tag)
        {
            $hooks[] = "tag/{$tag}";
        }

        return $hooks;
    }
}
?>