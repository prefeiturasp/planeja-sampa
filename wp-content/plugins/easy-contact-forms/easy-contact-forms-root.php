<?php

/**
 * @file
 *
 * 	EasyContactFormsRoot class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

require_once 'easy-contact-forms-utils.php';
require_once 'easy-contact-forms-database.php';
require_once 'easy-contact-forms-securitymanager.php';
require_once 'easy-contact-forms-ihtml.php';
require_once 'easy-contact-forms-layout.php';

/**
 * 	a front-end object processing all the reaquests to the application
 *
 * 	it is organized in the way allowing dispatching incoming requests
 * 	performing basic functions and load particular class instances if
 * 	necessary
 *
 */
class EasyContactFormsRoot {

	/**
	 * 	processRequest
	 *
	 * 	this is a sibling of the processEvent method. Currently does nothing
	 *
	 * @param array $_reqmap
	 * 	incoming request
	 */
	function processRequest($_reqmap) {

		EasyContactFormsRoot::processEvent($_reqmap);

	}

	/**
	 * 	Main dispatcher
	 *
	 * @param array $_mainmap
	 * 	Array containing request values
	 */
	function processEvent($_mainmap) {


		$_dispmethod = $_mainmap["m"];

		if ($_dispmethod == "save") {
			return EasyContactFormsRoot::saveObjects($_mainmap);
		}

		if ($_dispmethod == "apply") {
			return EasyContactFormsRoot::saveObjects($_mainmap);
		}

		if ($_dispmethod == "list") {
			return EasyContactFormsRoot::listObjects($_mainmap);
		}

		if ($_dispmethod == "mtmview") {
			return EasyContactFormsRoot::mtmView($_mainmap);
		}

		if ($_dispmethod == "ajaxsuggest") {
			return EasyContactFormsRoot::ajaxSuggest($_mainmap);
		}

		if ($_dispmethod == "upload") {
			return EasyContactFormsRoot::upload($_mainmap);
		}

		if ($_dispmethod == "delete") {
			return EasyContactFormsRoot::deleteFile($_mainmap);
		}

		$newobjtype = $_mainmap["t"];

		$newobject = EasyContactFormsClassLoader::getObject($newobjtype);
		if ($newobject) {
			return $newobject->dispatch($_mainmap);
		}
		else {
			EasyContactFormsIHTML::getNotLoggedInHTML();
		}

	}

	/**
	 * 	download
	 *
	 * 	loads the file handler and transfers the download request
	 *
	 * @param array $map
	 * 	request data
	 */
	function download($map) {

		$file = EasyContactFormsClassLoader::getObject('Files');
		$file->download($map);

	}

	/**
	 * 	upload
	 *
	 * 	loads the file handler and passes the request to hadle the uploaded
	 * 	file
	 *
	 * @param array $_uldmap
	 * 	request data
	 */
	function upload($_uldmap) {

		if (! EasyContactFormsSecurityManager::roleObjectCheck($_uldmap)) {
			return;
		}
		$file = EasyContactFormsClassLoader::getObject('Files');
		$file->upload($_uldmap);

	}

	/**
	 * 	deleteFile
	 *
	 * 	loads the file handler and passes the request to delete a file
	 *
	 * @param array $_delmap
	 * 	request data
	 */
	function deleteFile($_delmap) {

		if (! EasyContactFormsSecurityManager::roleObjectCheck($_delmap)) {
			return;
		}
		$file = EasyContactFormsClassLoader::getObject('Files');
		$file->deletedocfile($_delmap);

	}

	/**
	 * 	mDelete
	 *
	 * 	deletes one or several records
	 *
	 * @param string $type
	 * 	object type to be deleted
	 * @param array $_delmap
	 * 	request values
	 */
	function mDelete($type, $_delmap) {

		if (!isset($_delmap['a'])) {
			return;
		}
		$__args = json_decode(stripslashes($_delmap['a']));
		if (!isset($__args->m) || $__args->m != 'mdelete') {
			return;
		}

		$args = $__args->a;
		$proxy = EasyContactFormsClassLoader::getObject($type);
		foreach ($args as $arg) {
			$id = intval($arg);
			$proxy->delete($id);
		}

	}

	/**
	 * 	moveRow
	 *
	 * 	moves a row up and down
	 *
	 * @param string $mrtype
	 * 	a type of object to move
	 * @param array $viewmap
	 * 	request values
	 * @param array $mrfilters
	 * 	optional filter values
	 * @param string $alias
	 * 	an alias to use when building a query
	 */
	function moveRow($mrtype, $viewmap, $mrfilters = array(), $alias = '') {

		if (!isset($viewmap['a'])) {
			return;
		}
		$a = json_decode(stripslashes($viewmap['a']));
		if (!isset($a->m) || $a->m != 'moveRow') {
			return;
		}
		$mrrowid = intval($a->lpi);
		$mr_direction = intval($a->lpd);
		$lpsort = (isset($a->srt)) ? $a->srt : 'ASC';

		$sort_direction = ($lpsort == 'DESC') ? -1 : 1;

		if ($mr_direction == $sort_direction) {
			$func_name = "MAX";
			$mrsign = "<";
		}
		else {
			$func_name = "MIN";
			$mrsign = ">";
		}

		$obj = EasyContactFormsClassLoader::getObject($mrtype);
		$t_name = $obj->getTableName();

		$query = "SELECT ListPosition FROM $t_name WHERE id = '$mrrowid'";
		$l_pos = EasyContactFormsDB::getValue($query);

		$dot = ($alias == '') ? '' : '.';
		$as = ($alias == '') ? '' : 'AS';

		$mrfilters['fnames'][] = $alias . $dot . "ListPosition $mrsign :_l_pos";
		$mrfilters['fvalues'][':_l_pos'] = $l_pos;

		$query = "SELECT $func_name(ListPosition) AS lp FROM $t_name $as $alias";

		$rs = EasyContactFormsDB::select($query, $mrfilters);

		if ((count($rs) > 0) && !empty($rs[0]->lp)) {
			$c_pos = $rs[0]->lp;

			$query = "SELECT id FROM $t_name WHERE ListPosition = $c_pos";
			$c_oid = EasyContactFormsDB::getValue($query);

			$valuemap['ListPosition'] = $c_pos;
			EasyContactFormsDB::update($valuemap, $obj->type, $mrrowid);

			$valuemap = array();
			$valuemap['ListPosition'] = $l_pos;
			EasyContactFormsDB::update($valuemap, $obj->type, $c_oid);
		}

	}

	/**
	 * 	listObjects
	 *
	 * 	returns a list of subordinated objects in response to master/detail
	 * 	request
	 *
	 * @param array $_ismap
	 * 	request data
	 *
	 * @return string
	 * 	a json-encoded array
	 */
	function listObjects($_ismap) {

		$obj = EasyContactFormsClassLoader::getObject($_ismap['t']);
		if (! EasyContactFormsSecurityManager::roleObjectCheck($_ismap)) {
			return '{}';
		}

		$t_name = $obj->getTableName();
		$fields = $obj->getFieldNames();
		$mastervalue = intval($_ismap['oid']);
		$masterfield = $_ismap['fld'];
		$orderby = $_ismap['srt'];
		if (!in_array($masterfield, $fields)) {
			return '{}';
		}
		if (!in_array($orderby, $fields)) {
			return '{}';
		}
		$query = "SELECT id, Description FROM $t_name WHERE $masterfield='$mastervalue' ORDER BY $orderby";
		$array = EasyContactFormsDB::getObjects($query);
		echo json_encode($array);

	}

	/**
	 * 	saveObjects
	 *
	 * 	saves one or more objects in the database. Object data is passed as
	 * 	json-encoded arrays
	 *
	 * @param array $_smap
	 * 	request data
	 */
	function saveObjects($_smap) {

		if (! EasyContactFormsSecurityManager::roleObjectCheck($_smap)) {
			return;
		}

		$values = $_smap["a"];
		$values = json_decode(stripslashes($values));
		EasyContactFormsRoot::saveForms($values, $_smap['easycontactusr']);

	}

	/**
	 * 	saveMainView
	 *
	 * 	saves main view data objects in the database. Object data is passed
	 * 	as json-encoded arrays
	 *
	 * @param array $_smap
	 * 	request data
	 */
	function saveMainView($_smap) {

		if (!isset($_smap['a'])) {
			return;
		}
		$a = $_smap["a"];
		$a = json_decode(stripslashes($a));
		if (!isset($a->m) || $a->m != 'save') {
			return;
		}
		EasyContactFormsRoot::saveForms($a->a, $_smap['easycontactusr']);

	}

	/**
	 * 	saveForms
	 *
	 * 	saves one or more objects in the database
	 *
	 * @param array $values
	 * 	object data
	 * @param int $userid
	 * 	current user id
	 */
	function saveForms($values, $userid) {


		foreach ($values as $form) {
			if (count((array) $form->a) == 0) {
				continue;
			}
			$objid = intval($form->oid);
			$objtype = $form->t; 			
			$obj = EasyContactFormsClassLoader::getObject($objtype, TRUE, $objid);
			if (!$obj) {
				continue;
			}
			$obj->user = $userid;
			$obj->update($form->a, $obj->getId());
		}

	}

	/**
	 * 	mtmView
	 *
	 * 	handles many-to-many views
	 *
	 * @param array $_amap
	 * 	request data
	 */
	function mtmView($_amap) {

		$viewname = $_amap['n'];

		$_amap['m'] = 'view';		
		$_amap = EasyContactFormsUtils::intercept($_amap);
		if ($_amap == NULL) {
			return EasyContactFormsIHTML::getNotLoggedInHTML();
		}
		$newviewname = ucwords($_amap["n"]);

		$_amap['m'] = 'mtmview';
		$_amap['n'] = $viewname;

		$atype = $_amap['t'];
		$aobj = EasyContactFormsClassLoader::getObject($atype);
		if (isset($_amap['m2']) && $_amap['m2'] = 'addRow') {
			$aobj->getEmptyObject($_amap);
		}
		$aobj->user = $_amap['easycontactusr'];
		$aobj->map = $_amap;
		$aobj->jsconfig = $aobj->getJSConfig($_amap);

		$aramethodname = 'get' . $viewname . $newviewname . 'View';
		$aobj->$aramethodname($_amap);

	}

	/**
	 * 	ajaxSuggestResponse
	 *
	 * 	packs the data into the transport format
	 *
	 * @param array $_suggestionArray
	 * 	a php array to pack
	 *
	 * @return string
	 * 	a json-encoded object
	 */
	function ajaxSuggestResponse($_suggestionArray) {

		$response = (object) array('results' => $_suggestionArray);
		$response = json_encode($response);
		echo $response;

	}

	/**
	 * 	ajaxSuggest
	 *
	 * 	provides a json-encoded filtered list to show on the client side
	 *
	 * @param array $_asmap
	 * 	request data
	 *
	 * @return string
	 * 	the json-encoded array
	 */
	function ajaxSuggest($_asmap) {

		if (! EasyContactFormsSecurityManager::roleObjectCheck($_asmap)) {
			$_asresponse = array();
			$_asresponse['info'] = EasyContactFormsT::get('YouAreNotLoggedIn');
			$_asresponsearray = array();
			$_asresponsearray[] = $_asresponse;
			return EasyContactFormsRoot::ajaxSuggestResponse((object) $_asresponsearray);
		};
		$_asmethod = $_asmap['m2'];
		$_astype = $_asmap['t'];
		$_asobject = EasyContactFormsClassLoader::getObject($_astype);
		$_suggestionArray = $_asobject->$_asmethod($_asmap);
		EasyContactFormsRoot::ajaxSuggestResponse($_suggestionArray);

	}

	/**
	 * 	ajaxCall
	 *
	 * 	handles ajax-based requests
	 *
	 * @param array $_acmap
	 * 	request data
	 *
	 * @return string
	 * 	arbitrary data in response to requests
	 */
	function ajaxCall($_acmap) {

		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-cache, must-revalidate");
		header("Pragma: no-cache");
		$_acmap = EasyContactFormsSecurityManager::getRights($_acmap);

		echo EasyContactFormsRoot::processRequest($_acmap);

	}

}

/**
 * 	EasyContactFormsClassLoader. checks if there is a php file containing
 * 	the class definition and loads this file if it is
 *
 */
class EasyContactFormsClassLoader {

	/**
	 * 	getObject. a function returing an instantiated object
	 *
	 * 	an object may be loaded as a classs or an instance
	 *
	 * @param string $type_name
	 * 	A name of type to return an instance for
	 * @param boolean $data
	 * 	indicates if the object should be loaded as a 'stub' containing class
	 * 	methods or there is a need to create a new object instance or
	 * 	initialize the instance with data from the database
	 * @param int $new_id
	 * 	if the new objid is set this method returns object's instance from
	 * 	the database. Creates a new object otherwise.
	 *
	 * @return object
	 * 	returns eigther an object 'stub' having only class methods, or a real
	 * 	'object' having initialized fields
	 */
	function getObject($type_name, $data = FALSE, $new_id = NULL) {
		if (@include_once 'easy-contact-forms-' . strtolower($type_name) . '.php') {
			$classname = 'EasyContactForms' . $type_name;
			try {
				$inst = new $classname($data, $new_id);
				$valid =	$inst->isValid();
				if ($data && !empty($new_id) && !$valid) {
					return NULL;
				}
				return $inst;
			} catch (Exception $e) {
				return NULL;
			}
		}
		else {
			EasyContactFormsIHTML::getNotLoggedInHTML();
			exit;
		}
	}

}
