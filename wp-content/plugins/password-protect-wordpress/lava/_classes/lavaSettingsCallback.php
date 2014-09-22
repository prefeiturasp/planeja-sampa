<?php
/**
 * The lava Settings Callback class
 * 
 * This class has all the callback methods involved with settings
 * 
 * @package Lava
 * @subpackage lavaSettingsCallback
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaSettingsCallback
 * 
 * @package Lava
 * @subpackage LavaSettingsCallback
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaSettingsCallback extends lavaBase
{
    /**
     * lavaSettingsCallback::lavaConstruct()
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
        add_filter( $this->_slug( "{$hookTag}-type/password" ), array( $this, "addShowPassword" ) );
        add_filter( $this->_slug( "{$hookTag}-tag/reset-to-default" ), array( $this, "addResetToDefault" ) );

        //settingControl
        $hookTag = "settingControl";
        add_filter( $this->_slug( "{$hookTag}-type/timeperiod" ), array( $this, "addTimePeriodSelector" ), 10, 2 );
        add_filter( $this->_slug( "{$hookTag}-type/password" ), array( $this, "addPasswordWrapper" ), 10, 2 );
        add_filter( $this->_slug( "{$hookTag}-type/checkbox" ), array( $this, "addCheckboxUx" ), 10, 2 );
        add_filter( $this->_slug( "{$hookTag}-type/text" ), array( $this, "addTextWrapper" ), 10, 2 );
        add_filter( $this->_slug( "{$hookTag}-type/select" ), array( $this, "addSelectUx" ), 10, 2 );
		$this->addFilter( "{$hookTag}-type/image", "addImageUx", 10, 2 );
        $this->addFilter( "{$hookTag}-type/color", "addColorUx", 10, 2 );
        $this->addFilter( "{$hookTag}-type/code", "addCodeUx", 10, 2 );
        $this->addFilter( "{$hookTag}-type/textarea", "addTextareaUx", 10, 2 );

        //settingsHiddenInputs
        $hookTag = "settingsHiddenInputs";
        add_action( $this->_slug( "{$hookTag}"), array( $this, "nonces") );

        //hiddenForms
        $hooktag = "hiddenForms";
        $this->addFilter( "hiddenForms" );
    }


    /**
     * lavaSettingsCallback::addResetToDefault()
     * 
     * Adds the "reset to default" html to the setting actions
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function addResetToDefault( $settingActions )
    {
        $settingActions .=      '<span class="action js-only reset-setting flex-3">' . __( "Reset to default", $this->_framework() ) . '</span>'.
                                '<span style="display:none" class="action js-only undo-reset-setting flex-3">' . __( "Undo Reset", $this->_framework() ) . '</span>';
        return $settingActions;
    }

    /**
     * lavaSettingsCallback::addShowPassword()
     * 
     * Adds the "show password" html to the setting actions
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function addShowPassword( $settingActions )
    {
        $settingActions =      '<span class="js-only action show-password-handle flex-1">' . __( "Show Password", $this->_framework() ) . '</span>'.
                                '<span style="display:none" class="js-only action hide-password-handle flex-1">' . __( "Hide Password", $this->_framework() ) . '</span>'.$settingActions;
        return $settingActions;
    }

    /**
     * lavaSettingsCallback::addTimePeriodSelector()
     * 
     * Adds the "show password" html to the setting actions
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function addTimePeriodSelector( $settingControl, $theSetting )
    {
        $seconds = $theSetting->getValue( true );

        $selectedAttr = 'selected="selected"';

        $weeksSelected = $daysSelected = $hoursSelected = $minutesSelected = "";
        if( $seconds % ( 60 * 60 * 24 * 7 ) == 0 )
        {
            $weeksSelected = $selectedAttr;
            $theValue = round( $seconds / ( 60 * 60 * 24 * 7 ) );
        }
        elseif( $seconds % ( 60 * 60 * 24 ) == 0 )
        {
            $daysSelected = $selectedAttr;
            $theValue = round( $seconds / ( 60 * 60 * 24 ) );
        }
        elseif( $seconds % ( 60 * 60 ) == 0 )
        {
            $hoursSelected = $selectedAttr;
            $theValue = round( $seconds / ( 60 * 60  ) );
        }
        else
        {
            $minutesSelected = $selectedAttr;
            $theValue = round( $seconds / 60 );
        }
        $settingControl .=  '<div class="input-cntr show-status clearfix js-only lava-focus-outer">'.
                                '<div class="validation" data-state="not-invoked"></div>'.
                                '<input class="time-period-ux lava-focus-inner lava-auto-resize" type="text" value="' . $theValue . '"/>'.
                            '</div>'.
                            
                            '<select class="scale-selector js-only">'.
                                '<option ' . $minutesSelected . ' value="' . 60 . '" >' . __( "Minutes"/* used as part of an input "[input] Minutes" */, $this->_framework() ) . '</option>'.
                                '<option ' . $hoursSelected . ' value="' . 60 * 60 . '" >' . __( "Hours"/* used as part of an input "[input] Hours" */, $this->_framework() ) . '</option>'.
                                '<option ' . $daysSelected . ' value="' . 60 * 60 * 24 . '" >' . __( "Days"/* used as part of an input "[input] Days" */, $this->_framework() ) . '</option>'.
                                '<option ' . $weeksSelected . ' value="' . 60 * 60 * 24 * 7 . '" >' . __( "Weeks"/* used as part of an input "[input] Weeks" */, $this->_framework() ) . '</option>'.
                            '</select>';
        return $settingControl;
    }

    /**
     * lavaSettingsCallback::addPasswordWrapper()
     * 
     * Adds the wrapping html to the password input
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function addPasswordWrapper( $settingControl, $theSetting )
    {
        $placeholder = 'placeholder="'. $theSetting->getProperty( "placeholder" ) .'"';
        $settingControl =  '<div class="input-cntr lava-focus-outer show-status clearfix" data-show="password">'.
                                '<div class="validation" data-state="not-invoked"></div>'.
                                '<input '.$placeholder.' type="text" class="lava-auto-resize password-show lava-focus-inner" value="' . $theSetting->getValue( true ) . '"/>'.
                                $settingControl.
                            '</div>';
        return $settingControl;
    }

    /**
     * lavaSettingsCallback::addTextWrapper()
     * 
     * Adds the wrapping html to the text input
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function addTextWrapper( $settingControl, $theSetting )
    {
        $settingKey = $theSetting->getKey();
        $settingWho = $theSetting->who;
        $pluginSlug =  $this->_slug();
        $settingInputName = "{$pluginSlug}[{$settingWho}/{$settingKey}]";
        $settingInputID = "{$pluginSlug}-{$settingWho}-{$settingKey}";

        $placeholder = 'placeholder="'. $theSetting->getProperty( "placeholder" ) .'"';
        $settingControl =  '<div class="input-cntr show-status clearfix lava-focus-outer">'.
                                '<div class="validation" data-state="not-invoked"></div>'.
                                '<input id="' . $settingInputID . '" class="lava-focus-inner lava-auto-resize" name="' . $settingInputName . '"  '.$placeholder.' type="text" value="' . $theSetting->getValue( true ) . '"/>'.
                            '</div>';
        return $settingControl;
    }

	/**
     * lavaSettingsCallback::addImageUx()
     * 
     * Adds the image uploader HTML and CSS
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function addImageUx( $settingControl, $theSetting )
    {
        $settingKey = $theSetting->getKey();
        $settingWho = $theSetting->who;
        $pluginSlug =  $this->_slug();
        $settingInputName = "{$pluginSlug}[{$settingWho}/{$settingKey}]";
        $settingInputID = "{$pluginSlug}-{$settingWho}-{$settingKey}";
        $settingUploadInputName = "{$pluginSlug}_upload-{$settingWho}-{$settingKey}";//the name attribute for the FILE input
        $settingUploadHiddenInputName = "{$pluginSlug}_upload[{$settingWho}/{$settingKey}]";//the base name attribute for the upload settings
        $settingUploadInputID = "{$pluginSlug}_upload-{$settingWho}-{$settingKey}";
        $settingValue = $theSetting->getValue( true );

        $formData = array(
            array(
                "name" => "action",
                "value" => $this->_slug( "image_upload" )
            ),
            array(
                "name" => "nonce",
                "value" => wp_create_nonce( $this->_slug( "image_upload" ) )
            )
        );

        $formData = json_encode($formData);

        $fileInputClass = "lava-ajax-request-label-" . rand(10000,99999);

        $theInput = '<input data-file_input_class="' . $fileInputClass . '" data-form_data=\'' . $formData . '\' class="' . $fileInputClass . ' lava-file_upload-manual_select" id="' . $settingUploadInputID . '" type="file" name="' . $settingUploadInputName . '" />';
        $theLabel = '</span><label for="' . $settingUploadInputID . '">' . $theInput;

        $placeholder = 'placeholder="'. $theSetting->getProperty( "placeholder" ) .'"';
        $settingControl =  '<label for="' . $settingUploadInputID . '" ><div class="image-thumb lava-file_upload lava-file_upload-dropzone show-status clearfix">'.
								'<img src="' . $settingValue . '" />'.
                                '<div class="lava-message lava-message-absolute-in-cntr lava-message-red lava-message-html5"><span class="drag-drop-only">' .
                                    __( sprintf("To change the image either drop one here or %sselect an image%s", $theLabel, "</label>"), $this->_framework() ) .
                                '</div>'.
                                '<div class="lava-message lava-message-absolute-in-cntr lava-message-red uploading-message">' . __( "Uploading", $this->_framework() ) . '</div>' .
                                '<input data-actual="true" class="lava-file-upload-url-dump" id="' . $settingInputID . '" name="' . $settingInputName . '" type="hidden" value="' . $settingValue . '"/>'.
                                '<input class="' . $fileInputClass . '" name="' . $settingUploadHiddenInputName . '[upload_key]"  type="hidden" value="' . $settingUploadInputName . '"/>'.//tells the upload handler where to look for a file (so it doesn't blindly copy entire contents of $_FILES)
                                '<input class="' . $fileInputClass . '" name="' . $settingUploadHiddenInputName . '[callback_tag]"  type="hidden" value="' . "{$settingWho}-{$settingKey}" . '"/>'.
                                '<div class="lava-shadow-overlay"></div>'.
                            '</div></label>';
        return $settingControl;
    }

    function addColorUx( $settingControl, $theSetting ) {
        $settingControl = '<div class="color-preview">'.
                                $settingControl .
                                '<div class="lava-message lava-message-absolute-in-cntr lava-message-red bottom-rounded">Click to change</div>' .
                                '<div class="lava-shadow-overlay"></div>' .
                                '<span class="color-hex"></span>' .
                            '</div>';
        return $settingControl;
    }

    /**
     * lavaSettingsCallback::addCheckboxUx()
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function addCheckboxUx( $settingControl, $theSetting )
    {
        $checked = "unchecked";
        if( $theSetting->getValue( true ) == "on")
        {
            $checked = 'checked';
        }
        $settingControl .=  '<div title ="' . __( /* In context of a checkbox slider */"Click to enable/disable ", $this->_framework() ) . '" class="js-only tiptip checkbox-ux '.$checked.'"></div>';
        return $settingControl;
    }

	/**
     * lavaSettingsCallback::addSelectUx()
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function addSelectUx( $settingControl, $theSetting )
    {
		$settingKey = $theSetting->getKey();
        $settingWho = $theSetting->who;
        $pluginSlug =  $this->_slug();
        $settingInputName = "{$pluginSlug}[{$settingWho}/{$settingKey}]";
        $settingInputID = "{$pluginSlug}-{$settingWho}-{$settingKey}";
		$options = $theSetting->getProperty( "setting-options" );
		$value = $theSetting->getValue();

		if( !is_array($options) ) {
			$options = array();
		}

		$settingControl = '<select id="' . $settingInputID . '" name="' . $settingInputName . '" >';
								foreach( $options as $option ) {
									$selected = 'data-bob="test"';
									if( $value == $option['value'] ) {
										$selected = 'selected="selected"';
									}
									$settingControl .= '<option ' . $selected . ' value="' . $option['value'] . '" >' . $option['name'] . '</option>';
								}
		$settingControl .='</select>';
        return $settingControl;
    }

    function addCodeUx( $settingControl, $theSetting ) {
        $settingVars = $theSetting->getVars();
        extract( $settingVars );

        $settingControl =   '<div class="lava-code-box lava-focus-outer show-status">'.
                                '<div class="code-box-top"></div>'.
                                '<div class="code-box-mid">'.
                                    '<textarea data-actual="true" class="lava-code-textarea" id="' . $settingInputID . '" name="' . $settingInputName . '" >' . $settingValue . '</textarea>'.
                                '</div>'.
                                '<div class="code-box-bot"></div>'.
                            '</div>';
        return $settingControl;
    }

    function addTextareaUx( $settingControl, $theSetting ) {
        $settingVars = $theSetting->getVars();
        extract( $settingVars );

        $settingControl = '<textarea class="lava-auto-resize" data-actual="true" name="' . $settingInputName . '" id="' .  $settingInputID . '" >' . $settingValue . '</textarea>';

        return $settingControl;
    }

    /**
     * lavaSettingsCallback::nonces()
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function nonces()
    {
        wp_nonce_field();//set the referrer field
        $capabilities = array(
            "manage_options" => "setting-nonce"
        );
        $otherNonces = array(
            "purpose" => "save"
        );
        if( is_network_admin() )
        {
            $capabilities["manage_network_options"] = "network-setting-nonce";
        }
        foreach( $capabilities as $capability => $name )
        {
            if( current_user_can( $capability ) )
            {
                $action = $this->_slug( $name );
                wp_nonce_field( $action, $name, false );
            }
        }
        foreach( $otherNonces as $name => $value )
        {
            echo "<input class=\"lava-form-$name\" type=\"hidden\" name=\"$name\" value=\"$value\" />";
        }
    }

    /**
     * lavaSettingsCallback::removeActions()
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function removeActions( $settingActions, $theSetting )
    {
        $settingActions = "";
        return $settingActions;
    }

    function hiddenForms( $forms ) {
        $defaults = array(
            "id" => "",
            "fields" => array(),
            "capabilities" => array()
        );
        $resetForm = array(
            "id" => "lava-settings-reset",
            "fields" => array(
                "purpose" => "reset",
                "reset-scope" => "total"
            ),
            "capabilities" => array(
                "manage_options" => "setting-nonce"
            )
        );
        $forms['lava-settings-reset'] = wp_parse_args($resetForm, $defaults);

        return $forms;
    }

    
    /**
     * lavaSettingsCallback::()
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    
}
?>