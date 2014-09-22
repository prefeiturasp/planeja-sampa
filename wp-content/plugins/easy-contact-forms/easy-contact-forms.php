<?php
/*
Plugin Name: Easy Contact Forms
Plugin URI: http://easy-contact-forms.com 
Version: 1.4.7
Author: ChampionForms.com
Author URI: http://championforms.com
Description: Easy Contact Forms. Easy to create. Easy to fill out. Easy to change. Easy to manage. Easy to protect	
*/
	

$easycontactforms_request = (object) array(); 	

if (!class_exists('EasyContactForms')) {
	class EasyContactForms {
		static function install() {
			$plugin_prefix_root = plugin_dir_path( __FILE__ );
			$plugin_prefix_filename = "{$plugin_prefix_root}/easy-contact-forms.install.php";
			include_once $plugin_prefix_filename;	
			easycontactforms_install();
			easycontactforms_install_data();
		}	
		static function uninstall() {
			$plugin_prefix_root = plugin_dir_path( __FILE__ );
			$plugin_prefix_filename = "{$plugin_prefix_root}/easy-contact-forms.install.php";
			include_once $plugin_prefix_filename;	
			easycontactforms_uninstall();
		}	
	}
	$easycontactforms = new EasyContactForms(); 	
} 	

if ( isset($easycontactforms) && function_exists('register_activation_hook') ){

	register_activation_hook( __FILE__, array('EasyContactForms', 'install') );
	register_uninstall_hook( __FILE__, array('EasyContactForms', 'uninstall') );
	add_action( 'admin_menu', 'easycontactforms_main_page', 1 );
	add_action( 'wp_ajax_nopriv_easy-contact-forms-submit', 'easycontactforms_entrypoint' );
	add_action( 'wp_ajax_easy-contact-forms-submit', 'easycontactforms_entrypoint' );	
	add_shortcode( 'easy_contact_forms_frontend', 'easycontactforms_entrypoint_shortcode' );
	add_shortcode( 'easy_contact_forms', 'easycontactforms_formentrypoint' );
	add_action('wp_head', 'easycontactforms_w3c_load_styles');
	add_action('phpmailer_init', 'easycontactforms_phpmailer_init');	
	add_action( 'plugins_loaded', 'easycontactforms_update_db_check');

} 
function easycontactforms_update_db_check() {

	$db_version = '1.4.7';
	require_once 'easy-contact-forms-root.php'; 		
	require_once 'easy-contact-forms-applicationsettings.php'; 		
	$as = EasyContactFormsApplicationSettings::getInstance();
	if ($as->isEmpty('ProductVersion') || $as->get('ProductVersion') != $db_version) {
		EasyContactForms::install();
	}
}

function easycontactforms_main_page() {
		add_menu_page( 'Easy Contact Forms', 'Contact Forms', 'manage_options', 'easy-contact-forms-main-page', 'easycontactforms_entrypoint'); 
		add_submenu_page('easy-contact-forms-main-page', __('Easy Contact Forms Support'), __('Support'), 'manage_options', 'easy-contact-forms-support',  'easycontactforms_get_support_page');
 
}

function easycontactforms_get_support_page() {
 
	require_once 'easy-contact-forms-support.php';
	require_once 'easy-contact-forms-utils.php';
	$support = new EasyContactFormsSupport();
	$support->getSupportPage();
 
}

function easycontactforms_tag() {
 
	easycontactforms_entrypoint();
 
}
/**
 * 	easycontactforms_w3c_load_styles
 *
 *
 * @return
 * 
 */

function easycontactforms_w3c_load_styles() {

	if (is_admin()) {
		return;
	}

	require_once 'easy-contact-forms-root.php';
	require_once 'easy-contact-forms-applicationsettings.php';
	$as = EasyContactFormsApplicationSettings::getInstance();
	if ($as->get('w3cCompliant') && !$as->isEmpty('w3cStyle')) {
		if (!defined ('EASYCONTACTFORMS__APPLICATION_ROOT'))
			DEFINE('EASYCONTACTFORMS__APPLICATION_ROOT', rtrim(get_bloginfo('wpurl'), '/'));
		if (!defined ('EASYCONTACTFORMS__engineWebAppDirectory'))
			DEFINE('EASYCONTACTFORMS__engineWebAppDirectory', plugins_url('', __FILE__));
		if (!defined ('_EASYCONTACTFORMS_PLUGIN_PATH'))
			DEFINE('_EASYCONTACTFORMS_PLUGIN_PATH', rtrim(plugin_dir_path( __FILE__ ), "/\\"));
		require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-appconfigdata.php';
		$forms = EasyContactFormsClassLoader::getObject('CustomForms');
		echo $forms->basicLoadStyle($as->get('w3cStyle'));
	}

}

/**
 * 	Easy Contact Forms form entrypoint
 *
 * @param array $map
 * 
 *
 * @return string
 * 
 */

function easycontactforms_formentrypoint($map) {

	if (!defined ('EASYCONTACTFORMS__APPLICATION_ROOT'))
		DEFINE('EASYCONTACTFORMS__APPLICATION_ROOT', rtrim(get_bloginfo('wpurl'), '/'));
	if (!defined ('EASYCONTACTFORMS__engineWebAppDirectory'))
		DEFINE('EASYCONTACTFORMS__engineWebAppDirectory', plugins_url('', __FILE__));
	if (!defined ('_EASYCONTACTFORMS_PLUGIN_PATH'))
		DEFINE('_EASYCONTACTFORMS_PLUGIN_PATH', rtrim(plugin_dir_path( __FILE__ ), "/\\"));

	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-utils.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-database.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-root.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-applicationsettings.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-customforms.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-appconfigdata.php';

	$js = '';
	$as = EasyContactFormsApplicationSettings::getInstance();
	if (!$as->get('FixJSLoading')) {
		wp_enqueue_script('ufoforms', plugins_url('easy-contact-forms-forms.1.4.7.js', __FILE__));
	} else {

		$js .= '<script type="text/javascript" src="' . plugins_url('easy-contact-forms-forms.1.4.7.js', __FILE__) . '"></script>';

	}
	if ($as->get('FixJSLoading2')) {
		$js .= '<script type="text/javascript">ufoForms.initValidation();</script>';
	}

	$pb = $as->getPBLink();

	if (!isset($map['pn'])) {
		$pagename = get_query_var('pagename');
		$pageid = get_query_var('page_id');
		if ( !$pagename && $pageid > 0 ) {
			global $wp_query;
			$post = $wp_query->get_queried_object();
			$pagename = $post->post_name;
		}
		$map['pn'] = $pagename;
	}

	$map = array_merge($map, $_REQUEST);
	global $current_user;
	$userid = $current_user->ID;
	unset($map['frid']);
	$map['frid'] = $userid;
	return EasyContactFormsCustomForms::getForm($map) . $pb . $js;

}

/**
 * 	easycontactforms_phpmailer_init
 *
 * @param  $phpmailer
 * 
 *
 * @return
 * 
 */

function easycontactforms_phpmailer_init($phpmailer) {

	global $easycontactforms_request;
	if (!isset($easycontactforms_request->attachment)) {
		return;
	}
	require_once 'easy-contact-forms-database.php';
	for ($i = 0; $i < count($easycontactforms_request->attachment); $i++) {
		$spec = $easycontactforms_request->attachment[$i];
		$phpmailer->addAttachment($spec->path, $spec->name);
	}
	unset($easycontactforms_request->attachment);
	$easycontactforms_request->attachment = null;

}

/**
 * 	Easy Contact Forms entrypoint
 *
 */

function easycontactforms_entrypoint() {

	$l_locale = get_locale();

	$map = $_REQUEST;

	if (!defined ('EASYCONTACTFORMS__APPLICATION_ROOT'))
		DEFINE('EASYCONTACTFORMS__APPLICATION_ROOT', rtrim(get_bloginfo('wpurl'), '/'));
	if (!defined ('EASYCONTACTFORMS__engineWebAppDirectory'))
		DEFINE('EASYCONTACTFORMS__engineWebAppDirectory', plugins_url('', __FILE__));
	if (!defined ('_EASYCONTACTFORMS_PLUGIN_PATH'))
		DEFINE('_EASYCONTACTFORMS_PLUGIN_PATH', rtrim(plugin_dir_path( __FILE__ ), "/\\"));

	$tag = strtolower(str_replace('_', '-', $l_locale));
	$map['l'] = $tag;

	require_once _EASYCONTACTFORMS_PLUGIN_PATH  . DIRECTORY_SEPARATOR . 'easy-contact-forms-strings.php';

	if (!(@include_once _EASYCONTACTFORMS_PLUGIN_PATH  . DIRECTORY_SEPARATOR . 'easy-contact-forms-resources_' . $tag . '.php')) {

		require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-resources_en-gb.php';
		$map['l'] = 'en-gb';
	}

	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-utils.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-database.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-root.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-applicationsettings.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-appconfigdata.php';
	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-securitymanager.php';

	global $current_user;
	$userid = $current_user->ID;

	unset($map['frid']);
	$map['frid'] = $userid;

	if (isset($map['ac']) && ($map['ac'] == '1')) {
		EasyContactFormsRoot::ajaxCall($map);
		die();
	}

	$map = EasyContactFormsSecurityManager::getRights($map);
	if (isset($map['m']) && ($map['m'] == 'download')) {
		EasyContactFormsRoot::download($map);
		die();
	}

	if (!isset($map['m'])) {
		$map['m'] = 'show';
	}
	if (!isset($map['t'])) {
		$map['t'] = 'DashBoardView';
	}

	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-mouse');
	wp_enqueue_script('jquery-ui-sortable');

	wp_enqueue_style('easy-contact-forms-admin-ui-css','http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/themes/smoothness/jquery-ui.css',false,'1.4.7',false);

	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-position');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-dialog');

	wp_enqueue_script('json-json', plugins_url('js/json.js', __FILE__));
	wp_enqueue_script('easy-contact-forms-html', plugins_url('easy-contact-formshtml.1.4.7.js', __FILE__));
	wp_enqueue_script('jqui-scrollto', plugins_url('js/jqui/scrollto.js', __FILE__));
	wp_enqueue_script('js-as', plugins_url('js/as.js', __FILE__));

	wp_enqueue_script('calendar-stripped', plugins_url('js/calendar/calendar_stripped.js', __FILE__));

	wp_enqueue_script('calendar-setup-stripped', plugins_url('js/calendar/calendar-setup_stripped.js', __FILE__));

	wp_enqueue_script('calendar-lang-en', plugins_url('js/calendar/lang/calendar-en.js', __FILE__));

	wp_enqueue_script('js-as', plugins_url('js/as.js', __FILE__));

	if (EasyContactFormsApplicationSettings::getInstance()->get('UseTinyMCE')) {
		wp_enqueue_script('tiny_mce', plugins_url('js/tinymce/tiny_mce.js', __FILE__));
	}

	$js = "config = {};";
	$js .= "config.url='" . admin_url( 'admin-ajax.php' ) . "';";
	$js .= "config.phonenumberre=/^" . EasyContactFormsApplicationSettings::getInstance()->get('PhoneRegEx') . "/;";
	$js .= "config.initial = {t:'" . $map['t'] . "', m:'" . $map['m'] . "'};";
	$js .= "config.bodyid = 'divEasyContactForms';";
	$js .= "config.resources = {};";

	$js .= "config.resources['EmailFormatIsExpected'] = " . json_encode(EasyContactFormsT::get('EmailFormatIsExpected')) . ";";

	$js .= "config.resources['ValueLengthShouldBeBetween'] = " . json_encode(EasyContactFormsT::get('ValueLengthShouldBeBetween')) . ";";

	$js .= "config.resources['ValueLengthShouldBeMoreThan'] = " . json_encode(EasyContactFormsT::get('ValueLengthShouldBeMoreThan')) . ";";

	$js .= "config.resources['ValueLengthShouldBeLessThan'] = " . json_encode(EasyContactFormsT::get('ValueLengthShouldBeLessThan')) . ";";

	$js .= "config.resources['ThisIsAPhoneNumber'] = " . json_encode(EasyContactFormsT::get('ThisIsAPhoneNumber')) . ";";

	$js .= "config.resources['ThisIsAnIntegerField'] = " . json_encode(EasyContactFormsT::get('ThisIsAnIntegerField')) . ";";

	$js .= "config.resources['ThisFieldIsRequired'] = " . json_encode(EasyContactFormsT::get('ThisFieldIsRequired')) . ";";

	$js .= "config.resources['ThisIsAFieldOfCurrencyFormat'] = " . json_encode(EasyContactFormsT::get('ThisIsAFieldOfCurrencyFormat')) . ";";

	$js .= "config.resources['ItwillDeleteRecordsAreYouSure'] = " . json_encode(EasyContactFormsT::get('ItwillDeleteRecordsAreYouSure')) . ";";

	$js .= "config.resources['NoRecordsSelected'] = " . json_encode(EasyContactFormsT::get('NoRecordsSelected')) . ";";
	$js .= "config.resources['CloseFilter'] = " . json_encode(EasyContactFormsT::get('CloseFilter')) . ";";
	$js .= "config.resources['Search'] = " . json_encode(EasyContactFormsT::get('Search')) . ";";
	$js .= "config.resources['NoResults'] = " . json_encode(EasyContactFormsT::get('NoResults')) . ";";
	$js .= "config.resources['Uploading'] = " . json_encode(EasyContactFormsT::get('Uploading')) . ";";
	$js .= "config.resources['Upload'] = " . json_encode(EasyContactFormsT::get('Upload')) . ";";
	$js .= "config.resources['CF_Pin'] = " . json_encode(EasyContactFormsT::get('CF_Pin')) . ";";
	$js .= "config.resources['CF_UnPin'] = " . json_encode(EasyContactFormsT::get('CF_UnPin')) . ";";
	$js .= "var appManConfig = config;";

	echo "<link href='" . EASYCONTACTFORMS__engineWebAppDirectory . '/js/calendar/css/calendar-system.css' . "' rel='stylesheet' type='text/css'/>";

	if (function_exists('is_admin')) {

		$paramName = is_admin() ? 'DefaultStyle2' : 'DefaultStyle';
		$styleName = EasyContactFormsApplicationSettings::getInstance()->get($paramName);

		$paramName = is_admin() ? 'ApplicationWidth2' : 'ApplicationWidth';
		$appWidth = EasyContactFormsApplicationSettings::getInstance()->get($paramName);

	}
	else {

		$styleName = EASYCONTACTFORMS__DEFAULT_STYLE;
		$appWidth = EasyContactFormsApplicationSettings::getInstance()->get('ApplicationWidth');

	}

	$wrStyle = 'style=\'width:' . $appWidth . 'px\'';

	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'styles' . DIRECTORY_SEPARATOR . $styleName . DIRECTORY_SEPARATOR . 'easy-contact-forms-getstyle.php';

	require_once _EASYCONTACTFORMS_PLUGIN_PATH . DIRECTORY_SEPARATOR . 'easy-contact-forms-menu.php';
	echo "<div id='ufo-app-wrapper' $wrStyle>";
	EasyContactFormsMenu::getMenu($map);
	echo "<div id='divEasyContactForms'>";
	echo "<script>$js</script>";
	echo EasyContactFormsRoot::processRequest($map);
	echo "</div>";
	echo "</div>";

}

	/**
	 * 	easycontactforms_entrypoint_shortcode
	 *
	 *
	 * @return
	 * 
	 */
	function easycontactforms_entrypoint_shortcode() {

		ob_start();
		easycontactforms_entrypoint();
		$var = ob_get_contents();
		ob_end_clean();
		return $var;

	}
