<?php


/**
 * Handle the callback
 */
function oa_social_login_callback ()
{
	//Callback Handler
	if (isset ($_POST) AND !empty ($_POST ['oa_action']) AND $_POST ['oa_action'] == 'social_login' AND !empty ($_POST ['connection_token']))
	{
		//OneAll Connection token
		$connection_token = trim ($_POST ['connection_token']);

		//Read settings
		$settings = get_option ('oa_social_login_settings');

		//API Settings
		$api_connection_handler = ((!empty ($settings ['api_connection_handler']) AND $settings ['api_connection_handler'] == 'fsockopen') ? 'fsockopen' : 'curl');
		$api_connection_use_https = ((!isset ($settings ['api_connection_use_https']) OR $settings ['api_connection_use_https'] == '1') ? true : false);
		$api_subdomain = (!empty ($settings ['api_subdomain']) ? trim ($settings ['api_subdomain']) : '');

		//We cannot make a connection without a subdomain
		if (!empty ($api_subdomain))
		{
			//See: http://docs.oneall.com/api/resources/connections/read-connection-details/
			$api_resource_url = ($api_connection_use_https ? 'https' : 'http') . '://' . $api_subdomain . '.api.oneall.com/connections/' . $connection_token . '.json';

			//API Credentials
			$api_credentials = array (
				'api_key' => (!empty ($settings ['api_key']) ? $settings ['api_key'] : ''),
				'api_secret' => (!empty ($settings ['api_secret']) ? $settings ['api_secret'] : '')
			);

			//Get connection details
			$result = oa_social_login_do_api_request ($api_connection_handler, $api_resource_url, $api_credentials);
			if (is_object ($result) AND property_exists ($result, 'http_code') AND $result->http_code == 200 AND property_exists ($result, 'http_data'))
			{
				//Decode result
				$decoded_result = @json_decode ($result->http_data);
				if (is_object ($decoded_result) AND isset ($decoded_result->response->result->data->user))
				{
					//User data
					$user_data = $decoded_result->response->result->data->user;

					//Social network profile data
					$identity = $user_data->identity;

					//Unique user token provided by OneAll
					$user_token = $user_data->user_token;

					//Identity Provider
					$user_identity_provider = $identity->source->name;

					//Thumbnail
					$user_thumbnail = (!empty ($identity->thumbnailUrl) ? trim ($identity->thumbnailUrl) : '');

					//Picture
					$user_picture = (!empty ($identity->pictureUrl) ? trim ($identity->pictureUrl) : '');

					//Firstname
					$user_first_name = (!empty ($identity->name->givenName) ? $identity->name->givenName : '');

					//Lastname
					$user_last_name = (!empty ($identity->name->familyName) ? $identity->name->familyName : '');

					//Fullname
					if (!empty ($identity->name->formatted))
					{
						$user_full_name = $identity->name->formatted;
					}
					elseif (!empty ($identity->name->displayName))
					{
						$user_full_name = $identity->name->displayName;
					}
					else
					{
						$user_full_name = trim ($user_first_name . ' ' . $user_last_name);
					}

					// Email Address.
					$user_email = '';
					if (property_exists ($identity, 'emails') AND is_array ($identity->emails))
					{
						$user_email_is_verified = false;
						while ($user_email_is_verified !== true AND (list(, $email) = each ($identity->emails)))
						{
							$user_email = $email->value;
							$user_email_is_verified = ($email->is_verified == '1');
						}
					}

					//User Website
					if (!empty ($identity->profileUrl))
					{
						$user_website = $identity->profileUrl;
					}
					elseif (!empty ($identity->urls [0]->value))
					{
						$user_website = $identity->urls [0]->value;
					}
					else
					{
						$user_website = '';
					}

					//Preferred Username
					if (!empty ($identity->preferredUsername))
					{
						$user_login = $identity->preferredUsername;
					}
					elseif (!empty ($identity->displayName))
					{
						$user_login = $identity->displayName;
					}
					else
					{
						$user_login = $user_full_name;
					}

					//New user created?
					$new_registration = false;

					//Sanitize Login
					$user_login = str_replace ('.', '-', $user_login);
					$user_login = sanitize_user ($user_login, true);

					// Get user by token
					$user_id = oa_social_login_get_userid_by_token ($user_token);

					//Try to link to existing account
					if (!is_numeric ($user_id))
					{
						//This is a new user
						$new_registration = true;

						//Linked enabled?
						if (!isset ($settings ['plugin_link_verified_accounts']) OR $settings ['plugin_link_verified_accounts'] == '1')
						{
							//Only if email is verified
							if (!empty ($user_email) AND $user_email_is_verified === true)
							{
								//Read existing user
								if (($user_id_tmp = email_exists ($user_email)) !== false)
								{
									$user_data = get_userdata ($user_id_tmp);
									if ($user_data !== false)
									{
										$user_id = $user_data->ID;
										$user_login = $user_data->user_login;

										delete_metadata ('user', null, 'oa_social_login_user_token', $user_token, true);
										update_user_meta ($user_id, 'oa_social_login_user_token', $user_token);
										update_user_meta ($user_id, 'oa_social_login_identity_provider', $user_identity_provider);
										wp_cache_delete ($user_id, 'users');
										wp_cache_delete ($user_login, 'userlogins');
									}
								}
							}
						}
					}


					//New User
					if (!is_numeric ($user_id))
					{
						//Username is mandatory
						if (!isset ($user_login) OR strlen (trim ($user_login)) == 0)
						{
							$user_login = $user_identity_provider . 'User';
						}

						//Username must be unique
						if (username_exists ($user_login))
						{
							$i = 1;
							$user_login_tmp = $user_login;
							do
							{
								$user_login_tmp = $user_login . ($i++);
							}
							while (username_exists ($user_login_tmp));
							$user_login = $user_login_tmp;
						}

						//Email Restriction
						$user_email = apply_filters ('oa_social_login_filter_new_user_email', $user_email);
						if ($user_email === 'disallowed')
						{
							trigger_error (__ ('This Social Network account may not be used to register', 'oa_social_login'), E_USER_ERROR);
						}

						//Email must be unique
						$placeholder_email_used = false;
						if (!isset ($user_email) OR !is_email ($user_email) OR email_exists ($user_email))
						{
							$user_email = oa_social_login_create_rand_email ();
							$placeholder_email_used = true;
						}

						//Generate a new password
						$user_password = wp_generate_password ();

						//Build user data
						$user_data = array (
							'user_login' => $user_login,
							'display_name' => (!empty ($user_full_name) ? $user_full_name : $user_login),
							'user_email' => $user_email,
							'first_name' => $user_first_name,
							'last_name' => $user_last_name,
							'user_url' => $user_website,
							'user_pass' => $user_password
						);

						// Create a new user
						$user_id = wp_insert_user ($user_data);
						if (is_numeric ($user_id))
						{
							delete_metadata ('user', null, 'oa_social_login_user_token', $user_token, true);
							update_user_meta ($user_id, 'oa_social_login_user_token', $user_token);
							update_user_meta ($user_id, 'oa_social_login_identity_provider', $user_identity_provider);

							//Email is required
							if (!empty ($settings ['plugin_require_email']))
							{
								//We don't have the real email
								if ($placeholder_email_used)
								{
									update_user_meta ($user_id, 'oa_social_login_request_email', 1);
								}
							}

							//Notify Administrator
							if (!empty ($settings ['plugin_notify_admin']))
							{
								oa_social_login_user_notification ($user_id, $user_identity_provider);
							}

							wp_cache_delete ($user_id, 'users');
							wp_cache_delete ($user_login, 'userlogins');
							do_action ('user_register', $user_id);
						}
					}

					//Sucess
					$user_data = get_userdata ($user_id);
					if ($user_data !== false)
					{
						//Update user thumbnail
						if (!empty ($user_thumbnail))
						{
							update_user_meta ($user_id, 'oa_social_login_user_thumbnail', $user_thumbnail);
						}

						//Update user picture
						if (!empty ($user_picture))
						{
							update_user_meta ($user_id, 'oa_social_login_user_picture', $user_picture);
						}

						//Setup Cookie
						wp_clear_auth_cookie ();
						wp_set_auth_cookie ($user_data->ID, true);
						do_action ('wp_login', $user_data->user_login, $user_data);

						//Where did the user come from?
						$oa_social_login_source = (!empty ($_REQUEST ['oa_social_login_source']) ? strtolower (trim ($_REQUEST ['oa_social_login_source'])) : '');

						//Use safe redirection?
						$redirect_to_safe = false;

						//Build the url to redirect the user to
						switch ($oa_social_login_source)
						{
							//*************** Registration ***************
							case 'registration':
							//Default redirection
								$redirect_to = admin_url ();

								//Redirection in URL
								if (!empty ($_GET ['redirect_to']))
								{
									$redirect_to = $_GET ['redirect_to'];
									$redirect_to_safe = true;
								}
								else
								{
									//Redirection customized
									if (isset ($settings ['plugin_registration_form_redirect']))
									{
										switch (strtolower ($settings ['plugin_registration_form_redirect']))
										{
											//Homepage
											case 'homepage':
												$redirect_to = home_url ();
												break;

											//Custom
											case 'custom':
												if (isset ($settings ['plugin_registration_form_redirect_custom_url']) AND strlen (trim ($settings ['plugin_registration_form_redirect_custom_url'])) > 0)
												{
													$redirect_to = trim ($settings ['plugin_registration_form_redirect_custom_url']);
												}
												break;

											//Default/Dashboard
											default:
											case 'dashboard':
												$redirect_to = admin_url ();
												break;
										}
									}
								}
								break;


							//*************** Login ***************
							case 'login':
							//Default redirection
								$redirect_to = home_url ();

								//Redirection in URL
								if (!empty ($_GET ['redirect_to']))
								{
									$redirect_to = $_GET ['redirect_to'];
									$redirect_to_safe = true;
								}
								else
								{
									//Redirection customized
									if (isset ($settings ['plugin_login_form_redirect']))
									{
										switch (strtolower ($settings ['plugin_login_form_redirect']))
										{
											//Dashboard
											case 'dashboard':
												$redirect_to = admin_url ();
												break;

											//Custom
											case 'custom':
												if (isset ($settings ['plugin_login_form_redirect_custom_url']) AND strlen (trim ($settings ['plugin_login_form_redirect_custom_url'])) > 0)
												{
													$redirect_to = trim ($settings ['plugin_login_form_redirect_custom_url']);
												}
												break;

											//Default/Homepage
											default:
											case 'homepage':
												$redirect_to = home_url ();
												break;
										}
									}
								}
								break;

							// *************** Comments ***************
							case 'comments':
								$redirect_to = oa_social_login_get_current_url () . '#comments';
								break;

							//*************** Widget/Shortcode ***************
							default:
							case 'widget':
							case 'shortcode':
							// This is a new user
								$opt_key = ($new_registration === true ? 'register' : 'login');

								//Default value
								$redirect_to = oa_social_login_get_current_url ();

								//Redirection customized
								if (isset ($settings ['plugin_shortcode_' . $opt_key . '_redirect']))
								{
									switch (strtolower ($settings ['plugin_shortcode_' . $opt_key . '_redirect']))
									{
										//Current
										case 'current':
											$redirect_to = oa_social_login_get_current_url ();
											break;

										//Homepage
										case 'homepage':
											$redirect_to = home_url ();
											break;

										//Dashboard
										case 'dashboard':
											$redirect_to = admin_url ();
											break;

										//Custom
										case 'custom':
											if (isset ($settings ['plugin_shortcode_' . $opt_key . '_redirect_url']) AND strlen (trim ($settings ['plugin_shortcode_' . $opt_key . '_redirect_url'])) > 0)
											{
												$redirect_to = trim ($settings ['plugin_shortcode_' . $opt_key . '_redirect_url']);
											}
											break;
									}
								}
								break;
						}

						//Check if url set
						if (!isset ($redirect_to) OR strlen (trim ($redirect_to)) == 0)
						{
							$redirect_to = home_url ();
						}

						//Filter for redirection urls
						if ($new_registration === true)
						{
							$redirect_to = apply_filters ('oa_social_login_filter_registration_redirect_url', $redirect_to, $user_data);
						}
						else
						{
							$redirect_to = apply_filters ('oa_social_login_filter_login_redirect_url', $redirect_to, $user_data);
						}

						//Use safe redirection
						if ($redirect_to_safe === true)
						{
							wp_safe_redirect ($redirect_to);
						}
						else
						{
							wp_redirect ($redirect_to);
						}
						exit ();
					}
				}

			}
		}
	}
}


/**
 * Send an API request by using the given handler
 */
function oa_social_login_do_api_request ($handler, $url, $options = array (), $timeout = 25)
{
	//FSOCKOPEN
	if ($handler == 'fsockopen')
	{
		return oa_social_login_fsockopen_request ($url, $options, $timeout);
	}
	//CURL
	else
	{
		return oa_social_login_curl_request ($url, $options, $timeout);
	}
}


/**
 * Check if fsockopen can be used
 */
function oa_social_login_check_fsockopen ($secure = true)
{
	$result = oa_social_login_fsockopen_request (($secure ? 'https' : 'http') . '://www.oneall.com/ping.html');
	if (is_object ($result) AND property_exists ($result, 'http_code') AND $result->http_code == 200)
	{
		if (property_exists ($result, 'http_data'))
		{
			if (strtolower ($result->http_data) == 'ok')
			{
				return true;
			}
		}
	}
	return false;
}


/**
 * Checks if CURL can be used
 */
function oa_social_login_check_curl ($secure = true)
{
	if (in_array ('curl', get_loaded_extensions ()) AND function_exists ('curl_init'))
	{
		$result = oa_social_login_curl_request (($secure ? 'https' : 'http') . '://www.oneall.com/ping.html');
		if (is_object ($result) AND property_exists ($result, 'http_code') AND $result->http_code == 200)
		{
			if (property_exists ($result, 'http_data'))
			{
				if (strtolower ($result->http_data) == 'ok')
				{
					return true;
				}
			}
		}
	}
	return false;
}


/**
 * Sends a CURL request.
 */
function oa_social_login_curl_request ($url, $options = array (), $timeout = 15)
{
	//Store the result
	$result = new stdClass ();

	//Send request
	$curl = curl_init ();
	curl_setopt ($curl, CURLOPT_URL, $url);
	curl_setopt ($curl, CURLOPT_HEADER, 0);
	curl_setopt ($curl, CURLOPT_TIMEOUT, $timeout);
	curl_setopt ($curl, CURLOPT_VERBOSE, 0);
	curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt ($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt ($curl, CURLOPT_USERAGENT, 'SocialLogin ' . OA_SOCIAL_LOGIN_VERSION . 'WP (+http://www.oneall.com/)');

	// BASIC AUTH?
	if (isset ($options ['api_key']) AND isset ($options ['api_secret']))
	{
		curl_setopt ($curl, CURLOPT_USERPWD, $options ['api_key'] . ":" . $options ['api_secret']);
	}

	//Make request
	if (($http_data = curl_exec ($curl)) !== false)
	{
		$result->http_code = curl_getinfo ($curl, CURLINFO_HTTP_CODE);
		$result->http_data = $http_data;
		$result->http_error = null;
	}
	else
	{
		$result->http_code = -1;
		$result->http_data = null;
		$result->http_error = curl_error ($curl);
	}

	//Done
	return $result;
}


/**
 * Sends an fsockopen request.
 */
function oa_social_login_fsockopen_request ($url, $options = array (), $timeout = 15)
{
	//Store the result
	$result = new stdClass ();

	//Make sure that this is a valid URL
	if (($uri = parse_url ($url)) == false)
	{
		$result->http_code = -1;
		$result->http_data = null;
		$result->http_error = 'invalid_uri';
		return $result;
	}

	//Make sure that we can handle the scheme
	switch ($uri ['scheme'])
	{
		case 'http':
			$port = (isset ($uri ['port']) ? $uri ['port'] : 80);
			$host = ($uri ['host'] . ($port != 80 ? ':' . $port : ''));
			$fp = @fsockopen ($uri ['host'], $port, $errno, $errstr, $timeout);
			break;

		case 'https':
			$port = (isset ($uri ['port']) ? $uri ['port'] : 443);
			$host = ($uri ['host'] . ($port != 443 ? ':' . $port : ''));
			$fp = @fsockopen ('ssl://' . $uri ['host'], $port, $errno, $errstr, $timeout);
			break;

		default:
			$result->http_code = -1;
			$result->http_data = null;
			$result->http_error = 'invalid_schema';
			return $result;
			break;
	}

	//Make sure that the socket has been opened properly
	if (!$fp)
	{
		$result->http_code = -$errno;
		$result->http_data = null;
		$result->http_error = trim ($errstr);
		return $result;
	}

	//Construct the path to act on
	$path = (isset ($uri ['path']) ? $uri ['path'] : '/');
	if (isset ($uri ['query']))
	{
		$path .= '?' . $uri ['query'];
	}

	//Create HTTP request
	$defaults = array (
		'Host' => "Host: $host",
		'User-Agent' => 'User-Agent: SocialLogin ' . OA_SOCIAL_LOGIN_VERSION . 'WP (+http://www.oneall.com/)'
	);

	//Enable basic authentication
	if (isset ($options ['api_key']) AND isset ($options ['api_secret']))
	{
		$defaults ['Authorization'] = 'Authorization: Basic ' . base64_encode ($options ['api_key'] . ":" . $options ['api_secret']);
	}

	//Build and send request
	$request = 'GET ' . $path . " HTTP/1.0\r\n";
	$request .= implode ("\r\n", $defaults);
	$request .= "\r\n\r\n";
	fwrite ($fp, $request);

	//Fetch response
	$response = '';
	while (!feof ($fp))
	{
		$response .= fread ($fp, 1024);
	}

	//Close connection
	fclose ($fp);

	//Parse response
	list($response_header, $response_body) = explode ("\r\n\r\n", $response, 2);

	//Parse header
	$response_header = preg_split ("/\r\n|\n|\r/", $response_header);
	list($header_protocol, $header_code, $header_status_message) = explode (' ', trim (array_shift ($response_header)), 3);

	//Build result
	$result->http_code = $header_code;
	$result->http_data = $response_body;

	//Done
	return $result;
}

