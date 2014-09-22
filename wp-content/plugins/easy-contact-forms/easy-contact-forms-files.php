<?php

/**
 * @file
 *
 * 	EasyContactFormsFiles class definition
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
 * 	EasyContactFormsFiles
 *
 */
class EasyContactFormsFiles extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsFiles class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'Files';

		$this->fieldmap = array(
				'id' => NULL,
				'Doctype' => '',
				'Docfield' => '',
				'Docid' => 0,
				'Name' => '',
				'Type' => '',
				'Size' => 0,
				'Protected' => 0,
				'Webdir' => 0,
				'Count' => 0,
				'Storagename' => '',
				'ObjectOwner' => 0,
			);

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a file record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		EasyContactFormsFiles::deletefile($id);

		$stmts[] = "DELETE FROM #wp__easycontactforms_files WHERE id='$id';";

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

		$request = EasyContactFormsUtils::parseRequest($request, 'Docid', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'Size', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'Protected', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'Webdir', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'Count', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'ObjectOwner', 'int');

		parent::update($request, $id);

	}

	/**
	 * 	deletedocfile
	 *
	 * 	deletes a file accosiated with a particular object
	 *
	 * @param array $map
	 * 	request data
	 */
	function deletedocfile($map) {

		$values = array();
		$values[':docid'] = intval($map['oid']);
		$values[':docfield'] = $map['fld'];
		$values[':doctype'] = $map['t'];

		$query = "SELECT
				id
			FROM
				#wp__easycontactforms_files
			WHERE
				Docid=:docid
				AND Docfield=:docfield
				AND Doctype=:doctype";

		$fileid = EasyContactFormsDB::select($query, array('fvalues' => $values));
		if (count($fileid) == 0) {
			return;
		}
		$fileid = $fileid[0]->id;
		EasyContactFormsFiles::deletefile($fileid);
		$query = "DELETE FROM #wp__easycontactforms_files WHERE id='$fileid';";
		EasyContactFormsDB::query($query);

	}

	/**
	 * 	deletefile
	 *
	 * 	deletes a file
	 *
	 * @param int $id
	 * 	file id
	 */
	function deletefile($id) {

		$query = "SELECT
				Doctype,
				Storagename,
				Docfield,
				Docid,
				Webdir,
				Protected
			FROM
				#wp__easycontactforms_files
			WHERE
				id='$id'";

		$filespec = EasyContactFormsDB::getObjects($query);
		if (count($filespec) == 0) {
			return;
		}
		$webdir = $filespec[0]->Webdir;
		if ($webdir) {
			EasyContactFormsFiles::deletedirfile($filespec);
		}
		else {
			EasyContactFormsFiles::deletepfile($filespec);
		}

	}

	/**
	 * 	deletepfile
	 *
	 * 	deletes a regular file
	 *
	 * @param object $filespec
	 * 	file data
	 */
	function deletepfile($filespec) {

		$ds = DIRECTORY_SEPARATOR;
		$storagename = $filespec[0]->Storagename;
		$filedir = EASYCONTACTFORMS__fileUploadDir;
		$filepath = $filedir . $ds . $storagename;
		if (is_file($filepath)) {
			unlink($filepath);
		}

	}

	/**
	 * 	deletedirfile
	 *
	 * 	deletes a file from a web directory
	 *
	 * @param object $filespec
	 * 	file data
	 */
	function deletedirfile($filespec) {

		$ds = DIRECTORY_SEPARATOR;
		$filepath
			= EASYCONTACTFORMS__fileUploadDir . $ds .
			$filespec[0]->Doctype . $ds .
			$filespec[0]->Docid . $ds .
			$filespec[0]->Docfield . $ds .
			$filespec[0]->Storagename;

		if (is_file($filepath)) {
			unlink($filepath);
		}

		$dir
			= EASYCONTACTFORMS__fileUploadDir . $ds .
			$filespec[0]->Doctype . $ds .
			$filespec[0]->Docid . $ds .
			$filespec[0]->Docfield . $ds;

		$filepath = $dir . $ds . 'index.html';
		if (is_file($filepath)) {
			unlink($filepath);
		}
		if (is_dir($dir)) {
			@rmdir($dir);
		}

		$dir
			= EASYCONTACTFORMS__fileUploadDir . $ds .
			$filespec[0]->Doctype . $ds .
			$filespec[0]->Docid;

		if (EasyContactFormsUtils::hasMoreFiles($dir, array('index.html'))) {
			return;
		}

		$filepath = $dir . $ds . 'index.html';
		if (is_file($filepath)) {
			unlink($filepath);
		}
		if (is_dir($dir)) {
			@rmdir($dir);
		}

	}

	/**
	 * 	getFileValue
	 *
	 * 	check if a file associated with an object exists, and returns a
	 * 	selected value
	 *
	 * @param string $field
	 * 	field name
	 * @param string $docfield
	 * 	file field name
	 * @param object $obj
	 * 	object
	 *
	 * @return arbitrary
	 * 	the selected value
	 */
	function getFileValue($field, $docfield, $obj) {

		$query = "SELECT $field FROM #wp__easycontactforms_files WHERE Doctype='" . $obj->type . "' AND Docfield='$docfield' AND Docid='" . $obj->getId() . "'";

		return EasyContactFormsDB::getValue($query);

	}

	/**
	 * 	fileNotFound
	 *
	 * 	prints a 'file not found' message
	 *
	 * @param array $_map
	 * 	request data
	 */
	function fileNotFound($_map) {

		echo EasyContactFormsIHTML::showMessage(
			EasyContactFormsT::get('FileNotFoundInDownloads'),
			'warningMessage');
		exit;

	}

	/**
	 * 	download
	 *
	 * 	file download
	 *
	 * @param array $_map
	 * 	request data
	 */
	function download($_map) {

		$id = '';
		$query = '';
		if (isset($_map['oid'])) {
			$id = intval($_map['oid']);
			$query = 'SELECT * FROM #wp__easycontactforms_files WHERE id=\'' . $id . '\' AND Webdir=FALSE';
		}
		else {
			EasyContactFormsFiles::fileNotFound($_map);
		}

		$token = isset($_map['token']) ? $_map['token'] : '' ;
		$md5 = md5(EasyContactFormsSecurityManager::getServerPwd() . $id);
		if (isset($_map['token']) && $md5 != $token) {
			EasyContactFormsFiles::fileNotFound($_map);
		}
		if (!isset($_map['token']) && (!isset($_map['easycontactusr']) || $_map['easycontactusr']->id == 0 )) {
			EasyContactFormsIHTML::getNotLoggedInHTML();
			exit;
		}

		$response = EasyContactFormsDB::getObjects($query);
		if ((EasyContactFormsDB::err()) || (count($response) == 0)) {
				EasyContactFormsFiles::fileNotFound($_map);
		}

		$ds = DIRECTORY_SEPARATOR;
		$downloaddir = EASYCONTACTFORMS__fileUploadDir;

		$Count = intval($response[0]->Count);
		$Size = $response[0]->Size;
		$Type = $response[0]->Type;
		$Name = $response[0]->Name;
		$filepath = $downloaddir . $ds . $response[0]->Storagename;
		if (!is_file($filepath)) {
			EasyContactFormsFiles::fileNotFound($_map);
		}
		header("Content-length: $Size");
		header("Content-type: $Type");
		header("Content-Disposition: attachment; filename=$Name");
		readfile($filepath);
		$valuemap = array();
		$valuemap['Count'] = ++$Count;
		EasyContactFormsDB::update($valuemap, 'Files', $response[0]->id);
		exit;

	}

	/**
	 * 	getFileDownloadLink
	 *
	 * 	builds a file download link
	 *
	 * @param string $doctype
	 * 	object type
	 * @param string $docfield
	 * 	object field
	 * @param int $docid
	 * 	object id
	 * @param  $open
	 * 
	 *
	 * @return string
	 * 	file uri
	 */
	function getFileDownloadLink($doctype, $docfield, $docid, $open = FALSE) {

		$query = "SELECT
				id,
				Name,
				Webdir
			FROM
				#wp__easycontactforms_files
			WHERE
				Docid='$docid'
				AND Docfield='$docfield'
				AND Doctype='$doctype'";

		$filedata = EasyContactFormsDB::getObjects($query);
		if (sizeof($filedata) == 0) {
			return FALSE;
		}
		$filedata = $filedata[0];
		$ds = DIRECTORY_SEPARATOR;
		if ($filedata->Webdir) {
			return EASYCONTACTFORMS__FILE_DONWLOAD . '/' .
			EASYCONTACTFORMS__fileFolder . '/' .
			$doctype . '/' .
			$docid . '/' .
			$docfield . '/' .
			$filedata->Name;
		}
		else {
			if ($open) {
				$md5 = md5(EasyContactFormsSecurityManager::getServerPwd() . $filedata->id);
				return EASYCONTACTFORMS__engineRoot .
					'&m=download&oid=' . $filedata->id . '&token=' . $md5;
			}
			else {
				return EASYCONTACTFORMS__engineRoot .
					'&m=download&oid=' . $filedata->id;
			}
		}

	}

	/**
	 * 	upload
	 *
	 * 	uploads a file
	 *
	 * @param array $_uldmap
	 * 	request data
	 */
	function upload($_uldmap) {

		if (isset($_uldmap["webdirupload"]) && $_uldmap["webdirupload"] == "on") {
			EasyContactFormsFiles::webdirUpload($_uldmap);
		}
		else {
			EasyContactFormsFiles::protectedUpload($_uldmap);
		}

	}

	/**
	 * 	protectedUpload
	 *
	 * 	takes a file from a temporary folder and registers it in the file
	 * 	manager
	 *
	 * @param array $_uldmap
	 * 	request data
	 * @param array $filespecmap
	 * 	file spec
	 */
	function protectedUpload($_uldmap, $filespecmap = NULL) {

		$filespecmapnull = false;
		if (is_null($filespecmap)) {
			$filespecmapnull = true;
			$filerequestid
				= $_uldmap['t'] . '_' .
				$_uldmap['fld'] . '_' .
				$_uldmap['oid'];
			$filespecmap = $_FILES[$filerequestid];
		}

		if (!isset($filespecmap)) {
			return FALSE;
		}
		if ($filespecmap['error'] != UPLOAD_ERR_OK) {
			return FALSE;
		}
		$ds = DIRECTORY_SEPARATOR;

		$protect = 0;
		if (isset($_uldmap['protect'])) {
			$protect = ($_uldmap['protect'] == "on") ? 1 : 0;
		}
		$oowner = isset($_uldmap['easycontactusr']) ? $_uldmap['easycontactusr']->id : 0;

		$filename = $filespecmap['name'];
		$tmpname	= $filespecmap['tmp_name'];
		$filesize = $filespecmap['size'];
		$filetype = $filespecmap['type'];
		$Type = $_uldmap['t'];
		$fieldname = $_uldmap['fld'];
		$id = $_uldmap['oid'];
		$basename = EasyContactFormsUtils::subStringBefore($filename, ".");

		if ($protect &&
			(($basename == NULL) || preg_match('/^[A-Fa-f0-9]{32}$/', $basename))) {
			echo EasyContactFormsIHTML::showMessage(
				EasyContactFormsT::get('ImpossibleToPerformOperation'), 'warningMessage');
			return FALSE;
		}

		global $wpdb;

		$query = "SELECT
				Count
			FROM
				#wp__easycontactforms_files
			WHERE
				Doctype=%s
				AND Docid=%d
				AND Docfield=%s";

		$query = $wpdb->prepare($query, $Type, $id, $fieldname);
		$counter = EasyContactFormsDB::getValue($query);
		$counter = isset($counter) ? $counter : 0;

		$query = "SELECT
				id
			FROM
				#wp__easycontactforms_files
			WHERE
				Doctype=%s
				AND Docid=%d
				AND Docfield=%s";

		$query = $wpdb->prepare($query, $Type, $id, $fieldname);
		$fileid = EasyContactFormsDB::getValue($query);
		if (isset($fileid)) {
			EasyContactFormsFiles::deletefile($fileid);
			EasyContactFormsFiles::delete($fileid);
		}
		$file = EasyContactFormsClassLoader::getObject('Files', true);
		$file->set('Count', $counter);
		$file->set('Docfield', $fieldname);
		$file->set('Doctype', $Type);
		$file->set('Docid', $id);
		$file->set('Name', $filename);
		$file->set('Size', $filesize);
		$file->set('Type', $filetype);
		$file->set('Protected', $protect);
		$file->set('Webdir', 0);
		$file->set('ObjectOwner', $oowner);

		$filespec = (object) array();
		$filespec->protect = $protect;
		$filespec->fieldname = $fieldname;
		$filespec->docType = $Type;
		$filespec->filename = $filename;

		if ($Type == "Files") {
			$filespec->id = $file->get('id');
			$Storagename = $file->getStorageFileName($filespec);
			$file->set('Storagename', $Storagename);
			$file->set('Docid', $file->get('id'));
		}
		else {
			$filespec->id = $id;
			$Storagename = $file->getStorageFileName($filespec);
			$file->set('Storagename', $Storagename);
		}
		$file->save();

		$filedirectory = EASYCONTACTFORMS__fileUploadDir;
		if (!is_dir($filedirectory)) {
			if (!EasyContactFormsUtils::createFolder($filedirectory)) {
				return FALSE;
			}
		}
		$newpath = $filedirectory . $ds . $Storagename;

		if ($filespecmapnull) {
			if (!move_uploaded_file($tmpname, $newpath)) {
				return FALSE;
			}
		}
		else {
			rename($tmpname, $newpath);
		}
		return TRUE;

	}

	/**
	 * 	getStorageFileName
	 *
	 * 	return a new file name
	 *
	 * @param object $filespec
	 * 	file data
	 *
	 * @return string
	 * 	file name
	 */
	function getStorageFileName($filespec) {

		$storagename
			= $filespec->docType . '_' .
			$filespec->id . '_' .
			$filespec->fieldname . '_' .
			$filespec->filename ;
		if (!$filespec->protect) {
			return $storagename;
		}
		$strarr = explode(".", $filespec->filename);
		$ext = $strarr[count($strarr) - 1];
		$md5name = md5(EasyContactFormsSecurityManager::getServerPwd() . $storagename . 'easycontactforms');
		$newfilename = $storagename . '_' . $md5name . '.' . $ext;
		return $newfilename;

	}

	/**
	 * 	webdirUpload
	 *
	 * 	takes a file from a temporary folder, registers it in the file
	 * 	manager
	 * 	places the file to a web directory for direct download and makes a
	 * 	thumbnail
	 * 	copy if it is necessary
	 *
	 * @param array $_uldmap
	 * 	request data
	 */
	function webdirUpload($_uldmap) {

		$filerequestid = $_uldmap['t'] . '_' . $_uldmap['fld'] . '_' . $_uldmap['oid'];
		if ($_FILES[$filerequestid]['error'] != UPLOAD_ERR_OK) {
			return FALSE;
		}

		$oowner = $_uldmap['easycontactusr']->id;

		$filename = $_FILES[$filerequestid]['name'];
		$tmpname	= $_FILES[$filerequestid]['tmp_name'];
		$filesize = $_FILES[$filerequestid]['size'];
		$filetype = mysql_real_escape_string($_FILES[$filerequestid]['type']);

		$id = intval($_uldmap['oid']);
		$Type = mysql_real_escape_string($_uldmap['t']);
		$fieldname = mysql_real_escape_string($_uldmap['fld']);
		$filename = mysql_real_escape_string($filename);

		$ds = DIRECTORY_SEPARATOR;

		$targdir = EASYCONTACTFORMS__fileUploadDir . $ds . $Type . $ds . $id . $ds . $fieldname;

		$query = "SELECT Name FROM #wp__easycontactforms_files WHERE Doctype='$Type' AND Docid='$id' AND Docfield='$fieldname'";

		$name = EasyContactFormsDB::getValue($query);

		$filepath = $targdir . $ds . $name;
		if (is_file($filepath)) {
			unlink($filepath);
		}
		$filepath = $targdir . $ds . $filename;

		$query = "DELETE FROM #wp__easycontactforms_files WHERE Doctype='$Type' AND Docid='$id' AND Docfield='$fieldname'";
		EasyContactFormsDB::query($query);

		$valuemap = array();
		$valuemap['Count'] = '0';
		$valuemap['Docfield'] = $fieldname;
		$valuemap['Doctype'] = $Type;
		$valuemap['Docid'] = $id;
		$valuemap['Name'] = $filename;
		$valuemap['Size'] = $filesize;
		$valuemap['Type'] = $filetype;
		$valuemap['Protected'] = 0;
		$valuemap['Webdir'] = 1;
		$valuemap['Storagename'] = $filename;
		$valuemap['ObjectOwner'] = $oowner;

		$isid = EasyContactFormsDB::insert($valuemap, 'Files');
		if ($Type == 'Files') {
			$valuemap = array();
			$valuemap['Docid'] = $isid;
			EasyContactFormsDB::update($valuemap, 'Files', $isid);
		}

		if (!is_dir($targdir)) {
			EasyContactFormsUtils::createFolder($targdir);
		}

		move_uploaded_file($tmpname, $filepath);

		if (isset($_uldmap['thumbnailx']) && intval($_uldmap['thumbnailx']) != 0) {
			$newfieldname = 'thumb' . $fieldname;
			$newfilename = 'thumb' . $filename;
			$newtargdir = EASYCONTACTFORMS__fileUploadDir . $ds . $Type . $ds . $id . $ds . $newfieldname;

			$query = "SELECT Name FROM #wp__easycontactforms_files WHERE Doctype='$Type' AND Docid='$id' AND Docfield='thumb$fieldname'";

			$name = EasyContactFormsDB::getValue($query);
			if (is_file($newtargdir . $ds . $name)) {
				unlink($newtargdir . $ds . $name);
			}

			EasyContactFormsUtils::createFolder($newtargdir);

			EasyContactFormsFiles::imgResize($filepath, $newtargdir . $ds . $newfilename, $_uldmap['thumbnailx'], $_uldmap['thumbnaily'], 0xFFFFFF, 80);

			$query = "DELETE FROM #wp__easycontactforms_files WHERE Doctype='$Type' AND Docid='$id' AND Docfield='$newfieldname'";

			EasyContactFormsDB::query($query);

			$valuemap = array();
			$valuemap['Count'] = '0';
			$valuemap['Docfield'] = $newfieldname;
			$valuemap['Doctype'] = $Type;
			$valuemap['Docid'] = $id;
			$valuemap['Name'] = $newfilename;
			$valuemap['Size'] = filesize($newtargdir . $ds . $newfilename);
			$valuemap['Type'] = $filetype;
			$valuemap['Protected'] = 0;
			$valuemap['Webdir'] = 1;
			$valuemap['Storagename'] = $newfilename;
			$valuemap['ObjectOwner'] = $oowner;

			EasyContactFormsDB::insert($valuemap, 'Files');
		}
		if (isset($_uldmap['resizex']) && intval($_uldmap['resizex']) != 0) {
			EasyContactFormsFiles::imgResize($filepath, $filepath, $_uldmap['resizex'], $_uldmap['resizey'], 0xFFFFFF, 80);
			$valuemap = array();
			$valuemap['Size'] = filesize($filepath);
			EasyContactFormsDB::update($valuemap, 'Files', $isid);
		}
		echo json_encode(array('success' => 'TRUE'));
		return TRUE;

	}

	/**
	 * 	imgResize
	 *
	 * 	resizes an image based on defined parameters and crates a jpg
	 * 	thumbnail image
	 *
	 * @param string $src
	 * 	source image path
	 * @param string $dest
	 * 	destination image path
	 * @param int $width
	 * 	destination image width
	 * @param int $height
	 * 	destination image height
	 * @param int $rgb
	 * 	initial color
	 * @param int $quality
	 * 	jpg image quality
	 *
	 * @return boolean
	 * 	TRUE if succees, FALSE otherwise
	 */
	function imgResize($src, $dest, $width, $height, $rgb = 0xFFFFFF, $quality = 80) {

		if ($height == 0) {
			return FALSE;
		}

		if (!file_exists($src)) {
			return FALSE;
		}
		$size = getimagesize($src);

		if ($size === FALSE) {
			return FALSE;
		}

		$format = strtolower(substr($size['mime'], strpos($size['mime'], '/') + 1));

		$icfunc = 'imagecreatefrom' . $format;
		if (!function_exists($icfunc)) {
			return FALSE;
		}

		$targprop = $width/$height;
		$imgprop = $size[0]/$size[1];

		$min = min($targprop, $imgprop);

		$usex = FALSE;
		$usey = FALSE;

		if ($min == $imgprop) {
			$ratio = $width/$size[0];
			$usey = TRUE;
		}
		else {
			$ratio = $height/$size[1];
			$usex = TRUE;
		}

		$new_width = floor($size[0] * $ratio);
		$new_height = floor($size[1] * $ratio);

		$new_left = $usex ? 0 : floor(($width - $new_width) / 2);
		$new_top = $usey ? 0 : floor(($height - $new_height) / 2);

		$isrc = $icfunc($src);
		$idest = imagecreatetruecolor($width, $height);
		imagefill($idest, 0, 0, $rgb);
		imagecopyresampled($idest, $isrc, $new_left, $new_top, 0, 0, $new_width, $new_height, $size[0], $size[1]);
		imagejpeg($idest, $dest, $quality);
		imagedestroy($isrc);
		imagedestroy($idest);
		return TRUE;

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

			case 'deletefile':
				$this->deletefile($dispmap);
				return NULL;

			case 'download':
				$this->download($dispmap);
				return NULL;

			case 'upload':
				$this->upload($dispmap);
				return NULL;

			default : return $dispmap;
		}

	}

}
