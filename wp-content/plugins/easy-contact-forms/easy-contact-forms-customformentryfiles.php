<?php

/**
 * @file
 *
 * 	EasyContactFormsCustomFormEntryFiles class definition
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
 * 	EasyContactFormsCustomFormEntryFiles
 *
 */
class EasyContactFormsCustomFormEntryFiles extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsCustomFormEntryFiles class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'CustomFormEntryFiles';

		$this->fieldmap = array(
				'id' => NULL,
				'Description' => '',
				'Date' => 0,
				'CustomFormsEntries' => 0,
			);

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a
	 * 	customformentryfile record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		$query = "SELECT id FROM #wp__easycontactforms_files WHERE doctype='CustomFormEntryFiles' AND docid='$id';";
		EasyContactFormsDB::cDelete($query, 'Files');

		$stmts[] = "DELETE FROM #wp__easycontactforms_customformentryfiles WHERE id='$id';";

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
		$request = EasyContactFormsUtils::parseRequest($request, 'CustomFormsEntries', 'int');

		parent::update($request, $id);

	}

	/**
	 * 	getEmptyObject. Overrides EasyContactFormsBase::getEmptyObject()
	 *
	 * 	creates and initializes a new CustomFormEntryFile
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
		$fields->Date = EasyContactFormsUtils::getDate(NULL, TRUE);
		$fields->CustomFormsEntries = EasyContactFormsDB::getFirst('CustomFormsEntries');

		return parent::getEmptyObject($map, $fields);

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
	 * 	getDetailedMainView
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $viewmap
	 * 	request data
	 */
	function getDetailedMainView($viewmap) {

		$spar = $this->getOrder($viewmap);

		$orderby = EasyContactFormsDB::getOrderBy(array('id', 'Description', 'CustomFormsEntriesDescription'), $spar, "CustomFormEntryFiles.Description");

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();

		$viewfilters = EasyContactFormsDB::getFilter($viewfilters, $rparams, 'CustomFormEntryFiles.', 'CustomFormsEntries', '=', 'int');

		EasyContactFormsRoot::mDelete('CustomFormEntryFiles', $viewmap);

		$query = "SELECT
				CustomFormEntryFiles.id,
				CustomFormEntryFiles.Description,
				CustomFormsEntries.Description AS CustomFormsEntriesDescription,
				CustomFormEntryFiles.CustomFormsEntries AS CustomFormsEntries
			FROM
				#wp__easycontactforms_customformentryfiles AS CustomFormEntryFiles
			LEFT JOIN
				#wp__easycontactforms_customformsentries AS CustomFormsEntries
					ON
						CustomFormEntryFiles.CustomFormsEntries=CustomFormsEntries.id";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->mdeleteconfig = '{messageText:"' . EasyContactFormsT::get('ItwillDeleteRecordsAndFilesAreYouSure') . '"}';
		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomFormEntryFiles'><?php

		include 'views/easy-contact-forms-customformentryfilesdetailedmainview.php';

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
			'CustomFormsEntriesDescription',
			'Description',
		);

		$orderby = EasyContactFormsDB::getOrderBy($sortfields, $spar, "DataSource.id DESC");

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'DataSource.', 'id', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'DataSource.', 'Description');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'DataSource.', 'Date', 'date');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'DataSource.', 'CustomFormsEntries', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'DataSource.', 'CustomForms', 'int');
		EasyContactFormsRoot::mDelete('CustomFormEntryFiles', $viewmap);

		$query = "SELECT
				DataSource.id,
				DataSource.CustomFormsEntries,
				DataSource.CustomFormsEntriesDescription,
				DataSource.Description,
				DataSource.Date,
				DataSource.CustomForms,
				DataSource.CustomFormsDescription
			FROM(
				SELECT
					CustomFormEntryFiles.id AS id,
					CustomFormEntryFiles.CustomFormsEntries AS CustomFormsEntriesDescription,
					CustomFormEntryFiles.CustomFormsEntries AS CustomFormsEntries,
					CustomFormEntryFiles.Description AS Description,
					CustomFormEntryFiles.Date AS Date,
					CustomForms.id AS CustomForms,
					CustomForms.Description AS CustomFormsDescription
				FROM
					#wp__easycontactforms_customformentryfiles AS CustomFormEntryFiles
				LEFT JOIN
					#wp__easycontactforms_customformsentries AS CustomFormsEntries
				LEFT JOIN
					#wp__easycontactforms_customforms AS CustomForms
						ON
							CustomFormsEntries.CustomForms=CustomForms.id
						ON
							CustomFormEntryFiles.CustomFormsEntries=CustomFormsEntries.id) AS DataSource";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->mdeleteconfig = '{messageText:"' . EasyContactFormsT::get('ItwillDeleteRecordsAndFilesAreYouSure') . '"}';
		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomFormEntryFiles'><?php

		include 'views/easy-contact-forms-customformentryfilesmainview.php';

	}

}
