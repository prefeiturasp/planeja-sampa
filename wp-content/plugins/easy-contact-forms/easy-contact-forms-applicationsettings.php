<?php

/**
 * @file
 *
 * 	EasyContactFormsApplicationSettings class definition
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
 * 	EasyContactFormsApplicationSettings
 *
 */
class EasyContactFormsApplicationSettings extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsApplicationSettings class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'ApplicationSettings';

		$this->fieldmap = array(
				'id' => NULL,
				'Description' => '',
				'TinyMCEConfig' => '',
				'UseTinyMCE' => 0,
				'ApplicationWidth' => 0,
				'ApplicationWidth2' => 0,
				'DefaultStyle' => '',
				'DefaultStyle2' => '',
				'SecretWord' => '',
				'NotLoggenInText' => '',
				'FileFolder' => '',
				'SendFrom' => '',
				'FixJSLoading' => 0,
				'FormCompletionMinTime' => 0,
				'FormCompletionMaxTime' => 0,
				'FixStatus0' => 0,
				'ProductVersion' => '',
				'PhoneRegEx' => '',
				'InitTime' => 0,
				'ShowPoweredBy' => 0,
				'DateFormat' => '',
				'DateTimeFormat' => '',
				'FixStatus02' => 0,
				'w3cCompliant' => 0,
				'w3cStyle' => '',
				'FixJSLoading2' => 0,
				'AllowMarkupInEntries' => 0,
			);

		if ($objdata) {
			$this->init($new_id);
		}

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

		$request = EasyContactFormsUtils::parseRequest($request, 'UseTinyMCE', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'ApplicationWidth', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'ApplicationWidth2', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'FixJSLoading', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'FormCompletionMinTime', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'FormCompletionMaxTime', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'FixStatus0', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'InitTime', 'date');
		$request = EasyContactFormsUtils::parseRequest($request, 'ShowPoweredBy', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'FixStatus02', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'w3cCompliant', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'FixJSLoading2', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'AllowMarkupInEntries', 'boolean');

		parent::update($request, $id);

	}

	/**
	 * 	addMessage
	 *
	 * @param  $message
	 * 
	 * @param  $class
	 * 
	 * @param  $id
	 * 
	 *
	 * @return
	 * 
	 */
	function addMessage($message, $class = 'ufo-message-notification', $id = NULL) {

		if (!isset($this->messages)){
			$this->messages = array();
		}
		$msg = (object) array('message' => $message, 'class' => $class);
		if (!is_null($id)) {
			$msg->id = $id;
		}
		$this->messages[] = $msg;

	}

	/**
	 * 	allowPBLink
	 *
	 *
	 * @return
	 * 
	 */
	function allowPBLink() {

		$as = EasyContactFormsApplicationSettings::getInstance(FALSE);
		$as->set('ShowPoweredBy', true);
		$as->save();
		$this->setOption('ApplicationSettings', 'ShowPoweredBy', '1');

	}

	/**
	 * 	get
	 *
	 * 	Overrides the base class get function
	 *
	 * @param string $prop
	 * 	the object property name
	 *
	 * @return arbitrary
	 * 	the requested value
	 */
	function get($prop) {

		if (isset($this->fields->$prop)) {
			return $this->fields->$prop;
		}
		$value = $this->getValue($prop, 1);
		$this->fields->$prop = $value;
		return $value;

	}

	/**
	 * 	getAvaliableFormStyles
	 *
	 *
	 * @return
	 * 
	 */
	function getAvaliableFormStyles() {

		echo '<option value="">...</option>';
		$forms = EasyContactFormsClassLoader::getObject('CustomForms');
		echo $forms->basicGetAvaliableStyles($this->get('w3cStyle'));

	}

	/**
	 * 	getDateFormat
	 *
	 * @param string $formatName
	 * 	Expected format to return
	 * @param string $time
	 * 	include time
	 *
	 * @return string
	 * 	returns a date format according to client needs
	 */
	function getDateFormat($formatName, $time = FALSE) {

		$dateFormat = $time ? $this->get('DateTimeFormat') : $this->get('DateFormat');
		$dateFormat = explode('^', $dateFormat);
		switch ($formatName) {
			case 'PHP': return $dateFormat[0];
			case 'JS': return $dateFormat[1];
			case 'RegEx': return $dateFormat[2];
			default : return $dateFormat[3];
		}
	}

	/**
	 * 	getEmailTemplate
	 *
	 * 	Makes a list of object fields available for adding into the email
	 * 	template
	 *
	 * @param string $type
	 * 	An object type
	 */
	function getEmailTemplate($type) {
	}

	/**
	 * 	getEmailTemplateRow
	 *
	 * 	Produces a clickable table row
	 *
	 * @param string $type
	 * 	an object type
	 * @param string $field
	 * 	an object field
	 * @param string $label
	 * 	a field label
	 * @param boolean $direct
	 * 	use direct name
	 * @param  $tmce
	 * 	use TinyMCE
	 */
	function getEmailTemplateRow($type, $field, $label, $direct = FALSE, $tmce = FALSE) {
		$direct = $direct ? 'true' : 'false';
		$tmce = $tmce && EasyContactFormsApplicationSettings::getInstance()->get('UseTinyMCE') ? 'true' : 'false';
			?>

		
<tr style='border:0'>
  <td style='border:0'>
    <a id='<?php echo $type . $field . 'icLink';?>' title='<?php echo EasyContactFormsT::get('ClickToAddToTheTemplate');?>' href='javascript:;' class='ufo-id-link' onmousedown='ufo.insertContent(this, "<?php echo $type;?>","{<?php echo $field;?>}", <?php echo $direct;?>, <?php echo $tmce;?>)'>
      <?php echo $label;?>
    </a>
  </td>
</tr>

		<?php
	}

	/**
	 * 	getInstance
	 *
	 * 	Returns a single EasyContactFormsApplicationSettings instance
	 *
	 * @param  $showmessages
	 * 
	 *
	 * @return object
	 * 	the EasyContactFormsApplicationSettings instance
	 */
	function getInstance($showmessages = TRUE) {

		static $obj;
		if (!isset($obj)) {
			$obj = new EasyContactFormsApplicationSettings();

			$obj->selectstmt = "SELECT id, UseTinyMCE, ApplicationWidth, ApplicationWidth2, FixJSLoading, FormCompletionMinTime, FormCompletionMaxTime, FixStatus0, InitTime, ShowPoweredBy, FixStatus02, w3cCompliant, FixJSLoading2, AllowMarkupInEntries, FileFolder, PhoneRegEx FROM " . $obj->getTableName() . " WHERE id = :id";

			$obj->fields = $obj->getObjectData(1);
			$needssave = FALSE;
			if ($obj->get('SecretWord') == '') {
				$obj->set('SecretWord', md5('mt=' . microtime()));
				$needssave = TRUE;
			}
			if ($obj->hasField('InitTime') && $obj->isEmpty('InitTime')) {
				$obj->set('InitTime', date(DATE_ATOM));
				$needssave = TRUE;
			}
			if ($obj->hasField('PhoneRegEx') && $obj->isEmpty('PhoneRegEx')) {
				$obj->set('PhoneRegEx', '(\+{0,1}\d{1,2})*\s*(\(?\d{3}\)?\s*)*\d{3}(-{0,1}|\s{0,1})\d{2}(-{0,1}|\s{0,1})\d{2}$');
				$needssave = TRUE;
			}
			if ($obj->hasField('FileFolder') && $obj->get('FileFolder') == '') {
				$obj->set('FileFolder', 'files');
				$needssave = TRUE;
			}
			if ($needssave) {
				$obj->save();
			}
		}
		if ($showmessages) {
			$obj->getLinkMessage();
		}
		return $obj;

	}

	/**
	 * 	getLinkMessage
	 *
	 *
	 * @return
	 * 
	 */
	function getLinkMessage() {

		if (!is_admin()) {
			return;
		}
		if (!defined('EASYCONTACTFORMS__APPLICATION_ROOT')) {
			return;
		}
		if (isset($this->allowPBLinkIssued)) {
			return;
		}
		$this->allowPBLinkIssued = TRUE;
		$inittime = $this->get('InitTime');
		if ($inittime == 0) {
			return;
		}
		$link = $this->get('ShowPoweredBy');
		$reply = $this->getOption('ApplicationSettings', 'ShowPoweredBy');
		if (time() - $inittime > 60 * 60 * 24 * 3 && !$reply && !$link && class_exists('EasyContactFormsT')) {
			$message = "";
			$message .= EasyContactFormsT::get('AllowPoweredBy');

			$message .= "&nbsp;&nbsp;<a href='javascript:;' onclick='ufo.setOptionValue({divid:\"allowbplink\", undef:\"ApplicationSettings\", t:\"ApplicationSettings\", fld:\"ShowPoweredBy\", a:1});'>" . EasyContactFormsT::get('NeverAskMeAgain') . "</a>";

			$this->addMessage($message, 'ufo-message-notification', 'allowbplink');
		}

	}

	/**
	 * 	getOption
	 *
	 * @param  $group
	 * 
	 * @param  $name
	 * 
	 *
	 * @return
	 * 
	 */
	function getOption($group, $name) {

		$Description = $name;
		$OptionGroup = $group;

		$query = "SELECT
				Options.Value
			FROM
				#wp__easycontactforms_options AS Options
			WHERE
				Options.Description LIKE '$Description'
				AND Options.OptionGroup LIKE '$OptionGroup'";

		$value = EasyContactFormsDB::getValue($query);
		return $value;

	}

	/**
	 * 	getPBLink
	 *
	 *
	 * @return
	 * 
	 */
	function getPBLink() {

		$link = $this->get('ShowPoweredBy');
		if ($link) {

			return "<div class='ufo-pb-link'>Powered by <a target=_blank href='http://championforms.com'>Champion Forms</a></div>";

		}
		return '';

	}

	/**
	 * 	isEmpty
	 *
	 * 	overrides the base class function
	 *
	 * @param string $prop
	 * 	the property name
	 *
	 * @return boolean
	 * 	TRUE if the field is unset or empty
	 */
	function isEmpty($prop) {

		if (isset($this->fields->$prop)) {
			return empty($this->fields->$prop);
		}
		$value = $this->getValue($prop, 1);
		$this->fields->$prop = $value;
		return empty($value);

	}

	/**
	 * 	setOption
	 *
	 * @param  $group
	 * 
	 * @param  $name
	 * 
	 * @param  $value
	 * 
	 *
	 * @return
	 * 
	 */
	function setOption($group, $name, $value) {

		$Description = $name;
		$OptionGroup = $group;

		$query = "SELECT
				Options.id
			FROM
				#wp__easycontactforms_options AS Options
			WHERE
				Options.Description LIKE '$Description'
				AND Options.OptionGroup LIKE '$OptionGroup'";

		$id = EasyContactFormsDB::getValue($query);

		if (!$id) {
			$option = EasyContactFormsClassLoader::getObject('Options', true);
			$option->set('Description', $Description);
			$option->set('OptionGroup', $OptionGroup);
		}
		else {
			$option = EasyContactFormsClassLoader::getObject('Options', true, $id);
		}
		$option->set('Value', $value);
		$option->save();

	}

	/**
	 * 	setOptionValue
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function setOptionValue($map) {

		$name = mysql_real_escape_string($map['fld']);
		$value = mysql_real_escape_string($map['a']);
		$group = mysql_real_escape_string($map['undef']);
		$this->setOption($group, $name, $value);

	}

	/**
	 * 	showMessages
	 *
	 *
	 * @return
	 * 
	 */
	function showMessages() {

		if (!is_admin()) {
			return;
		}
		if (!isset($this->messages)) {
			return;
		}
		foreach ($this->messages as $message) {
			$id = isset($message->id) ? " id='{$message->id}'" : "";
			$extraclass = isset($message->id) ? " ufo-id-link" : "";
			echo "<div{$id} class='{$message->class}{$extraclass}'>{$message->message}</div>";
		}

	}

	/**
	 * 	getDateFormatList
	 *
	 *
	 * @return
	 * 
	 */
	function getDateFormatList() {

		$query="SELECT Options.Value AS id, Options.Description FROM #wp__easycontactforms_options AS Options WHERE Options.OptionGroup LIKE \"%dateformats%\"";

		return $this->getList($query);
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

			case 'allowPBLink':
				$this->allowPBLink($dispmap);
				return NULL;

			case 'setOptionValue':
				$this->setOptionValue($dispmap);
				return NULL;

			default : return $dispmap;
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

		$formmap['oid'] = '1';
		$query = "SELECT * FROM #wp__easycontactforms_applicationsettings WHERE id=:id";

		$obj = $this->formQueryInit($formmap, $query);

		$obj->UseTinyMCEChecked = $obj->get('UseTinyMCE') ? 'checked' : '';
		$obj->UseTinyMCE = $obj->get('UseTinyMCE') ? 'on' : 'off';

		$obj->set('TinyMCEConfig', htmlspecialchars($obj->get('TinyMCEConfig')));

		$obj->ShowPoweredByChecked = $obj->get('ShowPoweredBy') ? 'checked' : '';
		$obj->ShowPoweredBy = $obj->get('ShowPoweredBy') ? 'on' : 'off';

		$obj->set('DefaultStyle', htmlspecialchars($obj->get('DefaultStyle'), ENT_QUOTES));
		$obj->set('SecretWord', htmlspecialchars($obj->get('SecretWord'), ENT_QUOTES));
		$this->DateFormat = $this->getDateFormatList();
		$obj->set('PhoneRegEx', htmlspecialchars($obj->get('PhoneRegEx'), ENT_QUOTES));
		$obj->set('FileFolder', htmlspecialchars($obj->get('FileFolder'), ENT_QUOTES));

		$obj->FixJSLoadingChecked = $obj->get('FixJSLoading') ? 'checked' : '';
		$obj->FixJSLoading = $obj->get('FixJSLoading') ? 'on' : 'off';
		$obj->FixJSLoading2Disabled = $obj->get('FixJSLoading') ? '' : 'disabled';

		$obj->FixJSLoading2Checked = $obj->get('FixJSLoading2') ? 'checked' : '';
		$obj->FixJSLoading2 = $obj->get('FixJSLoading2') ? 'on' : 'off';

		$obj->FixStatus0Checked = $obj->get('FixStatus0') ? 'checked' : '';
		$obj->FixStatus0 = $obj->get('FixStatus0') ? 'on' : 'off';

		$obj->FixStatus02Checked = $obj->get('FixStatus02') ? 'checked' : '';
		$obj->FixStatus02 = $obj->get('FixStatus02') ? 'on' : 'off';

		$obj->w3cCompliantChecked = $obj->get('w3cCompliant') ? 'checked' : '';
		$obj->w3cCompliant = $obj->get('w3cCompliant') ? 'on' : 'off';

		$obj->AllowMarkupInEntriesChecked
			= $obj->get('AllowMarkupInEntries') ? 'checked' : '';
		$obj->AllowMarkupInEntries = $obj->get('AllowMarkupInEntries') ? 'on' : 'off';

		$obj->set('SendFrom', htmlspecialchars($obj->get('SendFrom'), ENT_QUOTES));
		$obj->set('NotLoggenInText', htmlspecialchars($obj->get('NotLoggenInText')));

		?>
		<input type='hidden' class='ufostddata' id='t' value='<?php echo $obj->type;?>'>
		<input type='hidden' class='ufostddata' id='oid' value='<?php echo $obj->getId();?>'>
		<?php

		require_once 'views/easy-contact-forms-applicationsettingsmainform.php';

	}

}
