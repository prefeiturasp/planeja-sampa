<?php

/**
 * @file
 *
 * 	EasyContactFormsCustomFormsEntries class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

require_once 'easy-contact-forms-baseclass.php';

/**
 * 	EasyContactFormsCustomFormsEntries
 *
 */
class EasyContactFormsCustomFormsEntries extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsCustomFormsEntries class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'CustomFormsEntries';

		$this->fieldmap = array(
				'id' => NULL,
				'Date' => 0,
				'Content' => '',
				'Header' => '',
				'Data' => '',
				'CustomForms' => 0,
				'Users' => 0,
				'Description' => '',
				'SiteUser' => 0,
				'PageName' => '',
			);

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a
	 * 	customformsentry record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		$query = "SELECT id FROM #wp__easycontactforms_customformentryfiles WHERE CustomFormsEntries='$id';";
		EasyContactFormsDB::cDelete($query, 'CustomFormEntryFiles');

		$stmts[] = "DELETE FROM #wp__easycontactforms_customformsentries WHERE id='$id';";

		return $stmts;

	}

	/**
	 * 	update. Overrides EasyContactFormsBase::update()
	 *
	 * 	updates an object with request data
	 *
	 * @param array $request
	 * 	request data
	 * @param int $id
	 * 	object id
	 */
	function update($request, $id) {

		$request = EasyContactFormsUtils::parseRequest($request, 'Date', 'date');
		$request = EasyContactFormsUtils::parseRequest($request, 'CustomForms', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'Users', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'SiteUser', 'int');

		parent::update($request, $id);

	}

	/**
	 * 	getEmptyObject. Overrides EasyContactFormsBase::getEmptyObject()
	 *
	 * 	creates and initializes a new CustomFormsEntry
	 *
	 * @param array $map
	 * 	request data
	 * @param array $fields
	 * 	a field array
	 *
	 * @return object
	 * 	the initialized instance
	 */
	function getEmptyObject($map, $fields = NULL) {

		$fields = (object) array();
		$fields->CustomForms = EasyContactFormsDB::getFirst('CustomForms');
		$fields->Users = EasyContactFormsDB::getFirst('Users');

		return parent::getEmptyObject($map, $fields);

	}

	/**
	 * 	processEntry
	 *
	 * 	Add button onclick handler
	 *
	 * @param array $map
	 * 	Request data
	 */
	function processEntry($map) {
		$currentuser = isset($map['easycontactusr']) ? $map['easycontactusr'] : NULL;
		if (is_null($currentuser)) {
			return;
		}
		$ownerid = $currentuser->id;

		$id = intval($map['oid']);
		if ($id == 0) {
			return;
		}

		$entry = EasyContactFormsClassLoader::getObject('CustomFormsEntries', TRUE, $id);
		$entry->process($ownerid);

		unset($map['oid']);
		unset($map['n']);
		$mn = isset($map['a']) ? 'viewDetailed' : 'view';
		$map['m'] =  $mn;
		EasyContactFormsRoot::processEvent($map);

	}

	/**
	 * 	process
	 *
	 * 	Applies the form entry data to db
	 *
	 * @param int $ownerid
	 * 	User who owns the results
	 */
	function process($ownerid = NULL) {

		$form = EasyContactFormsClassLoader::getObject('CustomForms', true, $this->get('CustomForms'));

		if (!class_exists('EasyContactFormsT')) {
			$l_locale = get_locale();
			$tag = strtolower(str_replace('_', '-', $l_locale));
			require_once 'easy-contact-forms-strings.php';
			if (!(@include_once 'easy-contact-forms-resources_' . $tag . '.php')) {
				require_once 'easy-contact-forms-resources_en-gb.php';
			}
		}
		if (is_null($ownerid)) {
			$ownerid = $form->get('ObjectOwner');
		}

		$content = $this->get('Content');
		$xml = simplexml_load_string($content);

		$userid = (string) $xml->attributes()->userid;
		$userid = empty($userid) ? NULL : intval($userid);

		if (is_null($userid)) {

			$users = EasyContactFormsClassLoader::getObject('Users');
			$user = $users->getEmptyObject(array());
			$user->set('ObjectOwner', $ownerid);

			$siteuserid = $this->get('SiteUser');
			$siteuserid = empty($siteuserid) ? NULL : $siteuserid;

			if (!is_null($siteuserid)) {

				$usrquery = "SELECT
							user_login AS username,
							user_email AS email,
							display_name AS name
						FROM
							#wp__users
						WHERE
							ID='$siteuserid'";

				$susr = EasyContactFormsDB::getObjects($usrquery);
				$susr = $susr[0];
				$susrname = !empty($susr->name) ? $susr->name : $susr->username;
				if (!empty($susrname)) {
					$user->set('Description', $susrname);
				}
				if (!empty($susr->email)) {
					$user->set('email', $susr->email);
				}
				$user->set('CMSId', $siteuserid);

			}
		}
		else {
			$user = EasyContactFormsClassLoader::getObject('Users', true, $userid);
			$user->set('Role', NULL);
		}

		$fields = EasyContactFormsClassLoader::getObject('CustomFormFields');

		foreach ($xml->children() as $xmlfld) {
			$value = (string) $xmlfld->h1;
			unset($xmlfld->h1);
			if (empty($value)) {
				continue;
			}
			$fldid = (string) $xmlfld->attributes()->id;
			$fldid = intval($fldid);

			$settings = $fields->getValue('Settings', $fldid);

			if (empty($settings)) {
				continue;
			}

			$settings = simplexml_load_string($settings);

			$link = (string) $settings->LinkToAppField;
			if (!empty($link)) {
				$link = explode('_', $link);
				if (count($link) > 1) {
					$user->set($link[1], $value);
				}
			}
		}

		$content = $xml->asXML();
		$user->set('History',	$content . '<br /><br />' . $user->get('History'));
		$user->save();

		$this->set('Date', EasyContactFormsUtils::getDate($this->get('Date'), false, true, true));
		$this->set('Users', $user->get('id'));
		$this->save();

		$form->set('TotalProcessedEntries', $form->get('TotalProcessedEntries') + 1);
		$form->save();

	}

	/**
	 * 	dispatch. Overrides EasyContactFormsBase::dispatch()
	 *
	 * 	invokes requested object methods
	 *
	 * @param array $dispmap
	 * 	request data
	 */
	function dispatch($dispmap) {

		$dispmap = parent::dispatch($dispmap);
		if ($dispmap == NULL) {
			return NULL;
		}

		$dispmethod = $dispmap["m"];
		switch ($dispmethod) {

			case 'processEntry':
				$this->processEntry($dispmap);
				return NULL;

			default : return $dispmap;
		}

	}

	/**
	 * 	getViews. Overrides EasyContactFormsBase::getViews()
	 *
	 * 	selects an object view to show on the client side
	 *
	 * @param array $vmap
	 * 	request data
	 */
	function getViews($vmap) {

		$viewname = parent::getViews($vmap);

		switch ($viewname) {

			case 'detailedmain':
				return $this->getDetailedMainView($vmap);
				break;

			default:return '';
		}

	}

	/**
	 * 	getASForm
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $formmap
	 * 	request data
	 */
	function getASForm($formmap) {

		$fields = array();
		$fields[] = 'id';
		$fields[] = 'Date';
		$fields[] = 'Content';
		$fields[] = 'Description';
		$fields[] = 'PageName';

		$obj = $this->formInit($formmap, $fields);

		require 'views/easy-contact-forms-customformsentriesasform.php';

	}

	/**
	 * 	getASList
	 *
	 * 	returns an array prepared to show in the ajax suggestion list
	 *
	 * @param array $map
	 * 	request data
	 */
	function getASList($map) {

		$query = "SELECT id, Description FROM #wp__easycontactforms_customformsentries";
		if (isset($map['oid'])) {
			$objid = intval($map['oid']);
			$query .= " WHERE id ='$objid'";
		}
		else {
			$query .= " WHERE Description LIKE :input";
		}
		return $this->getBasicASList($map, $query);

	}

	/**
	 * 	getMainForm
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $formmap
	 * 	request data
	 */
	function getMainForm($formmap) {

		$query = "SELECT
				CustomFormsEntries.id,
				CustomFormsEntries.Date,
				CustomFormsEntries.PageName,
				CustomFormsEntries.Content,
				CustomForms.Description AS CustomFormsDescription,
				CustomFormsEntries.CustomForms AS CustomForms
			FROM
				#wp__easycontactforms_customformsentries AS CustomFormsEntries
			LEFT JOIN
				#wp__easycontactforms_customforms AS CustomForms
					ON
						CustomFormsEntries.CustomForms=CustomForms.id
			WHERE
				CustomFormsEntries.id=:id";

		$obj = $this->formQueryInit($formmap, $query);

		?>
		<input type='hidden' class='ufostddata' id='t' value='<?php echo $obj->type;?>'>
		<input type='hidden' class='ufostddata' id='oid' value='<?php echo $obj->getId();?>'>
		<?php

		require_once 'views/easy-contact-forms-customformsentriesmainform.php';

	}

	/**
	 * 	getDetailedMainView
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $viewmap
	 * 	request data
	 */
	function getDetailedMainView($viewmap) {

		$spar = $this->getOrder($viewmap);

		$sortfields = array(
			'id',
			'Date',
			'CustomFormsDescription',
			'UsersDescription',
			'PageName',
			'SiteUser',
		);

		$orderby = EasyContactFormsDB::getOrderBy($sortfields, $spar, "CustomFormsEntries.id DESC");

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();

		$viewfilters = EasyContactFormsDB::getFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'CustomForms', '=', 'int');

		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'id', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'Date', 'date');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'Content');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'Users', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'SiteUser', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'PageName');
		EasyContactFormsRoot::mDelete('CustomFormsEntries', $viewmap);

		$query = "SELECT
				CustomFormsEntries.id,
				CustomFormsEntries.Date,
				CustomFormsEntries.PageName,
				CustomFormsEntries.SiteUser,
				CustomForms.Description AS CustomFormsDescription,
				CustomFormsEntries.CustomForms AS CustomForms,
				CONCAT(Users.Description, ' ', Users.Name) AS UsersDescription,
				CustomFormsEntries.Users AS Users
			FROM
				#wp__easycontactforms_customformsentries AS CustomFormsEntries
			LEFT JOIN
				#wp__easycontactforms_customforms AS CustomForms
					ON
						CustomFormsEntries.CustomForms=CustomForms.id
			LEFT JOIN
				#wp__easycontactforms_users AS Users
					ON
						CustomFormsEntries.Users=Users.id";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->Users = (object) array();
		$this->Users->view = $this;
		$this->Users->field = 'Users';
		$this->Users->filter = TRUE;
		$this->Users->config['m2'] = 'getUserASList';
		$this->Users->config['t'] = 'Users';
		$this->Users->asparams['listItemClass'] = 'ufo-user-list-item';
		$this->Users->inpstyle = " style='width:130px;'";

		$this->SiteUser = (object) array();
		$this->SiteUser->view = $this;
		$this->SiteUser->field = 'SiteUser';
		$this->SiteUser->filter = TRUE;
		$this->SiteUser->config['t'] = 'Users';
		$this->SiteUser->config['m2'] = 'getEUserASList';
		$this->SiteUser->inpstyle = " style='width:130px;'";

		$this->mdeleteconfig = '{messageText:"' . EasyContactFormsT::get('ItwillDeleteRecordsAndFilesAreYouSure') . '"}';
		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomFormsEntries'><?php

		include 'views/easy-contact-forms-customformsentriesdetailedmainview.php';

	}

	/**
	 * 	getMainView
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $viewmap
	 * 	request data
	 */
	function getMainView($viewmap) {

		$spar = $this->getOrder($viewmap);

		$sortfields = array(
			'id',
			'Date',
			'CustomFormsDescription',
			'UsersDescription',
			'PageName',
			'SiteUser',
		);

		$orderby = EasyContactFormsDB::getOrderBy($sortfields, $spar, "CustomFormsEntries.id DESC");

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'id', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'Date', 'date');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'Content');

		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'CustomForms', 'int');

		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'Users', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'SiteUser', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormsEntries.', 'PageName');
		EasyContactFormsRoot::mDelete('CustomFormsEntries', $viewmap);

		$query = "SELECT
				CustomFormsEntries.id,
				CustomFormsEntries.Date,
				CustomFormsEntries.PageName,
				CustomFormsEntries.SiteUser,
				CustomForms.Description AS CustomFormsDescription,
				CustomFormsEntries.CustomForms AS CustomForms,
				CONCAT(Users.Description, ' ', Users.Name) AS UsersDescription,
				CustomFormsEntries.Users AS Users
			FROM
				#wp__easycontactforms_customformsentries AS CustomFormsEntries
			LEFT JOIN
				#wp__easycontactforms_customforms AS CustomForms
					ON
						CustomFormsEntries.CustomForms=CustomForms.id
			LEFT JOIN
				#wp__easycontactforms_users AS Users
					ON
						CustomFormsEntries.Users=Users.id";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->Users = (object) array();
		$this->Users->view = $this;
		$this->Users->field = 'Users';
		$this->Users->filter = TRUE;
		$this->Users->config['m2'] = 'getUserASList';
		$this->Users->config['t'] = 'Users';
		$this->Users->asparams['listItemClass'] = 'ufo-user-list-item';
		$this->Users->inpstyle = " style='width:130px;'";

		$this->SiteUser = (object) array();
		$this->SiteUser->view = $this;
		$this->SiteUser->field = 'SiteUser';
		$this->SiteUser->filter = TRUE;
		$this->SiteUser->config['t'] = 'Users';
		$this->SiteUser->config['m2'] = 'getEUserASList';
		$this->SiteUser->inpstyle = " style='width:130px;'";

		$this->mdeleteconfig = '{messageText:"' . EasyContactFormsT::get('ItwillDeleteRecordsAndFilesAreYouSure') . '"}';
		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomFormsEntries'><?php

		include 'views/easy-contact-forms-customformsentriesmainview.php';

	}

}
