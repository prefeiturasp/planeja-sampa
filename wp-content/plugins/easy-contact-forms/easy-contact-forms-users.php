<?php

/**
 * @file
 *
 * 	EasyContactFormsUsers class definition
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
 * 	EasyContactFormsUsers
 *
 */
class EasyContactFormsUsers extends EasyContactFormsBusinessObject {

	/**
	 * 	EasyContactFormsUsers class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'Users';

		$this->fieldmap = array(
				'id' => NULL,
				'Description' => '',
				'Name' => '',
				'ContactType' => 0,
				'Birthday' => 0,
				'Role' => 0,
				'CMSId' => 0,
				'Notes' => '',
				'email' => '',
				'email2' => '',
				'Cell' => '',
				'Phone1' => '',
				'Phone2' => '',
				'Phone3' => '',
				'SkypeId' => '',
				'Website' => '',
				'ContactField3' => '',
				'ContactField4' => '',
				'Country' => '',
				'Address' => '',
				'City' => '',
				'State' => '',
				'Zip' => '',
				'Comment' => '',
				'History' => '',
				'Options' => '',
			);

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getEmptyObject. Overrides EasyContactFormsBase::getEmptyObject()
	 *
	 * 	creates and initializes a new User
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
		$fields->ContactType = EasyContactFormsDB::getFirst('ContactTypes');
		$fields->Role = 4;

		return parent::getEmptyObject($map, $fields);

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a user object
	 * 	record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		$query = "SELECT
				Users.Role,
				Users.CMSId
			FROM
				#wp__easycontactforms_users AS Users
			WHERE
				Users.id='$id'";

		$objs = EasyContactFormsDB::getObjects($query);
		if (count($objs) == 0) {
			return array();
		}
		$usr = $objs[0];
		if ($usr->Role == 1) {
			$as = EasyContactFormsApplicationSettings::getInstance();
			$as->addMessage(EasyContactFormsT::get('CannotDeleteSuperAdmin'));
			return array();
		}

		$stmts = array();

		$stmts[] = "DELETE FROM #wp__easycontactforms_customforms_mailinglists WHERE Contacts='$id';";

		$stmts[] = "DELETE FROM #wp__easycontactforms_users WHERE id='$id';";

		return $stmts;

	}

	/**
	 * 	getEUserASList
	 *
	 * 	Prepares a site user list to send to the Ajax Suggest list component
	 *
	 * @param array $_asmap
	 * 	request data
	 */
	function getEUserASList($_asmap) {

		$plainselect = "SELECT ID as id, user_login AS Description, display_name  as info FROM #wp__users AS Users";

		$values = array();
		$_result = array();
		$_idmode = isset($_asmap['oid']);

		if ($_idmode) {
			$values['fvalues'][':input'] = intval($_asmap['oid']);
			$_query = "$plainselect WHERE Users.ID=:input";
		}
		else {
			$asinput = EasyContactFormsUtils::getASInput($_asmap);
			if (!$asinput) {
				return $_result;
			}
			$values['fvalues'][':input'] = "%$asinput->input%";
			$_limit	= $asinput->limit;
			$_query = "$plainselect WHERE Users.display_name LIKE :input $_limit";
		}

		$_aslist = EasyContactFormsDB::select($_query, $values);

		foreach ($_aslist as $_asitem) {
			$_resultitem = array();
			$_resultitem['id'] = $_asitem->id;
			$_resultitem['value'] = $_asitem->Description;
			$_resultitem['info'] = $_asitem->info;
			$_result[] = (object) $_resultitem;
		}
		return $_result;

	}

	/**
	 * 	getUserASList
	 *
	 * 	Prepares a list of EasyContactForms users to send to the Ajax Suggest
	 * 	list component
	 *
	 * @param array $_asmap
	 * 	request data
	 */
	function getUserASList($_asmap) {

		$plainselect = "SELECT
						Users.id,
						Users.Description
					FROM(
						SELECT
							CONCAT(Users1.Description,' ', Users1.Name) AS Description,
							Users1.id
						FROM
							#wp__easycontactforms_users AS Users1) AS Users";

		$values = array();
		$_result = array();
		$_idmode = isset($_asmap['oid']);

		if ($_idmode) {
			$values['fvalues'][':input'] = intval($_asmap['oid']);
			$_query = "$plainselect WHERE Users.id=:input";
		}
		else {
			$asinput = EasyContactFormsUtils::getASInput($_asmap);
			if (!$asinput) {
				return $_result;
			}
			$values['fvalues'][':input'] = "%$asinput->input%";
			$_limit	= $asinput->limit;

			$_query = "$plainselect WHERE Users.Description LIKE :input $_limit";
		}

		$_aslist = EasyContactFormsDB::select($_query, $values);

		foreach ($_aslist as $_asitem) {
			$_resultitem = array();
			$_resultitem['id'] = $_asitem->id;
			$_resultitem['value'] = trim($_asitem->Description);
			$_resultitem['info'] = $this->getObjectASForm($_asitem->id);
			$_result[] = (object) $_resultitem;
		}
		return $_result;

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

		$query = "SELECT
				Users.Role,
				Users.CMSId
			FROM
				#wp__easycontactforms_users AS Users
			WHERE
				Users.id='$id'";

		$objs = EasyContactFormsDB::getObjects($query);
		$usr = $objs[0];
		global $current_user;
		$currentadmin = $usr->Role == 1 &&
				isset($request->Role) &&
				isset($current_user) &&
				$current_user->ID == $usr->CMSId;
		if ($currentadmin) {
			$as = EasyContactFormsApplicationSettings::getInstance();
			$as->addMessage(EasyContactFormsT::get('CannotChangeCurrentUserRole'));
			$request->Role = 1;
		}

		$request = EasyContactFormsUtils::parseRequest($request, 'ContactType', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'Birthday', 'date');
		$request = EasyContactFormsUtils::parseRequest($request, 'Role', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'CMSId', 'int');

		parent::update($request, $id);

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

			case 'getEUserASList':
				$this->getEUserASList($dispmap);
				return NULL;

			case 'getUserASList':
				$this->getUserASList($dispmap);
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

			case 'managemain':
				return $this->getManageMainView($vmap);
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

		$query = "SELECT
				Users.id,
				Users.Name,
				Users.Notes,
				Users.email,
				Users.Cell,
				Users.Phone1,
				Users.ContactField3,
				Users.Description,
				ContactTypes.Description AS ContactTypeDescription
			FROM
				#wp__easycontactforms_users AS Users
			LEFT JOIN
				#wp__easycontactforms_contacttypes AS ContactTypes
					ON
						Users.ContactType=ContactTypes.id
			WHERE
				Users.id=:id";

		$obj = $this->formQueryInit($formmap, $query);

		require 'views/easy-contact-forms-usersasform.php';

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

		$fields = array();
		$fields[] = 'id';
		$fields[] = 'Description';
		$fields[] = 'ContactType';
		$fields[] = 'Role';
		$fields[] = 'Name';
		$fields[] = 'CMSId';
		$fields[] = 'Notes';
		$fields[] = 'ContactField3';
		$fields[] = 'ContactField4';
		$fields[] = 'Birthday';
		$fields[] = 'Country';
		$fields[] = 'City';
		$fields[] = 'State';
		$fields[] = 'Zip';
		$fields[] = 'Address';
		$fields[] = 'email';
		$fields[] = 'email2';
		$fields[] = 'Cell';
		$fields[] = 'Phone1';
		$fields[] = 'Phone2';
		$fields[] = 'Phone3';
		$fields[] = 'SkypeId';
		$fields[] = 'Website';
		$fields[] = 'Comment';
		$fields[] = 'History';

		$obj = $this->formInit($formmap, $fields);
		$obj->set('Description', htmlspecialchars($obj->get('Description'), ENT_QUOTES));
		$obj->set('Name', htmlspecialchars($obj->get('Name'), ENT_QUOTES));

		$obj->CMSId = (object) array();
		$obj->CMSId->view = $obj;
		$obj->CMSId->field = 'CMSId';
		$obj->CMSId->config['t'] = 'Users';
		$obj->CMSId->config['m2'] = 'getEUserASList';
		$obj->CMSId->inpstyle = " style='width:100%'";

		$obj->set('Notes', htmlspecialchars($obj->get('Notes')));
		$obj->set('ContactField3', htmlspecialchars($obj->get('ContactField3')));
		$obj->set('ContactField4', htmlspecialchars($obj->get('ContactField4')));
		$obj->Birthday = EasyContactFormsUtils::getDate($obj->get('Birthday'));
		$obj->set('Country', htmlspecialchars($obj->get('Country'), ENT_QUOTES));
		$obj->set('City', htmlspecialchars($obj->get('City'), ENT_QUOTES));
		$obj->set('State', htmlspecialchars($obj->get('State'), ENT_QUOTES));
		$obj->set('Zip', htmlspecialchars($obj->get('Zip'), ENT_QUOTES));
		$obj->set('Address', htmlspecialchars($obj->get('Address')));
		$obj->set('email', htmlspecialchars($obj->get('email'), ENT_QUOTES));
		$obj->set('email2', htmlspecialchars($obj->get('email2'), ENT_QUOTES));
		$obj->set('Cell', htmlspecialchars($obj->get('Cell'), ENT_QUOTES));
		$obj->set('Phone1', htmlspecialchars($obj->get('Phone1'), ENT_QUOTES));
		$obj->set('Phone2', htmlspecialchars($obj->get('Phone2'), ENT_QUOTES));
		$obj->set('Phone3', htmlspecialchars($obj->get('Phone3'), ENT_QUOTES));
		$obj->set('SkypeId', htmlspecialchars($obj->get('SkypeId'), ENT_QUOTES));
		$obj->set('Website', htmlspecialchars($obj->get('Website'), ENT_QUOTES));
		$obj->set('Comment', htmlspecialchars($obj->get('Comment')));

		?>
		<input type='hidden' class='ufostddata' id='t' value='<?php echo $obj->type;?>'>
		<input type='hidden' class='ufostddata' id='oid' value='<?php echo $obj->getId();?>'>
		<?php

		require_once 'views/easy-contact-forms-usersmainform.php';

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
			'Description',
			'Name',
			'ContactTypeDescription',
			'Birthday',
			'RoleDescription',
			'CMSId',
			'email',
		);

		$orderby = EasyContactFormsDB::getOrderBy($sortfields, $spar, "Users.Description");

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'id', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Description');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Name');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'ContactType', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Birthday', 'date');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Role', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'CMSId', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Notes');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'email');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'email2');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Cell');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Phone1');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Phone2');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Phone3');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'SkypeId');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Website');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'ContactField3');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'ContactField4');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Country');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Address');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'City');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'State');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Zip');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'History');
		EasyContactFormsRoot::mDelete('Users', $viewmap);

		$query = "SELECT
				Users.id,
				Users.Description,
				Users.Name,
				Users.Birthday,
				Users.CMSId,
				Users.email,
				ContactTypes.Description AS ContactTypeDescription,
				Users.ContactType AS ContactType,
				Roles.Description AS RoleDescription
			FROM
				#wp__easycontactforms_users AS Users
			LEFT JOIN
				#wp__easycontactforms_contacttypes AS ContactTypes
					ON
						Users.ContactType=ContactTypes.id
			LEFT JOIN
				#wp__easycontactforms_roles AS Roles
					ON
						Users.Role=Roles.id";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->CMSId = (object) array();
		$this->CMSId->view = $this;
		$this->CMSId->field = 'CMSId';
		$this->CMSId->filter = TRUE;
		$this->CMSId->config['t'] = 'Users';
		$this->CMSId->config['m2'] = 'getEUserASList';
		$this->CMSId->inpstyle = " style='width:130px;'";

		$obj = $this;
		?><input type='hidden' name='t' id='t' value='Users'><?php

		include 'views/easy-contact-forms-usersmainview.php';

	}

	/**
	 * 	getManageMainView
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $viewmap
	 * 	request data
	 */
	function getManageMainView($viewmap) {

		$spar = $this->getOrder($viewmap);
		$orderby = EasyContactFormsDB::getOrderBy(array('id', 'Description', 'Name'), $spar, "Users.Description");

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();
		$viewfilters = EasyContactFormsDB::getMTMFilter($viewmap, $viewfilters, 'Users');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'id', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Description');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'Name');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'Users.', 'ContactType', 'int');

		$query = "SELECT
				Users.id,
				Users.Description,
				Users.Name
			FROM
				#wp__easycontactforms_users AS Users";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->showlist = FALSE;
		$obj = $this;
		?><input type='hidden' name='t' id='t' value='Users'><?php

		include 'views/easy-contact-forms-usersmanagemainview.php';

	}

}
