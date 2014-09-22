<?php

/**
 * @file
 *
 * 	EasyContactFormsCustomForms_MailingLists class definition
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
 * 	EasyContactFormsCustomForms_MailingLists
 *
 */
class EasyContactFormsCustomForms_MailingLists extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsCustomForms_MailingLists class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'CustomForms_MailingLists';
		$this->fieldmap = array('id' => NULL, 'CustomForms' => 0, 'Contacts' => 0);

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a
	 * 	customformmailinglist record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		$stmts[] = "DELETE FROM #wp__easycontactforms_customforms_mailinglists WHERE id='$id';";

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

		$request = EasyContactFormsUtils::parseRequest($request, 'CustomForms', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'Contacts', 'int');

		parent::update($request, $id);

	}

	/**
	 * 	getCustomFormsMainView
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $viewmap
	 * 	request data
	 */
	function getCustomFormsMainView($viewmap) {

		$spar = $this->getOrder($viewmap);
		$orderby = EasyContactFormsDB::getOrderBy(array('id', 'ContactsDescription'), $spar);

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();

		$viewfilters = EasyContactFormsDB::getFilter($viewfilters, $rparams, 'CustomForms_MailingLists.', 'CustomForms', '=', 'int');

		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms_MailingLists.', 'Contacts', 'int');

		EasyContactFormsRoot::mDelete('CustomForms_MailingLists', $viewmap);

		$query = "SELECT
				CustomForms_MailingLists.id,
				CONCAT(Users.Description, ' ', Users.Name) AS ContactsDescription,
				CustomForms_MailingLists.Contacts AS Contacts
			FROM
				#wp__easycontactforms_customforms_mailinglists AS CustomForms_MailingLists
			LEFT JOIN
				#wp__easycontactforms_users AS Users
					ON
						CustomForms_MailingLists.Contacts=Users.id";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->Contacts = (object) array();
		$this->Contacts->view = $this;
		$this->Contacts->field = 'Contacts';
		$this->Contacts->filter = TRUE;
		$this->Contacts->config['m2'] = 'getUserASList';
		$this->Contacts->config['t'] = 'Users';
		$this->Contacts->asparams['listItemClass'] = 'ufo-user-list-item';
		$this->Contacts->inpstyle = " style='width:130px;'";

		$this->objid = $rparams['CustomForms']->values[0];
		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomForms_MailingLists'><?php

		include 'views/easy-contact-forms-customforms_mailinglistscustomformsmainview.php';

	}

	/**
	 * 	getUsersMainView
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $viewmap
	 * 	request data
	 */
	function getUsersMainView($viewmap) {

		$spar = $this->getOrder($viewmap);
		$orderby = EasyContactFormsDB::getOrderBy(array('id', 'CustomFormsDescription'), $spar);

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();

		$viewfilters = EasyContactFormsDB::getFilter($viewfilters, $rparams, 'CustomForms_MailingLists.', 'Contacts', '=', 'int');

		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms_MailingLists.', 'CustomForms', 'int');

		EasyContactFormsRoot::mDelete('CustomForms_MailingLists', $viewmap);

		$query = "SELECT
				CustomForms_MailingLists.id,
				CustomForms.Description AS CustomFormsDescription,
				CustomForms_MailingLists.CustomForms AS CustomForms
			FROM
				#wp__easycontactforms_customforms_mailinglists AS CustomForms_MailingLists
			LEFT JOIN
				#wp__easycontactforms_customforms AS CustomForms
					ON
						CustomForms_MailingLists.CustomForms=CustomForms.id";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->objid = $rparams['Contacts']->values[0];
		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomForms_MailingLists'><?php

		include 'views/easy-contact-forms-customforms_mailinglistsusersmainview.php';

	}

}
