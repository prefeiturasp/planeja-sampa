<?php
/*
Title: Default with options
Author: Daniel Chatfield
*/
$thePlugin = lava::currentPlugin();
$skinUrl = $thePlugin->_skins()->getSkin( __FILE__ )->skinUrl();
$thePlugin->_skins()
    ->getSkin( __FILE__ )
    ->addSkinSetting( "logo" )
        ->setName( __( "Logo", $thePlugin->_slug() ) )
        ->setType( "image" )
        ->setDefault( $skinUrl . 'static/images/logo.png' )
        ->addTag( "is-premium" )
    ->addSkinSetting( "enable_message" )
        ->setName( __( "Display a message", $thePlugin->_slug() ) )
        ->setHelp( __( "Set a message to appear above the form. Any urls and email addresses will be converted into links.", $thePlugin->_slug() ) )
        ->setType( "checkbox" )
        ->setDefault( "off" )
        ->settingToggle( "message" )
        ->addTag( "is-premium" )
    ->addSkinSetting( "message" )
        ->setType( "textarea" )
        ->addTag( "no-margin" )
        ->addTag( "align-center" )
        ->addTag( "is-premium" )
    ->addPresetSkinSetting( "custom_css" )
        ->addTag( "is-premium" )
;
?>