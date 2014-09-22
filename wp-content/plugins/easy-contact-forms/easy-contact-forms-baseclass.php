<?php

/**
 * @file
 *
 * 	EasyContactFormsBase class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

/**
 * 	EasyContactFormsBase
 *
 * 	A basic class all the database objects should extend
 *
 */
class EasyContactFormsBase {


	/**
	 *
	 * @var boolean $valid
	 * 	TRUE if represents and real db object
	 */

	protected $valid = FALSE;

	/**
	 *
	 * @var array $fieldmap
	 * 	A list of database object fields
	 */

	protected $fieldmap;

	/**
	 *
	 * @var string $selectstmt
	 * 	An sql statement executed to initialize the object instance
	 */

	protected $selectstmt;

	/**
	 *
	 * @var object $fields
	 * 	An object containing database record values
	 */

	protected $fields;

	/**
	 *
	 * @var array $rsholder
	 * 	An array containing list query cache
	 */

	protected $rsholder = array();

	/**
	 *
	 * @var string $idDelimeter
	 * 	A symbol used to split element id parts
	 */

	private $idDelimeter = '-';
	/**
	 * 	getFieldNames
	 *
	 * 	lists object fields. this is a method useful for incoming requests
	 * 	filtering
	 *
	 *
	 * @return array
	 * 	the list of database object fields
	 */
	function getFieldNames() {

		return array_keys($this->fieldmap);

	}

	/**
	 * 	init
	 *
	 * 	depending on provided id returns either an initialized database
	 * 	object instance or
	 * 	creates a new database object
	 *
	 * @param int $new_id
	 * 	object id. if it is NULL or empty, a new object is created
	 *
	 * @return object
	 * 	class instance
	 */
	function init($new_id = NULL) {

		if (isset($new_id) && ($new_id != '0') && (!empty($new_id))) {
			$this->fields = $this->getObjectData($new_id);
		}
		else {
			$this->fields = $this->getNew();
		}

	}

	/**
	 * 	isValid
	 *
	 * 	returns the valid property value
	 *
	 *
	 * @return boolean
	 * 	the value
	 */
	function isValid() {

			return $this->valid;

	}

	/**
	 * 	getObjectData
	 *
	 * 	gets object data from a database
	 *
	 * @param int $new_id
	 * 	object id
	 *
	 * @return object
	 * 	php object containing a database record data
	 */
	function getObjectData($new_id) {

		if (!isset($this->selectstmt)) {
			$names = implode(', ', array_keys($this->fieldmap));
			$this->selectstmt = "SELECT $names FROM " . $this->getTableName() . " WHERE id = :id";
		}
		$fields = EasyContactFormsDB::select(
			$this->selectstmt,
			array('fvalues' => array(':id' => intval($new_id)))
		);
		$this->valid = count($fields) == 1;
		return $this->valid ? $fields[0] : (object) array();

	}

	/**
	 * 	formInit
	 *
	 * 	simplificattion of field-list-based object form initiation
	 *
	 * @param array $formmap
	 * 	request data
	 * @param array $fieldarray
	 * 	a list of fields to request from a database
	 *
	 * @return object
	 * 	object to be shown on the client side
	 */
	function formInit($formmap, $fieldarray) {

		$query = 'SELECT ' . implode(', ', $fieldarray) . ' FROM ' . $this->getTableName() . ' WHERE id = :id';
		return $this->formQueryInit($formmap, $query);

	}

	/**
	 * 	formQueryInit
	 *
	 * 	check if theres is the need to create a new object and if so creates
	 * 	it
	 * 	excecutes a prepared select statement otherwise
	 *
	 * @param array $formmap
	 * 	request data
	 * @param string $query
	 * 	a select statement to execute
	 *
	 * @return object
	 * 	object to be shown on the client side
	 */
	function formQueryInit($formmap, $query) {

		$method = $formmap['m'];
		if ($method != 'new') {
			$objid = $formmap['oid'];
		}
		else {
			$obj = $this->getEmptyObject($formmap);
			$this->jsconfig = $this->getJSConfig($formmap);
			$objid = $obj->getId();
		}
		$this->selectstmt = $query;
		$this->init($objid);
		return $this;

	}

	/**
	 * 	getNew
	 *
	 * 	creates a new database object
	 *
	 *
	 * @return object
	 * 	initialized object data
	 */
	function getNew() {

		$insid = EasyContactFormsDB::insert($this->fieldmap, $this->type);
		$fieldlist = $this->getFieldNames();
		if (in_array('ListPosition', $fieldlist)) {
			EasyContactFormsDB::updateListPosition($this->type, $insid);
		}
		return $this->getObjectData($insid);

	}

	/**
	 * 	delete
	 *
	 * 	deletes an object record and all subordinated object records from the
	 * 	database
	 *
	 * @param int $objid
	 * 	object id
	 */
	function delete($objid) {

		$queries = $this->getDeleteStatements($objid);
		foreach ($queries as $query) {
			EasyContactFormsDB::query($query);
		}

	}

	/**
	 * 	update
	 *
	 * 	filters out the object data from a request and puts it to a database
	 *
	 * @param array $request
	 * 	object data to put to a database
	 * @param int $id
	 * 	object id
	 */
	function update($request, $id) {

		$fldvalues = array();
		$request = (array) $request;
		foreach ($this->getFieldNames() as $key) {
			if (isset($request[$key])) {
				$fldvalues[$key] = $request[$key];
			}
		}
		EasyContactFormsDB::update($fldvalues, $this->type, $id);
	}

	/**
	 * 	save
	 *
	 * 	'this' object update
	 *
	 */
	function save() {

		$this->update($this->fields, $this->getId());

	}

	/**
	 * 	copy
	 *
	 * 	Copies a db object
	 *
	 * @param array $settings
	 * 	extra settings
	 *
	 * @return object
	 * 	An object copy
	 */
	function sibling($settings = NULL) {

		$fields = $this->fields;
		$fields = clone $fields;
		$newobj = EasyContactFormsClassLoader::getObject($this->type, true);
		$newid = $newobj->get('id');
		$newobj->fields = $fields;
		$newobj->set('id', $newid);
		if (isset($fields->ListPosition)) {
			$newobj->set('ListPosition', $newid);
		}
		if (isset($fields->Description)) {
			$newobj->set('Description', $fields->Description . ' (copy)');
		}
		if (!is_null($settings)) {
			foreach ($settings as $key=>$value) {
				$newobj->set($key, $value);
			}
		}
		$newobj->save();
		return $newobj;

	}

	/**
	 * 	getTableName
	 *
	 * 	returns object's table name
	 *
	 *
	 * @return string
	 * 	returns object's table name
	 */
	function getTableName() {

		return EasyContactFormsDB::getTableName($this->type);

	}

	/**
	 * 	getByName
	 *
	 * 	Finds an object in the database by a given name
	 *
	 * @param string $name
	 * 	Object name
	 * @param boolean $new
	 * 	whether to create a new object if it is not found
	 *
	 * @return object
	 * 	returns a found or a newly crated object
	 */
	function getByName($name, $new = FALSE) {

		$name = mysql_real_escape_string($name);
		$tablename = $this->getTableName();
		$query = "SELECT id FROM {$tablename} WHERE Description='{$name}'";
		$objid = EasyContactFormsDB::getValue($query);
		if (is_numeric($objid)) {
			$obj = EasyContactFormsClassLoader::getObject($this->type, true, $objid);
			return $obj;
		}
		if ($new) {
			$obj = $this->getEmptyObject(array());
			$obj->set('Description', $name);
			$obj->save();
			$obj->_new = TRUE;
			return $obj;
		}
		return FALSE;

	}

	/**
	 * 	getFilter
	 *
	 * 	gets filter data from a request, and json-decodes it
	 *
	 * @param array $viewmap
	 * 	Request data
	 * @param array $rparams
	 * 	A raw filter array
	 *
	 * @return array
	 * 	filter data prepared from
	 */
	function getFilter($viewmap, $rparams = NULL) {

		if (is_null($rparams)) {
			$rparams = array();
		}

		if (isset($viewmap['filter'])) {
			$jspar = json_decode(stripslashes($viewmap['filter']));
			$rparams = EasyContactFormsDB::getStdFilters($jspar, $rparams);
		}
		if (isset($viewmap['specialfilter'])) {
			$jspar = json_decode(stripslashes($viewmap['specialfilter']));
			$rparams = EasyContactFormsDB::getStdFilters($jspar, $rparams);
		}
		return $rparams;

	}

	/**
	 * 	getOrder
	 *
	 * 	gets the table sorting data from a request
	 *
	 * @param array $viewmap
	 * 	request data
	 *
	 * @return array
	 * 	a list of filds to order by
	 */
	function getOrder($viewmap) {

		$spar = array();
		if (isset($viewmap['sort'])) {
			$spar = json_decode(stripslashes($viewmap['sort']));
			$spar = EasyContactFormsDB::getStdOrder($spar);
		}
		return $spar;

	}

	/**
	 * 	ifRole
	 *
	 * 	a method useful for role-based GUI fields including and excluding
	 * 	performs a role-name and role-id based check. Roles are passed via
	 * 	the function argument list
	 *
	 *
	 * @return boolean
	 * 	TRUE if current user is of requested role, FALSE otherwise
	 */
	function ifRole() {

		$args = func_get_args();
		if (is_array($args[0])) {
			$args = $args[0];
		}
		if (count($args) == 0) {
			return TRUE;
		}
		foreach ($args as $arg) {
			if ($arg == $this->user->role->id) {
				return TRUE;
			}
			if ($arg == $this->user->role->Description) {
				return TRUE;
			}
		}
		return FALSE;

	}

	/**
	 * 	ifnRole
	 *
	 * 	Logical NOT to the previuos method
	 *
	 */
	function ifnRole() {

		$args = func_get_args();
		return !$this->ifRole($args);

	}

	/**
	 * 	ifUser
	 *
	 * 	a method similar to ifRole, but performs a user id based check
	 *
	 */
	function ifUser() {

		$args = func_get_args();
		if (is_array($args[0])) {
			$args = $args[0];
		}
		if (count($args) == 0) {
			return TRUE;
		}
		foreach ($args as $arg) {
			if ($arg == $this->user->id) {
				return TRUE;
			}
		}
		return FALSE;

	}

	/**
	 * 	ifnUser
	 *
	 * 	Logical NOT to the previuos method
	 *
	 */
	function ifnUser() {

		$args = func_get_args();
		return !$this->ifUser($args);

	}

	/**
	 * 	getStdList
	 *
	 * 	creates a simple list of records
	 *
	 * @param string $obj
	 * 	the object type to select
	 * @param string $fld
	 * 	a optional field to order by
	 *
	 * @return array
	 * 	an array containing object records
	 */
	function getStdList($obj, $fld = NULL) {

		$orderby = isset($fld) ? $fld : 'Description';
		$tablename = EasyContactFormsDB::getTableName($obj);
		$query = "SELECT id, Description FROM $tablename  ORDER BY $orderby";
		return $this->getList($query);

	}

	/**
	 * 	getList
	 *
	 * 	performs an actual query to a database
	 *
	 * @param string $listquery
	 * 	a query to execute
	 * @param array $params
	 * 	an optional list of query parameters
	 *
	 * @return array
	 * 	an array containing object records
	 */
	function getList($listquery, $params = NULL) {

		$listquery = EasyContactFormsDB::getQueryText($listquery, $params);
		if (!isset($this->rsholder[$listquery])) {
			$rslist = EasyContactFormsDB::getObjects($listquery);
			if (EasyContactFormsDB::err()) {
				return NULL;
			}
			$this->rsholder[$listquery] = $rslist;
		}
		else {
			$rslist = $this->rsholder[$listquery];
		}
		return $rslist;

	}

	/**
	 * 	getEmptyObject
	 *
	 * 	performs creation of a new objects along with settings necessary
	 * 	defaults
	 *
	 * @param array $map
	 * 	request data
	 * @param array $fields
	 * 	an optional array of pre-initialized object fields
	 *
	 * @return object
	 * 	initialized object
	 */
	function getEmptyObject($map, $fields = NULL) {

		if ($fields == NULL) {
			$fields = (object) array();
		}
		$fmap = $this->getFieldNames();

		$user = isset($map['easycontactusr']) ? $map['easycontactusr'] : NULL;

		if (in_array('ObjectOwner', $fmap) && !is_null($user)) {
			$fields->ObjectOwner = $user->id;
		}

		if (isset($map['a'])) {
			$rvalues = json_decode(stripslashes($map['a']));
			if (is_array($rvalues)) {
				foreach ($rvalues as $value) {
					$name = $value->fld;
					$name = mysql_real_escape_string($name);
					$fields->$name = $value->oid;
				}
			}
			else {
				$name = $rvalues->fld;
				$name = mysql_real_escape_string($name);
				$fields->$name = $rvalues->oid;
			}
		}

		$classname = 'EasyContactForms' . $this->type;
		$obj = new $classname(TRUE);
		if (!is_null($user)) {
			$obj->user = $user;
		}

		if (in_array('Description', $fmap) && !isset($fields->Description)) {
			$fields->Description
				= EasyContactFormsT::get('New' . $this->type) . $obj->getId();
		}

		$obj->_empty = TRUE;
		$obj->update($fields, $obj->getId());

		$obj = new $classname(TRUE, $obj->getId());
		if (!is_null($user)) {
			$obj->user = $user;
		}
		return $obj;

	}

	/**
	 * 	getId
	 *
	 * 	returns object id
	 *
	 *
	 * @return int
	 * 	object id
	 */
	function getId() {

		return $this->fields->id;

	}

	/**
	 * 	isEmpty
	 *
	 * 	checks if an object property value is empty
	 *
	 * @param string $prop
	 * 	property name
	 *
	 * @return boolean
	 * 	TRUE if the property value is empty
	 */
	function isEmpty($prop) {

		if (!isset($this->fields->$prop)) {
			return TRUE;
		}
		return empty($this->fields->$prop);

	}

	/**
	 * 	hasField
	 *
	 * 	checks if an object has a property
	 *
	 * @param string $prop
	 * 	property name
	 *
	 * @return boolean
	 * 	TRUE if it has
	 */
	function hasField($prop) {

		$fields = (array) $this->fields;
		return array_key_exists($prop, $fields);

	}

	/**
	 * 	get
	 *
	 * 	return object's property value
	 *
	 * @param string $prop
	 * 	property name
	 *
	 * @return arbitrary
	 * 	property value
	 */
	function get($prop) {

		if (isset($this->fields->$prop)) {
			return $this->fields->$prop;
		}
		return NULL;

	}

	/**
	 * 	getValue
	 *
	 * 	Returns a field value of an object with given id
	 *
	 * @param string $fldName
	 * 	field name
	 * @param int $id
	 * 	object id
	 *
	 * @return arbitrary
	 * 	the value
	 */
	function getValue($fldName, $id) {

		$tablename = $this->getTableName();
		$id = intval($id);
		return EasyContactFormsDB::getValue("SELECT $fldName FROM $tablename WHERE id='{$id}';");

	}

	/**
	 * 	set
	 *
	 * 	sets object property value
	 *
	 * @param string $prop
	 * 	property name
	 * @param arbitrary $value
	 * 	property value
	 */
	function set($prop, $value) {

		$this->fields->$prop = $value;

	}

	/**
	 * 	setData
	 *
	 * 	Sets object data
	 *
	 * @param array $data
	 * 	database record
	 */
	function setData($data) {

		$this->fields = $data;

	}

	/**
	 * 	getData
	 *
	 * 	Returns object data
	 *
	 *
	 * @return object
	 * 	A data record
	 */
	function getData() {

		return $this->fields;

	}

	/**
	 * 	getViews
	 *
	 * 	passes a request to a particular object view
	 *
	 * @param array $vmap
	 * 	request data
	 */
	function getViews($vmap) {

		$viewname = strtolower($vmap['n']);
		switch ($viewname) {
		
			case 'main':
				$this->getMainView($vmap);
				return NULL;
			default: return $viewname;
		}

	}

	/**
	 * 	getForms
	 *
	 * 	passes a request to a particular object view
	 *
	 * @param array $vmap
	 * 	request data
	 */
	function getForms($vmap) {

		$viewname = strtolower($vmap['n']);
		switch ($viewname) {
		
			case 'main':
				$this->getMainForm($vmap);
				return NULL;
			default: return $viewname;
		}

	}

	/**
	 * 	getListHTML
	 *
	 * 	returns an intiated html <select> object
	 *
	 * @param array $rslist
	 * 	aray containing database data
	 * @param string $fieldname
	 * 	value object field name
	 * @param boolean $notnull
	 * 	allow / disallow to unset the field
	 * @param string $obj
	 * 	object type
	 * @param string $orderby
	 * 	an optional fields to order by
	 *
	 * @return string
	 * 	html string
	 */
	function getListHTML($rslist, $fieldname = NULL, $notnull = FALSE, $obj = NULL, $orderby = NULL) {

		if (!isset($rslist)) {
			$rslist = $this->getStdList($obj, $orderby);
		}
		$htmlstring = ($notnull) ? '' : "<option value = ''>...</option>\n";
		$tocompare = isset($fieldname) ? $this->get($fieldname) : 'none';
		foreach ($rslist as $listrow) {
			$selectedoption = ($tocompare == $listrow->id) ?
				"' selected" : "'";
			$htmlstring .= "<option value = '" . $listrow->id .
				$selectedoption . ">";
			$htmlstring .= $listrow->Description;
			$htmlstring .= "</option>\n";
		}
		return $htmlstring;

	}

	/**
	 * 	getRoleListHTML
	 *
	 * 	a special role list. similar to the previous one
	 *
	 * @param string $fieldname
	 * 	a role value field
	 * @param boolean $notnull
	 * 	allows/disallows to unset the object field
	 *
	 * @return string
	 * 	html select object, containing a role list
	 */
	function getRoleListHTML($fieldname, $notnull = FALSE) {

		$rslist = $this->getList(
			'SELECT id, Description FROM #wp__easycontactforms_roles WHERE id <> 2'
		);
		return $this->getListHTML($rslist, $fieldname, $notnull);

	}

	/**
	 * 	getBasicASList
	 *
	 * 	prepares data for ajax suggest lists
	 *
	 * @param array $_asmap
	 * 	request data
	 * @param string $query
	 * 	an sql statement to execute
	 *
	 * @return array
	 * 	array containing values prepared to be send via json
	 */
	function getBasicASList($_asmap, $query) {

		$values = array();
		$_result = array();
		$asinput = EasyContactFormsUtils::getASInput($_asmap);
		if ($asinput) {
			$values['fvalues'][':input'] = "%$asinput->input%";
			$_limit	 = $asinput->limit;
			$query = $query . ' ' . $_limit;
		}

		$_items = EasyContactFormsDB::select($query, $values);

		foreach ($_items as $record) {
			$_resultitem = array();
			$_resultitem['id'] = $record->id;
			$_resultitem['value'] = trim($record->Description);
			$_resultitem['info'] = $this->getObjectASForm($record->id);
			$_result[] = (object) $_resultitem;
		}
		return $_result;

	}

	/**
	 * 	getObjectASForm
	 *
	 * 	initializes an object splash view
	 *
	 * @param int $objid
	 * 	selected object id
	 *
	 * @return string
	 * 	html text to pass to the client side
	 */
	function getObjectASForm($objid) {

		$fmap = array();
		$fmap['oid'] = $objid;
		$fmap['m'] = 'show';
		ob_start();
		$this->getASForm($fmap);
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}

	/**
	 * 	getJSConfig
	 *
	 * 	Creates js configuration
	 *
	 * @param array $map
	 * 	Request data
	 *
	 * @return string
	 * 	js object
	 */
	function getJSConfig($map) {

		$methods = array('view','show');
		foreach ($methods as $method) {
			if ($map['m'] == $method) {
				return '"' . $this->type . '"';
			}
		}
		$config = (object) array();
		$params = array(
			'm',
			't',
			'oid',
			'n',
			'viewTarget',
		);

		foreach ($params as $param) {
			if (isset($map[$param])) {
				$config->$param = $map[$param];
			}
		}

		if (isset($map['specialfilter'])) {
			$specialfilter = json_decode(stripslashes($map['specialfilter']));
			if (isset($specialfilter[0]) && is_object($specialfilter[0])) {
				$config->a = (object) array();
				$config->a->fld = $specialfilter[0]->property;
				$config->a->oid = $specialfilter[0]->value->values[0];
				$config->a = json_encode($config->a);
			}
		}

		if ($map['m'] == 'mtmview' && isset($map['a'])) {
			$config->a = $map['a'];
		}			

		return	EasyContactFormsUtils::toJs($config);

	}

	/**
	 * 	idJoin
	 *
	 * 	creates html element id
	 *
	 *
	 * @return string
	 * 	element id
	 */
	function idJoin() {

		$args = func_get_args();
		return implode($this->idDelimeter, $args);

	}

	/**
	 * 	elId
	 *
	 * 	creates html element id
	 *
	 * @param string $name
	 * 	object field name
	 * @param int $index
	 * 	object id
	 *
	 * @return string
	 * 	element id
	 */
	function elId($name, $index) {

		return $this->idJoin($index, $name);

	}

	/**
	 * 	sId
	 *
	 * 	returns an id for filter sql operator lists
	 *
	 * @param string $name
	 * 	object field name
	 */
	function sId($name) {

		return $this->idJoin('sgn', $name);

	}

	/**
	 * 	oId
	 *
	 * 	returns an id for table sorting headers
	 *
	 * @param string $name
	 * 	object field name
	 */
	function oId($name) {

		return $this->idJoin('srt', $name);

	}

	/**
	 * 	sList
	 *
	 * 	returns a list of sql operators
	 *
	 * @param string $group
	 * 	operator group name
	 * @param boolean $useany
	 * 	adds/removes the 'ANY' list item
	 *
	 * @return string
	 * 	the operator list
	 */
	function sList($group, $useany = TRUE) {

		return EasyContactFormsDB::getSignSList($group, $useany);		

	}

	/**
	 * 	dispatch
	 *
	 * 	dispatches an incoming request
	 *
	 * @param array $dispmap
	 * 	request data
	 */
	function dispatch($dispmap) {

		$dispmap = EasyContactFormsUtils::intercept($dispmap);
		if ($dispmap == NULL) {
			EasyContactFormsIHTML::getNotLoggedInHTML();
			return NULL;
		}
		$this->user = $dispmap['easycontactusr'];
		$this->map = $dispmap;
		$this->jsconfig = $this->getJSConfig($dispmap);

		$dispmethod = $dispmap["m"];
		switch ($dispmethod) {

			case 'show':
			case 'new':
				$this->getForms($dispmap);
				return NULL;

			case 'view':
			case 'viewDetailed':
				$this->getViews($dispmap);
				return NULL;

			default:return $dispmap;
		}

	}

}

/**
 * 	EasyContactFormsForms
 *
 * 	A basic class all the form view objects should extend
 *
 */
class EasyContactFormsForms extends EasyContactFormsBase {

	/**
	 * 	dispatch
	 *
	 * @param  $dispmap
	 * 
	 *
	 * @return
	 * 
	 */
	function dispatch($dispmap) {

		$dispmap = EasyContactFormsUtils::intercept($dispmap);
		if ($dispmap==null){
			EasyContactFormsIHTML::getNotLoggedInHTML();
			return;
		};
		$method =	'get'.$this->type.'Form';
		return	$this->$method($dispmap);

	}

}

/**
 * 	EasyContactFormsBusinessObject. extends the Base class. Adds tracking
 * 	abilities
 *
 */
class EasyContactFormsBusinessObject extends EasyContactFormsBase {

	/**
	 * 	update
	 *
	 * 	filters out the object data from a request and puts it to a database
	 *
	 * @param array $request
	 * 	object data to put to a database
	 * @param int $id
	 * 	object id
	 */
	function update($request, $id) {

		$request = (array) $request;

		$newcomment = NULL;
		if (isset($request['Comment']) && !empty($request['Comment'])) {
			$newcomment = $request['Comment'];
			unset($request['Comment']);
			$history = $this->getValue('History', $id);
			$fcomment = $this->formatComment($newcomment, $request);
			$request['History'] = $fcomment . $history;
		}
		parent::update($request, $id);

	}

	/**
	 * 	formatComment
	 *
	 * 	returns a formatted comment
	 *
	 * @param int $comment
	 * 	old object status
	 * @param array $request
	 * 	request data
	 *
	 * @return string
	 * 	the formatted comment
	 */
	function formatComment($comment, $request) {

		$as = EasyContactFormsApplicationSettings::getInstance();
		if (!$as->get('UseTinyMCE')) {
			$comment = nl2br(htmlspecialchars($comment));
		}

		$lastname = '';
		$name = '';
		if (isset($this->user)){
			$userid = $this->user->id;
			$users = EasyContactFormsClassLoader::getObject('Users');
			$lastname = $users->getValue('Description', $userid);
			$name = $users->getValue('Name', $userid);
			$this->_userName = trim("{$name} {$lastname}");
		}
		$date = date($as->getDateFormat('PHP', TRUE));

		return "
				<div class='ufo-div-comment'>
					<div class='ufo-div-comment-header'>
						{$name}&nbsp;{$lastname}
						<br />
						<span>
							{$date}
						</span>
					</div>
					<div class='ufo-div-comment-comment'>
						{$comment}
					</div>
				</div>
				<div class='ufo-clear-both'></div>
				";

	}

}
