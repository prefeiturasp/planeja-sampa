<?php

/**
 * @file
 *
 * 	EasyContactFormsCustomForms class definition
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
 * 	EasyContactFormsCustomForms
 *
 */
class EasyContactFormsCustomForms extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsCustomForms class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'CustomForms';

		$this->fieldmap = array(
				'id' => NULL,
				'Description' => '',
				'NotificationSubject' => '',
				'SendFrom' => '',
				'SendConfirmation' => 0,
				'ConfirmationSubject' => '',
				'ConfirmationText' => '',
				'Redirect' => 0,
				'RedirectURL' => '',
				'ShortCode' => '',
				'Template' => 0,
				'ObjectOwner' => 0,
				'SubmissionSuccessText' => '',
				'StyleSheet' => '',
				'HTML' => '',
				'SendFromAddress' => '',
				'ShowSubmissionSuccess' => 0,
				'SuccessMessageClass' => '',
				'FailureMessageClass' => '',
				'Width' => 0,
				'WidthUnit' => '',
				'LineHeight' => 0,
				'LineHeightUnit' => '',
				'FormClass' => '',
				'FormStyle' => '',
				'Style' => '',
				'ConfirmationStyleSheet' => '',
				'TotalEntries' => 0,
				'TotalProcessedEntries' => 0,
				'Impressions' => 0,
				'NotificationText' => '',
				'IncludeVisitorsAddressInReplyTo' => 0,
				'ReplyToNameTemplate' => '',
				'ConfirmationReplyToName' => '',
				'ConfirmationReplyToAddress' => '',
				'NotificationStyleSheet' => '',
				'SendConfirmationAsText' => 0,
				'SendNotificationAsText' => 0,
				'FadingDelay' => 0,
				'MessageDelay' => 0,
				'IncludeIntoReporting' => 0,
			);

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a customform
	 * 	record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		$stmts[] = "DELETE FROM #wp__easycontactforms_customforms_mailinglists WHERE CustomForms='$id';";

		$query = "SELECT id FROM #wp__easycontactforms_customformfields WHERE CustomForms='$id';";
		EasyContactFormsDB::cDelete($query, 'CustomFormFields');

		$query = "SELECT id FROM #wp__easycontactforms_customformsentries WHERE CustomForms='$id';";
		EasyContactFormsDB::cDelete($query, 'CustomFormsEntries');

		$query = "SELECT id FROM #wp__easycontactforms_customformentrystatistics WHERE CustomForms='$id';";
		EasyContactFormsDB::cDelete($query, 'CustomFormEntryStatistics');

		$stmts[] = "DELETE FROM #wp__easycontactforms_customforms WHERE id='$id';";

		return $stmts;

	}

	/**
	 * 	getEmptyObject. Overrides EasyContactFormsBase::getEmptyObject()
	 *
	 * 	creates and initializes a new CustomForm
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
		$fields->Width = 0;
		$fields->WidthUnit = 'em';
		$fields->LineHeight = 10;
		$fields->LineHeightUnit = 'em';
		$fields->Style = 'formscompressed';
		$fields->IncludeVisitorsAddressInReplyTo = TRUE;

		return parent::getEmptyObject($map, $fields);

	}

	/**
	 * 	add
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function add($map) {

		$cf = 0;
		foreach ($map as $key => $value) {
			if (!EasyContactFormsUtils::beginsWith($key, 'hidden-')) {
				continue;
			}
			$cf = intval(EasyContactFormsUtils::cutPrefix($key, 'hidden-'));
			break;
		}
		if ($cf == 0) {
			return;
		}
		$form = EasyContactFormsClassLoader::getObject('CustomForms', true, $cf);
		if (!$form) {
			return;
		}

		$query = "SELECT
				CustomFormFields.id
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.CustomForms='$cf'";

		$flds = EasyContactFormsDB::getObjects($query);
		foreach ($flds as $fldid) {
			$fld = EasyContactFormsClassLoader::getObject('CustomFormFields', true, $fldid->id);
			$form->validate($fld, $map);
		}
		$response = (object) array();
		$response->formid = "ufo-form-id-$cf";
		$status = 1;
		if (isset($form->sendBack) && $form->sendBack == TRUE) {
			if (isset($form->errors) && count($form->errors) > 0) {
				$errors = array();
				foreach ($form->errors as $fld=>$err) {
					$errors[] = "<strong>{$fld}</strong><br />{$err}";
				}
				$errors = implode('</div><div>', $errors);
				$errors = "<div>$errors</div>";

				$submitfailureclass = $form->isEmpty('FailureMessageClass') ? 'ufo-form-submit-failure' : $form->get('FailureMessageClass');

				$response->className = $submitfailureclass;
				$response->text = $errors;
			}
			$response->status = 2;
		}
		else {
			$form->newEntry($map);
			$response->status = 0;
			if ($form->get('ShowSubmissionSuccess') && !$form->isEmpty('SubmissionSuccessText')) {

				$submitsuccessclass = $form->isEmpty('SuccessMessageClass') ? 'ufo-form-submit-success' : $form->get('SuccessMessageClass');

				$response->className = $submitsuccessclass;
				$response->text = $form->get('SubmissionSuccessText');
				$response->status = 1;
			}
			$url = $form->get('RedirectURL');
			if ($form->get('Redirect') && !empty($url)) {
				$response->status = 1;
				$response->url = $url;
			}
		}
		header('Content-Type: application/javascript');
		echo (json_encode($response));

	}

	/**
	 * 	newEntry
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function newEntry($map) {

		if ($this->processSpam(!isset($map['ufo-sign']), $map)) {
			return;
		}
		$sign = isset($map['ufo-sign']) ? $map['ufo-sign'] : '';
		$time =  substr($sign, 32);
		if ($this->processSpam(md5(EasyContactFormsSecurityManager::getServerPwd() .''. $time) . ''. $time != $sign, $map)) {
			return;
		}
		$delta = time() - intval($time);

		$as = EasyContactFormsApplicationSettings::getInstance();
		$mintime = $as->get('FormCompletionMinTime');
		$maxtime = $as->get('FormCompletionMaxTime');
		if ($this->processSpam(!empty($mintime) && $delta < $mintime, $map)) {
			return;
		}
		if ($this->processSpam(!empty($maxtime) && $delta > $maxtime, $map)) {
			return;
		}
		$fldvalues = array();
		foreach ($map as $key => $value) {
			if (!EasyContactFormsUtils::beginsWith($key, 'id-')) {
				continue;
			}
			$fldid = intval(EasyContactFormsUtils::cutPrefix($key, 'id-'));
			$value = stripslashes($value);
			$fldvalues[$fldid] = htmlspecialchars($value, ENT_QUOTES);
		}
		if ($this->processSpam(count($fldvalues) == 0, $map)) {
			return;
		}
		$formid = $this->get('id');
		if ($this->processSpam(!isset($formid) || empty($formid), $map)) {
			return;
		}
		$s = implode(',', array_keys($fldvalues));

		$query = "SELECT
				CustomFormFields.id
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.CustomForms='$formid'
				AND CustomFormFields.id IN ($s)";

		$fields = EasyContactFormsDB::getObjects($query);
		if ($this->processSpam(count($fields) == 0, $map)) {
			return;
		}
		$cfe = EasyContactFormsClassLoader::getObject('CustomFormsEntries', true);
		$cfe->set('Date', current_time('mysql'));
		$cfe->set('CustomForms', $formid);
		if (isset($map['form-pagename'])) {
			$cfe->set('PageName', $map['form-pagename']);
			$fpstat = EasyContactFormsClassLoader::getObject('CustomFormEntryStatistics');
			$fpstat->increaseConversions($formid, $map['form-pagename']);
		}
		$map['_new_entry_id'] = $cfe->get('id');

		$query = "SELECT
				CustomFormFields.id,
				CustomFormFields.Settings,
				CustomFormFieldTypes.id AS tid,
				CustomFormFieldTypes.Description,
				CustomFormFieldTypes.ValueField
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			INNER JOIN
				#wp__easycontactforms_customformfieldtypes AS CustomFormFieldTypes
					ON
						CustomFormFields.Type=CustomFormFieldTypes.id
			WHERE
				CustomFormFields.CustomForms='$formid'
			ORDER BY
				CustomFormFields.ListPosition";

		$fields = EasyContactFormsDB::getObjects($query);
		$text = new EasyContactFormsSimpleXML('<div/>');
		$clientid = 0;
		if (!isset($map['ufo-preview'])) {
			if (isset($map['easycontactusr']) && isset($map['easycontactusr']->id) && !empty($map['easycontactusr']->id)) {
				$clientid = intval($map['easycontactusr']->id);
				$text->addAttribute('userid', $clientid);
			}
			global $current_user;
			$siteuserid = $current_user->ID;
			if (!empty($siteuserid)) {
				$siteuserid = intval($siteuserid);
			}
		}
		$clientemail = '';
		$emessagedata = (object) array();
		$attachments = (object) array();
		$attachments->confirmation = array();
		$attachments->notification = array();
		foreach($fields as $fld) {
			$process = $fld->ValueField || $fld->tid == 14;
			if (!$process) {
				continue;
			}
			$value = isset($fldvalues[$fld->id]) ? $fldvalues[$fld->id] : '';
			$xml = simplexml_load_string($fld->Settings);
			$default = $this->getFieldValue($xml, true, 'DefaultValue', 'SetDefaultValue');
			$required = (string) $xml->Required;
			$required = $required == 'on';
			$spam = $required && $fld->tid == 14 && $value != $default;
			if ($this->processSpam($spam, $map)) {
				return;
			}
			$spam = (!$required) && $fld->tid == 14 && $value != '';
			if ($this->processSpam($spam, $map)) {
				return;
			}
			if ($fld->tid == 14) {
				continue;
			}

			if (!($as->get('AllowMarkupInEntries') && $fld->tid == 10)) {
				$value = htmlspecialchars($value);
			}
			$validate = isset($xml->Validate) && (string) $xml->Validate == 'on';
			if ($clientemail == '' && $fld->tid == 5 && $validate) {
				$clientemail = $value;
			}
			$fldlabel = (string) $xml->Label;
			$fldlabel = $fldlabel == '' ? $fld->Description : $fldlabel;
			$displayvalue = $value;
			$displayxmlvalue = null;
			$fld = EasyContactFormsClassLoader::getObject('CustomFormFields', true, $fld->id);
			$phase = (object) array('index' => 6);
			include $fld->getTMPFileName('proc');
			if (isset($fld->_skip) && $fld->_skip === TRUE) {
				continue;
			}
			$d = $this->getTemplateTag($fld->get('Description'), $fld->get('id'));
			$d = trim($d, '{}');
			$emessagedata->$d = $displayvalue;
			$fldlabel = $text->prepare($fldlabel);
			$displayvalue = $text->prepare($displayvalue);
			$value = $text->prepare($value);
			$iddiv = $text->addChild('div');
			$iddiv->addAttribute('id', $fld->get('id'));
			$h1 = $iddiv->addChild('h1', $value);
			$h1->addAttribute('style', 'display:none');
			$ldiv = $iddiv->addChild('div');
			$label = $ldiv->addChild('label', $fldlabel);
			$label->addAttribute('class', 'ufo-cform-label');
			if (is_null($displayxmlvalue)) {
				$iddiv->addChild('div', $displayvalue);
			}
			else {
				$div = $iddiv->addChild('div');
				EasyContactFormsSimpleXML::mergeXML($div, $displayxmlvalue);
			}
		}
		$cfe->set('Content', $text->asXML());
		if (!empty($siteuserid)) {
			$cfe->set('SiteUser', $siteuserid);
		}
		$cfe->save();

		unset($text->attributes()->userid);
		foreach ($text->children() as $child) {
			unset($child->attributes()->id);
			unset($child->h1);
		}
		$text->addAttribute('class', 'ufo-form-envelope');
		$this->doEmailing($text->asCHTML(), $emessagedata, $clientemail, $clientid, $attachments);
		$update = (object) array();
		$update->TotalEntries = $this->get('TotalEntries') + 1;
		$this->update($update, $this->get('id'));

	}

	/**
	 * 	processSpam
	 *
	 * @param  $condition
	 * 
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function processSpam($condition, $map) {
		if ($condition && isset($map['_new_entry_id'])) {
			$cfe = EasyContactFormsClassLoader::getObject('CustomFormsEntries');
			$cfe->delete($map['_new_entry_id']);
		}

		return $condition;

	}

	/**
	 * 	check
	 *
	 * @param  $text
	 * 
	 * @param  $signature
	 * 
	 * @param  $pbk
	 * 
	 * @param  $b
	 * 
	 *
	 * @return
	 * 
	 */
	function check($text, $signature, $pbk, $b) {

				$set = split(' ', $signature);
				$hash = '';
				for($i=0; $i<count($set); $i++){
						$code = bcpowmod($set[$i], $pbk, $b);
						while(bccomp($code, '0') != 0){
								$ascii = bcmod($code, '256');
								$code = bcdiv($code, '256', 0);
								$hash .= chr($ascii);
						}
				}
				return ($hash == md5($text));

	}

	/**
	 * 	copy
	 *
	 * 	Copies the form
	 *
	 * @param none $map
	 * 	request data
	 */
	function copy($map) {

		$formid = intval($map['oid']);
		$form = EasyContactFormsClassLoader::getObject('CustomForms', true, $formid);
		if (!$form) {
			return '';
		}
		$newform = $form->copyForm();

	}

	/**
	 * 	copyForm
	 *
	 * 	Copies the form
	 *
	 */
	function copyForm() {

		$formid = $this->get('id');
		$newform = $this->sibling();
		$newform->save();
		$ntemplate = $newform->get('NotificationText');
		$nstemplate = $newform->get('NotificationSubject');
		$ctemplate = $newform->get('ConfirmationText');
		$cstemplate = $newform->get('ConfirmationSubject');
		$rtname = $newform->get('ReplyToNameTemplate');
		$newformid = $newform->get('id');

		$query = "SELECT
				CustomFormFields.id
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.CustomForms='$formid'
				AND CustomFormFields.Type IN (1,
					2)
			ORDER BY
				ListPosition";

		$cids = EasyContactFormsDB::getObjects($query);
		foreach ($cids as $cid) {
			$oldcid = $cid->id;
			$cfield = EasyContactFormsClassLoader::getObject('CustomFormFields', true, $oldcid);
			$container = $cfield->sibling(array('CustomForms' => $newformid, 'Description' => $cfield->get('Description')));
			$cntid = $container->get('id');
			$container->set('FieldSet', $cntid);
			$container->save();

			$query = "SELECT
							CustomFormFields.id
						FROM
							#wp__easycontactforms_customformfields AS CustomFormFields
						WHERE
							CustomFormFields.CustomForms='$formid'
							AND CustomFormFields.Type NOT IN (1,
								2)
							AND CustomFormFields.FieldSet='$oldcid'
						ORDER BY
							ListPosition";

			$fldids = EasyContactFormsDB::getObjects($query);
			foreach ($fldids as $fldid) {
				$cfield = EasyContactFormsClassLoader::getObject('CustomFormFields', true, $fldid->id);

				$sibling = $cfield->sibling(array('CustomForms' => $newformid, 'FieldSet' => $cntid, 'Description' => $cfield->get('Description')));

				$sibling->updateTemplate();

				$ntemplate = $this->getTemplateTag($cfield->get('Description'), $sibling->get('id'), $ntemplate, $cfield->get('id'));

				$nstemplate = $this->getTemplateTag($cfield->get('Description'), $sibling->get('id'), $nstemplate, $cfield->get('id'));

				$ctemplate = $this->getTemplateTag($cfield->get('Description'), $sibling->get('id'), $ctemplate, $cfield->get('id'));

				$cstemplate = $this->getTemplateTag($cfield->get('Description'), $sibling->get('id'), $cstemplate, $cfield->get('id'));

				$rtname = $this->getTemplateTag($cfield->get('Description'), $sibling->get('id'), $rtname, $cfield->get('id'));
			}
		}
		$newform->set('NotificationText', $ntemplate);
		$newform->set('NotificationSubject', $nstemplate);
		$newform->set('ConfirmationText', $ctemplate);
		$newform->set('ConfirmationSubject', $cstemplate);
		$newform->set('ReplyToNameTemplate', $rtname);
		$newform->save();
		return $newform;

	}

	/**
	 * 	Lists installed client side form styles
	 *
	 *
	 * @return
	 * 
	 */
	function getAvaliableStyles() {

		$current = $this->get('Style');;
		return $this->basicGetAvaliableStyles($current);

	}

	/**
	 * 	Lists installed client side form styles
	 *
	 * @param  $current
	 * 
	 *
	 * @return
	 * 
	 */
	function basicGetAvaliableStyles($current) {

		$ds = DIRECTORY_SEPARATOR;
		$styleroot = dirName(__FILE__) . $ds . 'forms' . $ds . 'styles';
		$dirs = array();
		$dir = dir($styleroot);
		while(($cdir = $dir->read()) !== false) {
			if($cdir != '.' && $cdir != '..' && is_dir($styleroot . $ds . $cdir)) {
				$selected = $cdir == $current ? ' selected' : '';
				$dirs[] = "<option{$selected}>{$cdir}</option>";
			}
		}
		$dir->close();

		$styleroot = str_replace('easy-contact-forms', 'easycontact_templates', plugin_dir_path(__FILE__)) . 'easycontact_templates.php';

		if (is_file($styleroot)) {
			require_once $styleroot;
			$dirs = easycontact_templates_getavaliablestyles($dirs, $current);
		}
		return implode('', $dirs);

	}

	/**
	 * 	Lists available fields
	 *
	 * @param  $elId
	 * 
	 *
	 * @return
	 * 
	 */
	function getEmailTemplateLinks($elId) {

		echo '<div style="margin:0.625em 0">';
		echo EasyContactFormsT::get('ClickLinkBelowToInsertFieldIntoTemplate');
		echo '</div>';
		$cf = $this->get('id');

		$query = "SELECT
				CustomFormFields.id,
				CustomFormFields.Description,
				CustomFormFields.Type
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			INNER JOIN
				#wp__easycontactforms_customformfieldtypes AS CustomFormFieldTypes
					ON
						CustomFormFields.Type=CustomFormFieldTypes.id
			WHERE
				CustomFormFieldTypes.ValueField=TRUE
				AND CustomFormFields.CustomForms=$cf
			ORDER BY
				CustomFormFields.ListPosition";

		$fields = EasyContactFormsDB::getObjects($query);

		$txt = '';
		echo '<table class="vtable ufo-template-links" style="border:0">';
		foreach ($fields as $fld) {
			$d = $this->getTemplateTag($fld->Description, $fld->id);
			$d = trim($d, '{}');
			EasyContactFormsApplicationSettings::getEmailTemplateRow($elId, $d, $fld->Description, TRUE, TRUE);
		}
		echo '</table>';

	}

	/**
	 * 	Prepares a template tag
	 *
	 * @param  $d
	 * 
	 * @param  $fldid
	 * 
	 * @param  $template
	 * 
	 * @param  $oldid
	 * 
	 *
	 * @return
	 * 
	 */
	function getTemplateTag($d, $fldid, $template = -1, $oldid = NULL) {

		$d = str_replace(' ', '', $d);
		$d = str_replace('"', '', $d);
		$d = str_replace('\'', '', $d);
		$d = str_replace('&', '', $d);
		$d = str_replace('>', '', $d);
		$d = str_replace('<', '', $d);
		$d = str_replace('.', '', $d);
		$d = str_replace('}', '', $d);
		$d = str_replace('{', '', $d);
		$ftempalte = $fldid . '.' . $d;
		$ftemplate = '{' . $ftempalte . '}';
		if ($template == -1) {
			return $ftemplate;
		}
		$oftempalte = $oldid . '.' . $d;
		$oftemplate = '{' . $oftempalte . '}';
		$template = str_replace($oftemplate, $ftemplate, '' . $template);
		return $template;

	}

	/**
	 * 	getForm
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function getForm($map) {

		$fid = isset($map['fid']) ? intval($map['fid']) : 0;
		if ($fid == 0) {
			return '';
		}
		$cfid = isset($map['hidden-' . $fid]);
		$form = new EasyContactFormsCustomForms(true, $fid);
		if (!$form->isValid()) {
			return '';
		}
		$map = EasyContactFormsSecurityManager::getRights($map);
		$form->user = $map['easycontactusr'];
		if (!$cfid) {
			if (isset($map['pn'])){
				$form->pageName = $map['pn'];
				$fpstat = EasyContactFormsClassLoader::getObject('CustomFormEntryStatistics');
				$fpstat->increaseImpressions($form);
			}
			$html = $form->preprocess();
			$form->set('Impressions', $form->get('Impressions') + 1);
			$form->save();
		}
		else {
			$html = $form->preprocess($map);
			if (!isset($form->sendBack) || $form->sendBack == FALSE) {
				$form->newEntry($map);
				$html = array();
				$html[] = $form->loadStyle();
				if (!$form->isEmpty('StyleSheet')) {
					$html[] = '<style type="text/css">' . $form->get('StyleSheet') . '</style>';
				}

				$submitsuccessclass = $form->isEmpty('SuccessMessageClass') ? 'ufo-form-submit-success' : $form->get('SuccessMessageClass');

				$submitsuccesstext = $form->isEmpty('SubmissionSuccessText') ? '' : $form->get('SubmissionSuccessText');
				$html[] = "<div class='$submitsuccessclass'>{$submitsuccesstext}</div>";
				$html = implode('', $html);
			}
		}

		return $html;

	}

	/**
	 * 	getStyle
	 *
	 *
	 * @return
	 * 
	 */
	function getStyle() {

		$id = $this->get('id');

		$query = "SELECT
				CustomFormFields.id,
				CustomFormFields.Settings
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.CustomForms='$id'
				AND CustomFormFields.Type='14';";

		$fields = EasyContactFormsDB::getObjects($query);
		$classes = array();
		foreach ($fields as $fld) {
			$xml = simplexml_load_string($fld->Settings);
			$classname = (string) $xml->RowCSSClass;
			if (empty($classname)) {
				$classes[]='.ufo-row-' . $fld->id . '{display:none;}';
			}
		}
		return $this->fields->StyleSheet . implode('', $classes);

	}

	/**
	 * 	getStyleSpec
	 *
	 *
	 * @return
	 * 
	 */
	function getStyleSpec() {

		$stylespec = (object) array();
		$stylespec->name = false;
		$stylespec->split = false;
		$stylespec->counter = 0;
		$stylespec->multi = false;
		$stylespec->splitheader = false;
		if ($this->isEmpty('Style')) {
		 return $stylespec;
		}
		$style = $this->get('Style');
		$style = explode('_', $style);
		if (count($style) == 1) {
		 return $stylespec;
		}
		if ($style[0] != 'easycontact') {
		 return $stylespec;
		}
		$stylespec->name = $style[1];
		$stylespec->split = true;
		if (count($style) == 2) {
		 return $stylespec;
		}
		$stylespec->multi = true;
		return $stylespec;

	}

	/**
	 * 	loads form style
	 *
	 *
	 * @return
	 * 
	 */
	function loadStyle() {

		if ($this->isEmpty('Style')) {
			return '';
		}
		$style = $this->get('Style');
		return $this->basicLoadStyle($style);

	}

	/**
	 * 	basicLoadStyle
	 *
	 * @param  $style
	 * 
	 *
	 * @return
	 * 
	 */
	function basicLoadStyle($style) {

		$ds = DIRECTORY_SEPARATOR;
		$styleroot = dirName(__FILE__) . $ds . 'forms' . $ds . 'styles' . $ds . $style;
		ob_start();
		if (is_dir($styleroot)) {
			require $styleroot . $ds . 'easy-contact-forms-getstyle.php';
		}
		else {

			$styleroot = str_replace('easy-contact-forms', 'easycontact_templates', plugin_dir_path(__FILE__)) . 'easycontact_templates.php';

			if (is_file($styleroot)) {
				require_once $styleroot;
				easycontact_templates_loadstyle($style);
			}
		}
		$html = ob_get_contents();
		ob_end_clean();
		return $html;

	}

	/**
	 * 	preprocess
	 *
	 * @param  $pvarmap
	 * 
	 *
	 * @return
	 * 
	 */
	function preprocess($pvarmap = null) {
		$cf = $this->get('id');

		$query = "SELECT
				CustomFormFields.id
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.CustomForms='$cf'";

		$flds = EasyContactFormsDB::getObjects($query);
		$varmap = is_null($pvarmap) ? array() : $pvarmap;
		$currentuser = $this->user->id;

		foreach ($flds as $fldid) {
			$fld = EasyContactFormsClassLoader::getObject('CustomFormFields', true, $fldid->id);
			$varmap = $this->preprocessField($fld, $varmap, !is_null($pvarmap));
		}
		$html = $this->get('HTML');

		$previewflag =  isset($this->preview) && $this->preview ? '<input type="hidden" id="ufo-preview" value="true" name="ufo"/>' : '';

		$html = str_replace('{preview}', $previewflag, $html);

		foreach ($varmap as $key=>$value){
			if (!is_string($value)) {
				continue;
			}
			$html = str_replace('{' . $key. '}', $value, $html);
		}

		$as = EasyContactFormsApplicationSettings::getInstance();
		if ($as->get('w3cCompliant')) {
			$html = str_replace('{__requesturi}', htmlspecialchars($_SERVER['REQUEST_URI']), $html);
		}

		$pagename = isset($this->pageName) ? $this->pageName : '';
		$html = str_replace('{__pagename}', $pagename, $html);

		$time = time();
		$sign = md5(EasyContactFormsSecurityManager::getServerPwd() . '' . $time) . '' . $time;
		$html = str_replace('{ufosignature}', $sign, $html);
		$errors = '';
		if (isset($this->errors) && count($this->errors) > 0){
			$errors = array();
			foreach ($this->errors as $fld=>$err) {
				$errors[] = "<strong>{$fld}</strong><br />{$err}";
			}
			$errors = implode('</div><div>', $errors);
			$errors = "<div>$errors</div>";

			$submitfailureclass = $this->isEmpty('FailureMessageClass') ? 'ufo-form-submit-failure' : $this->get('FailureMessageClass');

			$errors = "<div class='$submitfailureclass'>$errors</div>";
		}

		return $errors.$html;

	}

	/**
	 * 	preprocessField
	 *
	 * @param  $fld
	 * 
	 * @param  $varmap
	 * 
	 * @param  $validate
	 * 
	 *
	 * @return
	 * 
	 */
	function preprocessField($fld, $varmap, $validate) {

		if ($validate) {
			$varmap = $this->validate($fld, $varmap);
		}

		$xml = simplexml_load_string($fld->get('Settings'));
		$currentuser = $this->user->id;
		$fldid = $fld->get('id');
		$varmap['display-' . $fldid] = '';

		if (empty($varmap['id-' . $fldid])) {
			$varmap['id-' . $fldid] = $this->getFieldValue($xml, true, 'DefaultValue', 'SetDefaultValue');
		}
		$ruo = (string) $xml->RegistredUsersOptions;
		$done = false;
		if (!$validate) {
			$phase = (object) array('index' => 3);
			include $fld->getTMPFileName('proc');
		}
		if ($done) {
			return $varmap;
		}
		if (!isset($xml->SetContactOptions)) {
			return $varmap;
		}

		$test1 = $this->getFieldValue($xml, false, 'LinkToAppField', 'SetContactOptions', $ruo != 'none', !empty($currentuser));

		if (!$test1) {
			return $varmap;
		}

		$link = (string) $xml->LinkToAppField;
		$link = explode('_', $link);
		if ($link[0] != 'Users') {
			return $varmap;
		}

		if (!isset($this->userobj)) {
			$this->userobj = EasyContactFormsClassLoader::getObject('Users', true, $currentuser);
		}

		$userval = $this->userobj->get($link[1]);
		if ($ruo == 'hidefilled' && !empty($userval)) {
			$varmap['display-' . $fldid] = "display:none;";
			$varmap['id-' . $fldid] =	$userval;
		}
		else {
			$varmap['id-' . $fldid] = $userval;
		}
		return $varmap;

	}

	/**
	 * 	getFieldValue
	 *
	 *
	 * @return
	 * 
	 */
	function getFieldValue() {

		$args = func_get_args();
		$node = $args[0];
		$sting = $args[1];

		$valueName = $args[2];
		$value = (string) $node->$valueName;

		if (empty($value)) {
			return $sting ? '' : FALSE;
		}

		for ($i = 3; $i < count($args); $i++) {
			$arg = $args[$i];
			if (is_string($arg)) {
				$flag = (string) $node->$args[$i];
				if ($flag != 'on') {
					return $sting ? '' : FALSE;
				}
			}
			else {
				if ($arg === FALSE) {
					return $sting ? '' : FALSE;
				}
			}
		}
		return $sting ? $value : TRUE;

	}

	/**
	 * 	validate
	 *
	 * @param  $fld
	 * 
	 * @param  $varmap
	 * 
	 *
	 * @return
	 * 
	 */
	function validate($fld, $varmap) {

		$fldid = $fld->get('id');
		$txml = simplexml_load_string($fld->get('Template'));
		$validation = (string) $txml->Validation;
		if (empty($validation)) {
			$phase = (object) array('index' => 7);
			include $fld->getTMPFileName('proc');
			return $varmap;
		}
		$config = null;
		if (strpos($validation, 'ufoFormsConfig.validations.push(') !== FALSE) {
			list($first, $second) = explode('ufoFormsConfig.validations.push(', $validation, 2);
			list($validation, $second) = explode(');', $second, 2);
			$config = json_decode($validation);
		}
		if (is_null($config)) {
			return $varmap;
		}
		$fldvalue = isset($varmap['id-' . $fldid]) ? $varmap['id-' . $fldid] : NULL;
		$valid = NULL;
		$phase = (object) array('index' => 5);
		include $fld->getTMPFileName('proc');
		if (is_null($valid)) {
			foreach ($config->events as $key=>$handlers) {
				if ($key != 'blur') {
					continue;
				}
				foreach ($handlers as $handler) {
					$valid = $this->checkValid($fld, $fldvalue, $handler, $config);
					if (!$valid) {
						break;
					}
				}
			}
		}
		if (!$valid) {
			unset($varmap['id-' . $fldid]);
			$varmap['id-' . $fldid] = '';
		}
		return $varmap;

	}

	/**
	 * 	checkValid
	 *
	 * @param  $fld
	 * 
	 * @param  $fldvalue
	 * 
	 * @param  $handler
	 * 
	 * @param  $config
	 * 
	 *
	 * @return
	 * 
	 */
	function checkValid($fld, $fldvalue, $handler, $config) {

		if ($handler == 'default') {
			return TRUE;
		}

		if (!isset($config->Required) && $this->isEmptyValue($fld, $fldvalue, $config)) {
			return TRUE;
		}

		if ($handler == 'required' && $this->isEmptyValue($fld, $fldvalue, $config)) {
			$this->processInvalid($fld, 'required', $config);
			return FALSE;
		}

		if ($handler == 'minmax') {
			$min = isset($config->min) ? intval($config->min) : -1;
			$max = isset($config->max) ? intval($config->max) : PHP_INT_MAX;
			$invalid = strlen($fldvalue) < $min || strlen($fldvalue) > $max;
			if ($invalid) {
				$this->processInvalid($fld, 'minmax', $config);
				return FALSE;
			}
		}

		if ($handler == 'minmaxnumeric') {
			$min = isset($config->min) ? intval($config->min) : (~PHP_INT_MAX);
			$max = isset($config->max) ? intval($config->max) : PHP_INT_MAX;
			$invalid = $fldvalue < $min || $fldvalue > $max;
			if ($invalid) {
				$this->processInvalid($fld, 'minmaxnumeric', $config);
				return FALSE;
			}
		}

		$re = array();
		$re['email']='/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,6})+$/';
		$re['currency']='/^-?(?:0|[1-9]\d{0,2}(?:,?\d{3})*)(?:\.\d+)?$/';
		$re['integer']='/^\-?[0-9]+$/';
		$re['numeric']='/^[0-9]+$/';
		$re['phonenumber']='/^(\+{0,1}\d{1,2})*\s*(\(?\d{3}\)?\s*)*\d{3}(-{0,1}|\s{0,1})\d{2}(-{0,1}|\s{0,1})\d{2}$/';
		$as = EasyContactFormsApplicationSettings::getInstance();
		if (!$as->isEmpty('PhoneRegEx')) {
			$re['phonenumber']='/^' . $as->get('PhoneRegEx') . '/';
		}

		if (isset($re[$handler])) {
			$re = $re[$handler];
			$test = preg_match($re, $fldvalue) ? TRUE : FALSE;
			if (!$test) {
				$this->processInvalid($fld, $handler, $config);
				return FALSE;
			}
		}

		return TRUE;

	}

	/**
	 * 	processInvalid
	 *
	 * @param  $fld
	 * 
	 * @param  $handler
	 * 
	 * @param  $config
	 * 
	 *
	 * @return
	 * 
	 */
	function processInvalid($fld, $handler, $config) {

		$this->sendBack = TRUE;
		if (!isset($this->errors)) {
			$this->errors = array();
		}
		$errorMsg = $config->RequiredMessage;
		$this->errors[$fld->get('Description')] = $errorMsg;

	}

	/**
	 * 	isEmptyValue
	 *
	 * @param  $fld
	 * 
	 * @param  $fldvalue
	 * 
	 * @param  $config
	 * 
	 *
	 * @return
	 * 
	 */
	function isEmptyValue($fld, $fldvalue, $config) {

		if (isset($config->isDefaultBlank) && $config->isDefaultBlank && isset($config->defaultValue) && $config->defaultValue == $fldvalue) {

			return TRUE;
		}

		if ($fld->get('Type') == 11 && ('' . $fldvalue == '0')) {
			return FALSE;
		}

		if (empty($fldvalue)) {
			return TRUE;
		}

		return FALSE;

	}

	/**
	 * 	doEmailing
	 *
	 * @param  $submission
	 * 
	 * @param  $emessagedata
	 * 
	 * @param  $clientemail
	 * 
	 * @param  $clientid
	 * 
	 * @param  $attachments
	 * 
	 *
	 * @return
	 * 
	 */
	function doEmailing($submission, $emessagedata, $clientemail, $clientid, $attachments) {

		require_once 'easy-contact-forms-backoffice.php';
		$bo = new EasyContactFormsBackOffice();

		$submission = htmlspecialchars_decode($submission);

		if ($this->get('SendConfirmation')) {
			if (empty($clientemail) && !empty($clientid)) {
				$userdata = $bo->getSenderData($clientid);
				$clientemail = $userdata->email;
			}
			if (!empty($clientemail)) {
				$message = (object) array();
				$message->ishtml = $this->get('SendConfirmationAsText') ? false : true;
				$message->subject = $this->get('ConfirmationSubject');
				$message->body = '' . $this->get('ConfirmationText');
				$message = EasyContactFormsBackOffice::fillInTemplate($message, $emessagedata);
				$message->body = str_replace('{submission}', $submission, $message->body);
				if (!$this->isEmpty('ConfirmationStyleSheet') && $message->ishtml) {
					$message->body = '<style>' . $this->get('ConfirmationStyleSheet') . '</style>' . $message->body;
				}
				$message->body = stripslashes($message->body);
				$sender = (object) array();
				$sender->name = $this->get('SendFrom');
				$email = $this->get('SendFromAddress');
				if (empty($email)) {
					$email = get_option('admin_email');
				}
				if (!$this->isEmpty('ConfirmationReplyToName') || !$this->isEmpty('ConfirmationReplyToAddress')) {
					$message->replyto = (object) array();
					if (!$this->isEmpty('ConfirmationReplyToName')) {
						$message->replyto->name = $this->get('ConfirmationReplyToName');
						if (empty($sender->name)) {
							$sender->name = $this->get('ConfirmationReplyToName');
						}
					}
					if (!$this->isEmpty('ConfirmationReplyToAddress')) {
						$message->replyto->email = $this->get('ConfirmationReplyToAddress');
					}
				}
				$list = array($clientemail);
				$bo->send($message, $list, $sender, $email, $attachments->confirmation);
			}
		}

		$recps = $bo->getListMemberEmails('CustomForms', $this->get('id'));
		if (count($recps) == 0) {
			return;
		}
		$message = (object) array();
		$message->ishtml = $this->get('SendNotificationAsText') ? false : true;
		$message->subject = $this->get('NotificationSubject');
		$message->body = '' . $this->get('NotificationText');
		$message = EasyContactFormsBackOffice::fillInTemplate($message, $emessagedata);
		if($this->isEmpty('NotificationText') || $this->get('NotificationText') == '<p></p>') {
			if ($message->ishtml) {

				$submission = '<style>div.ufo-form-envelope div{padding:0.125em 0.625em;}label.ufo-cform-label {font-weight:bold;margin:0.125em -0.625em;}</style>' . $submission;

			}
			else {
				$submisstiontext = array();
				$xml = simplexml_load_string($submission);
				foreach ($xml->children() as $parent) {
					foreach ($parent->children() as $child) {
						$label = (string) $child->label;
						$str = (string)$child;
						if ($label != '') { 
							$submisstiontext[] = '  ' . $label;
						}
						else { 
							$submisstiontext[] = '    ' . $str;
						}
					}
				}
				$submission = implode(chr(13).chr(10), $submisstiontext);
			}
			$message->body = $submission;
		}
		$message->body = stripslashes($message->body);
		if (!$this->isEmpty('NotificationStyleSheet') && $message->ishtml) {
			$message->body = '<style>' . $this->get('NotificationStyleSheet') . '</style>' . $message->body;
		}
		$replyto = $this->get('IncludeVisitorsAddressInReplyTo') && !empty($clientemail);
		if ($replyto) {
			$message->replyto = (object) array();
			$message->replyto->email = $clientemail;
			if (!$this->isEmpty('ReplyToNameTemplate')) {
				$fake = (object) array();
				$fake->subject = '';
				$fake->body = $this->get('ReplyToNameTemplate');
				$fake = EasyContactFormsBackOffice::fillInTemplate($fake, $emessagedata);
				$message->replyto->name = htmlspecialchars_decode($fake->body);
			}
		}
		$sender = (object) array();
		$sender->name = $this->get('Description');
		$sender->email = EasyContactFormsApplicationSettings::getInstance()->get('SendFrom');
		if (empty($sender->email)) {
			$sender->email = get_option('admin_email');
		}
		$bo->send($message, $recps, $sender, '', $attachments->notification);

	}

	/**
	 * 	preview
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function preview($map) {

		$ds = DIRECTORY_SEPARATOR;

		$fid = intval($map['oid']);

		$query = "SELECT
				CustomForms.id,
				CustomForms.Description
			FROM
				#wp__easycontactforms_customforms AS CustomForms";

		$availableforms = EasyContactFormsDB::getObjects($query);

		$links = array();
		$links[] = "<ul style='width:90%;float:right;' class='ufo-tab-header ufo-tab-left'>";
		foreach ($availableforms as $aform) {
			$links[] = "<li>";
			$active = $aform->id == $fid ? 'ufo-active' : '';

			$links[] = "<a href='javascript:;'  class='ufo-preview-list $active' onclick='ufoCf.refreshForm(this, {$aform->id})'><span>{$aform->Description}</span></a>";

			$links[] = "</li>";
		}
			$links[] = "</ul>";
		$links = implode('', $links);

		$index = '';
		$js = "config = {};";
		$js .= "config.url='" . admin_url( 'admin-ajax.php' ) . "';";
		$js .= "config.initial = {t:'CustomForms', m:'preview'};";
		$js .= "config.bodyid = 'ufo-formpreview-wrapper';";
		$js .= "config.resources = {};";
		$js .= "var appManConfig = config;";

		$index .= "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd'>";

		$index .= "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='en' lang='en'>";
		$index .= "<head>";
		$index .= "<script type='text/javascript'>$js</script>";

		$index .= "<script type='text/javascript' src='" . EASYCONTACTFORMS__engineWebAppDirectory . "/js/jquery.js'></script>";

		$index .= "<script type='text/javascript' src='" . EASYCONTACTFORMS__engineWebAppDirectory . "/js/json.js'></script>";

		$index .= "<script type='text/javascript' src='" . EASYCONTACTFORMS__engineWebAppDirectory . "/js/as.js'></script>";

		$index .= "<script type='text/javascript' src='" . EASYCONTACTFORMS__engineWebAppDirectory . "/easy-contact-formshtml.1.4.7.js'></script>";

		$index .= "<script>$(document).ready(function(){ufoCf.refreshForm(this, {$fid});});</script>";
		$index .= "<style type='text/css'>";
		$index .= "*{margin:0;padding:0}";
		$index .= "html, body {height:100%;width:100%;overflow:hidden}";
		$index .= "table {height:100%;width:100%;table-layout:static;border-collapse:collapse}";
		$index .= "td{height:100%}";
		$index .= "iframe {height:100%;width:100%}";
		$index .= ".content {height:100%}";
		$index .= "</style>";
		$styleName = EasyContactFormsApplicationSettings::getInstance()->get('DefaultStyle2');
		ob_start();

		require_once _EASYCONTACTFORMS_PLUGIN_PATH . $ds . 'styles' . $ds . $styleName . $ds . 'easy-contact-forms-getstyle.php';

		$index .= ob_get_contents();
		ob_end_clean();
		$index .= "</head>";
		$index .= "<body>";
		$index .= "<table id='ufo-formpreview-wrapper'><tr>";
		$index .= "<td style='width:15%;vertical-align:top;padding:0.625em 0;background:#f5f5f5;border-right:0.063em solid #bbb'>";
		$index .= $links;
		$index .= "</td>";
		$index .= "<td style='width:85%;'>";

		$index .= "<iframe frameborder=0 class='ufo-form-preview ufo-id-link' style='overflow:auto' name='ufo-form-preview' id='ufo-form-preview'></iframe>";

		$index .= "</td>";
		$index .= "<tr></table></html>";

		echo $index;

	}

	/**
	 * 	getfilespec
	 *
	 * @param  $fid
	 * 
	 *
	 * @return
	 * 
	 */
	function getfilespec($fid) {

		$ds = DIRECTORY_SEPARATOR;
		$pwd = EasyContactFormsSecurityManager::getServerPwd();
		$spec = (object) array();
		$spec->htmlfile = 'form-' . $fid . '.html';
		$spec->subpath = array();
		$spec->subpath[] = 'forms';
		$spec->subpath[] = 'tmp';
		$spec->subpath[] = md5($pwd);
		$spec->dir = dirName(__FILE__) . $ds . implode($ds, $spec->subpath);
		$spec->filepath = $spec->dir . $ds . $spec->htmlfile;
		$spec->webfolder = EASYCONTACTFORMS__engineWebAppDirectory . '/' . implode('/', $spec->subpath);
		$spec->fileurl = $spec->webfolder . '/' . $spec->htmlfile;
		return $spec;

	}

	/**
	 * 	refreshForm
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function refreshForm($map) {

		$fid = intval($map['oid']);
		$form = new EasyContactFormsCustomForms(true, $fid);
		$form->user = EasyContactFormsSecurityManager::getGuest();
		$form->preview = true;
		$html = $form->preprocess();

		$text = array();

		$as = EasyContactFormsApplicationSettings::getInstance();
		if ($as->get('w3cCompliant') && !$as->isEmpty('w3cStyle')) {
			$text[] = $form->basicLoadStyle($as->get('w3cStyle'));
		}

		$text[] = "<script type='text/javascript' src='" . EASYCONTACTFORMS__engineWebAppDirectory . "/easy-contact-forms-forms.1.4.7.js'></script>";

		$text[] = "<table align=center style='height:100%'><tr>";
		$text[] = "<td style='padding-top:3.125em;vertical-align:top'>";
		$text[] = $html;
		$text[] = "</td>";
		$text[] = "</tr></table>";
		$text = implode('', $text);
		if (!isset($map['ufo-skipoutput'])) {
			echo $text;
			exit;
		}

	}

	/**
	 * 	resetStatistics
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function resetStatistics($map) {

		$fid = intval($map['oid']);
		if (empty($fid)) {
			return;
		}
		$form = EasyContactFormsClassLoader::getObject('CustomForms', true, $fid);
		$form->set('Impressions', 0);
		$form->set('TotalEntries', 0);
		$form->set('TotalProcessedEntries', 0);
		$form->save();
		$map['m'] = 'show';
		EasyContactFormsRoot::processEvent($map);

	}

	/**
	 * 	adds necessary style tags
	 *
	 * @param  $array
	 * 
	 * @param  &$stylespec
	 * 
	 * @param  $phase
	 * 
	 *
	 * @return
	 * 
	 */
	function templateHTML($array, &$stylespec, $phase) {

		if (!$stylespec->split) {
			return $array;
		}
		switch ($phase) {
			case 1: {
				if (!$stylespec->multi) {

					$array[] = '<div class="ufoformsplit ' . $stylespec->name . '"><div class="ufospheader"><div class="ufospl"><div class="ufospl">';

				}
				break;
			}
			case 2: {
				if (!$stylespec->multi && !$stylespec->splitheader) {
					$stylespec->splitheader = true;
					$array[] = '</div></div></div>';
					$array[] = '<div class="ufospbody"><div class="ufospl"><div class="ufospl"><div class="ufospl">';
					$array[] = '<div class="ufoformbodyint">';
				}
				break;
			}
			case 3: {
				if (!$stylespec->multi) {
					$array[] = '</div>';
					$array[] = '</div></div></div></div>';
					$array[] = '</div>';
				}
				break;
			}
			case 4: {
				if ($stylespec->multi) {
					$stylespec->counter++;
					$array[] = "<div class='ufoformsplit {$stylespec->name} {$stylespec->name}{$stylespec->counter}'>";
					$array[] = '<div class="ufospheader"><div class="ufospl"><div class="ufospl">';
					$array[] = '</div></div></div>';
					$array[] = '<div class="ufospbody"><div class="ufospl"><div class="ufospl"><div class="ufospl">';
					$array[] = '<div class="ufoformbodyint">';
				}
				break;
			}
			case 6: {
				if ($stylespec->multi) {
					$array[] = '</div>';
					$array[] = '</div></div></div></div>';
					$array[] = '</div>';
				}
				break;
			}
		}

		return $array;

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

		$request = EasyContactFormsUtils::parseRequest($request, 'SendConfirmation', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'Redirect', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'Template', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'ObjectOwner', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'ShowSubmissionSuccess', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'Width', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'LineHeight', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'TotalEntries', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'TotalProcessedEntries', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'Impressions', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'IncludeVisitorsAddressInReplyTo', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'SendConfirmationAsText', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'SendNotificationAsText', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'FadingDelay', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'MessageDelay', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'IncludeIntoReporting', 'boolean');

		parent::update($request, $id);

		$this->updateTemplate($id);

	}

	/**
	 * 	updateTemplate
	 *
	 * @param  $cf
	 * 
	 *
	 * @return
	 * 
	 */
	function updateTemplate($cf) {

		$query = "SELECT
				CustomFormFields.id,
				FieldSetListPosition.ListPosition AS FieldSetListPosition,
				IF(CustomFormFields.Type=1
					OR CustomFormFields.Type=2,
					1,
					0) AS Container
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			INNER JOIN
				#wp__easycontactforms_customformfieldtypes AS CustomFormFieldTypes
					ON
						CustomFormFields.Type=CustomFormFieldTypes.id
				LEFT JOIN(
				SELECT
					CustomFormFields.id,
					CustomFormFields.ListPosition
				FROM
					#wp__easycontactforms_customformfields AS CustomFormFields) AS FieldSetListPosition
					ON
						FieldSetListPosition.id=CustomFormFields.FieldSet
			WHERE
				CustomFormFields.CustomForms='$cf'
			ORDER BY
				FieldSetListPosition,
				Container DESC,
				CustomFormFields.ListPosition";

		$fields = EasyContactFormsDB::getObjects($query);
		$form = EasyContactFormsClassLoader::getObject('CustomForms', true, $cf);
		$stylespec = $form->getStyleSpec();

		$divmargin =  $form->isEmpty('LineHeight') ? '' : "margin-top:{$form->get('LineHeight')}{$form->get('LineHeightUnit')};";

		$rows = array();
		$containertag = '';
		$containerbottom = '';
		$containercf = '';
		$containerbottominside = '';
		$iscontainer = false;
		$as = EasyContactFormsApplicationSettings::getInstance();
		$w3c = $as->get('w3cCompliant');

		$vjs = array();
		$items = array();
		$ufovalidators = FALSE;
		foreach ($fields as $fld) {
		$fld = EasyContactFormsClassLoader::getObject('CustomFormFields', true, $fld->id);
		$phase = (object) array('index' => 8);
		include $fld->getTMPFileName('proc');
			$text = $fld->get('Template');
			$xml = simplexml_load_string($text);
			if (!$xml) continue;
			$entry = (object) array();
			$iscontainer = false;
			foreach($xml->children() as $child) {
				$name = $child->getName();
				if ($name == 'Container'){
					$iscontainer = true;
					if (!$containertag == '') {
						if (!empty($containerbottominside)) {
							$rows[] = $containerbottominside;
						}
						$rows[] = "</$containertag>";
						$rows = $form->templateHTML($rows, $stylespec, 6);
						$rows = $form->templateHTML($rows, $stylespec, 2);
						if (!empty($containerbottom)) {
							$rows[] = $containerbottom;
						}
						if (!empty($containercf)) {
							$rows[] = $containercf;
						}
					}
					$containerbottom = '';
					$containerbottominside = '';
					$containertag = (string) $child->attributes()->containertag;
					$containercf = (string) $child->attributes()->addcf == 'on' ? "<div style='clear:both;height:0.063em'></div>" : '';
				}

				$iscenter = ($name == 'Container' || $name == 'Input' || $name == 'Validation');
				$positionname = $iscenter ? 'center' : $child->attributes()->position;
				$width = isset($child->attributes()->width) ? $child->attributes()->width : '';
				$rowclass = isset($child->attributes()->rowclass) ? $child->attributes()->rowclass : '';

				if (!isset($entry->$positionname)) {
					$entry->$positionname = (object) array();
					$entry->$positionname->list = array();
					$entry->$positionname->width = '';
					$entry->$positionname->rowclass = '';
				}

				array_push($entry->$positionname->list, (string) $child);
				$entry->$positionname->width = $width;
				$entry->$positionname->rowclass = $rowclass;
			}

			if ($iscontainer) {
				if (isset($entry->top)) {
					$rows[] = implode('', $entry->top->list);
				}
				if (isset($entry->bottom)) {
					$containerbottom = implode('', $entry->bottom->list);
				}
				if (isset($entry->{'bottom-inside'})) {
					$containerbottominside = implode('', $entry->{'bottom-inside'}->list);
				}
				$rows = $form->templateHTML($rows, $stylespec, 4);
				$rows[] = implode('', $entry->center->list);

				if (isset($entry->{'top-inside'})) {
					$rows[] = implode('', $entry->{'top-inside'}->list);
				}
			}
			else {

				$fldid = $fld->get('id');
				$typeid = $fld->get('Type');

				$rowclass = empty($entry->center->rowclass) ? '' : ' ' . $entry->center->rowclass;

				$rows[] = "<div class='ufo-fieldtype-{$typeid} ufo-customform-row ufo-row-{$fldid}{$rowclass}' style='{$divmargin}{display-{$fldid}}'>";

				if (isset($entry->top)) {
					$entry->top->width = $entry->center->width;

					$rows = EasyContactFormsCustomForms::addRow($rows, $entry->top, isset($entry->left), isset($entry->right), $fldid, 1);

				}

				$entryleft = isset($entry->left) ? $entry->left : false;
				$entryright = isset($entry->right) ? $entry->right : false;
				$rows = EasyContactFormsCustomForms::addRow($rows, $entry->center, $entryleft, $entryright, $fldid, 2);

				if (isset($entry->bottom)) {
					$entry->bottom->width = $entry->center->width;

					$rows = EasyContactFormsCustomForms::addRow($rows, $entry->bottom, isset($entry->left), isset($entry->right), $fldid, 3);

				}
				$rows[] = '</div>';
			}
		}
		if (!empty($containerbottominside)) {
			$rows[] = $containerbottominside;
		}
		$rows = $form->templateHTML($rows, $stylespec, 6);
		$rows[] = "</$containertag>";
						$rows = $form->templateHTML($rows, $stylespec, 2);
		if (!empty($containerbottom)) {
			$rows[] = $containerbottom;
		}
		if (!empty($containercf)) {
			$rows[] = $containercf;
		}
		$rows = implode('', $rows);
		$html = array();
		$html[] = "<script type='text/javascript'>";
		$html[] = "if (typeof(ecfconfig) == 'undefined'){var ecfconfig={};}";
		$html[] = "ecfconfig[{$cf}]={};";
		if (!$form->isEmpty('FadingDelay')) {
			$html[] = "ecfconfig[{$cf}].fadeDelay='" . $form->get('FadingDelay') . "';";
		}
		if (!$form->isEmpty('MessageDelay')) {
			$html[] = "ecfconfig[{$cf}].messageDelay='" . $form->get('MessageDelay') . "';";
		}
		$ajaxurl = $as->get('FixStatus0') ? home_url('wp-admin/admin-ajax.php') : admin_url('admin-ajax.php');
		$ajaxurl = $as->get('FixStatus02') ? '/wp-admin/admin-ajax.php' : $ajaxurl;
		$html[] = "var ufobaseurl =  '{$ajaxurl}';";

		$html[] = "if (typeof(ufoFormsConfig) == 'undefined') {var ufoFormsConfig = {};ufoFormsConfig.submits = [];ufoFormsConfig.resets = [];ufoFormsConfig.validations = [];}";

		if (!$as->isEmpty('PhoneRegEx')) {
			$html[] = "ufoFormsConfig.phonenumberre = /^" . $as->get('PhoneRegEx') . "/;";
		}
		if ($as->get('w3cCompliant')) {
			$html[] = "ufoFormsConfig.w2c=true;";
		}
		$html[] = "</script>";
		if (!$as->get('w3cCompliant')) {
			$html[] = $form->loadStyle();
			$stylesheet = $form->getStyle();
			if (!empty($stylesheet)) {
				$html[] = "<style type='text/css'>{$stylesheet}</style>";
			}
		}
		$formclass = array();
		$formclass[] = 'ufo-form';
		if (!$form->isEmpty('FormClass')) {
			$formclass[] = $form->get('FormClass');
		}
		$formclass = implode(' ', $formclass);
		$formclass = " class='{$formclass}'";
		$formstyle = array();
		if (!$form->isEmpty('FormStyle')) {
			$formstyle[] =  $form->get('FormStyle');
		}
		if (!$form->isEmpty('Width')) {
			$formstyle[] =  "width:{$form->get('Width')}{$form->get('WidthUnit')}";
		}
		if (sizeof($formstyle) != 0) {
			$formstyle = " style='" . implode(';', $formstyle) . "'";
		}
		else {
			$formstyle = '';
		}
		$html[] = "<div{$formclass}{$formstyle} id='ufo-form-id-$cf'>";
		if ($as->get('w3cCompliant')) {

			$html[] = "<form method='post' action='{__requesturi}'><input type='hidden' id='cf-no-script-{$cf}' name='cf-no-script' value='1'/>";

		}
		else {
			$html[] = "<noscript><form method='post'><input type='hidden' name='cf-no-script' value='1'/></noscript>";
		}
		$html[] = "<fieldset>";
		$html[] = "<legend>Formulário</legend>";
		$html[] = "<input type='hidden' value='ufo-form-id-$cf' name='hidden-$cf' id='ufo-form-hidden-$cf'/>";
		$html[] = "<input type='hidden' value='{__pagename}' name='ufo-form-pagename' id='ufo-form-pagename'/>";
		$html[] = "{preview}";
		$html[] = "<input type='hidden' value='{ufosignature}' name='ufo-sign' id='ufo-sign'/>";
		$html = $form->templateHTML($html, $stylespec, 1);
		$html[] = $rows;
		$html = $form->templateHTML($html, $stylespec, 3);
		$html[] = "<div id='ufo-form-id-$cf-message'></div>";
		$html[] = "</fieldset>";
		if ($as->get('w3cCompliant')) {
			$html[] = "</form>";
		}
		else {
			$html[] = "<noscript></form></noscript>";
		}
		$html[] = "</div>";
		if (count($vjs) > 0) {
			$html[] = '<script type="text/javascript">' . implode('', $vjs) . '</script>';
		}
		$txt = implode('', $html);
		$html = array('HTML' => $txt, 'ShortCode' => '[easy_contact_forms fid=' . $cf . ']');
		EasyContactFormsDB::update($html, 'CustomForms', $cf);

	}

	/**
	 * 	addRow
	 *
	 * @param  $rows
	 * 
	 * @param  $center
	 * 
	 * @param  $left
	 * 
	 * @param  $right
	 * 
	 * @param  $fldid
	 * 
	 * @param  $rowindex
	 * 
	 *
	 * @return
	 * 
	 */
	function addRow($rows, $center, $left, $right, $fldid, $rowindex) {

		$as = EasyContactFormsApplicationSettings::getInstance();
		$wrappertag = $as->get('w3cCompliant') ? 'div' : 'span';
		$prefix = 'ufo-cell';
		$centerclass = $prefix.'-center';
		$leftclass = $prefix.'-left';
		$rightclass = $prefix.'-right';
		$cellspec = $prefix.'-'.$fldid.'-'.$rowindex;

		if (!isset($center)) {
			return $rows;
		}
		$width = $center->width != '' ? " style='width:{$center->width}'" : '';
		$center = implode('', $center->list);
		$center = "<{$wrappertag} class='{$centerclass}'{$width} id='{$cellspec}-center'>{$center}</{$wrappertag}>";
		if (!$left) {
			$left = '';
		}
		else {
			if (is_object($left)) {
				$left = implode('', $left->list);
			}
			else {
				$left = "<p style='display:none'></p>";
			}
			$left = "<{$wrappertag} class='{$leftclass}' id='{$cellspec}-left'>{$left}</{$wrappertag}>";
		}
		if (!$right) {
			$right = '';
		}
		else {
		if (is_object($right)) {
			$right = implode('', $right->list);
		}
		else {
			$right = "<p style='display:none'></p>";
		}
			$right = "<{$wrappertag} class='{$rightclass}' id='{$cellspec}-right'>{$right}</{$wrappertag}>";
		}

		$rows[] = "<div class='{$cellspec}-row' id='{$cellspec}'>" . $left . $center . $right . "</div>";
		return $rows;

	}

	/**
	 * 	val
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function val($map) {

		foreach ($map as $key=>$value){
			if (!EasyContactFormsUtils::beginsWith($key, 'id-')) {
				continue;
			}
			$names = explode('-', $key);
			$fldid = intval($names[1]);
			if ($fldid == 0 ) {
				continue;
			}
			$fld = EasyContactFormsClassLoader::getObject('CustomFormFields', true, $fldid);
			if (!$fld) {
				continue;
			}
			$phase = (object) array('index' => 4);
			include $fld->getTMPFileName('proc');
		}

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

			case 'add':
				$this->add($dispmap);
				return NULL;

			case 'copy':
				$this->copy($dispmap);
				return NULL;

			case 'preview':
				$this->preview($dispmap);
				return NULL;

			case 'refreshForm':
				$this->refreshForm($dispmap);
				return NULL;

			case 'resetStatistics':
				$this->resetStatistics($dispmap);
				return NULL;

			case 'val':
				$this->val($dispmap);
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
		$fields[] = 'ShortCode';
		$fields[] = 'SendConfirmation';
		$fields[] = 'ConfirmationSubject';
		$fields[] = 'SendFrom';
		$fields[] = 'ConfirmationReplyToAddress';
		$fields[] = 'SendFromAddress';
		$fields[] = 'ConfirmationReplyToName';
		$fields[] = 'ConfirmationText';
		$fields[] = 'Redirect';
		$fields[] = 'RedirectURL';
		$fields[] = 'IncludeIntoReporting';
		$fields[] = 'Impressions';
		$fields[] = 'TotalEntries';
		$fields[] = 'TotalProcessedEntries';
		$fields[] = 'ShowSubmissionSuccess';
		$fields[] = 'SubmissionSuccessText';
		$fields[] = 'NotificationSubject';
		$fields[] = 'ObjectOwner';
		$fields[] = 'IncludeVisitorsAddressInReplyTo';
		$fields[] = 'ReplyToNameTemplate';
		$fields[] = 'NotificationText';
		$fields[] = 'StyleSheet';
		$fields[] = 'ConfirmationStyleSheet';
		$fields[] = 'NotificationStyleSheet';
		$fields[] = 'Width';
		$fields[] = 'WidthUnit';
		$fields[] = 'LineHeight';
		$fields[] = 'LineHeightUnit';
		$fields[] = 'Style';
		$fields[] = 'FormClass';
		$fields[] = 'FormStyle';
		$fields[] = 'SuccessMessageClass';
		$fields[] = 'FailureMessageClass';
		$fields[] = 'SendConfirmationAsText';
		$fields[] = 'SendNotificationAsText';
		$fields[] = 'FadingDelay';
		$fields[] = 'MessageDelay';

		$obj = $this->formInit($formmap, $fields);
		$obj->set('Description', htmlspecialchars($obj->get('Description'), ENT_QUOTES));
		$obj->set('ShortCode', htmlspecialchars($obj->get('ShortCode'), ENT_QUOTES));

		$obj->SendConfirmationChecked
			= $obj->get('SendConfirmation') ? 'checked' : '';
		$obj->SendConfirmation = $obj->get('SendConfirmation') ? 'on' : 'off';

		$obj->set('ConfirmationSubject', htmlspecialchars($obj->get('ConfirmationSubject'), ENT_QUOTES));
		$obj->set('SendFrom', htmlspecialchars($obj->get('SendFrom'), ENT_QUOTES));
		$obj->set('ConfirmationReplyToAddress', htmlspecialchars($obj->get('ConfirmationReplyToAddress'), ENT_QUOTES));
		$obj->set('SendFromAddress', htmlspecialchars($obj->get('SendFromAddress'), ENT_QUOTES));
		$obj->set('ConfirmationReplyToName', htmlspecialchars($obj->get('ConfirmationReplyToName'), ENT_QUOTES));
		$obj->set('ConfirmationText', htmlspecialchars($obj->get('ConfirmationText')));

		$obj->RedirectChecked = $obj->get('Redirect') ? 'checked' : '';
		$obj->Redirect = $obj->get('Redirect') ? 'on' : 'off';
		$obj->RedirectURLDisabled = $obj->get('Redirect') ? '' : 'disabled';

		$obj->set('RedirectURL', htmlspecialchars($obj->get('RedirectURL'), ENT_QUOTES));

		$obj->IncludeIntoReportingChecked
			= $obj->get('IncludeIntoReporting') ? 'checked' : '';
		$obj->IncludeIntoReporting = $obj->get('IncludeIntoReporting') ? 'on' : 'off';

		$obj->ShowSubmissionSuccessChecked
			= $obj->get('ShowSubmissionSuccess') ? 'checked' : '';
		$obj->ShowSubmissionSuccess
			= $obj->get('ShowSubmissionSuccess') ? 'on' : 'off';
		$obj->SubmissionSuccessTextDisabled
			= $obj->get('ShowSubmissionSuccess') ? '' : 'disabled';

		$obj->set('SubmissionSuccessText', htmlspecialchars($obj->get('SubmissionSuccessText')));
		$obj->set('NotificationSubject', htmlspecialchars($obj->get('NotificationSubject'), ENT_QUOTES));

		$obj->ObjectOwner = (object) array();
		$obj->ObjectOwner->view = $obj;
		$obj->ObjectOwner->field = 'ObjectOwner';
		$obj->ObjectOwner->type = 'Users';
		$obj->ObjectOwner->config['m2'] = 'getUserASList';
		$obj->ObjectOwner->config['t'] = 'Users';
		$obj->ObjectOwner->asparams['listItemClass'] = 'ufo-user-list-item';

		$obj->IncludeVisitorsAddressInReplyToChecked
			= $obj->get('IncludeVisitorsAddressInReplyTo') ? 'checked' : '';
		$obj->IncludeVisitorsAddressInReplyTo
			= $obj->get('IncludeVisitorsAddressInReplyTo') ? 'on' : 'off';

		$obj->set('ReplyToNameTemplate', htmlspecialchars($obj->get('ReplyToNameTemplate'), ENT_QUOTES));
		$obj->set('NotificationText', htmlspecialchars($obj->get('NotificationText')));
		$obj->set('StyleSheet', htmlspecialchars($obj->get('StyleSheet')));
		$obj->set('ConfirmationStyleSheet', htmlspecialchars($obj->get('ConfirmationStyleSheet')));
		$obj->set('NotificationStyleSheet', htmlspecialchars($obj->get('NotificationStyleSheet')));
		$obj->set('WidthUnit', htmlspecialchars($obj->get('WidthUnit'), ENT_QUOTES));
		$obj->set('LineHeightUnit', htmlspecialchars($obj->get('LineHeightUnit'), ENT_QUOTES));
		$obj->set('FormClass', htmlspecialchars($obj->get('FormClass'), ENT_QUOTES));
		$obj->set('FormStyle', htmlspecialchars($obj->get('FormStyle')));
		$obj->set('SuccessMessageClass', htmlspecialchars($obj->get('SuccessMessageClass'), ENT_QUOTES));
		$obj->set('FailureMessageClass', htmlspecialchars($obj->get('FailureMessageClass'), ENT_QUOTES));

		$obj->SendConfirmationAsTextChecked
			= $obj->get('SendConfirmationAsText') ? 'checked' : '';
		$obj->SendConfirmationAsText
			= $obj->get('SendConfirmationAsText') ? 'on' : 'off';

		$obj->SendNotificationAsTextChecked
			= $obj->get('SendNotificationAsText') ? 'checked' : '';
		$obj->SendNotificationAsText
			= $obj->get('SendNotificationAsText') ? 'on' : 'off';

		?>
		<input type='hidden' name="data" class='ufostddata' id='t' value='<?php echo $obj->type;?>'>
		<input type='hidden' name="data" class='ufostddata' id='oid' value='<?php echo $obj->getId();?>'>
		<?php

		require_once 'views/easy-contact-forms-customformsmainform.php';

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
		$orderby = EasyContactFormsDB::getOrderBy(array('id', 'Description', 'ShortCode'), $spar, "CustomForms.Description");

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms.', 'id', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms.', 'Description');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms.', 'NotificationSubject');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms.', 'ConfirmationSubject');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms.', 'ConfirmationText');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms.', 'Redirect', 'boolean');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms.', 'ObjectOwner', 'int');
		EasyContactFormsRoot::mDelete('CustomForms', $viewmap);

		$query = "SELECT
				CustomForms.id,
				CustomForms.Description,
				CustomForms.ShortCode
			FROM
				#wp__easycontactforms_customforms AS CustomForms";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->ObjectOwner = (object) array();
		$this->ObjectOwner->view = $this;
		$this->ObjectOwner->field = 'ObjectOwner';
		$this->ObjectOwner->filter = TRUE;
		$this->ObjectOwner->config['m2'] = 'getUserASList';
		$this->ObjectOwner->config['t'] = 'Users';
		$this->ObjectOwner->asparams['listItemClass'] = 'ufo-user-list-item';
		$this->ObjectOwner->inpstyle = " style='width:8.125em;'";

		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomForms'><?php

		include 'views/easy-contact-forms-customformsmainview.php';

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
		$orderby = EasyContactFormsDB::getOrderBy(array('id', 'Description'), $spar, "CustomForms.Description");

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();
		$viewfilters = EasyContactFormsDB::getMTMFilter($viewmap, $viewfilters, 'CustomForms');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms.', 'id', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomForms.', 'Description');

		$query = "SELECT
				CustomForms.id,
				CustomForms.Description
			FROM
				#wp__easycontactforms_customforms AS CustomForms";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 10;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby, $this);

		$this->showlist = FALSE;
		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomForms'><?php

		include 'views/easy-contact-forms-customformsmanagemainview.php';

	}

}
