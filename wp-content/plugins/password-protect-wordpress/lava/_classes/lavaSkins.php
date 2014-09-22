<?php
/**
 * The lava Skins class
 * 
 * @package Lava
 * @subpackage lavaSkins
 * 
 * @author Daniel Chatfield
 * @copyright 2011
 * @version 1.0.0
 */
 
/**
 * lavaSkins
 * 
 * @package Lava
 * @subpackage LavaSkins
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaSkins extends lavaBase
{
    protected $skins = array();
    public $currentSkinSlug;
	public $translations = array();
    
    /**
     * lavaSkins::lavaConstruct()
     * 
     * This method is called by the __construct method of lavaBase and handles the construction
     * 
     * @return void
     * 
     * @since 1.0.0
     */
    function lavaConstruct()
    {
        $callbacks = $this->_new( 'lavaSkinsCallback' );
		
        //add the setting that holds which skin is selected
        $this->_settings()
            ->addSetting( 'skin', 'skins' )
                ->setType( 'skin' )
                ->setName( __( 'Select a skin', $this->_framework() ) )
				->setDefault( 'defaultcustom' );
    }

    function init() {
    	$this->parseSkins();
    }


    function parseSkins()
    {
        $skinPaths = glob( dirname( $this->_file() ) . '/skins/*' , GLOB_ONLYDIR);
        lava::currentPlugin( $this->_this() );//make sure theme files can access the plugin easily

        foreach( $skinPaths as $skinPath )
        {
            $includePath = $skinPath . '/skin.php';
            if( file_exists( $includePath ) )
            {
                $dir = str_replace(  '\\' , '/' , $skinPath );
                $dir = explode( '/', $dir );
                $dir = end( $dir );
                $this->currentSkinSlug = $dir;

				$skinName = $dir;
				$skinAuthor = "Undefined";

				if( $this->_request( "admin" ) )://only parse file headers on admin requests

					$skinHeader = file_get_contents( $includePath );

					if( strpos( substr( $skinHeader, 0, 20 ) , '/*' ) === false ) { //the substr prevents the search incorrectly matching the string in code (like on this line) by only searching the top of the file (where the header should be)
						//File has no header so leave defaults
					} else {
						$skinHeader = explode( '/*', $skinHeader );
						$skinHeader = $skinHeader[1];
						$skinHeader = explode( '*/', $skinHeader );
						$skinHeader = $skinHeader[0];
						$skinHeader = explode( "\n", $skinHeader );

						foreach( $skinHeader as $head )
						{
							$head = trim( $head );
							if( !empty( $head ) )
							{
								$head = explode( ":", $head );
								if( count( $head == 2 ) )
								{
									$property = strtoupper( $head[0] );
									$value = trim( $head[1] );

									switch( $property )
									{
										case 'NAME':
										case 'TITLE':
											$skinName = $value;
										break;
										case 'AUTHOR':
											$skinAuthor = $value;
										break;
									}
								}
							}
						}
					}
				endif;

				$this->registerSkin()
					->setName( $skinName )
					->setAuthor( $skinAuthor )
				;

                include_once( $includePath );
            }
        }
    }

    function registerSkin()
    {
        $skinSlug = $this->currentSkinSlug;

        $arguments = array(
            $skinSlug
        );
        $theSkin = $this->_new( "lavaSkin", $arguments );

        $this->skins[ $skinSlug ] = $theSkin;

		$this->_settings( false )
				->fetchSetting( "skin", "skins" )
                ->addsettingOption( $theSkin );

        return $theSkin;
    }

    function fetchSkin( $handle ) {
    	return $this->getSkin( $handle );
    }

	function getSkin( $handle )
	{
		if( array_key_exists( $handle, $this->skins ) )
		{
			return $this->skins[$handle];
		}
		$dir = str_replace(  '\\' , '/' , $handle );
		$dir = explode( '/', $dir );
		$dir = end( $dir );
		if( array_key_exists( $dir, $this->skins ) )
		{
			return $this->skins[$dir];
		}
		$dir = str_replace(  '\\' , '/' , dirname($handle) );
		$dir = explode( '/', $dir );
		$dir = end( $dir );
		if( array_key_exists( $dir, $this->skins ) )
		{
			return $this->skins[$dir];
		}
		return $this;
	}

    function fetchSkins()
    {
        return $this->skins;
    }

	function currentSkin() {
		return $this->_settings()->fetchSetting( "skin", "skins" )->getValue();
	}

	function fetchCurrentSkin() {
		$currentSkin = $this->currentSkin();
		return $this->fetchSkin( $currentSkin );
	}

	function renderTemplate( $templateName ) {
		$currentSkin = $this->currentSkin();

		if( !file_exists( dirname( $this->_file() ) . '/skins/' . $currentSkin . '/templates/' . $templateName . '.html' ) ) {
			$currentSkin = "default";
		}

		$filePath = dirname( $this->_file() ) . '/skins/' . $currentSkin . '/templates/' . $templateName . '.html';
		$options = array(
			"cache_dir" => dirname( $this->_file() ) . '/lava/_cache/'
		);

		$h2o = new h2o( $filePath, $options );

		$templateVars = apply_filters( $this->_slug( "_templateVars" ) , array() );

		return $h2o->render( $templateVars );
	}

	function addTranslation( $key, $value ) {
		$this->translations[ $key ] = $value;
		return $this->_skins( false );
	}

	function getTranslations() {
		return $this->translations;
	}
}
?>