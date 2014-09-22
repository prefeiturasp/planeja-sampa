<?php
/**
 * @file
 * Install functions for the Easy Contact Forms plugin.
 */

function easycontactforms_install() {
	global $wpdb;
	$collate = '';
	if (!empty($wpdb->charset))
		$collate = 'DEFAULT CHARACTER SET '. $wpdb->charset;
	if (!empty($wpdb->collate))
		$collate .= ' COLLATE ' . $wpdb->collate;

	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	require_once dirName(__FILE__) . DIRECTORY_SEPARATOR . 'easy-contact-forms-database.php';
				
	$sqls = array();
	$sqls[] = "CREATE TABLE #wp__easycontactforms_applicationsettings (
				id int(11) NOT NULL auto_increment,
				Description varchar(200) NOT NULL DEFAULT '',
				TinyMCEConfig text,
				UseTinyMCE tinyint(1),
				ApplicationWidth int(10),
				ApplicationWidth2 int(10),
				DefaultStyle varchar(50),
				DefaultStyle2 varchar(50),
				SecretWord varchar(50),
				NotLoggenInText text,
				FileFolder varchar(900),
				SendFrom varchar(100),
				FixJSLoading tinyint(1),
				FormCompletionMinTime int(10),
				FormCompletionMaxTime int(10),
				FixStatus0 tinyint(1),
				ProductVersion varchar(25),
				PhoneRegEx varchar(100),
				InitTime int(11),
				ShowPoweredBy tinyint(1),
				DateFormat varchar(500),
				DateTimeFormat varchar(500),
				FixStatus02 tinyint(1),
				w3cCompliant tinyint(1),
				w3cStyle varchar(50),
				FixJSLoading2 tinyint(1),
				AllowMarkupInEntries tinyint(1),
				PRIMARY KEY  (id),
				KEY Description (Description)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_contacttypes (
				id int(11) NOT NULL auto_increment,
				Description varchar(200) NOT NULL DEFAULT '',
				Notes text,
				PRIMARY KEY  (id),
				KEY Description (Description)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_customformentryfiles (
				id int(11) NOT NULL auto_increment,
				Description varchar(200) NOT NULL DEFAULT '',
				Date int(11),
				CustomFormsEntries int(11) NOT NULL DEFAULT 0,
				PRIMARY KEY  (id),
				KEY Description (Description),
				KEY CustomFormsEntries (CustomFormsEntries)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_customformentrystatistics (
				id int(11) NOT NULL auto_increment,
				PageName varchar(300),
				TotalEntries int(10),
				IncludeIntoReporting tinyint(1),
				CustomForms int(11) NOT NULL DEFAULT 0,
				Impressions int(10),
				PRIMARY KEY  (id),
				KEY CustomForms (CustomForms)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_customformfields (
				id int(11) NOT NULL auto_increment,
				Description varchar(200) NOT NULL DEFAULT '',
				Type int(11) NOT NULL DEFAULT 0,
				Settings text,
				Template text,
				ListPosition int(10) NOT NULL DEFAULT 0,
				CustomForms int(11) NOT NULL DEFAULT 0,
				FieldSet int(10),
				PRIMARY KEY  (id),
				KEY FieldSet (FieldSet),
				KEY Description (Description),
				KEY CustomForms (CustomForms),
				KEY Type (Type),
				KEY ListPosition (ListPosition)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_customformfieldtypes (
				id int(11) NOT NULL auto_increment,
				Description varchar(200) NOT NULL DEFAULT '',
				CssClass varchar(100),
				Settings text,
				Signature text,
				ListPosition int(10) NOT NULL DEFAULT 0,
				ValueField tinyint(1),
				HelpLink text,
				PRIMARY KEY  (id),
				KEY Description (Description),
				KEY ListPosition (ListPosition)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_customforms (
				id int(11) NOT NULL auto_increment,
				Description varchar(200) NOT NULL DEFAULT '',
				NotificationSubject varchar(200),
				SendFrom varchar(200),
				SendConfirmation tinyint(1),
				ConfirmationSubject varchar(200),
				ConfirmationText text,
				Redirect tinyint(1),
				RedirectURL text,
				ShortCode varchar(300),
				Template tinyint(1),
				ObjectOwner int(11) NOT NULL DEFAULT 0,
				SubmissionSuccessText text,
				StyleSheet text,
				HTML mediumtext,
				SendFromAddress varchar(200),
				ShowSubmissionSuccess tinyint(1),
				SuccessMessageClass varchar(200),
				FailureMessageClass varchar(200),
				Width int(10),
				WidthUnit varchar(5),
				LineHeight int(10),
				LineHeightUnit varchar(5),
				FormClass varchar(200),
				FormStyle text,
				Style varchar(50),
				ConfirmationStyleSheet text,
				TotalEntries int(10),
				TotalProcessedEntries int(10),
				Impressions int(10),
				NotificationText text,
				IncludeVisitorsAddressInReplyTo tinyint(1),
				ReplyToNameTemplate varchar(200),
				ConfirmationReplyToName varchar(200),
				ConfirmationReplyToAddress varchar(200),
				NotificationStyleSheet text,
				SendConfirmationAsText tinyint(1),
				SendNotificationAsText tinyint(1),
				FadingDelay int(10),
				MessageDelay int(10),
				IncludeIntoReporting tinyint(1),
				PRIMARY KEY  (id),
				KEY ObjectOwner (ObjectOwner),
				KEY Description (Description)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_customforms_mailinglists (
				id int(11) NOT NULL auto_increment,
				CustomForms int(11) NOT NULL DEFAULT 0,
				Contacts int(11) NOT NULL DEFAULT 0,
				PRIMARY KEY  (id),
				KEY CustomForms (CustomForms),
				KEY Contacts (Contacts)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_customformsentries (
				id int(11) NOT NULL auto_increment,
				Date int(11),
				Content mediumtext,
				Header text,
				Data text,
				CustomForms int(11) NOT NULL DEFAULT 0,
				Users int(11) NOT NULL DEFAULT 0,
				Description varchar(200) NOT NULL DEFAULT '',
				SiteUser int(11) NOT NULL DEFAULT 0,
				PageName varchar(300),
				PRIMARY KEY  (id),
				KEY Description (Description),
				KEY CustomForms (CustomForms),
				KEY Users (Users),
				KEY SiteUser (SiteUser)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_files (
				id int(11) NOT NULL auto_increment,
				Doctype varchar(80),
				Docfield varchar(80),
				Docid int(10),
				Name varchar(300),
				Type varchar(80),
				Size int(10),
				Protected tinyint(1),
				Webdir tinyint(1),
				Count int(11),
				Storagename varchar(300),
				ObjectOwner int(11),
				PRIMARY KEY  (id),
				KEY Docid (Docid),
				KEY typefieldid (Doctype,Docfield,Docid),
				KEY ObjectOwner (ObjectOwner)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_options (
				id int(11) NOT NULL auto_increment,
				Description varchar(200) NOT NULL DEFAULT '',
				OptionGroup varchar(20),
				Value text,
				PRIMARY KEY  (id),
				KEY OptionGroup (OptionGroup),
				KEY Description (Description)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_roles (
				id int(11) NOT NULL auto_increment,
				Description varchar(100) NOT NULL DEFAULT '',
				Admin tinyint(1),
				Employee tinyint(1),
				PRIMARY KEY  (id)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_users (
				id int(11) NOT NULL auto_increment,
				Description varchar(200) NOT NULL DEFAULT '',
				Name varchar(100),
				ContactType int(11) NOT NULL DEFAULT 0,
				Birthday int(11),
				Role int(11) NOT NULL DEFAULT 0,
				CMSId int(11),
				Notes text,
				email varchar(100),
				email2 varchar(100),
				Cell varchar(30),
				Phone1 varchar(30),
				Phone2 varchar(30),
				Phone3 varchar(30),
				SkypeId varchar(100),
				Website varchar(200),
				ContactField3 text,
				ContactField4 text,
				Country varchar(300),
				Address text,
				City varchar(300),
				State varchar(300),
				Zip varchar(20),
				Comment text,
				History text,
				Options text,
				PRIMARY KEY  (id),
				KEY ContactType (ContactType),
				KEY CMSId (CMSId),
				KEY Description (Description),
				KEY Role (Role),
				KEY descriptionname (Description,Name)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_acl (
				id int(11) NOT NULL auto_increment,
				objtype varchar(50) NOT NULL,
				method varchar(50) NOT NULL,
				name varchar(50) NOT NULL,
				role varchar(50) NOT NULL,
				PRIMARY KEY  (id)) $collate;";
				
	$sqls[] = "CREATE TABLE #wp__easycontactforms_sessions (
				id int(11) NOT NULL auto_increment,
				opentime timestamp DEFAULT CURRENT_TIMESTAMP,
				value text,
				sid char(32) NOT NULL,
				PRIMARY KEY  (id)) $collate;";
				
	foreach ($sqls as $sql){
		$sql = EasyContactFormsDB::wptn($sql);
		dbDelta($sql);
	}
}
function easycontactforms_install_data() {
	global $current_user, $wpdb;
	$userid = NULL;
	if (isset($current_user)) {;
		$userid = $current_user->ID;
	}
	$adminemail = get_option('admin_email');

	$rows = array(
		array(
			'objtype' => 'CustomForms',
			'method' => 'copy',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'DashBoardView',
			'method' => 'getFormPageStatistics',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ContactTypes',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFieldValues',
			'method' => 'viewDetailed',
			'name' => 'detailed',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormEntryFiles',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Orders',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ContactTypes',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'viewDetailed',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'getSettingsForm',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormsEntries',
			'method' => 'processEntry',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Options',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'ApplicationSettings',
			'method' => 'setOptionValue',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'OrderFiles',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'OrderFiles',
			'method' => 'viewDetailed',
			'name' => 'detailed',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'refreshForm',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormsEntries',
			'method' => 'processEntry',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Orders_Products',
			'method' => 'view',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'val',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'ApplicationSettings',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'updateOrder',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormsEntries',
			'method' => 'viewDetailed',
			'name' => 'detailedMain',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'DashBoardView',
			'method' => 'getFormStatistics',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'resetFormPageStatistics',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFields',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'deleteField',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ContactTypes',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'ApplicationSettings',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'viewDetailed',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Users',
			'method' => 'getEUserASList',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'OrderField1',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'ProductUnitTypes',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ContactField1',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Users',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'moveFieldSet',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Files',
			'method' => 'deletefile',
			'name' => 'main',
			'role' => 'Guest',
		),
		array(
			'objtype' => 'CustomFormEntryFiles',
			'method' => 'viewDetailed',
			'name' => 'detailedMain',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'AppTranslations',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Tasks',
			'method' => 'viewDetailed',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFieldTypes',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'ApplicationSettings',
			'method' => 'allowPBLink',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'resetFormPageStatistics',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Files',
			'method' => 'download',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Orders',
			'method' => 'viewDetailed',
			'name' => 'detailed',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Tasks',
			'method' => 'viewDetailed',
			'name' => 'detailed',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'updateFieldData',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'setFormPageStatisticsShowOnDashboard',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Roles',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ApplicationSettings',
			'method' => 'allowPBLink',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Files',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Products',
			'method' => 'viewDetailed',
			'name' => 'detailed',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'addCustomField',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFieldValues',
			'method' => 'viewDetailed',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms_MailingLists',
			'method' => 'view',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'val',
			'name' => 'main',
			'role' => 'Guest',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'viewDetailed',
			'name' => 'detailedMain',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'copyField',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'TaskStatuses',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'DashBoardView',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'RemoteSites',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Files',
			'method' => 'deletefile',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Users',
			'method' => 'getUserASList',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'add',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'getSettingsForm',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Options',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'OrderFiles',
			'method' => 'viewDetailed',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormEntryFiles',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'setFormPageStatisticsShowOnDashboard',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Options',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'updateOrder',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFieldTemplates',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'DashBoardView',
			'method' => 'getEntryStatistics',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Priorities',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ApplicationSettings',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Tasks',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'DashBoardView',
			'method' => 'getUserStatistics',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'copyField',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Users',
			'method' => 'getEUserASList',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Templates',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Users',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'ApplicationSettings',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'viewDetailed',
			'name' => 'detailedMain',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFieldTypes',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'val',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Files',
			'method' => 'download',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'resetStatistics',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Files',
			'method' => 'upload',
			'name' => 'main',
			'role' => 'Guest',
		),
		array(
			'objtype' => 'Users',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Files',
			'method' => 'deletefile',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Locales',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Orders',
			'method' => 'viewDetailed',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ContactFiles',
			'method' => 'viewDetailed',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormsEntries',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'preview',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'OrderStatuses',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ProductCategories',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFieldTypes',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Orders_MailingLists',
			'method' => 'view',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ContactFiles',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'add',
			'name' => 'main',
			'role' => 'Guest',
		),
		array(
			'objtype' => 'CustomFormEntryFiles',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFieldValues',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormsEntries',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms_MailingLists',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Users',
			'method' => 'getUserASList',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'DashBoardView',
			'method' => 'getEntryStatistics',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'DashBoardView',
			'method' => 'getFormStatistics',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'refreshForm',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormEntryFiles',
			'method' => 'viewDetailed',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ContactFiles',
			'method' => 'viewDetailed',
			'name' => 'detailed',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Users',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ApplicationSettings',
			'method' => 'setOptionValue',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'addCustomField',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'OrderField2',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ContactField2',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Tasks_MailingLists',
			'method' => 'view',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'copy',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'Files',
			'method' => 'download',
			'name' => 'main',
			'role' => 'Guest',
		),
		array(
			'objtype' => 'CustomFormEntryStatistics',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Options',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'DashBoardView',
			'method' => 'getFormPageStatistics',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'deleteField',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFields',
			'method' => 'viewDetailed',
			'name' => 'detailed',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomFormEntryFiles',
			'method' => 'new',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'TaskTypes',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Files',
			'method' => 'upload',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'add',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'moveFieldSet',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'ContactTypes',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Files',
			'method' => 'upload',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'CustomForms',
			'method' => 'preview',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFields',
			'method' => 'updateFieldData',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormFieldTypes',
			'method' => 'show',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'Products',
			'method' => 'show',
			'name' => 'main',
			'role' => 'Owner',
		),
		array(
			'objtype' => 'CustomFormsEntries',
			'method' => 'view',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
		array(
			'objtype' => 'DashBoardView',
			'method' => 'getUserStatistics',
			'name' => 'main',
			'role' => 'SuperAdmin',
		),
	);

	$table_name = EasyContactFormsDB::wptn('#wp__easycontactforms_acl');
	$wpdb->query('DELETE FROM ' . $table_name . ' WHERE 1;' );
	foreach ($rows as $row) {
		$wpdb->insert($table_name, $row);
	}


	$rows = array(
		array(
			'id' => 285,
			'Description' => 'Employee',
			'Name' => 'Employee',
			'ContactType' => 4,
			'Birthday' => 300492000,
			'Role' => 3,
			'Notes' => 'Ut auctor ultrices elementum. Donec quis velit quam, ac mattis turpis. Praesent venenatis auctor sagittis.<br /><br />Morbi pulvinar malesuada risus in tempor. Fusce eu sapien a sem aliquet pulvinar. Nullam elementum facilisis quam, sed sollicitudin tortor gravida et.',
			'email' => 'employee@championforms.example.com',
			'email2' => 'employee2@championforms.example.com',
			'Cell' => '+65 446-8025',
			'Phone1' => '+51 939-3961',
			'Phone2' => '+17 383-6744',
			'Phone3' => '+56 624-1157',
			'ContactField3' => 'Morbi pulvinar malesuada risus in tempor. Fusce eu sapien a sem aliquet pulvinar. Nullam elementum facilisis quam, sed sollicitudin tortor gravida et.',
			'ContactField4' => 'Etiam neque nunc, fermentum sit amet fermentum ut, ultrices vitae neque. Maecenas nibh enim, dictum a semper et, sagittis viverra purus.',
			'Country' => 'USA',
			'Address' => '239 Filbert Street',
			'City' => 'Ridley Park',
			'State' => 'PA',
			'Zip' => '19078',
		),
		array(
			'id' => 286,
			'Description' => 'SuperAdmin',
			'Name' => 'SuperAdmin',
			'ContactType' => 4,
			'Birthday' => 299800800,
			'Role' => 1,
			'CMSId' => $userid,
			'Notes' => 'Maecenas eget lectus ut odio mattis fringilla. Nunc sem leo, interdum id euismod sit amet, varius vel lorem.<br /><br />Ut auctor ultrices elementum. Donec quis velit quam, ac mattis turpis. Praesent venenatis auctor sagittis.',
			'email' => $adminemail,
			'Cell' => '+67 709-8655',
			'Phone1' => '+64 555-1383',
			'Phone2' => '+99 040-6159',
			'Phone3' => '+86 839-8655',
			'ContactField3' => 'Morbi quis magna urna, id viverra ipsum. Fusce nibh orci, interdum id pharetra ut, ultricies vel metus.',
			'ContactField4' => 'Nulla tincidunt justo nec diam molestie feugiat. Aenean et est non sapien ultrices posuere id a odio.',
			'Country' => 'USA',
			'Address' => '28 Victoria Court',
			'City' => 'Sanford',
			'State' => 'ME',
			'Zip' => '04073',
		),
		array(
			'id' => 287,
			'Description' => 'Pierce',
			'Name' => 'Courtney',
			'ContactType' => 4,
			'Birthday' => 173570400,
			'Role' => 4,
			'Notes' => 'Morbi quis magna urna, id viverra ipsum. Fusce nibh orci, interdum id pharetra ut, ultricies vel metus.<br /><br />Aliquam eu nisi vel lorem ultricies laoreet. Nulla eget mi ac leo porttitor luctus a nec purus. Phasellus in erat at nulla feugiat aliquam.',
			'email' => 'courtney@championforms.example.com',
			'email2' => 'courtney2@championforms.example.com',
			'Cell' => '+13 566-5699',
			'Phone1' => '+29 240-9540',
			'Phone2' => '+79 799-9207',
			'Phone3' => '+67 288-1822',
			'ContactField3' => 'Maecenas lacinia arcu nec nisl elementum nec cursus massa consequat.',
			'ContactField4' => 'Praesent vel quam nunc. Aliquam cursus blandit semper.',
			'Country' => 'USA',
			'Address' => '188 Seth Street',
			'City' => 'Fredonia',
			'State' => 'TX',
			'Zip' => '76842',
		),
		array(
			'id' => 288,
			'Description' => 'Hawkins',
			'Name' => 'Josefina',
			'ContactType' => 4,
			'Birthday' => 237420000,
			'Role' => 4,
			'Notes' => 'Maecenas egestas consectetur nisl quis convallis. Maecenas nisi sapien, molestie ac rutrum et, vehicula sed orci.<br /><br />Etiam neque nunc, fermentum sit amet fermentum ut, ultrices vitae neque. Maecenas nibh enim, dictum a semper et, sagittis viverra purus.',
			'email' => 'josefina@championforms.example.com',
			'email2' => 'josefina2@championforms.example.com',
			'Cell' => '+41 946-6091',
			'Phone1' => '+68 539-2340',
			'Phone2' => '+58 360-0962',
			'Phone3' => '+09 670-2242',
			'ContactField3' => 'Nullam a neque dolor. Pellentesque elementum, magna quis interdum volutpat, libero ipsum scelerisque turpis, porta pretium dolor lectus ac risus.',
			'ContactField4' => 'Morbi quis magna urna, id viverra ipsum. Fusce nibh orci, interdum id pharetra ut, ultricies vel metus.',
			'Country' => 'USA',
			'Address' => '20 Franklin Street',
			'City' => 'Montgomery',
			'State' => 'AL',
			'Zip' => '36107',
		),
		array(
			'id' => 289,
			'Description' => 'Maldonado',
			'Name' => 'Nicole',
			'ContactType' => 1,
			'Birthday' => 364168800,
			'Role' => 4,
			'Notes' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In sagittis tincidunt tortor, non bibendum risus lobortis ac.<br /><br />Praesent ut facilisis odio. Maecenas congue neque ut nisi placerat vitae suscipit mauris fermentum.',
			'email' => 'nicole@championforms.example.com',
			'email2' => 'nicole2@championforms.example.com',
			'Cell' => '+40 612-5881',
			'Phone1' => '+34 120-0730',
			'Phone2' => '+85 284-3971',
			'Phone3' => '+45 100-8004',
			'ContactField3' => 'Ut auctor ultrices elementum. Donec quis velit quam, ac mattis turpis. Praesent venenatis auctor sagittis.',
			'ContactField4' => 'Maecenas lacinia arcu nec nisl elementum nec cursus massa consequat.',
			'Country' => 'USA',
			'Address' => '53 Graystone Lakes',
			'City' => 'Unadilla',
			'State' => 'GA',
			'Zip' => '31091',
		),
		array(
			'id' => 290,
			'Description' => 'Vega',
			'Name' => 'Dan',
			'ContactType' => 1,
			'Birthday' => 112399200,
			'Role' => 4,
			'Notes' => 'Maecenas eget lectus ut odio mattis fringilla. Nunc sem leo, interdum id euismod sit amet, varius vel lorem.<br /><br />Phasellus in metus in magna vestibulum ultricies. In nec metus mauris, vitae semper justo.',
			'email' => 'dan@championforms.example.com',
			'email2' => 'dan2@championforms.example.com',
			'Cell' => '+55 667-1370',
			'Phone1' => '+41 365-4503',
			'Phone2' => '+92 160-0475',
			'Phone3' => '+04 365-5904',
			'ContactField3' => 'Nulla tincidunt justo nec diam molestie feugiat. Aenean et est non sapien ultrices posuere id a odio.',
			'ContactField4' => 'Nunc molestie hendrerit arcu, non dapibus nulla suscipit ac. Nam eget nulla sit amet ante mollis pharetra.',
			'Country' => 'USA',
			'Address' => '149 Seneca Drive',
			'City' => 'Silverton',
			'State' => 'OR',
			'Zip' => '97381',
		),
	);

	$table_name = EasyContactFormsDB::wptn('#wp__easycontactforms_users');
	$count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $table_name . ';' );
	if ($count == 0) {
		foreach ($rows as $row) {
			$wpdb->insert($table_name, $row);
		}
	}


	$rows = array(
		array(
			'id' => 622,
			'Description' => 'Section',
			'Type' => 2,
			'Settings' => '<?xml version="1.0"?>
<form>
  
  
  <LabelTagName>h3</LabelTagName>
  <Advanced/>
  <LabelCSSClass/>
  <LabelCSSStyle/>
  
  <Description/>
  
  
  <DescriptionCSSStyle/>
  
  <CSSClass/>
  
  
  <Width>230</Width>
  <WidthUnit>px</WidthUnit>
<DescriptionCSSClass><![CDATA[ufo-customfields-container-description]]></DescriptionCSSClass><DescriptionPosition><![CDATA[top-inside]]></DescriptionPosition><CSSStyle><![CDATA[]]></CSSStyle><SetStyle><![CDATA[off]]></SetStyle><SetSize><![CDATA[off]]></SetSize><ShowDescription><![CDATA[off]]></ShowDescription><ShowLabel><![CDATA[off]]></ShowLabel><AddCF>off</AddCF><Label><![CDATA[Section]]></Label></form>',
			'Template' => '<field>
    <Container containertag="div" addcf="off"><![CDATA[<div>
     ]]></Container>
</field>',
			'ListPosition' => 625,
			'CustomForms' => 2,
			'FieldSet' => 622,
		),
		array(
			'id' => 625,
			'Description' => 'Email',
			'Type' => 5,
			'Settings' => '<?xml version="1.0"?>
<form>
  
  <Label>Email</Label>
  
  <Advanced/>
  <LabelCSSClass/>
  <LabelCSSStyle/>
  <ShowDescription>off</ShowDescription>
  <Description/>
  <DescriptionPosition>bottom</DescriptionPosition>
  <DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass>
  <DescriptionCSSStyle/>
  <SetDefaultValue>off</SetDefaultValue>
  <DefaultValue>Your email</DefaultValue>
  <IsBlankValue>on</IsBlankValue>
  <DefaultValueCSSClass/>
  <Required>on</Required>
  <RequiredMessage>Please enter you email</RequiredMessage>
  
  <SetRequiredSuffix>on</SetRequiredSuffix>
  <RequiredSuffix>*</RequiredSuffix>
  <RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass>
  <RequiredSuffixCSSStyle/>
  
  <InvalidCSSClass/>
  <RequiredMessageCSSClass/>
  <RequiredMessageCSSStyle/>
  <Validate>on</Validate>
  
  <ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition>
  <ValidMessage/>
  <ValidMessagePosition>right</ValidMessagePosition>
  <ValidCSSClass/>
  <ValidCSSStyle/>
  <SetStyle>off</SetStyle>
  <CSSClass/>
  <CSSStyle/>
  <RowCSSClass/>
  <SetSize>off</SetSize>
  <Width>230</Width>
  <WidthUnit>px</WidthUnit>
  
  
  
<LinkToAppField><![CDATA[Users_email]]></LinkToAppField><SetValidMessage><![CDATA[on]]></SetValidMessage><AbsolutePosition><![CDATA[on]]></AbsolutePosition><SetContactOptions><![CDATA[on]]></SetContactOptions><LabelPosition><![CDATA[left-align-left]]></LabelPosition><RequiredMessagePosition><![CDATA[right]]></RequiredMessagePosition><RegistredUsersOptions><![CDATA[showfill]]></RegistredUsersOptions><ShowLabel><![CDATA[on]]></ShowLabel></form>',
			'Template' => '<field><ShowLabel position="left"><![CDATA[<label for=\'ufo-field-id-625\'  style=\'text-align:left\'>Email<span class=\'ufo-customfields-required-suffix\'>*</span></label>]]></ShowLabel><RequiredMessage position="right"><![CDATA[<div id=\'ufo-field-id-625-invalid\'  style=\'display:none\'></div>]]></RequiredMessage><ValidMessage position="right"><![CDATA[<div id=\'ufo-field-id-625-valid\'  style=\'display:none\'></div>]]></ValidMessage><Validation><![CDATA[<script type=\'text/javascript\'>ufoFormsConfig.validations.push({"events":{"blur":["required","email"]},"Required":true,"Validate":true,"showValid":true,"ValidMessageAbsolutePosition":true,"ValidMessagePosition":"right","RequiredMessage":"Please enter you email","AbsolutePosition":true,"RequiredMessagePosition":"right","id":"ufo-field-id-625","form":"ufo-form-id-2"});</script>]]></Validation><Input ><![CDATA[<input type=\'text\' id=\'ufo-field-id-625\' value=\'{id-625}\' name=\'id-625\' >]]></Input></field>',
			'ListPosition' => 648,
			'CustomForms' => 2,
			'FieldSet' => 622,
		),
		array(
			'id' => 627,
			'Description' => 'First name',
			'Type' => 4,
			'Settings' => '<?xml version="1.0"?>
<form>
  
  
  
  <Advanced/>
  <LabelCSSClass/>
  <LabelCSSStyle/>
  <ShowDescription>off</ShowDescription>
  <Description/>
  <DescriptionPosition>bottom</DescriptionPosition>
  <DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass>
  <DescriptionCSSStyle/>
  <SetDefaultValue>off</SetDefaultValue>
  <DefaultValue/>
  <IsBlankValue>on</IsBlankValue>
  <DefaultValueCSSClass/>
  
  
  
  <SetRequiredSuffix>on</SetRequiredSuffix>
  <RequiredSuffix>*</RequiredSuffix>
  <RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass>
  <RequiredSuffixCSSStyle/>
  
  <InvalidCSSClass/>
  <RequiredMessageCSSClass/>
  <RequiredMessageCSSStyle/>
  
  
  
  
  <ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition>
  <ValidMessage/>
  <ValidMessagePosition>right</ValidMessagePosition>
  <ValidCSSClass/>
  <ValidCSSStyle/>
  <SetStyle>off</SetStyle>
  <CSSClass/>
  <CSSStyle/>
  <RowCSSClass/>
  <SetSize>off</SetSize>
  <Width>230</Width>
  <WidthUnit>px</WidthUnit>
  
  
  
<Required><![CDATA[on]]></Required><Validate><![CDATA[on]]></Validate><LinkToAppField><![CDATA[Users_Name]]></LinkToAppField><Label><![CDATA[First name]]></Label><SetValidMessage><![CDATA[on]]></SetValidMessage><AbsolutePosition><![CDATA[on]]></AbsolutePosition><SetContactOptions><![CDATA[on]]></SetContactOptions><MinLength><![CDATA[2]]></MinLength><MaxLength><![CDATA[45]]></MaxLength><RequiredMessagePosition><![CDATA[right]]></RequiredMessagePosition><RequiredMessage><![CDATA[Your first name is required (from 2 to 45 characters)]]></RequiredMessage><LabelPosition><![CDATA[left-align-left]]></LabelPosition><RegistredUsersOptions><![CDATA[showfill]]></RegistredUsersOptions><ShowLabel><![CDATA[on]]></ShowLabel></form>',
			'Template' => '<field>
      <ShowLabel position="left"><![CDATA[
   <label for=\'ufo-field-id-627\'  style=\'text-align:left\'>
     First name         <span class=\'ufo-customfields-required-suffix\'>
           *         </span>
            </label>]]></ShowLabel>
          <RequiredMessage position="right"><![CDATA[<div id=\'ufo-field-id-627-invalid\'  style=\'display:none\'></div>]]></RequiredMessage>
        <ValidMessage position="right"><![CDATA[<div id=\'ufo-field-id-627-valid\'  style=\'display:none\'>
        </div>]]></ValidMessage>
        <Validation><![CDATA[<script type=\'text/javascript\'>ufoFormsConfig.validations.push({"events":{"blur":["required","minmax"]},"Required":true,"Validate":true,"showValid":true,"ValidMessageAbsolutePosition":true,"ValidMessagePosition":"right","RequiredMessage":"Your first name is required (from 2 to 45 characters)","AbsolutePosition":true,"RequiredMessagePosition":"right","min":"2","max":"45","id":"ufo-field-id-627","form":"ufo-form-id-2"});</script>]]></Validation>
    <Input ><![CDATA[<input type=\'text\' id=\'ufo-field-id-627\' value=\'{id-627}\' name=\'id-627\' >]]></Input>
</field>',
			'ListPosition' => 643,
			'CustomForms' => 2,
			'FieldSet' => 622,
		),
		array(
			'id' => 630,
			'Description' => 'Submit',
			'Type' => 6,
			'Settings' => '<?xml version="1.0"?>
<form>
  
  
  
  <Advanced/>
  <LabelCSSClass/>
  <LabelCSSStyle/>
  <SetStyle>off</SetStyle>
  <CSSClass/>
  <CSSStyle/>
  <RowCSSClass/>
  
  <Width>100</Width>
  <WidthUnit>px</WidthUnit>
<Label><![CDATA[Submit]]></Label><InputPosition><![CDATA[left]]></InputPosition><ShowLabel><![CDATA[on]]></ShowLabel><SetSize><![CDATA[off]]></SetSize></form>',
			'Template' => '<field>
    <Validation><![CDATA[<script type=\'text/javascript\'>var c = {};c.id = \'ufo-field-id-630\';c.form = \'ufo-form-id-2\';c.Label = \'Submit\';ufoFormsConfig.submits.push(c);</script>]]></Validation>
  <Input><![CDATA[<span id=\'ufo-field-id-630-span\'>
                 <noscript>
          <button type=\'submit\' id=\'ufo-field-id-630\' name=\'id-630\' >
       Submit     </button>
            </noscript>
        </span>]]></Input>
</field>',
			'ListPosition' => 675,
			'CustomForms' => 2,
			'FieldSet' => 622,
		),
		array(
			'id' => 647,
			'Description' => 'Last name',
			'Type' => 4,
			'Settings' => '<?xml version="1.0"?>
<form>
  
  
  <LabelPosition>left-align-left</LabelPosition>
  <Advanced/>
  <LabelCSSClass/>
  <LabelCSSStyle/>
  <ShowDescription>off</ShowDescription>
  <Description/>
  <DescriptionPosition>bottom</DescriptionPosition>
  <DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass>
  <DescriptionCSSStyle/>
  <SetDefaultValue>off</SetDefaultValue>
  <DefaultValue/>
  <IsBlankValue>on</IsBlankValue>
  <DefaultValueCSSClass/>
  
  
  
  <SetRequiredSuffix>on</SetRequiredSuffix>
  <RequiredSuffix>*</RequiredSuffix>
  <RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass>
  <RequiredSuffixCSSStyle/>
  
  <InvalidCSSClass/>
  <RequiredMessageCSSClass/>
  <RequiredMessageCSSStyle/>
  
  
  
  
  <ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition>
  <ValidMessage/>
  <ValidMessagePosition>right</ValidMessagePosition>
  <ValidCSSClass/>
  <ValidCSSStyle/>
  <SetStyle>off</SetStyle>
  <CSSClass/>
  <CSSStyle/>
  <RowCSSClass/>
  <SetSize>off</SetSize>
  <Width>230</Width>
  <WidthUnit>px</WidthUnit>
  
  
  
<Label><![CDATA[Last name]]></Label><Required><![CDATA[on]]></Required><Validate><![CDATA[on]]></Validate><SetValidMessage><![CDATA[on]]></SetValidMessage><AbsolutePosition><![CDATA[on]]></AbsolutePosition><LinkToAppField><![CDATA[Users_Description]]></LinkToAppField><SetContactOptions><![CDATA[on]]></SetContactOptions><MinLength><![CDATA[2]]></MinLength><MaxLength><![CDATA[45]]></MaxLength><RequiredMessage><![CDATA[Your last name is required (from 2 to 45 characters)]]></RequiredMessage><RequiredMessagePosition><![CDATA[right]]></RequiredMessagePosition><RegistredUsersOptions><![CDATA[showfill]]></RegistredUsersOptions><ShowLabel><![CDATA[on]]></ShowLabel></form>',
			'Template' => '<field>
      <ShowLabel position="left"><![CDATA[
   <label for=\'ufo-field-id-647\'  style=\'text-align:left\'>
     Last name         <span class=\'ufo-customfields-required-suffix\'>
           *         </span>
            </label>]]></ShowLabel>
          <RequiredMessage position="right"><![CDATA[<div id=\'ufo-field-id-647-invalid\'  style=\'display:none\'></div>]]></RequiredMessage>
        <ValidMessage position="right"><![CDATA[<div id=\'ufo-field-id-647-valid\'  style=\'display:none\'>
        </div>]]></ValidMessage>
        <Validation><![CDATA[<script type=\'text/javascript\'>ufoFormsConfig.validations.push({"events":{"blur":["required","minmax"]},"Required":true,"Validate":true,"showValid":true,"ValidMessageAbsolutePosition":true,"ValidMessagePosition":"right","RequiredMessage":"Your last name is required (from 2 to 45 characters)","AbsolutePosition":true,"RequiredMessagePosition":"right","min":"2","max":"45","id":"ufo-field-id-647","form":"ufo-form-id-2"});</script>]]></Validation>
    <Input ><![CDATA[<input type=\'text\' id=\'ufo-field-id-647\' value=\'{id-647}\' name=\'id-647\' >]]></Input>
</field>',
			'ListPosition' => 647,
			'CustomForms' => 2,
			'FieldSet' => 622,
		),
		array(
			'id' => 648,
			'Description' => 'Your request',
			'Type' => 10,
			'Settings' => '<?xml version="1.0"?>
<form>
  
  
  
  <Advanced/>
  <LabelCSSClass/>
  <LabelCSSStyle/>
  
  
  
  <DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass>
  <DescriptionCSSStyle/>
  <SetDefaultValue>off</SetDefaultValue>
  <DefaultValue/>
  <IsBlankValue>on</IsBlankValue>
  <DefaultValueCSSClass/>
  
  <RequiredMessage>This field is required</RequiredMessage>
  <RequiredMessagePosition>right</RequiredMessagePosition>
  <SetRequiredSuffix>on</SetRequiredSuffix>
  <RequiredSuffix>*</RequiredSuffix>
  <RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass>
  <RequiredSuffixCSSStyle/>
  <AbsolutePosition>on</AbsolutePosition>
  <InvalidCSSClass/>
  <RequiredMessageCSSClass/>
  <RequiredMessageCSSStyle/>
  <Validate>off</Validate>
  <MinLength/>
  <MaxLength/>
  <SetValidMessage>off</SetValidMessage>
  <ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition>
  <ValidMessage/>
  <ValidMessagePosition>right</ValidMessagePosition>
  <ValidCSSClass/>
  <ValidCSSStyle/>
  
  <CSSClass/>
  <CSSStyle/>
  <RowCSSClass/>
  
  
  <WidthUnit>px</WidthUnit>
  
  <HeightUnit>px</HeightUnit>
<Label><![CDATA[Your request]]></Label><Description><![CDATA[Please provide us with your request details]]></Description><DescriptionPosition><![CDATA[top]]></DescriptionPosition><Required><![CDATA[on]]></Required><SetSize><![CDATA[on]]></SetSize><LabelPosition><![CDATA[top-align-left]]></LabelPosition><ShowDescription><![CDATA[off]]></ShowDescription><Height><![CDATA[150]]></Height><Width><![CDATA[360]]></Width><ShowLabel><![CDATA[on]]></ShowLabel><SetContactOptions>off</SetContactOptions><SetStyle><![CDATA[off]]></SetStyle></form>',
			'Template' => '<field>
      <ShowLabel position="top"><![CDATA[
   <label for=\'ufo-field-id-648\'  style=\'text-align:left\'>
     Your request         <span class=\'ufo-customfields-required-suffix\'>
           *         </span>
            </label>]]></ShowLabel>
          <RequiredMessage position="right"><![CDATA[<div id=\'ufo-field-id-648-invalid\'  style=\'display:none\'></div>]]></RequiredMessage>
          <Validation><![CDATA[<script type=\'text/javascript\'>ufoFormsConfig.validations.push({"events":{"blur":["required"]},"Required":true,"RequiredMessage":"This field is required","AbsolutePosition":true,"RequiredMessagePosition":"right","id":"ufo-field-id-648","form":"ufo-form-id-2"});</script>]]></Validation>
    <Input  width="360px"><![CDATA[<textarea id=\'ufo-field-id-648\' name=\'id-648\'  style=\'height:150px;width:360px\'>{id-648}</textarea>]]></Input>
</field>',
			'ListPosition' => 660,
			'CustomForms' => 2,
			'FieldSet' => 622,
		),
		array(
			'id' => 659,
			'Description' => 'Last Name',
			'Type' => 14,
			'Settings' => '<?xml version="1.0"?>
<form><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetDefaultValue>off</SetDefaultValue><DefaultValue/><IsBlankValue>on</IsBlankValue><DefaultValueCSSClass/><Required>off</Required><RequiredMessage>This field is required</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><Validate>off</Validate><MinLength/><MaxLength/><SetValidMessage>off</SetValidMessage><ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition><ValidMessage/><ValidMessagePosition>right</ValidMessagePosition><ValidCSSClass/><ValidCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit><Label><![CDATA[Last Name]]></Label><ShowLabel><![CDATA[on]]></ShowLabel></form>',
			'Template' => '<field>
      <ShowLabel position="left"><![CDATA[
   <label for=\'ufo-field-id-659\'  style=\'text-align:left\'>
     Last Name   </label>]]></ShowLabel>
            <Input ><![CDATA[<input type=\'text\' id=\'ufo-field-id-659\' value=\'{id-659}\' name=\'id-659\' >]]></Input>
</field>',
			'ListPosition' => 639,
			'CustomForms' => 2,
			'FieldSet' => 622,
		),
		array(
			'id' => 675,
			'Description' => 'Please fill in the fields below',
			'Type' => 2,
			'Settings' => '<?xml version="1.0"?>
<form>
  
  
  <LabelTagName>h3</LabelTagName>
  <Advanced/>
  <LabelCSSClass/>
  <LabelCSSStyle/>
  <ShowDescription>off</ShowDescription>
  <Description/>
  <DescriptionPosition>top</DescriptionPosition>
  <DescriptionCSSClass>ufo-customfields-container-description</DescriptionCSSClass>
  <DescriptionCSSStyle/>
  <SetStyle>off</SetStyle>
  <CSSClass/>
  <CSSStyle/>
  <AddCF>off</AddCF>
  <SetSize>off</SetSize>
  <Width>230</Width>
  <WidthUnit>px</WidthUnit>
<ShowLabel><![CDATA[off]]></ShowLabel><Label><![CDATA[Please fill in the fields below]]></Label></form>',
			'Template' => '<field>
    <Container containertag="div" addcf="off"><![CDATA[<div>
     ]]></Container>
</field>',
			'ListPosition' => 624,
			'CustomForms' => 2,
			'FieldSet' => 675,
		),
	);

	$table_name = EasyContactFormsDB::wptn('#wp__easycontactforms_customformfields');
	$count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $table_name . ';' );
	if ($count == 0) {
		foreach ($rows as $row) {
			$wpdb->insert($table_name, $row);
		}
	}


	$rows = array(
		array(
			'id' => 2,
			'Description' => 'Contact form',
			'NotificationSubject' => 'New request received',
			'SendFrom' => 'Champion Forms',
			'SendConfirmation' => 0,
			'ConfirmationSubject' => 'We have received your request',
			'Redirect' => 0,
			'ShortCode' => '[easy_contact_forms fid=2]',
			'Template' => 0,
			'ObjectOwner' => 286,
			'SubmissionSuccessText' => 'Thank you for contacting us! We are glad to hear from you.',
			'HTML' => '<script type=\'text/javascript\'>if (typeof(ecfconfig) == \'undefined\'){var ecfconfig={};}ecfconfig[2]={};var ufobaseurl =  \'http://workhorse/wordpress-3.3/wp-admin/admin-ajax.php\';if (typeof(ufoFormsConfig) == \'undefined\') {var ufoFormsConfig = {};ufoFormsConfig.submits = [];ufoFormsConfig.resets = [];ufoFormsConfig.validations = [];}</script><link href=\'http://workhorse/wordpress-3.3/wp-content/plugins/champion-forms/forms/styles/easyform/css/std.css?ver=1.5.5\' rel=\'stylesheet\' type=\'text/css\'/><style type=\'text/css\'>.ufo-row-659{display:none;}</style><div class=\'ufo-form\' id=\'ufo-form-id-2\'><noscript><form method=\'post\'><input type=\'hidden\' name=\'cf-no-script\' value=\'1\'/></noscript><input type=\'hidden\' value=\'ufo-form-id-2\' name=\'hidden-2\' id=\'ufo-form-hidden-2\'/><input type=\'hidden\' value=\'{__pagename}\' name=\'ufo-form-pagename\' id=\'ufo-form-pagename\'/>{preview}<input type=\'hidden\' value=\'{ufosignature}\' name=\'ufo-sign\' id=\'ufo-sign\'/><div>
     </div><div>
     <div class=\'ufo-fieldtype-14 ufo-customform-row ufo-row-659\' style=\'margin-top:2px;{display-659}\'><div class=\'ufo-cell-659-2-row\' id=\'ufo-cell-659-2\'><span class=\'ufo-cell-left\' id=\'ufo-cell-659-2-left\'>
   <label for=\'ufo-field-id-659\'  style=\'text-align:left\'>
     Last Name   </label></span><span class=\'ufo-cell-center\' id=\'ufo-cell-659-2-center\'><input type=\'text\' id=\'ufo-field-id-659\' value=\'{id-659}\' name=\'id-659\' ></span></div></div><div class=\'ufo-fieldtype-4 ufo-customform-row ufo-row-627\' style=\'margin-top:2px;{display-627}\'><div class=\'ufo-cell-627-2-row\' id=\'ufo-cell-627-2\'><span class=\'ufo-cell-left\' id=\'ufo-cell-627-2-left\'>
   <label for=\'ufo-field-id-627\'  style=\'text-align:left\'>
     First name         <span class=\'ufo-customfields-required-suffix\'>
           *         </span>
            </label></span><span class=\'ufo-cell-center\' id=\'ufo-cell-627-2-center\'><script type=\'text/javascript\'>ufoFormsConfig.validations.push({"events":{"blur":["required","minmax"]},"Required":true,"Validate":true,"showValid":true,"ValidMessageAbsolutePosition":true,"ValidMessagePosition":"right","RequiredMessage":"Your first name is required (from 2 to 45 characters)","AbsolutePosition":true,"RequiredMessagePosition":"right","min":"2","max":"45","id":"ufo-field-id-627","form":"ufo-form-id-2"});</script><input type=\'text\' id=\'ufo-field-id-627\' value=\'{id-627}\' name=\'id-627\' ></span><span class=\'ufo-cell-right\' id=\'ufo-cell-627-2-right\'><div id=\'ufo-field-id-627-invalid\'  style=\'display:none\'></div><div id=\'ufo-field-id-627-valid\'  style=\'display:none\'>
        </div></span></div></div><div class=\'ufo-fieldtype-4 ufo-customform-row ufo-row-647\' style=\'margin-top:2px;{display-647}\'><div class=\'ufo-cell-647-2-row\' id=\'ufo-cell-647-2\'><span class=\'ufo-cell-left\' id=\'ufo-cell-647-2-left\'>
   <label for=\'ufo-field-id-647\'  style=\'text-align:left\'>
     Last name         <span class=\'ufo-customfields-required-suffix\'>
           *         </span>
            </label></span><span class=\'ufo-cell-center\' id=\'ufo-cell-647-2-center\'><script type=\'text/javascript\'>ufoFormsConfig.validations.push({"events":{"blur":["required","minmax"]},"Required":true,"Validate":true,"showValid":true,"ValidMessageAbsolutePosition":true,"ValidMessagePosition":"right","RequiredMessage":"Your last name is required (from 2 to 45 characters)","AbsolutePosition":true,"RequiredMessagePosition":"right","min":"2","max":"45","id":"ufo-field-id-647","form":"ufo-form-id-2"});</script><input type=\'text\' id=\'ufo-field-id-647\' value=\'{id-647}\' name=\'id-647\' ></span><span class=\'ufo-cell-right\' id=\'ufo-cell-647-2-right\'><div id=\'ufo-field-id-647-invalid\'  style=\'display:none\'></div><div id=\'ufo-field-id-647-valid\'  style=\'display:none\'>
        </div></span></div></div><div class=\'ufo-fieldtype-5 ufo-customform-row ufo-row-625\' style=\'margin-top:2px;{display-625}\'><div class=\'ufo-cell-625-2-row\' id=\'ufo-cell-625-2\'><span class=\'ufo-cell-left\' id=\'ufo-cell-625-2-left\'><label for=\'ufo-field-id-625\'  style=\'text-align:left\'>Email<span class=\'ufo-customfields-required-suffix\'>*</span></label></span><span class=\'ufo-cell-center\' id=\'ufo-cell-625-2-center\'><script type=\'text/javascript\'>ufoFormsConfig.validations.push({"events":{"blur":["required","email"]},"Required":true,"Validate":true,"showValid":true,"ValidMessageAbsolutePosition":true,"ValidMessagePosition":"right","RequiredMessage":"Please enter you email","AbsolutePosition":true,"RequiredMessagePosition":"right","id":"ufo-field-id-625","form":"ufo-form-id-2"});</script><input type=\'text\' id=\'ufo-field-id-625\' value=\'{id-625}\' name=\'id-625\' ></span><span class=\'ufo-cell-right\' id=\'ufo-cell-625-2-right\'><div id=\'ufo-field-id-625-invalid\'  style=\'display:none\'></div><div id=\'ufo-field-id-625-valid\'  style=\'display:none\'></div></span></div></div><div class=\'ufo-fieldtype-10 ufo-customform-row ufo-row-648\' style=\'margin-top:2px;{display-648}\'><div class=\'ufo-cell-648-1-row\' id=\'ufo-cell-648-1\'><span class=\'ufo-cell-center\' style=\'width:360px\' id=\'ufo-cell-648-1-center\'>
   <label for=\'ufo-field-id-648\'  style=\'text-align:left\'>
     Your request         <span class=\'ufo-customfields-required-suffix\'>
           *         </span>
            </label></span><span class=\'ufo-cell-right\' id=\'ufo-cell-648-1-right\'><p style=\'display:none\'></p></span></div><div class=\'ufo-cell-648-2-row\' id=\'ufo-cell-648-2\'><span class=\'ufo-cell-center\' style=\'width:360px\' id=\'ufo-cell-648-2-center\'><script type=\'text/javascript\'>ufoFormsConfig.validations.push({"events":{"blur":["required"]},"Required":true,"RequiredMessage":"This field is required","AbsolutePosition":true,"RequiredMessagePosition":"right","id":"ufo-field-id-648","form":"ufo-form-id-2"});</script><textarea id=\'ufo-field-id-648\' name=\'id-648\'  style=\'height:150px;width:360px\'>{id-648}</textarea></span><span class=\'ufo-cell-right\' id=\'ufo-cell-648-2-right\'><div id=\'ufo-field-id-648-invalid\'  style=\'display:none\'></div></span></div></div><div class=\'ufo-fieldtype-6 ufo-customform-row ufo-row-630\' style=\'margin-top:2px;{display-630}\'><div class=\'ufo-cell-630-2-row\' id=\'ufo-cell-630-2\'><span class=\'ufo-cell-center\' id=\'ufo-cell-630-2-center\'><script type=\'text/javascript\'>var c = {};c.id = \'ufo-field-id-630\';c.form = \'ufo-form-id-2\';c.Label = \'Submit\';ufoFormsConfig.submits.push(c);</script><span id=\'ufo-field-id-630-span\'>
                 <noscript>
          <button type=\'submit\' id=\'ufo-field-id-630\' name=\'id-630\' >
       Submit     </button>
            </noscript>
        </span></span></div></div></div><div id=\'ufo-form-id-2-message\'></div><noscript></form></noscript></div>',
			'ShowSubmissionSuccess' => 1,
			'WidthUnit' => 'px',
			'LineHeight' => 2,
			'LineHeightUnit' => 'px',
			'Style' => 'easyform',
			'Impressions' => 1,
			'IncludeVisitorsAddressInReplyTo' => 1,
			'SendConfirmationAsText' => 0,
			'SendNotificationAsText' => 0,
			'IncludeIntoReporting' => 1,
		),
	);

	$table_name = EasyContactFormsDB::wptn('#wp__easycontactforms_customforms');
	$count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $table_name . ';' );
	if ($count == 0) {
		foreach ($rows as $row) {
			$wpdb->insert($table_name, $row);
		}
	}


	$rows = array(
		array(
			'id' => 1,
			'Description' => '2012-01-25',
			'OptionGroup' => 'dateformats',
			'Value' => 'Y-m-d^%Y-%m-%d^\d{4}-\d{2}-\d{2}$^2012-01-25',
		),
		array(
			'id' => 2,
			'Description' => 'Y-m-d hh:mm',
			'OptionGroup' => 'datetimeformats',
			'Value' => 'Y-m-d H:i^%Y-%m-%d %H:%M^\d{4}-\d{1,2}-\d{1,2}\s\d{1,2}:\d{1,2}^Y-m-d hh:mm',
		),
		array(
			'id' => 3,
			'Description' => '01/25/2012',
			'OptionGroup' => 'dateformats',
			'Value' => 'm/d/Y^%m/%d/%Y^\d{2}\/\d{2}\/\d{4}$^01/25/2012',
		),
		array(
			'id' => 4,
			'Description' => 'd.m.Y hh:mm',
			'OptionGroup' => 'datetimeformats',
			'Value' => 'd.m.Y H:i^%d.%m.%Y %H:%M^\d{1,2}\.\d{1,2}\.\d{4}\s\d{1,2}:\d{1,2}^d.m.Y hh:mm',
		),
		array(
			'id' => 5,
			'Description' => '25/01/2012',
			'OptionGroup' => 'dateformats',
			'Value' => 'd/m/Y^%d/%m/%Y^\d{2}\/\d{2}\/\d{4}$^25/01/2012',
		),
		array(
			'id' => 6,
			'Description' => '25.01.2012',
			'OptionGroup' => 'dateformats',
			'Value' => 'd.m.Y^%d.%m.%Y^\d{2}\.\d{2}\.\d{4}$^25.01.2012',
		),
		array(
			'id' => 7,
			'Description' => '25-01-2012',
			'OptionGroup' => 'dateformats',
			'Value' => 'd-m-Y^%d-%m-%Y^\d{2}-\d{2}-\d{4}$^25-01-2012',
		),
	);

	$table_name = EasyContactFormsDB::wptn('#wp__easycontactforms_options');
	$existent = $wpdb->get_col( 'SELECT Description FROM ' . $table_name . ';' );
	foreach ($rows as $row) {
		$rid = $row['Description'];
		if (!in_array($rid, $existent)) {
			$wpdb->insert($table_name, $row);
		}
	}


	$rows = array(
		array(
			'id' => 1,
			'Description' => 'Client',
			'Notes' => 'Aliquam euismod tincidunt velit, in lobortis velit aliquam id. Morbi risus eros, fringilla et blandit at, semper sit amet magna.',
		),
		array(
			'id' => 4,
			'Description' => 'Employee',
			'Notes' => 'Nulla tincidunt justo nec diam molestie feugiat. Aenean et est non sapien ultrices posuere id a odio.',
		),
	);

	$table_name = EasyContactFormsDB::wptn('#wp__easycontactforms_contacttypes');
	$count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $table_name . ';' );
	if ($count == 0) {
		foreach ($rows as $row) {
			$wpdb->insert($table_name, $row);
		}
	}


	$rows = array(
		array(
			'id' => 1,
			'Description' => 'Fieldset',
			'CssClass' => 'ufo-customfield-fieldset',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Fieldset</Label><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>top</DescriptionPosition><DescriptionCSSClass>ufo-customfields-container-description</DescriptionCSSClass><DescriptionCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><AddCF>off</AddCF><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit></form>',
			'ListPosition' => 100,
			'ValueField' => 0,
		),
		array(
			'id' => 2,
			'Description' => 'Section',
			'CssClass' => 'ufo-customfield-section',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Section</Label><LabelTagName>h3</LabelTagName><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>top</DescriptionPosition><DescriptionCSSClass>ufo-customfields-container-description</DescriptionCSSClass><DescriptionCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><AddCF>off</AddCF><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit></form>',
			'ListPosition' => 200,
			'ValueField' => 0,
		),
		array(
			'id' => 3,
			'Description' => 'Select',
			'CssClass' => 'ufo-customfield-select',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Select</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetOptions>on</SetOptions><HasEmpty>off</HasEmpty><EmptyOption/><Options><option index="1">Option1</option><option index="2">Option2</option><option index="3">Option3</option></Options><Required>off</Required><RequiredMessage>This field is required</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit></form>',
			'ListPosition' => 800,
			'ValueField' => 1,
		),
		array(
			'id' => 4,
			'Description' => 'Text',
			'CssClass' => 'ufo-customfield-text',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Text</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetDefaultValue>off</SetDefaultValue><DefaultValue/><IsBlankValue>on</IsBlankValue><DefaultValueCSSClass/><Required>off</Required><RequiredMessage>This field is required</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><Validate>off</Validate><MinLength/><MaxLength/><SetValidMessage>off</SetValidMessage><ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition><ValidMessage/><ValidMessagePosition>right</ValidMessagePosition><ValidCSSClass/><ValidCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit><SetContactOptions>off</SetContactOptions><RegistredUsersOptions>none</RegistredUsersOptions><LinkToAppField/></form>',
			'ListPosition' => 300,
			'ValueField' => 1,
		),
		array(
			'id' => 5,
			'Description' => 'Email',
			'CssClass' => 'ufo-customfield-email',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Email</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetDefaultValue>off</SetDefaultValue><DefaultValue>Your email</DefaultValue><IsBlankValue>on</IsBlankValue><DefaultValueCSSClass/><Required>on</Required><RequiredMessage>Please enter your email</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><Validate>on</Validate><SetValidMessage>on</SetValidMessage><ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition><ValidMessage/><ValidMessagePosition>right</ValidMessagePosition><ValidCSSClass/><ValidCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit><SetContactOptions>off</SetContactOptions><RegistredUsersOptions>none</RegistredUsersOptions><LinkToAppField/></form>',
			'ListPosition' => 500,
			'ValueField' => 1,
		),
		array(
			'id' => 6,
			'Description' => 'Submit button',
			'CssClass' => 'ufo-customfield-submitbutton',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Submit</Label><InputPosition>left</InputPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><WindowScroll/><SetSize>off</SetSize><Width>100</Width><WidthUnit>px</WidthUnit></form>',
			'ListPosition' => 1300,
			'ValueField' => 0,
		),
		array(
			'id' => 9,
			'Description' => 'Checkbox',
			'CssClass' => 'ufo-customfield-checkbox',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Checkbox</Label><LabelPosition>right-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle>width:200px;</LabelCSSStyle><DisplayValueOn>on</DisplayValueOn><DisplayValueOff>off</DisplayValueOff><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><Required>off</Required><RequiredMessage>This field is required</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle>width:200px;</RequiredMessageCSSStyle><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>on</SetSize><Width>20</Width><WidthUnit>px</WidthUnit></form>',
			'ListPosition' => 700,
			'ValueField' => 1,
		),
		array(
			'id' => 10,
			'Description' => 'Text Area',
			'CssClass' => 'ufo-customfield-textarea',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Text Area</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetDefaultValue>off</SetDefaultValue><DefaultValue/><IsBlankValue>on</IsBlankValue><DefaultValueCSSClass/><Required>off</Required><RequiredMessage>This field is required</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><Validate>off</Validate><MinLength/><MaxLength/><SetValidMessage>off</SetValidMessage><ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition><ValidMessage/><ValidMessagePosition>right</ValidMessagePosition><ValidCSSClass/><ValidCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>on</SetSize><Width>230</Width><WidthUnit>px</WidthUnit><Height>100</Height><HeightUnit>px</HeightUnit></form>',
			'ListPosition' => 400,
			'ValueField' => 1,
		),
		array(
			'id' => 11,
			'Description' => 'Number',
			'CssClass' => 'ufo-customfield-number',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Number</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetDefaultValue>off</SetDefaultValue><DefaultValue/><IsBlankValue>on</IsBlankValue><DefaultValueCSSClass/><Required>on</Required><RequiredMessage>Please enter a valid number</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><Validate>on</Validate><SetValidMessage>on</SetValidMessage><ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition><ValidMessage/><ValidMessagePosition>right</ValidMessagePosition><ValidCSSClass/><ValidCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit></form>',
			'ListPosition' => 600,
			'ValueField' => 1,
		),
		array(
			'id' => 12,
			'Description' => 'Radio Group',
			'CssClass' => 'ufo-customfield-radiogroup',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Radio Group</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetOptions>on</SetOptions><Options><option index="1">Option1</option><option index="2">Option2</option><option index="3">Option3</option></Options><Required>off</Required><RequiredMessage>This field is required</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><SetStyle>on</SetStyle><CSSClass/><CSSStyle>float:left</CSSStyle><RowCSSClass/><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit></form>',
			'ListPosition' => 900,
			'ValueField' => 1,
		),
		array(
			'id' => 13,
			'Description' => 'reCaptcha',
			'CssClass' => 'ufo-customfield-recaptcha',
			'Settings' => '<form><ShowLabel>off</ShowLabel><Label>ReCaptcha</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><Required>off</Required><RequiredMessage>Please try again</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass>none</InvalidCSSClass><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><Validate>off</Validate><SetValidMessage>on</SetValidMessage><ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition><ValidMessage/><ValidMessagePosition>right</ValidMessagePosition><ValidCSSClass/><ValidCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>on</SetSize><Width>500</Width><WidthUnit>px</WidthUnit><SetReCaptchaOptions>off</SetReCaptchaOptions><ReCaptchaTheme>red</ReCaptchaTheme><ReCaptchaLanguage/><ReCaptchaPublicKey/><ReCaptchaPrivateKey/></form>',
			'ListPosition' => 1100,
			'ValueField' => 0,
		),
		array(
			'id' => 14,
			'Description' => 'Hidden',
			'CssClass' => 'ufo-customfield-hidden',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Last Name</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetDefaultValue>off</SetDefaultValue><DefaultValue/><IsBlankValue>on</IsBlankValue><DefaultValueCSSClass/><Required>off</Required><RequiredMessage>This field is required</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><Validate>off</Validate><MinLength/><MaxLength/><SetValidMessage>off</SetValidMessage><ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition><ValidMessage/><ValidMessagePosition>right</ValidMessagePosition><ValidCSSClass/><ValidCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit></form>',
			'ListPosition' => 1000,
			'ValueField' => 0,
		),
		array(
			'id' => 16,
			'Description' => 'Phone Number',
			'CssClass' => 'ufo-customfield-phonenumber',
			'Settings' => '<form><ShowLabel>on</ShowLabel><Label>Phone Number</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetDefaultValue>off</SetDefaultValue><DefaultValue/><IsBlankValue>on</IsBlankValue><DefaultValueCSSClass/><Required>on</Required><RequiredMessage>Please enter a valid phone number</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><Validate>on</Validate><SetValidMessage>on</SetValidMessage><ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition><ValidMessage/><ValidMessagePosition>right</ValidMessagePosition><ValidCSSClass/><ValidCSSStyle/><SetStyle>off</SetStyle><CSSClass/><CSSStyle/><RowCSSClass/><SetSize>off</SetSize><Width>230</Width><WidthUnit>px</WidthUnit></form>',
			'ListPosition' => 650,
			'ValueField' => 1,
		),
		array(
			'id' => 20,
			'Description' => 'Google Map',
			'CssClass' => 'ufo-customfield-googlemap',
			'Settings' => '<form><GoolgeMapSettings>on</GoolgeMapSettings><Latitude/><Longitude/><Zoom>16</Zoom><ShowLabel>on</ShowLabel><Label>Google Map</Label><LabelPosition>top-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>top</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><SetStyle>off</SetStyle><CSSClass>ufo-google-map</CSSClass><CSSStyle/><RowCSSClass/><SetSize>on</SetSize><Width>400</Width><WidthUnit>px</WidthUnit><Height>500</Height><HeightUnit>px</HeightUnit></form>',
			'ListPosition' => 950,
			'ValueField' => 0,
		),
		array(
			'id' => 22,
			'Description' => 'File Upload',
			'CssClass' => 'ufo-customfield-fileupload',
			'Settings' => '<form><FileSettings>on</FileSettings><ButtonText>Upload</ButtonText><UploadingText>Uploading...</UploadingText><OnlyAdminsCanDownload>on</OnlyAdminsCanDownload><AttachToConfirmation>off</AttachToConfirmation><AttachToNotification>off</AttachToNotification><ShowLabel>off</ShowLabel><Label>File Upload</Label><LabelPosition>left-align-left</LabelPosition><Advanced/><LabelCSSClass/><LabelCSSStyle/><ShowDescription>off</ShowDescription><Description/><DescriptionPosition>bottom</DescriptionPosition><DescriptionCSSClass>ufo-customfields-field-description</DescriptionCSSClass><DescriptionCSSStyle/><Required>off</Required><RequiredMessage>Please select a file</RequiredMessage><RequiredMessagePosition>right</RequiredMessagePosition><SetRequiredSuffix>on</SetRequiredSuffix><RequiredSuffix>*</RequiredSuffix><RequiredSuffixCSSClass>ufo-customfields-required-suffix</RequiredSuffixCSSClass><RequiredSuffixCSSStyle/><AbsolutePosition>on</AbsolutePosition><InvalidCSSClass/><RequiredMessageCSSClass/><RequiredMessageCSSStyle/><Validate>off</Validate><SetValidMessage>on</SetValidMessage><ValidMessageAbsolutePosition>on</ValidMessageAbsolutePosition><ValidMessage/><ValidMessagePosition>right</ValidMessagePosition><ValidCSSClass/><ValidCSSStyle/><SetStyle>on</SetStyle><CSSClass>ufo-frontendbutton blue</CSSClass><CSSStyle>width:130px;</CSSStyle><RowCSSClass/></form>',
			'ListPosition' => 925,
			'ValueField' => 1,
		),
	);

	$table_name = EasyContactFormsDB::wptn('#wp__easycontactforms_customformfieldtypes');
	$existent = $wpdb->get_col( 'SELECT id FROM ' . $table_name . ';' );
	foreach ($rows as $row) {
		$rid = $row['id'];
		if (in_array($rid, $existent)) {
			$wpdb->update($table_name, $row, array('id' => $rid));
		}
		else {
			$wpdb->insert($table_name, $row);
		}
	}


	$rows = array(
		array(
			'id' => 1,
			'Description' => 'AppSettings',
			'TinyMCEConfig' => '{theme_advanced_buttons4:"",mode:"exact",theme_advanced_statusbar_location:"",theme_advanced_toolbar_align:"left",theme_advanced_resizing:"true",plugins:"fullscreen",theme_advanced_toolbar_location:"top",theme_advanced_buttons1:"bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",theme_advanced_buttons2:"bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,cleanup,|,forecolor,backcolor,|,fullscreen",theme_advanced_buttons3:"",theme:"advanced", relative_urls : false, remove_script_host: false}',
			'UseTinyMCE' => 1,
			'ApplicationWidth' => 900,
			'ApplicationWidth2' => 900,
			'DefaultStyle' => 'std2',
			'DefaultStyle2' => 'std2',
			'NotLoggenInText' => 'Please log in.',
			'FileFolder' => 'files',
			'FixJSLoading' => 0,
			'FixStatus0' => 0,
			'ProductVersion' => '1.4.7',
			'InitTime' => 0,
			'ShowPoweredBy' => 0,
			'DateFormat' => 'Y-m-d^%Y-%m-%d^\d{4}-\d{2}-\d{2}$^2012-01-25',
			'DateTimeFormat' => 'Y-m-d H:i^%Y-%m-%d %H:%M^\d{4}-\d{1,2}-\d{1,2}\s\d{1,2}:\d{1,2}^Y-m-d hh:mm',
			'FixStatus02' => 0,
			'w3cCompliant' => 0,
			'w3cStyle' => 'easyform',
			'FixJSLoading2' => 0,
			'AllowMarkupInEntries' => 0,
		),
	);

	$table_name = EasyContactFormsDB::wptn('#wp__easycontactforms_applicationsettings');
	$count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $table_name . ';' );
	if ($count == 0) {
		foreach ($rows as $row) {
			$wpdb->insert($table_name, $row);
		}
	}


	$rows = array(
		array(
			'id' => 1,
			'Description' => 'SuperAdmin',
			'Admin' => 0,
			'Employee' => 0,
		),
		array(
			'id' => 2,
			'Description' => 'Owner',
			'Admin' => 0,
			'Employee' => 0,
		),
		array(
			'id' => 4,
			'Description' => 'Guest',
			'Admin' => 0,
			'Employee' => 0,
		),
	);

	$table_name = EasyContactFormsDB::wptn('#wp__easycontactforms_roles');
	$count = $wpdb->get_var( 'SELECT COUNT(*) FROM ' . $table_name . ';' );
	if ($count == 0) {
		foreach ($rows as $row) {
			$wpdb->insert($table_name, $row);
		}
	}

	require_once dirName(__FILE__) . DIRECTORY_SEPARATOR . 'easy-contact-forms-root.php';
	require_once dirName(__FILE__) . DIRECTORY_SEPARATOR . 'easy-contact-forms-applicationsettings.php';
	$as = EasyContactFormsApplicationSettings::getInstance();
	$as->set('ProductVersion', '1.4.7');
	$as->save();}


function easycontactforms_uninstall() {

	global $wpdb;
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_applicationsettings;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_contacttypes;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_customformentryfiles;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_customformentrystatistics;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_customformfields;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_customformfieldtypes;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_customforms;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_customforms_mailinglists;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_customformsentries;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_files;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_options;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_roles;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_users;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_acl;";
	$sqls[] = "DROP TABLE IF EXISTS #wp__easycontactforms_sessions;";

	require_once dirName(__FILE__) . DIRECTORY_SEPARATOR . 'easy-contact-forms-database.php';
	foreach ($sqls as $sql){
		$sql = EasyContactFormsDB::wptn($sql);
		$wpdb->query($sql);
	}
}
