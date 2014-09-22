<?php

/**
 * @file
 *
 * 	EasyContactFormsCustomFormFieldTypes class definition
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
 * 	EasyContactFormsCustomFormFieldTypes
 *
 */
class EasyContactFormsCustomFormFieldTypes extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsCustomFormFieldTypes class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'CustomFormFieldTypes';

		$this->fieldmap = array(
				'id' => NULL,
				'Description' => '',
				'CssClass' => '',
				'Settings' => '',
				'Signature' => '',
				'ListPosition' => 0,
				'ValueField' => 0,
				'HelpLink' => '',
			);

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a
	 * 	customformfieldtype record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		$stmts[] = "DELETE FROM #wp__easycontactforms_customformfieldtypes WHERE id='$id';";

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

		$request = EasyContactFormsUtils::parseRequest($request, 'ListPosition', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'ValueField', 'boolean');

		parent::update($request, $id);

	}

}
