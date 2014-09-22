<?php

/**
 * @file
 *
 * 	EasyContactFormsSupport class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

/**
 * 	A proxy class to connect to the support API.
 *
 */
class EasyContactFormsSupport {

	/**
	 *
	 * @var string $supportApi
	 * 	support API Link
	 */

	protected $supportApi = 'http://easy-contact-forms.com/supportapi.php';
	/**
	 * 	getSupportPage
	 *
	 *
	 * @return
	 * 
	 */
	function getSupportPage() {

		if (!current_user_can('manage_options')) {
			return;
		}

		if (EasyContactFormsSupport::submitSupportForm()) {
			return;
		}

		wp_enqueue_script('easy-contact-forms-html', plugins_url('easy-contact-formshtml.1.4.7.js', __FILE__), array(), false, true);

		wp_enqueue_script('easy-contact-forms-env', plugins_url('easy-contact-forms.env.1.4.7.js', __FILE__), array(), false, true);

		$env = (object) array();
		$env->System = (object) array();
		$env->Wordpress = (object) array();
		$env->Theme = (object) array();

		global $current_user;
		$wp_url = rtrim(site_url(), '/');
		$version = '1.4.7';

		$env->System->PHPVersion = phpversion();
		$env->System->UserAgent = $_SERVER['HTTP_USER_AGENT'];
		$env->System->Server = $_SERVER['SERVER_SOFTWARE'];

		$env->Wordpress->Version = get_bloginfo('version');
		$env->Wordpress->Home = home_url();
		$env->Wordpress->Site = $wp_url;
		$env->Wordpress->AdminEmail = $current_user->user_email;

		$env->Theme = (object) array();

		if (function_exists('wp_get_theme')) {
			$theme = wp_get_theme();
		}
		else {
			$theme_path = get_stylesheet_directory().'/style.css';
			$theme = get_theme_data($theme_path);
			$theme = (object) $theme;
		}

		$env->Theme->Name = $theme->Name;
		$env->Theme->ThemeURI = $theme->ThemeURI;
		$env->Theme->Version = $theme->Version;

		$env->Plugins = array();
		$plugins = get_plugins();

		$plugintable = array();
		$plugintable[] = "<tr><th>" .__('Name'). "</th><th>" .__('Version'). "</th><th>&nbsp;&nbsp;URI</th></tr>";
		foreach(array_keys($plugins) as $key) {
			if (!is_plugin_active($key)) {
				continue;
			}
			$plugindata =& $plugins[$key];
			$plugin = (object) array();
			$env->Plugins[] = $plugin;
			$plugin->Name = $plugindata['Name'];
			$plugin->Version = $plugindata['Version'];
			$plugin->PluginURI = $plugindata['PluginURI'];

			$plugintable[] = "<tr><td>{$plugin->Name}</td><td>{$plugin->Version}</td><td>{$plugin->PluginURI}</td></tr>";
		}
		$plugintable = implode('', $plugintable);
		$plugintable = "<table class='ufo-object-table'>{$plugintable}</table>";

		global $wpdb;

		$query = "SELECT CustomForms.id AS id, CustomForms.Description AS Name, CustomForms.NotificationSubject, CustomForms.NotificationText, CustomForms.SendNotificationAsText AS NotificationAsText, CustomForms.SendConfirmation AS SendConfirmations, CustomForms.SendFrom AS SendFromName, CustomForms.SendFromAddress, CustomForms.ConfirmationSubject, CustomForms.ConfirmationText, CustomForms.IncludeVisitorsAddressInReplyTo AS ReplyToVisitors, CustomForms.ReplyToNameTemplate, CustomForms.ConfirmationReplyToName, CustomForms.ConfirmationReplyToAddress, Users.email AS RecipientEmail, Users.Role AS RecipientRole, EngineUsers.user_login AS RecipientUserName FROM {$wpdb->prefix}easycontactforms_customforms AS CustomForms	LEFT JOIN {$wpdb->prefix}easycontactforms_users AS Users LEFT JOIN {$wpdb->prefix}users AS EngineUsers ON Users.CMSId = EngineUsers.ID	ON CustomForms.ObjectOwner = Users.id";

		$env->Forms = $wpdb->get_results($query);
		$formtable = EasyContactFormsUtils::getSimpleTable($env->Forms);

		$env->Settings->SupportInfo = "Objects could not be loaded";

		if (@include_once 'easy-contact-forms-root.php') {

			require_once 'easy-contact-forms-applicationsettings.php';
			unset($env->Settings->SupportInfo);

			$as = EasyContactFormsClassLoader::getObject('ApplicationSettings', true, 1);

			$env->Settings = $as->getData();
			unset($env->Settings->SecretWord);
			unset($env->Settings->id);
			unset($env->Settings->Description);
		}

		$settinstable = EasyContactFormsUtils::getSimpleObjectTable($env->Settings);

			$data = "
		<h4>System Information:</h4>
		<table class='ufo-object-table'>
		<tr><th>Plugin Version:</th><td>{$version}</td></tr>
		<tr><th>WP Version:</th><td>{$env->Wordpress->Version}</td></tr>
		<tr><th>WP home URL</th><td>{$env->Wordpress->Home}</td></tr>
		<tr><th>WP site URL</th><td>{$env->Wordpress->Site}</td></tr>
		<tr><th>Admin email</th><td>{$env->Wordpress->AdminEmail}</td></tr>
		<tr><th>JS Errors</th><td id='support-data-table-js-errors'><span style='color:red'>Yes</span></td></tr>
		<tr><th>jQuery version</th><td id='support-data-table-query-version'></td></tr>
		<tr><th>PHP Version:</th><td>{$env->System->PHPVersion}</td></tr>
		<tr><th>User Agent:</th><td>{$env->System->UserAgent}</td></tr>
		<tr><th>Server Software:</th><td>{$env->System->Server}</td></tr>
		<tr><th>Theme Name:</th><td>{$env->Theme->Name}</td></tr>
		<tr><th>Theme URI:</th><td>{$env->Theme->ThemeURI}</td></tr>
		<tr><th>Theme Version:</th><td>{$env->Theme->Version}</td></tr>
		</table>

		<h4>Active Plugins:</h4>
		$plugintable

		<h4>Settings:</h4>
		$settinstable

		<h4>Form configuration:</h4>
		$formtable

		";

		$supportform = $this->getForm();
		if (!is_string($supportform)) {
			$supportform= $supportform->get_error_message();
		}
		echo "<div class='wrap'>";
		echo EasyContactFormsSupport::validateForm();
			echo "<h2>" . __('Easy Contact Forms Support') . "</h2>";

			echo "<div class='postbox-container-1' style='width:620px'>";
				echo "<div class='metabox-holder'>";
					echo "<div class='postbox'>";

						echo "<h3>" . __('Support Request') . "</h3>";
						echo "<div class='inside'>";
							echo "<form method='POST'>";
								echo "<input type='hidden' name='support-data-product-version' value='{$version}'>";
								echo "<input type='hidden' name='support-form-data' value='1'>";
								echo "<input type='hidden' name='support-data-query-version' id='support-data-query-version'>";
								echo "<input type='hidden' name='support-data-js-errors' id='support-data-js-errors' value='Yes'>";
								$env = base64_encode(serialize($env));
								echo "<input type='hidden' name='support-data-env' id='support-data-env' value='{$env}'>";
								echo $supportform;

								echo "<label><input type='checkbox' checked onchange='document.getElementById(\"support-data-env\").disabled = !this.checked'>&nbsp;" . __('Send Environment Info') . "</label><br />";

								echo "<br /><input class='button-primary' type='submit' value='" . __('Submit') . "'>";
							echo "</form>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";

			echo "<style>.ufo-object-table th {text-align:left;}.ufo-object-table{font-size:11px;}</style>";
			echo "<div class='postbox-container-1' style='width:620px'>";
				echo "<div class='metabox-holder'>";
					echo "<div class='postbox'>";
						echo "<h3>" . __('System information to be sent along with the support request:') . "</h3>";
						echo "<div class='inside'>";
							echo "<div>";
								echo $data;
							echo "</div>";
						echo "</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";

		echo "</div>";

	}

	/**
	 * 	apiCall
	 *
	 * @param  $action
	 * 
	 * @param  $args
	 * 
	 *
	 * @return
	 * 
	 */
	function apiCall($action, $args = null) {

		$body = array();
		$body['plugin'] = 'easy-contact-forms';
		$body['apiaction'] = $action;
		if (!is_null($args)) {
			$body['request'] = serialize($args);
		}

		global $wp_version;

		$request = array(
			'body' => $body,
			'user-agent' => 'WordPress/' . $wp_version . '; ' . get_bloginfo('url')
		);

		$response = wp_remote_post($this->supportApi, $request);

		if (is_wp_error($response)) {

			$response = new WP_Error('support_api_failed', __('An unexpected HTTP Error occurred during the Easy Contact Forms Support API request.</p> <p><a href="?" onclick="document.location.reload(); return false;">Try again</a>'), $response->get_error_message());

		} else {
			$response = $response['body'];
		}
		return $response;

	}

	/**
	 * 	getForm
	 *
	 *
	 * @return
	 * 
	 */
	function getForm() {

		return EasyContactFormsSupport::apiCall('get-support-form');

	}

	/**
	 * 	submitSupportForm
	 *
	 *
	 * @return
	 * 
	 */
	function submitSupportForm() {

		if (!current_user_can('manage_options')) {
			return FALSE;
		}
		$validation = EasyContactFormsSupport::validateForm();
		$showmessage = isset($_POST['support-form-data']) && empty($validation);
		if ($showmessage){
			$message = EasyContactFormsSupport::apiCall('support-form-submit', $_POST);
			echo $message;
			return TRUE;
		}
		return FALSE;

	}

	/**
	 * 	validateForm
	 *
	 *
	 * @return
	 * 
	 */
	function validateForm() {

		if (!isset($_POST['support-form-data'])){
			return '';
		}
		$messages = array();
		foreach ($_POST as $key=>$value) {
			$messages = EasyContactFormsSupport::validateRequired($key, $messages);
			$messages = EasyContactFormsSupport::validateMaxLen($key, $messages);
			$messages = EasyContactFormsSupport::validateEmail($key, $messages);
		}
		if (count($messages) == 0) {
			return '';
		}
		return "<div class='error'><p>" . implode('</p><p>', $messages) . "</p></div>";

	}

	/**
	 * 	validateRequired
	 *
	 * @param  $key
	 * 
	 * @param  $messages
	 * 
	 *
	 * @return
	 * 
	 */
	function validateRequired($key, $messages) {

		$index = strpos($key, 'required_');
		if ($index !== 0) {
			return $messages;
		}
			$r_key = substr($key, strlen('required_'));
		if (!isset($_POST[$r_key]) || empty($_POST[$r_key])){
			$messages[] = $_POST["message_{$r_key}"];
		}
		return $messages;

	}

	/**
	 * 	validateMaxLen
	 *
	 * @param  $key
	 * 
	 * @param  $messages
	 * 
	 *
	 * @return
	 * 
	 */
	function validateMaxLen($key, $messages) {

		$index = strpos($key, 'maxlength_');
		if ($index !== 0) {
			return $messages;
		}
		list($prefix, $value, $r_key) = explode('_', $key, 3);
		if (!isset($_POST[$r_key]) || empty($_POST[$r_key])){
			$messages[] = $_POST["message_{$r_key}"];
			return $messages;
		}
		if (strlen($_POST[$r_key]) > $value) {
			$messages[] = $_POST["message_{$r_key}"];
		}

		return $messages;
	}

	/**
	 * 	validateEmail
	 *
	 * @param  $key
	 * 
	 * @param  $messages
	 * 
	 *
	 * @return
	 * 
	 */
	function validateEmail($key, $messages) {

		$index = strpos($key, 'email_');
		if ($index !== 0) {
			return $messages;
		}
			$r_key = substr($key, strlen('email_'));
		if (!isset($_POST[$r_key]) || empty($_POST[$r_key])){
			$messages[] = $_POST["message_{$r_key}"];
			return $messages;
		}
		if (!preg_match('/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/', $_POST[$r_key])) {
			$messages[] = $_POST["message_{$r_key}"];
		}
		return $messages;

	}

}
