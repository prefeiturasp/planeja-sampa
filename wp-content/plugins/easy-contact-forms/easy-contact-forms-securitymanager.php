<?php

/**
 * @file
 *
 * 	EasyContactFormsSecurityManager class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

/**
 * 	EasyContactFormsSecurityManager
 *
 * 	Security and access functions
 *
 */
class EasyContactFormsSecurityManager {

	/**
	 * 	getInstance
	 *
	 * 	Returns an instance of security manager
	 *
	 *
	 * @return object
	 * 	a security manager instance
	 */
	function getInstance() {

		static $sminstance;
		if (!isset($sminstance)) {
			$sminstance = new EasyContactFormsSecurityManager();
		}
		return $sminstance;

	}

	/**
	 * 	getGuest
	 *
	 * 	returns a guest role object
	 *
	 *
	 * @return object
	 * 	the guest role object
	 */
	function getGuest() {

		$role = (object) array();
		$role->id = 4;
		$role->Description = 'Guest';
		$user = (object) array();
		$user->id = 0;
		$user->role = $role;
		return $user;

	}

	/**
	 * 	getRights
	 *
	 * 	inits a 'easycontactusr' variable with the current user role
	 *
	 * @param array $_ssmap
	 * 	request data
	 */
	function getRights($_ssmap) {

		$_ssmap['easycontactusr'] = EasyContactFormsSecurityManager::getGuest();

		$foreignid = intval($_ssmap['frid']);
		if ($foreignid == 0) {
			return $_ssmap;
		}
		if (isset($_ssmap['m'])) {
			$m = $_ssmap['m'];
			$sm = addcslashes($m, chr(34) . chr(39) . chr(92) . "&;%<>");
			$sm = str_replace(' ' , '', $sm);
			if ($sm != $m) {
				unset($_ssmap['m']);
				$_ssmap['m']='&';
				return $_ssmap;
			}
		}

		$query = "SELECT
				Users.Role AS roleid,
				Users.id
			FROM
				#wp__easycontactforms_users AS Users
			WHERE
				Users.CMSId='$foreignid'";

		$usr = EasyContactFormsDB::getObjects($query);
		if (EasyContactFormsDB::err()) {
			return $_ssmap;
		}

		if (count($usr) == 0) {
			return $_ssmap;
		}

		$usr = $usr[0];

		$query = "SELECT * FROM #wp__easycontactforms_roles AS Roles WHERE Roles.id = '" . $usr->roleid . "'";
		$role = EasyContactFormsDB::getObjects($query);

		if (EasyContactFormsDB::err()) {
			return $_ssmap;
		}
		if (count($role) == 0) {
			return $_ssmap;
		}

		$usr->role = (object) array();
		$usr->role->Description = $role[0]->Description;
		$usr->role->id = $role[0]->id;
		unset($usr->roleid);

		$_ssmap['easycontactusr'] = $usr;
		return $_ssmap;

	}

	/**
	 * 	roleObjectCheck
	 *
	 * 	performs a simple check if users of current user role have access to
	 * 	a selected object type
	 *
	 * @param array $_cmmap
	 * 	request data
	 *
	 * @return boolean
	 * 	TRUE if they have, FALSE if they do not
	 */
	function roleObjectCheck($_cmmap) {

		$obj = EasyContactFormsClassLoader::getObject($_cmmap['t']);
		$userrole = $_cmmap['easycontactusr']->role->Description;

		$query = "SELECT
				Count(id)
			FROM
				#wp__easycontactforms_acl
			WHERE
				objtype='{$obj->type}'
				AND role='$userrole'";

		$value = EasyContactFormsDB::getValue($query);
		return ($value > 0);

	}

	/**
	 * 	getYouAreNotLoggedInMessage
	 *
	 * 	Prints a 'not logged in message'
	 *
	 */
	function getYouAreNotLoggedInMessage() {

		require_once 'easy-contact-forms-ihtml.php';
		return EasyContactFormsIHTML::getNotLoggedInHTML();

	}

	/**
	 * 	getViewName
	 *
	 * 	Finds a name of an object view indended for a current user role
	 *
	 * @param array $_vnmap
	 * 	request data
	 *
	 * @return string
	 * 	view name
	 */
	function getViewName($_vnmap) {

		$objecttype = $_vnmap['t'];
		$vnmethod = $_vnmap['m'];

		if (empty($objecttype)) {
			return '';
		}
		if (empty($vnmethod)) {
			return '';
		}

		switch ($vnmethod) {
			case 'show':
			case 'new':
			case 'view':
			case 'viewDetailed':
				return EasyContactFormsSecurityManager::getObjectViewName($_vnmap);
				break;
			default:
				return EasyContactFormsSecurityManager::getObjectMethodViewName($_vnmap);
		}

	}

	/**
	 * 	getObjectMethodViewName
	 *
	 * 	returns a view name
	 *
	 * @param array $_cmmap
	 * 	Request data
	 *
	 * @return string
	 * 	the name
	 */
	function getObjectMethodViewName($_cmmap) {

		return EasyContactFormsSecurityManager::getObjectViewName($_cmmap);

	}

	/**
	 * 	isObjectOwner
	 *
	 * 	Check if a current user may play as object's owner
	 *
	 * @param string $objtype
	 * 	object type
	 * @param int $objid
	 * 	object id
	 * @param int $usrid
	 * 	user id
	 *
	 * @return boolean
	 * 	TRUE if he may, FALSE if he may not
	 */
	function isObjectOwner($objtype, $objid, $usrid) {

		$xml = EASYCONTACTFORMS__APPLICATION_DIR . DIRECTORY_SEPARATOR . 'easy-contact-forms-objects.xml';
		$xml = simplexml_load_file($xml);

		$nodes = $xml->xpath('//' . $objtype);
		$node = $nodes[0];
		$childname = strtolower($node->getName());

		while (TRUE) {
			$parents = $node->xpath('..');
			$parent = $parents[0];
			$parentname = $parent->getName();
			$noparents = ($parentname == 'objects');
			$obj = EasyContactFormsClassLoader::getObject($childname, TRUE, $objid);
			if (!$obj) {
				return FALSE;
			}
			if ($obj->get('ObjectOwner') == $usrid) {
				return TRUE;
			}
			if ($noparents) {
				break;
			}
			$objid = $obj->get($parentname);
			$node = $parent;
			$childname = strtolower($parentname);
		}
		return FALSE;

	}

	/**
	 * 	getOwnerRole
	 *
	 * 	Perform additional search in the roles.xml file to find
	 * 	if there are any exceptions to the general access rules
	 *
	 * @param string $roleid
	 * 	a current user role name
	 * @param string $objtype1
	 * 	an object name the user gets access to
	 * @param string $objtype2
	 * 	a subordinated object name the user gets access to
	 *
	 * @return string
	 * 	final role name
	 */
	function getOwnerRole($roleid, $objtype1, $objtype2) {

		$xml = EASYCONTACTFORMS__APPLICATION_DIR . DIRECTORY_SEPARATOR . 'easy-contact-forms-roles.xml';
		$xml = simplexml_load_file($xml);
		$roleid = $xml->xpath("$roleid/$objtype1/$objtype2");
		$roleid = $roleid ? $roleid[0] : 'Owner';

		return $roleid;

	}

	/**
	 * 	checkRole
	 *
	 * 	Performs additional role check
	 *
	 * @param array $_ofnmap
	 * 	request data
	 *
	 * @return string
	 * 	role name
	 */
	function checkRole($_ofnmap) {

		$usr = $_ofnmap['easycontactusr'];

		if ($usr->role->Description == 'SuperAdmin') {
			return $usr->role->Description;
		}
		if ($usr->role->Description == 'Guest') {
			return $usr->role->Description;
		}

		$objtype1 = @$_ofnmap['t'];
		$objtype2 = @$_ofnmap['t'];
		$method = @$_ofnmap['m'];
		$objid = @$_ofnmap['oid'];

		if (isset($_ofnmap['specialfilter'])) {
			$sf = json_decode(stripslashes($_ofnmap['specialfilter']));
			$objtype1 = $method == 'viewDetailed' ?
				$sf[0]->property :
				$_ofnmap['n'] ;
			$objid = $sf[0]->value->values[0];
		}

		if (isset($_ofnmap['a'])) {
			$a = json_decode(stripslashes($_ofnmap['a']));
			$mtm = isset($a->m) &&
				$a->m == 'mtmview';
			if ($mtm) {
				$objtype1 = $a->ca[0]->t;
				$objid = $a->ca[0]->oid;
			}
		}

		if (!isset($objid)) {
			return $usr->role->Description;
		}

		$obj = EasyContactFormsClassLoader::getObject($objtype1);
		$fieldlist = $obj->getFieldNames();
		if (!in_array('ObjectOwner', $fieldlist)) {
			return $usr->role->Description;
		}

		if (!EasyContactFormsSecurityManager::isObjectOwner($objtype1, $objid, $usr->id)) {
			return $usr->role->Description;
		}

		$usr->role->Description = EasyContactFormsSecurityManager::getOwnerRole(
			$usr->role->Description,
			$objtype1,
			$objtype2
			);

		return $usr->role->Description;

	}

	/**
	 * 	getObjectViewName
	 *
	 * 	Returns a view name
	 *
	 * @param array $_ovnmap
	 * 	request data
	 *
	 * @return string
	 * 	a view name
	 */
	function getObjectViewName($_ovnmap) {

		$ovnmethod = $_ovnmap["m"];
		$objecttype = $_ovnmap["t"];
		$roleid = EasyContactFormsSecurityManager::checkRole($_ovnmap);

		return EasyContactFormsSecurityManager::getACLViewName($roleid, $objecttype, $ovnmethod);

	}

	/**
	 * 	getACLViewName
	 *
	 * 	Returns a view name based on a user role, object type and request
	 * 	method
	 *
	 * @param string $role
	 * 	a role name
	 * @param string $type
	 * 	an object type
	 * @param string $method
	 * 	a method name
	 *
	 * @return string
	 * 	a view name
	 */
	function getACLViewName($role, $type, $method) {

		$query = "SELECT
				name
			FROM
				#wp__easycontactforms_acl
			WHERE
				objtype='$type'
				AND role='$role'
				AND method='$method'";

		$result = EasyContactFormsDB::getValue($query);
		if (EasyContactFormsDB::err()) {
			return '';
		}
		return $result;

	}

	/**
	 * 	getServerPwd
	 *
	 * 	Returns the Appplicataion Settings SecretWord constant value
	 *
	 *
	 * @return string
	 * 	the value
	 */
	function getServerPwd() {

		return EasyContactFormsApplicationSettings::getInstance()->get('SecretWord');

	}

	/**
	 * 	getGetSessionValue
	 *
	 * @param  $map
	 * 
	 * @param  $key
	 * 
	 *
	 * @return
	 * 
	 */
	function getGetSessionValue($map, $key) {

		if (!isset($map['sid'])) {
			return NULL;
		}
		$sessid = mysql_real_escape_string($map['sid']);
		if ($sessid != $map['sid']) {
			return NULL;
		}
		if (strlen($sessid) != 32) {
			return NULL;
		}
		$sid = $sessid;
		$dbtable = EasyContactFormsDB::wptn('#wp__easycontactforms_sessions');
		$query = 'SELECT value FROM ' . $dbtable . ' WHERE sid =\'' .$sid . '\'';
		$value = EasyContactFormsDB::getValue($query);
		$xml = simplexml_load_string($value);
		return (string) $xml->$key;

	}

	/**
	 * 	setSessionValue
	 *
	 * @param  $key
	 * 
	 * @param  $value
	 * 
	 * @param  $sid
	 * 
	 *
	 * @return
	 * 
	 */
	function setSessionValue($key, $value, $sid = NULL) {

		if (is_array($sid) && isset($sid['sid'])) {
			$sessid = mysql_real_escape_string($sid['sid']);
			if ($sessid != $sid['sid']) {
				return NULL;
			}
			if (strlen($sessid) != 32) {
				return NULL;
			}
			$sid = $sessid;
		}
		else {
			return NULL;
		}
		$dbtable = EasyContactFormsDB::wptn('#wp__easycontactforms_sessions');
		$query = "SELECT id, value FROM {$dbtable} WHERE sid=%s";
		global $wpdb;
		$query = $wpdb->prepare($query, $sid);
		$row = EasyContactFormsDB::getObjects($query);
		if (count($row) == 0) {
			return NULL;
		}
		$row = $row[0];
		$rid = $row->id;
		$xml = simplexml_load_string($row->value);
		$xml->$key = $value;
		$svalue = $xml->asXML();
		$query = "UPDATE {$dbtable} SET value=%s  WHERE id=%d";
		global $wpdb;
		$query = $wpdb->prepare($query, $svalue, $rid);
		EasyContactFormsDB::query($query);
		return $sid;

	}

	/**
	 * 	getSid
	 *
	 *
	 * @return
	 * 
	 */
	function getSid() {

		if (rand(1, 10) == 9) {
			$nDaysAgo = 3;

			$query = "DELETE FROM `#wp__easycontactforms_sessions` WHERE opentime < '" . date("Y-m-d H:i:s", time()-24*60*60*$nDaysAgo) . "'";

			EasyContactFormsDB::query($query);
		}
		$pwd = EasyContactFormsSecurityManager::getServerPwd();
		$maxid = EasyContactFormsDB::getValue('SELECT MAX(id) FROM #wp__easycontactforms_sessions');
		$sid = md5(($maxid + 10) . $pwd);
		$query = 'INSERT INTO #wp__easycontactforms_sessions(sid, value) VALUES (\'' . $sid . '\', \'<data />\')';
		EasyContactFormsDB::query($query);
		return $sid;

	}

}
