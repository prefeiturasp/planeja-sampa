<?php

/**
 * Initialise
 */
function oa_social_login_init ()
{
	//Localization
	if (function_exists ('load_plugin_textdomain'))
	{
		load_plugin_textdomain ('oa_social_login', false, OA_SOCIAL_LOGIN_BASE_PATH . '/languages/');
	}

	//Callback Handler
	oa_social_login_callback ();
}


/**
 * Add Site CSS
 **/
function oa_social_login_add_site_css ()
{
	if (!wp_style_is ('oa_social_login_site_css', 'registered'))
	{
		wp_register_style ('oa_social_login_site_css', OA_SOCIAL_LOGIN_PLUGIN_URL . "/assets/css/site.css");
	}

	if (did_action ('wp_print_styles'))
	{
		wp_print_styles ('oa_social_login_site_css');
	}
	else
	{
		wp_enqueue_style ('oa_social_login_site_css');
	}
}


/**
 * Check if the current connection is being made over https
 */
function oa_social_login_https_on ()
{
	if (!empty ($_SERVER ['SERVER_PORT']))
	{
		if (trim ($_SERVER ['SERVER_PORT']) == '443')
		{
			return true;
		}
	}

	if (!empty ($_SERVER ['HTTP_X_FORWARDED_PROTO']))
	{
		if (strtolower (trim ($_SERVER ['HTTP_X_FORWARDED_PROTO'])) == 'https')
		{
			return true;
		}
	}

	if (!empty ($_SERVER ['HTTPS']))
	{
		if (strtolower (trim ($_SERVER ['HTTPS'])) == 'on' OR trim ($_SERVER ['HTTPS']) == '1')
		{
			return true;
		}
	}

	return false;
}


/**
 * Send a notification to the administrator
 */
function oa_social_login_user_notification ($user_id, $user_identity_provider)
{
	//Get the user details
	$user = new WP_User ($user_id);
	$user_login = stripslashes ($user->user_login);

	//The blogname option is escaped with esc_html on the way into the database
	$blogname = wp_specialchars_decode (get_option ('blogname'), ENT_QUOTES);

	//Setup Message
	$message = sprintf (__ ('New user registration on your site %s:', 'oa_social_login'), $blogname) . "\r\n\r\n";
	$message .= sprintf (__ ('Username: %s', 'oa_social_login'), $user_login) . "\r\n\r\n";
	$message .= sprintf (__ ('Social Network: %s', 'oa_social_login'), $user_identity_provider) . "\r\n";

	//Send Message
	@wp_mail (get_option ('admin_email'), '[Social Login] ' . sprintf (__ ('[%s] New User Registration', 'oa_social_login'), $blogname), $message);
}


/**
 * Return the current url
 */
function oa_social_login_get_current_url ()
{
	//Get request URI - Should work on Apache + IIS
	$request_uri = ((!isset ($_SERVER ['REQUEST_URI'])) ? $_SERVER ['PHP_SELF'] : $_SERVER ['REQUEST_URI']);
	$request_port = ((!empty ($_SERVER ['SERVER_PORT']) AND $_SERVER ['SERVER_PORT'] <> '80') ? (":" . $_SERVER ['SERVER_PORT']) : '');
	$request_protocol = (oa_social_login_https_on () ? 'https' : 'http') . "://";
	$redirect_to = $request_protocol . $_SERVER ['SERVER_NAME'] . $request_port . $request_uri;

	//Remove the oa_social_login_source argument
	if (strpos ($redirect_to, 'oa_social_login_source') !== false)
	{
		//Break up url
		list($url_part, $query_part) = array_pad (explode ('?', $redirect_to), 2, '');
		parse_str ($query_part, $query_vars);

		//Remove oa_social_login_source argument
		if (is_array ($query_vars) AND isset ($query_vars ['oa_social_login_source']))
		{
			unset ($query_vars ['oa_social_login_source']);
		}

		//Build new url
		$redirect_to = $url_part . ((is_array ($query_vars) AND count ($query_vars) > 0) ? ('?' . http_build_query ($query_vars)) : '');
	}

	return $redirect_to;
}


/**
 * Escape an attribute
 */
function oa_social_login_esc_attr ($string)
{
	//Available since Wordpress 2.8
	if (function_exists ('esc_attr'))
	{
		return esc_attr ($string);
	}
	//Deprecated as of Wordpress 2.8
	elseif (function_exists ('attribute_escape'))
	{
		return attribute_escape ($string);
	}
	return htmlspecialchars ($string);
}


/**
 * Get the userid for a given token
 */
function oa_social_login_get_userid_by_token ($token)
{
	global $wpdb;

	// Sanitize token.
	$token = trim (strval ($token));

	// The token is required.
	if (strlen ($token) == 0)
	{
		return false;
	}

	// Read user for this token.
	$sql = "SELECT u.ID FROM " . $wpdb->usermeta . " AS um	INNER JOIN " . $wpdb->users . " AS u ON (um.user_id=u.ID)	WHERE um.meta_key = 'oa_social_login_user_token' AND um.meta_value=%s";
	return $wpdb->get_var ($wpdb->prepare ($sql, $token));
}


/**
 * Get the token for a given userid
 */
function oa_social_login_get_token_by_userid ($userid)
{
	global $wpdb;
	$sql = "SELECT um.meta_value FROM " . $wpdb->usermeta . " AS um	INNER JOIN " . $wpdb->users . " AS u ON (um.user_id=u.ID)	WHERE um.meta_key = 'oa_social_login_user_token' AND u.ID=%d";
	return $wpdb->get_var ($wpdb->prepare ($sql, $userid));
}


/**
 * Create a random email
 */
function oa_social_login_create_rand_email ()
{
	do
	{
		$email = md5 (uniqid (wp_rand (10000, 99000))) . "@example.com";
	}
	while (email_exists ($email));
	return $email;
}
