<?php

/**
 * @file
 *
 * 	EasyContactFormsDB class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

/**
 * 	EasyContactFormsDB
 *
 * 	database operations
 *
 */
class EasyContactFormsDB {

	/**
	 * 	wptn
	 *
	 * @param  $query
	 * 
	 *
	 * @return
	 * 
	 */
	function wptn($query) {

		global $wpdb;

		$query = str_replace("#wp__", $wpdb->prefix, $query);
		return $query;

	}

	/**
	 * 	query
	 *
	 * 	executes an arbitrary query
	 *
	 * @param string $sqltext
	 * 	sql query text
	 */
	function query($sqltext) {

		global $wpdb;

		$sqltext=EasyContactFormsDB::wptn($sqltext);
		$wpdb->query($sqltext);

	}

	/**
	 * 	getObjects
	 *
	 * 	returns an array of db objects
	 *
	 * @param string $sqltext
	 * 	a query to execute
	 *
	 * @return array
	 * 	db objects array
	 */
	function getObjects($sqltext) {

		global $wpdb;

		$sqltext=EasyContactFormsDB::wptn($sqltext);
		return $wpdb->get_results($sqltext);

	}

	/**
	 * 	getValue
	 *
	 * 	returns a first column first row value
	 *
	 * @param string $sqltext
	 * 	a query to execute
	 *
	 * @return arbitrary
	 * 	the requested value
	 */
	function getValue($sqltext) {

		global $wpdb;

		$sqltext=EasyContactFormsDB::wptn($sqltext);
		return $wpdb->get_var($sqltext);

	}

	/**
	 * 	err
	 *
	 * 	informs about possible errors. TBD
	 *
	 *
	 * @return boolean
	 * 	the error flag
	 */
	function err() {

		return FALSE;

	}

	/**
	 * 	select
	 *
	 * 	composes and executes a select statement
	 *
	 * @param string $select
	 * 	a select statement to excecute
	 * @param array $filters
	 * 	a list of filters and filter values
	 * @param string $orderby
	 * 	a comma separated list of order by fields
	 * @param object $limit
	 * 	an object having the start and limit fields
	 *
	 * @return array
	 * 	an array of db objects
	 */
	function select($select, $filters = NULL, $orderby = NULL, $limit = NULL) {

		$viewlimits = is_null($limit) ? '' : ' LIMIT ' . intval($limit->start) . ', ' . intval($limit->limit);

		$where = is_null($filters) ? '' : EasyContactFormsDB::getWhere($filters);

		$select = $select . $where;

		if (! is_null($filters) && isset($filters['fvalues'])){
			foreach ($filters['fvalues'] as $key => $value){
				if (is_array($value)) {
					if (!function_exists('ms_r_escape_s')) {
						function ms_r_escape_s(&$item) {
							$item = mysql_real_escape_string($item);
						}
					}
					array_walk($value, 'ms_r_escape_s');
					$replacement = "'" . implode("', '", $value) . "'";
				}
				else {
					$replacement = "'" . mysql_real_escape_string($value) . "'";
				}

				$select = str_replace($key, $replacement, $select);
			}
		}

		$orderby = is_null($orderby) ? '' : ' ' . $orderby;

		$select = $select . $orderby . $viewlimits;

		$select=EasyContactFormsDB::wptn($select);

		$result = EasyContactFormsDB::getObjects($select);
		return $result;

	}

	/**
	 * 	update
	 *
	 * 	executes an update statement
	 *
	 * @param array $valuemap
	 * 	an array containing values to put into a database
	 * @param string $type
	 * 	an updated object type
	 * @param int $id
	 * 	object id
	 */
	function update($valuemap, $type, $id) {

		if (count($valuemap) == 0) {
			return;
		}

		$update = '';
		$comma = '';
		foreach($valuemap as $key => $value){
			$update .= $comma . $key . " = '" . mysql_real_escape_string($value) . "'";
			$comma = ', ';
		}

		$obj = EasyContactFormsClassLoader::getObject($type);

		$dbtable = $obj->getTableName();
		$dbtable = EasyContactFormsDB::wptn($dbtable);

		$id = intval($id);
		$query = "UPDATE $dbtable SET $update WHERE id = '$id'";
		EasyContactFormsDB::query($query);

	}

	/**
	 * 	insert
	 *
	 * 	insert new object data
	 *
	 * @param array $valuemap
	 * 	values to insert
	 * @param string $type
	 * 	inserted object type
	 *
	 * @return int
	 * 	inserted object id
	 */
	function insert($valuemap, $type) {

		if (count($valuemap) == 0) {
			return;
		}

		$names = '';
		$values = '';
		$comma = '';
		foreach($valuemap as $key => $value) {
			if (is_null($value)) continue;
			$names .= $comma . '' . $key . '';
			$values .= $comma . " '" . $value . "'";
			$comma = ', ';
		}

		$obj = EasyContactFormsClassLoader::getObject($type);
		$dbtable = $obj->getTableName();
		$dbtable = EasyContactFormsDB::wptn($dbtable);

		$query = 'INSERT INTO ' . $dbtable . '(' . $names . ') VALUES (' . $values . ')';
		EasyContactFormsDB::query($query);

		global $wpdb;

		return $wpdb->insert_id;

	}

	/**
	 * 	cDelete
	 *
	 * 	executes a part of cascade delete operation
	 *
	 * @param string $query
	 * 	a select query to perform
	 * @param string $type
	 * 	a type of objects being deleted
	 */
	function cDelete($query, $type) {

		$delids = EasyContactFormsDB::getObjects($query);
		$delobj = EasyContactFormsClassLoader::getObject($type);
		foreach ($delids as $delrow) {
			$delobj->delete($delrow->id);
		}

	}

	/**
	 * 	getFirst
	 *
	 * 	selects a first record in an object table
	 *
	 * @param string $type
	 * 	a type of object to look up
	 * @param boolean $lp
	 * 	indicates a way of object ordering
	 *
	 * @return int
	 * 	a found object id
	 */
	function getFirst($type, $lp = FALSE) {

		$orderby = $lp ? 'ListPosition' : 'Description';
		$tablename = EasyContactFormsDB::getTableName($type);
		$query = 'SELECT id FROM ' . $tablename . ' ORDER BY ' . $orderby . ' LIMIT 1';
		$result = EasyContactFormsDB::getValue($query);
		return $result;

	}

	/**
	 * 	getSign
	 *
	 * 	returns a human readable form of sql operator
	 *
	 * @param int $signid
	 * 	operator id
	 *
	 * @return string
	 * 	sql operator
	 */
	function getSign($signid) {

		if ($signid == '2') {
			return '<';
		}
		if ($signid == '4') {
			return '<=';
		}
		if ($signid == '9') {
			return '<>';
		}
		if ($signid == '3') {
			return '=';
		}
		if ($signid == '1') {
			return '>';
		}
		if ($signid == '5') {
			return '>=';
		}
		if ($signid == '10') {
			return 'ANY';
		}
		if ($signid == '11') {
			return 'BETWEEN';
		}
		if ($signid == '7') {
			return 'IN';
		}
		if ($signid == '8') {
			return 'LIKE';
		}
		if ($signid == '6') {
			return 'NOT';
		}
		if ($signid == '12') {
			return 'NOT IN';
		}
		return ' = ';

	}

	/**
	 * 	getSignSList
	 *
	 * 	returns a list of comparison operators
	 *
	 * @param string $group
	 * 	a name of operator group
	 * @param boolean $useany
	 * 	allows/disallows the 'ANY' operator
	 *
	 * @return array
	 * 	comparison operators
	 */
	function getSignSList($group, $useany) {

		$signs = array();
		switch ($group) {
			case 'string':

				$signs = array(
					'8' => EasyContactFormsT::get('SIGN_LIKE'),
					'3' => EasyContactFormsT::get('SIGN_EQUAL'),
				);

				break;

			case 'ref':

				$signs = array(
					'3' => EasyContactFormsT::get('SIGN_EQUAL'),
					'9' => EasyContactFormsT::get('SIGN_NOT_EQUAL'),
				);

				break;

			case 'bool':

				$signs = array(
					'10' => EasyContactFormsT::get('SIGN_ANY'),
					'3' => EasyContactFormsT::get('SIGN_EQUAL'),
				);

				break;

			case 'date':

				$signs = array(
					'1' => EasyContactFormsT::get('SIGN_MORE'),
					'5' => EasyContactFormsT::get('SIGN_MOREOREQUAL'),
					'2' => EasyContactFormsT::get('SIGN_LESS'),
					'4' => EasyContactFormsT::get('SIGN_LESSOREQUAL'),
				);

				break;

			case 'general':

				$signs = array(
					'3' => EasyContactFormsT::get('SIGN_EQUAL'),
					'1' => EasyContactFormsT::get('SIGN_MORE'),
					'5' => EasyContactFormsT::get('SIGN_MOREOREQUAL'),
					'2' => EasyContactFormsT::get('SIGN_LESS'),
					'4' => EasyContactFormsT::get('SIGN_LESSOREQUAL'),
					'9' => EasyContactFormsT::get('SIGN_NOT_EQUAL'),
				);

				break;

		}
		if ($useany && $group != 'bool') {
			$signs[10] = EasyContactFormsT::get('SIGN_ANY');
		}
		$options = '';
		foreach ($signs as $key => $value) {
			$options .= "<option value = '$key' > $value</option > \n";
		}
		return $options;

	}

	/**
	 * 	getStdOrder
	 *
	 * 	prepares a sort field list
	 *
	 * @param array $orders
	 * 	a predefined list of order fields
	 * @param array $filterarray
	 * 	an array containing predefined table order settings
	 *
	 * @return array
	 * 	sort list
	 */
	function getStdOrder($orders, $filterarray = NULL) {

		$stdfilters = ($filterarray == NULL)?array():$filterarray;
		foreach ($orders as $order) {
			$stdfilters[$order->property] = $order->direction;
		}
		return $stdfilters;

	}

	/**
	 * 	getStdFilters
	 *
	 * 	prepares an array of filters
	 *
	 * @param array $filters
	 * 	an array containg a requested set of filters
	 * @param array $filterarray
	 * 	an array containig a predefined set of filters
	 *
	 * @return array
	 * 	the prepared filter list
	 */
	function getStdFilters($filters, $filterarray = NULL) {

		$stdfilters = is_null($filterarray) ? array() : $filterarray;
		foreach ($filters as $filter) {
			$stdfilters[$filter->property] = $filter->value;
		}
		return $stdfilters;

	}

	/**
	 * 	getMTMFilter
	 *
	 * 	peforms many-to-many table filterfing
	 *
	 * @param array $map
	 * 	request data
	 * @param array $filters
	 * 	filter list
	 * @param string $alias
	 * 	object alias to use when preparing filter statements
	 *
	 * @return array
	 * 	an array containig mtm filters
	 */
	function getMTMFilter($map, $filters, $alias) {

		if (!isset($map['a'])) {
			return $filters;
		}

		$jsparams = json_decode(stripslashes($map['a']));
		if ($jsparams->m != 'mtmview') {
			return $filters;
		}

		foreach ($jsparams->ca as $obj) {
			$mobj = EasyContactFormsClassLoader::getObject($obj->mt);

			$tablename = $mobj->getTableName();
			$fields = $mobj->getFieldNames();
			if (!in_array($obj->n, $fields)) {
				continue;
			}
			if (!in_array($obj->fld, $fields)) {
				continue;
			}
			$objid = intval($obj->oid);
			if (!isset($filters['fnames'])) {
				$filters['fnames'] = array();
			}
			$filters['fnames'][] = $alias . '.id NOT IN (SELECT ' .
				$obj->n . ' FROM ' . $tablename .
				" WHERE {$obj->fld} = '{$objid}')";
		}

		return $filters;

	}

	/**
	 * 	getSignFilter
	 *
	 * 	prepares a comparison operator based statement, reading the operator
	 * 	value from the incoming request
	 *
	 * @param array $filters
	 * 	a filter array to add values to
	 * @param array $rparams
	 * 	incoming request filters
	 * @param string $alias
	 * 	object alias to use when building the statement
	 * @param string $pttype
	 * 	object type
	 * @param string $datatype
	 * 	data type
	 * @param string $defaultvalue
	 * 	default value
	 *
	 * @return array
	 * 	comparison statement
	 */
	function getSignFilter($filters, $rparams, $alias, $pttype, $datatype = NULL, $defaultvalue = NULL) {

		$novalue = (!isset($rparams[$pttype]) || empty($rparams[$pttype])); 
		if ($novalue) {
			return $filters;
		}
		$value = $rparams[$pttype];
		if (!isset($value->sign) || empty($value->sign)) {
			return $filters;
		}

		$sign = intval($value->sign);
		if ($sign == '10') {
			return $filters;
		}

		$sign = EasyContactFormsDB::getSign($sign);

		return EasyContactFormsDB::getFilter($filters, $rparams, $alias, $pttype, $sign, $datatype, $defaultvalue);

	}

	/**
	 * 	getFilter
	 *
	 * 	prepares a comparison operator based statement, reading the operator
	 * 	value from the incoming request
	 *
	 * @param array $filters
	 * 	a filter array to add values to
	 * @param array $rparams
	 * 	incoming request filters
	 * @param string $alias
	 * 	object alias to use when building the statement
	 * @param string $pttype
	 * 	object type
	 * @param string $sign
	 * 	an operator to use when building the statement
	 * @param string $datatype
	 * 	data type
	 * @param string $defaultvalue
	 * 	default value
	 *
	 * @return array
	 * 	comparison statement
	 */
	function getFilter($filters, $rparams, $alias, $pttype, $sign, $datatype = NULL, $defaultvalue = NULL) {

		$novalue = (!isset($rparams[$pttype]) || empty($rparams[$pttype])) && is_null($defaultvalue); 
		if ($novalue) {
			return $filters;
		}
		if (isset($rparams[$pttype]) && !empty($rparams[$pttype])) {
			$value = $rparams[$pttype];
		}
		else {
			$value =$defaultvalue;
		}
		if (!isset($filters['fnames'])) {
			$filters['fnames'] = array();
			$filters['fvalues'] = array();
		}

		$lalias = $alias . $pttype;
		$falias = str_replace('.', '_', $lalias);

		if ($sign == 'BETWEEN') {
			$filters['fnames'][] = "$lalias BETWEEN :$falias1 AND :$falias2";
			return $filters;
			$filters['fvalues'][":$falias1"]
				= EasyContactFormsUtils::convert($value->values[0], $datatype, TRUE);
			$filters['fvalues'][":$falias2"]
				= EasyContactFormsUtils::convert($value->values[1], $datatype, TRUE);
			return $filters;
		}

		if ($sign == 'IN' || $sign == 'NOT IN') {
			$filters['fnames'][] = "$lalias $sign (:$falias)";
			$values = array();
			foreach ($value->values as $vvalue) {
				$values[] = EasyContactFormsUtils::convert($vvalue, $datatype, TRUE);
			}
			$filters['fvalues'][":$falias"] = $values;
			return $filters;
		}

		$value = EasyContactFormsUtils::convert($value->values[0], $datatype, TRUE);

		if ($sign == 'LIKE') {
			$filters['fnames'][] = "$lalias LIKE :$falias";
			$filters['fvalues'][":$falias"] = "%$value%";
			return $filters;
		}

		$filters['fnames'][] = "$lalias $sign :$falias";
		$filters['fvalues'][":$falias"] = $value;

		return $filters;

	}

	/**
	 * 	getWhere
	 *
	 * 	build the where part
	 *
	 * @param array $filters
	 * 	a set of filters to use for building
	 * @param array $viewmap
	 * 	not used
	 * @param string $alias
	 * 	not used
	 *
	 * @return string
	 * 	a where statement
	 */
	function getWhere($filters, $viewmap = NULL, $alias = NULL) {

		if (is_null($filters)) {
			return '';
		}

		if (!isset($filters['fnames'])) {
			return '';
		}
		if (count($filters['fnames']) == 0) {
			return '';
		}

		$fnames = implode(' AND ', $filters['fnames']);
		$prefix = isset($filters['skipWhere']) ? ' AND ' : ' WHERE ';

		return $prefix . $fnames;

	}

	/**
	 * 	getOrderBy
	 *
	 * 	build the order by part
	 *
	 * @param array $fields
	 * 	a list of fields to use
	 * @param array $orders
	 * 	an incoming sort request
	 * @param string $defaultorder
	 * 	default order by statement
	 *
	 * @return string
	 * 	the build ORDER BY
	 */
	function getOrderBy($fields, $orders, $defaultorder = '') {

		$orderby = '';
		$comma = '';
		foreach ($orders as $key => $value) {
			$ovalue = $value == 'DESC' ? ' DESC' : ' ASC';
			if (! in_array($key, $fields)) {
				continue;
			}
			$orderby .= $comma . $key . $ovalue;
			$comma = ', ';
		}
		$comma = (($orderby != '') && ($defaultorder != '')) ? ', ' : '';
		$orderby .= $comma . $defaultorder;
		$header = ($orderby == '') ? '' : 'ORDER BY ';
		return $header . $orderby;

	}

	/**
	 * 	getRowCount
	 *
	 * 	returns a count of rows
	 *
	 * @param string $rcsql
	 * 	a statement to return a count of rows for
	 * @param array $filters
	 * 	a set of applied filters
	 *
	 * @return int
	 * 	row count
	 */
	function getRowCount($rcsql, $filters = NULL) {

		list($first, $second) = explode('FROM', $rcsql, 2);
		$rcquery = 'SELECT COUNT(*) AS c FROM ' . $second;
		$rcresult = EasyContactFormsDB::select($rcquery, $filters);
		return $rcresult[0]->c;

	}

	/**
	 * 	getQueryText
	 *
	 * 	creates a parameter-value based sql query
	 *
	 * @param string $query
	 * 	sql text
	 * @param array $params
	 * 	parameter key => values pairs
	 *
	 * @return string
	 * 	sql text
	 */
	function getQueryText($query, $params) {

		if (isset($params)) {
			foreach ($params as $key => $value) {
				$query = str_replace('%' . $key,
				EasyContactFormsUtils::addMSlashes($value),
				$query);
			}
		}
		return $query;

	}

	/**
	 * 	getTableName
	 *
	 * 	return an object table name
	 *
	 * @param string $objname
	 * 	object type name
	 *
	 * @return string
	 * 	table name
	 */
	function getTableName($objname) {

		return '#wp__easycontactforms_' . strtolower($objname) . '';

	}

	/**
	 * 	updateListPosition
	 *
	 * 	updates an object list position value right after object is created
	 *
	 * @param string $type
	 * 	object type name
	 * @param int $id
	 * 	object id
	 */
	function updateListPosition($type, $id) {

		$valuemap = array();
		$valuemap['ListPosition'] = $id;
		EasyContactFormsDB::update($valuemap, $type, $id);

	}

}
