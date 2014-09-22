<?php

/**
 * @file
 *
 * 	EasyContactFormsContactTypes class definition
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
 * 	EasyContactFormsContactTypes
 *
 */
class EasyContactFormsContactTypes extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsContactTypes class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'ContactTypes';
		$this->fieldmap = array('id' => NULL, 'Description' => '', 'Notes' => '');

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a contacttype
	 * 	record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		$stmts[] = "DELETE FROM #wp__easycontactforms_contacttypes WHERE id='$id';";

		return $stmts;

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
		$fields[] = 'Notes';

		$obj = $this->formInit($formmap, $fields);
		$obj->set('Description', htmlspecialchars($obj->get('Description'), ENT_QUOTES));
		$obj->set('Notes', htmlspecialchars($obj->get('Notes')));

		?>
		<input type='hidden' class='ufostddata' id='t' value='<?php echo $obj->type;?>'>
		<input type='hidden' class='ufostddata' id='oid' value='<?php echo $obj->getId();?>'>
		<?php

		require_once 'views/easy-contact-forms-contacttypesmainform.php';

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
		$orderby = EasyContactFormsDB::getOrderBy(array('id', 'Description'), $spar, "ContactTypes.Description");

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'ContactTypes.', 'id', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'ContactTypes.', 'Description');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'ContactTypes.', 'Notes');
		EasyContactFormsRoot::mDelete('ContactTypes', $viewmap);

		$query = "SELECT
				ContactTypes.id,
				ContactTypes.Description
			FROM
				#wp__easycontactforms_contacttypes AS ContactTypes";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$obj = $this;
		?><input type='hidden' name='t' id='t' value='ContactTypes'><?php

		include 'views/easy-contact-forms-contacttypesmainview.php';

	}

}
