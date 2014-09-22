<?php

/**
 * @file
 *
 * 	EasyContactFormsCustomFormFields class definition
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
 * 	EasyContactFormsCustomFormFields
 *
 */
class EasyContactFormsCustomFormFields extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsCustomFormFields class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'CustomFormFields';

		$this->fieldmap = array(
				'id' => NULL,
				'Description' => '',
				'Type' => 0,
				'Settings' => '',
				'Template' => '',
				'ListPosition' => 0,
				'CustomForms' => 0,
				'FieldSet' => 0,
			);

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a customformfield
	 * 	record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		$stmts[] = "DELETE FROM #wp__easycontactforms_customformfields WHERE id='$id';";

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

		$request = EasyContactFormsUtils::parseRequest($request, 'Type', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'ListPosition', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'CustomForms', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'FieldSet', 'int');

		parent::update($request, $id);

	}

	/**
	 * 	getEmptyObject. Overrides EasyContactFormsBase::getEmptyObject()
	 *
	 * 	creates and initializes a new CustomFormField
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
		$fields->Type = EasyContactFormsDB::getFirst('CustomFormFieldTypes', TRUE);
		$fields->CustomForms = EasyContactFormsDB::getFirst('CustomForms');

		return parent::getEmptyObject($map, $fields);

	}

	/**
	 * 	addCustomField
	 *
	 * 	Adds a new field and returns a field list view
	 *
	 * @param array $map
	 * 	request data
	 *
	 * @return string
	 * 	the list
	 */
	function addCustomField($map) {

		$args = $map['cfa'];
		$args = json_decode(stripslashes($args));

		$field = EasyContactFormsClassLoader::getObject('CustomFormFields', true);
		$type = EasyContactFormsClassLoader::getObject('CustomFormFieldTypes', true, $args->tid);
		$field->set('Description', $type->get('Description'));
		$fldtype  = $type->get('id');
		$field->set('Type', $fldtype);
		if ($fldtype == 2 || $fldtype == 1) {
			$field->set('FieldSet', $field->get('id'));
		}
		else {
			$sf = intval($args->fid);

			$query = "SELECT
				CustomFormFields.FieldSet AS FieldSet
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.CustomForms='$sf'
				AND CustomFormFields.Type IN (1,
					2)
			ORDER BY
				ListPosition DESC
			LIMIT 1";

			$fldset = EasyContactFormsDB::getValue($query);
			$field->set('FieldSet', $fldset);
		}
		$field->set('Settings', $type->get('Settings'));
		$field->set('CustomForms', $args->fid);
		$field->save();
		$field->updateTemplate();

		$map['m'] = 'viewDetailed';
		EasyContactFormsRoot::processEvent($map);

	}

	/**
	 * 	updateTemplate
	 *
	 *
	 * @return
	 * 
	 */
	function updateTemplate() {

		$text =  $this->fillTemplate('tpl', $this->get('Settings'));
		parent::update(array('Template' => $text), $this->get('id'));
		$forms = EasyContactFormsClassLoader::getObject('CustomForms');
		$forms->updateTemplate($this->get('CustomForms'));

	}

	/**
	 * 	fillTemplate
	 *
	 * @param  $tpl
	 * 
	 * @param  $typesettings
	 * 
	 *
	 * @return
	 * 
	 */
	function fillTemplate($tpl, $typesettings) {
		$typexml = simplexml_load_string($typesettings);
		if (!$typexml) return '';
		$xml = simplexml_load_string($this->get('Settings'));
		if (!$xml) return '';
		$vars = array();
		$vars['id'] = $this->get('id');
		$vars['formid'] = $this->get('CustomForms');

		foreach ($typexml->children() as $item) {
			$name = $item->getName();
			if (!isset($xml->$name)) {
				$value = ($name == 'Options') ? $typexml->$name : (string) $typexml->$name;
			}
			else {
				$value = ($name == 'Options') ? $xml->$name : (string) $xml->$name;
			}
			$vars[$name] = $value;
		}

		extract($vars);
		$filename = $this->getTMPFileName($tpl);
		ob_start();
		include $filename;
		$str = ob_get_contents();
		ob_end_clean();
		return $str;

	}

	/**
	 * 	getTMPFileName
	 *
	 * @param  $tpl
	 * 
	 *
	 * @return
	 * 
	 */
	function getTMPFileName($tpl) {

		$ds = DIRECTORY_SEPARATOR;
		$dir1 = dirName(__FILE__) . '/forms/proc';

		$dir2 = str_replace('easy-contact-forms', 'easycontact_templates', plugin_dir_path(__FILE__)) . 'easy-contact-forms/forms/proc';

		if ($tpl == 'sform') {
			$fname = '_frm';
		}
		else if ($tpl == 'tpl') {
			$fname = '_tpl';
		}
		else {
			$fname = '_prc';
		}
		$filename = $dir1 . $ds . $fname . $this->get('Type') . '.php';
		if (!is_file($filename)){
			$filename = $dir2 . $ds . $fname . $this->get('Type') . '.php';
		}

		return $filename;

	}


	/**
	 * 	deleteField
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function deleteField($map) {

		$a = $map['a'];
		$a = stripslashes($a);
		$a = json_decode($a);
		$fldid = $a->a[0];
		$fldid = intval($fldid);

		$cf = EasyContactFormsDB::getValue("SELECT CustomFormFields.CustomForms FROM #wp__easycontactforms_customformfields AS CustomFormFields WHERE CustomFormFields.id = '$fldid'");

		$map['m'] = 'viewDetailed';
		EasyContactFormsRoot::processEvent($map);
		$forms = EasyContactFormsClassLoader::getObject('CustomForms');
		$forms->updateTemplate($cf);

	}

	/**
	 * 	get
	 *
	 * @param  $prop
	 * 
	 *
	 * @return
	 * 
	 */
	function get($prop) {

		if ($prop == 'Processor' && isset($this->fields->Processor)) {
			$id = $this->get('Type');

			$query = "SELECT
				CustomFormFieldTypes.Processor
			FROM
				#wp__easycontactforms_customformfieldtypes AS CustomFormFieldTypes
			WHERE
				CustomFormFieldTypes.id='$id'";

			$this->fields->Processor = EasyContactFormsDB::getValue($query);

		}
		return parent::get($prop);

	}

	/**
	 * 	getAppFieldList
	 *
	 * 	Returns a list of available application fields
	 *
	 * @param  $type
	 * 
	 * @param  $val
	 * 
	 * @param  $id
	 * 
	 * @param  $formid
	 * 
	 *
	 * @return string
	 * 	the list
	 */
	function getAppFieldList($type, $val, $id, $formid) {

		$map = array();
		$map['email']['Users']['email'] = EasyContactFormsT::get('email');
		$map['text']['Users']['Description'] = EasyContactFormsT::get('LastName');
		$map['text']['Users']['Name'] = EasyContactFormsT::get('FirstName');
		$map['text']['Users']['email'] = EasyContactFormsT::get('email');
		$id = intval($id);
		$formid = intval($formid);

		$query = "SELECT
				CustomFormFields.Settings AS Settings
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.id<>'$id'
				AND CustomFormFields.CustomForms='$formid'";

		$settings = EasyContactFormsDB::getObjects($query);

		$links = array();
		foreach ($settings as $text) {
			$xml = simplexml_load_string($text->Settings);
			$link = (string) $xml->LinkToAppField;
			if ($link != '') {
				$links[]= $link;
			}
		}

		$tree = $map[$type];

		echo "<option value=''>...</option>";
		$counter = 0;
		$objtype = '';
		foreach ($tree as $rkey => $rvalue) {
			$counter++;
			if ($counter != 1) {
				echo '</optgroup>';
			}
			echo "<optgroup label='" . EasyContactFormsT::get($rkey) . "'>";
			foreach ($rvalue as $lkey => $lvalue) {
				$key =  "{$rkey}_{$lkey}";
				if (in_array($key, $links)) {
					continue;
				}
				$selected = $key == $val ? ' selected' : '';
				echo "<option value='{$key}'{$selected}>$lvalue</option>";
			}
		}
		echo '</optgroup>';

	}

	/**
	 * 	getFieldsList
	 *
	 * @param  $view
	 * 
	 * @param  $obj
	 * 
	 * @param  $i
	 * 
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function getFieldsList($view, $obj, $i, $map) {

		$listbegin = ($obj->get('Type') == 1 || $obj->get('Type') == 2);
		$listend = $i != 1 && $listbegin;
		$listclass = $obj->get('Type') == 1 ? 'ufo-customform-fieldset' : 'ufo-customform-fieldlist';
		if ($listend) {
			echo '</ul>';
		}
		if ($listbegin) {
			echo "<ul id='ufo-customform-ul-". $obj->get('id') . "' class='ufo-customform-ul $listclass'>";
		}
		$this->getListItem($obj, $listbegin);
		if ($i == $this->rowCount) {
			echo '</ul>';
		}

	}

	/**
	 * 	getListItem
	 *
	 * @param  $obj
	 * 
	 * @param  $headeritem
	 * 
	 *
	 * @return
	 * 
	 */
	function getListItem($obj, $headeritem) {

		$hasrecords = false;
		if ($headeritem) {
			$fldset = $obj->get('id');

			$query = "SELECT
				COUNT(CustomFormFields.id)
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.FieldSet=$fldset";

			$count = EasyContactFormsDB::getValue($query);
			$hasrecords = ($count > 1);
			$lijsconf = str_replace('viewDetailed', 'moveFieldSet', $this->jsconfig);
		}

		?>
<li id='ufo-customform-fld-li-<?php echo $obj->get('id');?>' class='ufo-customform-li ufo-id-link <?php echo $headeritem ? 'ufo-fieldset-header' : '';?> <?php echo $hasrecords ? 'ufo-customform-fieldset-filled' : '';?>'>
  <a title='<?php echo EasyContactFormsT::get('Delete');?>' href='javascript:;' class='icon_cf_field_delele ufo-customform-field-delete ufo-customform-field-image' onclick='ufoCf.deleteField(<?php echo $this->jsconfig;?>, <?php echo $obj->get('id');?>)'></a>
  <?php if ($headeritem) { ?>
    <div class='ufo-customform-field-image'>
      <div title='<?php echo EasyContactFormsT::get('CF_Up');?>' class='icon_cf_fieldset_up' onclick = 'ufo.moveRow(<?php echo $lijsconf;?>, 1, "<?php echo $obj->get('id');?>")'></div>
      <div title='<?php echo EasyContactFormsT::get('CF_Down');?>' class='icon_cf_fieldset_down' onclick = 'ufo.moveRow(<?php echo $lijsconf;?>, -1, "<?php echo $obj->get('id');?>")'></div>
    </div>
  <?php  } ?>
  <div title='<?php echo $obj->get('TypeDescription');?>' class='ufo-customform-field-image <?php echo $obj->get('CssClass');?>'></div>
  <span id='ufo-fld-label-<?php echo $obj->get('id');?>' class='ufo-id-link' onclick='ufoCf.setActiveField(this, <?php echo $obj->get('id');?>)'>
    <?php echo $obj->get('Description');?>
  </span>
</li><?php

	}

	/**
	 * 	getFieldTypes
	 *
	 * 	Returns a list of available field types
	 *
	 * @param  $map
	 * 
	 *
	 * @return string
	 * 	the list
	 */
	function getFieldTypes($map) {

		$rparams = $this->getFilter($map);
		$formid = intval($rparams['CustomForms']->values[0]);

		$query = "SELECT
				COUNT(CustomFormFields.id)
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.CustomForms=$formid";

		$fcount = EasyContactFormsDB::getValue($query);

		$query = "SELECT
				CustomFormFieldTypes.id,
				CustomFormFieldTypes.Description,
				CustomFormFieldTypes.ListPosition,
				CustomFormFieldTypes.CssClass
			FROM
				#wp__easycontactforms_customformfieldtypes AS CustomFormFieldTypes
			ORDER BY
				CustomFormFieldTypes.ListPosition";

		$types = EasyContactFormsDB::getObjects($query);
		$list = '';
		foreach ($types as $type) {

			$config = '{viewTarget:\'CustomFormFieldsDiv\', specialfilter:[{property:\'CustomForms\',value:{values:[' . $formid . ']}}], cfa:{tid:' . $type->id . ', fid:' . $formid . '}, m:\'addCustomField\', t:\'CustomFormFields\', a:{fld:\'CustomForms\', oid:' . $formid . '}}';

			$liclass = ($type->id == 1 || $type->id == 2) ? ' ufo-customform-fieldtype-li-fs' : ' ufo-customform-fieldtype-li';
			$lidclass = $fcount == 0 && !($type->id == 1 || $type->id == 2) ? ' ufo-customform-fieldtype-disabled' : '';
			$list .= '<li';
			$list .= ' class="ufo-id-link ufo-customform-fieldtype' . $liclass . $lidclass. '"';
			$list .= ' id="ufo-customform-fld-type-' . $type->id . '"';
			$list .= ' onclick="ufoCf.addCustomField(this, ' . $config . ')">';
			$list .= '<span>';
			$list .= '<span class="' . $type->CssClass . '">';
			$list .= $type->Description;
			$list .= '</span>';
			$list .= '</span>';
			$list .= '</li>';
		}
		$list = '<ul style="padding:0;">' . $list . '</ul>';
		$toolbar = '<div class="buttons" id="ufo-field-type-buttons">';
		ob_start();

		EasyContactFormsCustomFormFields::getSettingsFormButton('ufo-form-t-introduction', EasyContactFormsT::get('CF_TutorialsIntroduction'), "onclick='window.open(\"http://championforms.com/champion-forms-getting-started/view\")'", 'icon_video_tutorial');

		EasyContactFormsCustomFormFields::getSettingsFormButton('ufo-form-t-field-settings', EasyContactFormsT::get('CF_TutorialsFieldSettings'), "onclick='window.open(\"http://championforms.com/champion-forms-field-settings/view\")'", 'icon_video_tutorial');

		EasyContactFormsCustomFormFields::getSettingsFormButton('ufo-form-t-styling', EasyContactFormsT::get('CF_TutorialsStyling'), "onclick='window.open(\"http://championforms.com/champion-forms-styling/view\")'", 'icon_video_tutorial');

		EasyContactFormsCustomFormFields::getSettingsFormButton('ufo-form-t-client-data-collection', EasyContactFormsT::get('CF_TutorialsClientDataCollection'), "onclick='window.open(\"http://championforms.com/champion-forms-client-data-collection/view\")'", 'icon_video_tutorial');

		$toolbar .= ob_get_contents();
		ob_end_clean();
		$toolbar .= '<div style="clear:left"></div>';
		$toolbar .= '</div>';
		$list = $toolbar . $list;

		$list = '<link href="' . EASYCONTACTFORMS__engineWebAppDirectory . '/forms/fldcons/fldcons.1.4.7.css" rel="stylesheet" type="text/css"/>'  . $list ;

		echo $list;

	}

	/**
	 * 	getSelectOptions
	 *
	 * @param  $id
	 * 
	 * @param  $type
	 * 
	 *
	 * @return
	 * 
	 */
	function getSelectOptions($id, $type) {

		$id = intval($id);

		$query = "SELECT
				CustomFormFields.Settings
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.id=$id";

		$fieldsettings = EasyContactFormsDB::getValue($query);
		$xml = simplexml_load_string($fieldsettings);

		$index = 0;
		$sort = array();
		$sort2 = array();
		foreach ($xml->Options->children() as $option) {
			$oindex = (string) $option->attributes()->index;
			$sort[] = $oindex;
			$sort2[$oindex] = $option[0];
		}
		natsort($sort);
		$counter = 0;
		$valuetoset = null;
		foreach ($sort as $item) {
			$option = $sort2[$item];
			if ($type == 'select') {
				$selected = $option->attributes()->default == 'true' ? ' selected=\'selected\'' : '';
				echo "<option value='$option'$selected>$option</option>";
			}
			if ($type == 'radiogroup') {
				if ($option->attributes()->default == 'true') {
					$valuetoset = $option;
				}
				$checked = $option->attributes()->default == 'true' ? ' checked' : '';
				echo "<div class='ufo-radiogroup-row'>";
				echo "<input style='width:16px;display:inline-block' type='radio'{$checked} id='id-{$id}-{$counter}'";
				echo " name='id-{$id}' value='$option'";

				echo " onclick='ufoForms.get(\"ufo-field-id-{$id}\").value=this.value;var c=ufoForms.els[\"ufo-field-id-{$id}\"];if(c){c.isvalid=true;ufoForms.changeView(true, c);ufoForms.validateForm(c.form);}'/>";

				echo "<label style='display:inline-block' for='id-{$id}-{$counter}'>$option</label>";
				echo "</div>";
				$counter++;
			}
		}
		if ($type == 'radiogroup' && !is_null($valuetoset)) {
			echo "<script>ufoForms.docReady(function(){ufoForms.get(\"ufo-field-id-{$id}\").value='$valuetoset'})</script>";
		}

	}

	/**
	 * 	getSettingsForm
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function getSettingsForm($map) {

		$id = intval($map['oid']);
		$field = EasyContactFormsClassLoader::getObject($this->type, true, $id);
		$type = EasyContactFormsClassLoader::getObject('CustomFormFieldTypes', true, $field->get('Type'));

		echo "<div class='buttons'>";
		echo '<input type="hidden" class="ufo-form-value" id="oid" value="' . $id . '"/>';

		echo "<input type='hidden' value='var c = {};c.id = \"customformfields-btn\";AppMan.addSubmit(c);' class='ufo-eval'/>";

		$field->getSettingsFormButton('customformfields-btn', EasyContactFormsT::get('Apply'), "onclick='ufoCf.updateFieldData(this)'", 'icon_button_save small-submit-button');

		$field->getSettingsFormButton('ufo-form-expandall', EasyContactFormsT::get('CF_ExpandAll'), "onclick='ufoCf.expandAll(this, true)'", 'icon_cf_fieldset_expand');

		$field->getSettingsFormButton('ufo-form-collapseall', EasyContactFormsT::get('CF_CollapseAll'), "onclick='ufoCf.expandAll(this, false)'", 'icon_cf_fieldset_collapse');

		$field->getSettingsFormButton('ufo-form-default', EasyContactFormsT::get('CF_ExpandChecked'), "onclick='ufoCf.expandAll(this, undefined)'", 'icon_cf_fieldset_default');

		$field->getSettingsFormButton('ufo-form-pin', EasyContactFormsT::get('CF_Pin'), "onclick='ufoCf.pinSettingForm()'", 'ufo-customform-fieldform-uppinned');

		if (!$type->isEmpty('HelpLink')) {

			$field->getSettingsFormButton('ufo-form-field-help', $type->get('Description') . '.&nbsp;' . EasyContactFormsT::get('Help'), "onclick='window.open(\"" . $type->get('HelpLink') . "\")'", 'icon_video_tutorial');

		}
		echo "<div style='clear:left'></div>";
		echo "</div>";
		echo $field->fillTemplate('sform', $type->get('Settings'));
		?>
		<div class='ufo-float-left ufo-customform-settings-savebutton-div'>
			<?php echo EasyContactFormsIHTML::getButton(
				array(
					'id' => "ufo-customform-settings-savebutton",
					'label' => EasyContactFormsT::get('Apply'),
					'events' => "onclick='ufoCf.updateFieldData(this)'",
					'iclass' => " class='icon_button_save ufo-id-link small-submit-button' ",
					'bclass' => "button internalimage",
				)
			);?>
		</div>
		<?php
		echo "<div style='clear:left'></div>";

	}

	/**
	 * 	getSettingsFormButton
	 *
	 * @param  $id
	 * 
	 * @param  $title
	 * 
	 * @param  $events
	 * 
	 * @param  $class
	 * 
	 *
	 * @return
	 * 
	 */
	function getSettingsFormButton($id, $title, $events, $class) {

		?>
		<div class='ufo-float-left'>
			<?php echo EasyContactFormsIHTML::getButton(
				array(
					'id' => $id,
					'title' => $title,
					'events' => $events,
					'iclass' => " class='$class ufo-id-link' ",
					'bclass' => "ufo-imagebutton",
				)
			);?>
		</div>
		<?php
	}

	/**
	 * 	getSettingsFormOptionList
	 *
	 * 	Creates an option list
	 *
	 * @param  $xml
	 * 
	 */
	function getSettingsFormOptionList($xml) {

		$index = 0;
		$sort = array();
		$sort2 = array();
		foreach ($xml->children() as $option) {
			$oindex = (string) $option->attributes()->index;
			$sort[] = $oindex;
			$sort2[$oindex] = $option[0];
		}
		natsort($sort);

		?>
<div>
  <div class='buttons'>
    <?php EasyContactFormsCustomFormFields::getSettingsFormButton('ufo-fieldform-option-add', EasyContactFormsT::get('CF_AddOption'), "onclick='ufoCf.addOption(this.id);'", 'icon_button_add'); ?>
    <?php EasyContactFormsCustomFormFields::getSettingsFormButton('ufo-fieldform-option-unset', EasyContactFormsT::get('CF_RemoveDefault'), "onclick='ufoCf.unsetDefault(this.id)'", 'icon_cf_remove_default_option'); ?>
    <div style='clear:left'></div>
  </div>
  <ul id='ufo-customform-fieldform-option-list' class='ufo-customform-fieldform-option-ul ufo-id-link'>
    <?php foreach ($sort as $item) { ?>
    <?php $option = $sort2[$item]; ?>
      <li class='ufo-customform-fieldform-option-li <?php echo count($sort) == 1 ? 'ufo-fieldform-option-single-child' : '';?>'>
        <input type='radio' id='ufo-fieldform-option-default-<?php echo $index;?>' value='<?php echo $option->attributes()->default == 'true' ? 'on' : ''; ?>' name='ufo-fieldform-option-default' title='<?php echo EasyContactFormsT::get('CF_Default');?>' tabindex='-1' <?php echo $option->attributes()->default == 'true' ? 'checked' : ''; ?> class='ufo-formvalue ufo-customform-option-default' onchange='ufoCf.unsetOptionValues();this.value=(this.checked)?"on":"off";'/>
        <input type='text' id='ufo-fieldform-option-li-<?php echo $index;?>' value='<?php echo $option;?>' class='ufo-formvalue textinput ufo-text ufo-fieldform-option-li'/>
        <a id='ufo-fieldform-option-add-<?php echo $index;?>' title='<?php echo EasyContactFormsT::get('Add');?>' href='javascript:;' class='icon_button_add ufo-customform-option-add ufo-id-link' onclick='ufoCf.addOption(this.id);'></a>
        <a id='ufo-fieldform-option-delete-<?php echo (++$index);?>' title='<?php echo EasyContactFormsT::get('Delete');?>' class='icon_button_delete ufo-customform-option-delete ufo-id-link' onclick='ufoCf.deleteOption(this);'></a>
      </li>
    <?php } ?>
  </ul>
</div><?php

	}

	/**
	 * 	moveFieldSet
	 *
	 * 	Moves a fieldset up and down
	 *
	 * @param  $map
	 * 
	 */
	function moveFieldSet($map) {

		$filter = array();
		$filter['fnames'] = array();
		$filter['fnames'][] = 'CustomFormFields.Type IN (:type)';
		$filter['fvalues'] = array();
		$filter['fvalues'][':type'] = array();;
		$filter['fvalues'][':type'][] = 1;
		$filter['fvalues'][':type'][] = 2;

		$rparams = $this->getFilter($map);
		$filter['fnames'][] = 'CustomFormFields.CustomForms = :CustomForms';
		$cf = intval($rparams['CustomForms']->values[0]);
		$filter['fvalues'][':CustomForms'] = $cf;

		EasyContactFormsRoot::moveRow('CustomFormFields', $map, $filter, 'CustomFormFields');
		$forms = EasyContactFormsClassLoader::getObject('CustomForms');
		$forms->updateTemplate($cf);

		$map['m'] = 'viewDetailed';
		EasyContactFormsRoot::processEvent($map);

	}

	/**
	 * 	updates field settings
	 *
	 * @param array $map
	 * 	request data
	 */
	function updateFieldData($map) {
		$values = $map["a"];
		$values = json_decode(stripslashes($values));
		$form = $values[0];
		$objid = intval($form->oid);
		$data = $form->a;
		$fld = EasyContactFormsClassLoader::getObject('CustomFormFields', true, $objid);

		$flds = array();
		$type = $fld->get('Type');

		$query = "SELECT
				CustomFormFieldTypes.Settings
			FROM
				#wp__easycontactforms_customformfieldtypes AS CustomFormFieldTypes
			WHERE
				CustomFormFieldTypes.id=$type";

		$typesettings = EasyContactFormsDB::getValue($query);
		$fieldsettings = $fld->get('Settings');

		$typexml = simplexml_load_string($typesettings);
		$fldxml = new EasyContactFormsSimpleXML($fieldsettings);

		foreach ($typexml->children() as $item) {
			$name = $item->getName();
			if (isset($data->$name)) {
				$value = $data->$name;
				$value = str_replace('&', '&amp;', $value);
				$value = str_replace(chr(39), '&#39;', $value);
				unset($fldxml->$name);
				$fldxml->addCDATA($name, $value);
				if ($name == 'Label') {
					$flds['Description'] = $value;
				}
			}
			else {
				$typeval = (string) $typexml->$name;
				if (!isset($fldxml->$name) && !empty($typeval)) {
					$fldxml->$name = $typeval;
				}
			}
		}

		$options = array();

		foreach ($data as $key => $value) {
			if (! EasyContactFormsUtils::beginsWith($key, 'ufo-fieldform-option-li')) {
				continue;
			}
			$options[$key] = $value;
		}
		if (sizeof($options) > 0) {
			unset($fldxml->Options);
			$fldxml->Options['type']='items';
			foreach ($options as $key => $value) {
				$value = htmlspecialchars($value, ENT_QUOTES);
				$option = $fldxml->Options->addChild('option', $value);
				$oid = explode('-', $key);
				$oid = $oid[count($oid)-1];
				$option['index'] = $oid;
				$dname = "ufo-fieldform-option-default-$oid";
				if ($data->$dname == 'on') {
					$option['default'] = 'true';
				}
			}
		}

		$phase = (object) array('index' => 2);
		include $fld->getTMPFileName('proc');
		$xml = $fldxml->asXML();

		$flds['Settings'] = $xml;
		parent::update($flds, $objid);
		$fld->set('Settings', $xml);
		$fld->updateTemplate();

	}

	/**
	 * 	updateOrder
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function updateOrder($map) {

		$input = $map['a'];
		$input = explode(':', $input);
		$fsid = $input[0];
		$fsid = explode('-', $fsid);
		$fsid = $fsid[count($fsid)-1];
		$fsid = intval($fsid);
		if ($fsid == 0) {
			return;
		}
		$input = explode('&', $input[1]);
		$ids = array();
		$ids[] = $fsid;
		foreach ($input as $items) {
			$items = explode('[]=', $items);
			$id = $items[1];
			$ids[] = intval($id);
		}
		$sqlids = implode(',', $ids);

		$query = "SELECT
				CustomFormFields.id,
				CustomFormFields.CustomForms,
				CustomFormFields.ListPosition AS ListPosition
			FROM
				#wp__easycontactforms_customformfields AS CustomFormFields
			WHERE
				CustomFormFields.id IN (%s)
			ORDER BY
				ListPosition";

		$query = sprintf($query, $sqlids);
		$objects = EasyContactFormsDB::getObjects($query);

		$lp = array();
		for ($i = 0; $i < count($ids); $i++) {
			$lp[$ids[$i]] = $objects[$i]->ListPosition;
			$cf = $objects[$i]->CustomForms;
		}
		if (!isset($cf)) {
			return;
		}

		$cff = EasyContactFormsClassLoader::getObject('CustomFormFields');
		foreach ($lp as $id => $l) {
			$fields = array();
			$fields['ListPosition'] = $l;
			$fields['FieldSet'] = $fsid;
			$cff->update($fields, $id);
		}
		$cfo = EasyContactFormsClassLoader::getObject('CustomForms');
		$cfo->updateTemplate($cf);

	}

	/**
	 * 	getFieldSetList
	 *
	 * @param  $params
	 * 
	 *
	 * @return
	 * 
	 */
	function getFieldSetList($params) {

		$query="SELECT CustomFormFields.id, CustomFormFields.Description FROM #wp__easycontactforms_customformfields AS CustomFormFields WHERE CustomFormFields.CustomForms = '%CustomForms' AND CustomFormFields.Type = 1";

		return $this->getList($query, $params);
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

			case 'addCustomField':
				$this->addCustomField($dispmap);
				return NULL;

			case 'copyField':
				$this->copyField($dispmap);
				return NULL;

			case 'deleteField':
				$this->deleteField($dispmap);
				return NULL;

			case 'getSettingsForm':
				$this->getSettingsForm($dispmap);
				return NULL;

			case 'moveFieldSet':
				$this->moveFieldSet($dispmap);
				return NULL;

			case 'updateFieldData':
				$this->updateFieldData($dispmap);
				return NULL;

			case 'updateOrder':
				$this->updateOrder($dispmap);
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

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();
		$viewfilters = EasyContactFormsDB::getFilter($viewfilters, $rparams, 'CustomFormFields.', 'CustomForms', '=', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormFields.', 'Description');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormFields.', 'Type', 'int');
		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormFields.', 'FieldSet', 'int');
		EasyContactFormsRoot::mDelete('CustomFormFields', $viewmap);

		$query = "SELECT
				CustomFormFields.id,
				CustomFormFields.Description,
				CustomFormFields.ListPosition,
				CustomFormFields.CssClass,
				CustomFormFields.TypeDescription,
				CustomFormFields.FieldSet,
				CustomFormFields.CustomForms,
				CustomFormFields.Type,
				CustomFormFields.FieldSetListPosition,
				CustomFormFields.Container
				FROM(
				SELECT
					CustomFormFields.id,
					CustomFormFields.Description,
					CustomFormFields.ListPosition,
					CustomFormFieldTypes.CssClass,
					CustomFormFieldTypes.Description AS TypeDescription,
					CustomFormFields.FieldSet,
					CustomFormFields.CustomForms,
					CustomFormFields.Type,
					FieldSetListPosition.ListPosition AS FieldSetListPosition,
					IF(CustomFormFields.Type=1
						OR CustomFormFields.Type=2,
						1,
						0) AS Container
				FROM
					#wp__easycontactforms_customformfields AS CustomFormFields
				LEFT JOIN
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
				ORDER BY
					FieldSetListPosition,
					Container DESC,
					ListPosition) AS CustomFormFields";

		$this->start = isset($viewmap['start']) ? intval($viewmap['start']) : 0;
		$this->limit = isset($viewmap['limit']) ? intval($viewmap['limit']) : 500;
		$this->rowCount = EasyContactFormsDB::getRowCount($query, $viewfilters);

		$resultset = EasyContactFormsDB::select($query, $viewfilters, NULL, $this);

		$params = array();
		$params['CustomForms'] = $rparams['CustomForms']->values[0];
		$this->FieldSet = $this->getFieldSetList($params);

		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomFormFields'><?php

		include 'views/easy-contact-forms-customformfieldsdetailedmainview.php';

	}

}
