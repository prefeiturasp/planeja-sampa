<?php
/**
 * @file
 *
 * 	EasyContactFormsApplicationSettings main form html template
 *
 * 	@see EasyContactFormsApplicationSettings::getMainForm()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */


EasyContactFormsLayout::getFormHeader('ufo-formpage ufo-mainform ufo-' . strtolower($obj->type));
echo EasyContactFormsUtils::getTypeFormDescription($obj->getId(), 'ApplicationSettings');
EasyContactFormsLayout::getFormHeader2Body();

?>
  <div>
    <?php EasyContactFormsLayout::getTabHeader(array('GeneralSettings', 'AdditionalSettings', 'TinyMCESettings'), 'top');?>
    <div class='ufo-tab-wrapper ufo-tab-top'>
      <div id='GeneralSettings' class='ufo-tabs ufo-tab ufo-active'>
        <div class='ufo-float-left ufo-width50'>
          <label for='ShowPoweredBy'>
            <input type='checkbox' id='ShowPoweredBy' value='<?php echo $obj->ShowPoweredBy;?>' <?php echo $obj->ShowPoweredByChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";'/>
            <?php echo EasyContactFormsT::get('ShowPoweredBy');?>
          </label>
          <label for='SecretWord'>
            <?php echo EasyContactFormsT::get('SecretWord');?>
            <span id='SecretWordHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='SecretWordHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_SecretWord');?>' class='ufo-id-link'/>
          <input type='text' id='SecretWord' value='<?php echo $obj->get('SecretWord');?>' class='textinput ufo-text ufo-formvalue' style='width:230px'/>
          <input type='hidden' value='var c = {};c.id = "SecretWord";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("50");c.max="50";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
          <div id='SecretWord-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          <label for='DateFormat'>
            <?php echo EasyContactFormsT::get('DateFormatLabel');?>
            <span id='DateFormatHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='DateFormatHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_DateFormatLabel');?>' class='ufo-id-link'/>
          <select id='DateFormat' class='inputselect ufo-select ufo-formvalue' style='width:230px'>
            <?php echo $obj->getListHTML($obj->DateFormat,'DateFormat', TRUE); ?>
          </select>
          <label for='PhoneRegEx'>
            <?php echo EasyContactFormsT::get('PhoneRegEx');?>
            <span id='PhoneRegExHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='PhoneRegExHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_PhoneRegEx');?>' class='ufo-id-link'/>
          <input type='text' id='PhoneRegEx' value='<?php echo $obj->get('PhoneRegEx');?>' class='textinput ufo-text ufo-formvalue' style='width:230px'/>
          <input type='hidden' value='var c = {};c.id = "PhoneRegEx";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("100");c.max="100";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
          <div id='PhoneRegEx-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          <label for='FileFolder'>
            <?php echo EasyContactFormsT::get('FileFolder');?>
            <span class='mandatoryast'>*</span>
            <span id='FileFolderHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='FileFolderHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_FileFolder');?>' class='ufo-id-link'/>
          <input type='text' id='FileFolder' value='<?php echo $obj->get('FileFolder');?>' class='textinput ufo-text ufo-formvalue' style='width:230px'/>
          <input type='hidden' value='var c = {};c.id = "FileFolder";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("100");c.max="100";c.events.blur.push("minmax");c.required={};c.required.msg=AppMan.resources.ThisFieldIsRequired;c.events.blur.push("required");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
          <div id='FileFolder-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          <label for='FixJSLoading'>
            <input type='checkbox' id='FixJSLoading' value='<?php echo $obj->FixJSLoading;?>' <?php echo $obj->FixJSLoadingChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";ufo.cbDisable(this, false, ["FixJSLoading2"]);'/>
            <?php echo EasyContactFormsT::get('FixJSLoading');?>
            <span id='FixJSLoadingHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='FixJSLoadingHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_FixJSLoading');?>' class='ufo-id-link'/>
          <label for='FixJSLoading2'>
            <input type='checkbox' id='FixJSLoading2' value='<?php echo $obj->FixJSLoading2;?>' <?php echo $obj->FixJSLoading2Checked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";' <?php echo $obj->FixJSLoading2Disabled;?>/>
            <?php echo EasyContactFormsT::get('FixJSLoading2');?>
          </label>
          <label for='FixStatus0'>
            <input type='checkbox' id='FixStatus0' value='<?php echo $obj->FixStatus0;?>' <?php echo $obj->FixStatus0Checked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";'/>
            <?php echo EasyContactFormsT::get('FixStatus0');?>
            <span id='FixStatus0Hin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='FixStatus0Hint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_FixStatus0');?>' class='ufo-id-link'/>
          <label for='FixStatus02'>
            <input type='checkbox' id='FixStatus02' value='<?php echo $obj->FixStatus02;?>' <?php echo $obj->FixStatus02Checked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";'/>
            <?php echo EasyContactFormsT::get('FixStatus02');?>
            <span id='FixStatus02Hin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='FixStatus02Hint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_FixStatus02');?>' class='ufo-id-link'/>
          <label for='w3cCompliant'>
            <input type='checkbox' id='w3cCompliant' value='<?php echo $obj->w3cCompliant;?>' <?php echo $obj->w3cCompliantChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";'/>
            <?php echo EasyContactFormsT::get('w3cCompliant');?>
            <span id='w3cCompliantHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='w3cCompliantHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_w3cCompliant');?>' class='ufo-id-link'/>
          <label for='w3cStyle'>
            <?php echo EasyContactFormsT::get('DefaultW3CStyle');?>
            <span id='w3cStyleHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='w3cStyleHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_DefaultW3CStyle');?>' class='ufo-id-link'/>
          <select id='w3cStyle' class='textinput ufo-formvalue ufo-select inputselect' style='width:230px'>
            <?php echo $obj->getAvaliableFormStyles();?>
          </select>
          <label for='AllowMarkupInEntries'>
            <input type='checkbox' id='AllowMarkupInEntries' value='<?php echo $obj->AllowMarkupInEntries;?>' <?php echo $obj->AllowMarkupInEntriesChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";'/>
            <?php echo EasyContactFormsT::get('AllowMarkupInEntries');?>
          </label>
        </div>
        <div class='ufo-float-right ufo-width50'>
          <fieldset>
            <legend>
              <?php echo EasyContactFormsT::get('EmailSettings');?>
            </legend>
            <label for='SendFrom'>
              <?php echo EasyContactFormsT::get('SendFromAddress');?>
              <span id='SendFromHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
            </label>
            <input type='hidden' id='SendFromHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_SendFromAddress');?>' class='ufo-id-link'/>
            <input type='text' id='SendFrom' value='<?php echo $obj->get('SendFrom');?>' class='textinput ufo-text ufo-formvalue' style='width:230px'/>
            <input type='hidden' value='var c = {};c.id = "SendFrom";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("100");c.max="100";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='SendFrom-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
            <label for='FormCompletionMinTime'>
              <?php echo EasyContactFormsT::get('FormCompletionMinTime');?>
              <span id='FormCompletionMinTimeHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
            </label>
            <input type='hidden' id='FormCompletionMinTimeHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_FormCompletionMinTime');?>' class='ufo-id-link'/>
            <input type='text' id='FormCompletionMinTime' value='<?php echo $obj->get('FormCompletionMinTime');?>' class='textinput ufo-text ufo-formvalue' style='width:230px'/>
            <input type='hidden' value='var c = {};c.id = "FormCompletionMinTime";c.events = {};c.events.blur = [];c.integer={};c.integer.msg=AppMan.resources.ThisIsAnIntegerField;c.events.blur.push("integer");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='FormCompletionMinTime-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
            <label for='FormCompletionMaxTime'>
              <?php echo EasyContactFormsT::get('FormCompletionMaxTime');?>
              <span id='FormCompletionMaxTimeHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
            </label>
            <input type='hidden' id='FormCompletionMaxTimeHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_FormCompletionMaxTime');?>' class='ufo-id-link'/>
            <input type='text' id='FormCompletionMaxTime' value='<?php echo $obj->get('FormCompletionMaxTime');?>' class='textinput ufo-text ufo-formvalue' style='width:230px'/>
            <input type='hidden' value='var c = {};c.id = "FormCompletionMaxTime";c.events = {};c.events.blur = [];c.integer={};c.integer.msg=AppMan.resources.ThisIsAnIntegerField;c.events.blur.push("integer");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='FormCompletionMaxTime-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </fieldset>
          <label for='NotLoggenInText' class='ufo-label-top'>
            <?php echo EasyContactFormsT::get('NotLoggedInText');?>
            <span id='NotLoggenInTextHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='NotLoggenInTextHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_NotLoggedInText');?>' class='ufo-id-link'/>
          <textarea id='NotLoggenInText' class='textinput ufo-textarea ufo-formvalue' style='width:100%;height:100px'><?php echo $obj->get('NotLoggenInText');?></textarea>
        </div>
        <div style='clear:left'></div>
      </div>
      <div id='AdditionalSettings' class='ufo-tabs ufo-tab'>
        <div>
          <label for='ApplicationWidth2'>
            <?php echo EasyContactFormsT::get('AdminPartApplicationWidth');?>
            <span id='ApplicationWidth2Hin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='ApplicationWidth2Hint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_AdminPartApplicationWidth');?>' class='ufo-id-link'/>
          <input type='text' id='ApplicationWidth2' value='<?php echo $obj->get('ApplicationWidth2');?>' class='ufo-text ufo-formvalue' style='width:230px'/>
        </div>
        <div>
          <label for='ApplicationWidth'>
            <?php echo EasyContactFormsT::get('ApplicationWidth');?>
            <span id='ApplicationWidthHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='ApplicationWidthHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_ApplicationWidth');?>' class='ufo-id-link'/>
          <input type='text' id='ApplicationWidth' value='<?php echo $obj->get('ApplicationWidth');?>' class='textinput ufo-text ufo-formvalue' style='width:230px'/>
          <input type='hidden' value='var c = {};c.id = "ApplicationWidth";c.events = {};c.events.blur = [];c.integer={};c.integer.msg=AppMan.resources.ThisIsAnIntegerField;c.events.blur.push("integer");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
          <div id='ApplicationWidth-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
        </div>
        <div>
          <label for='DefaultStyle'>
            <?php echo EasyContactFormsT::get('DefaultStyle');?>
            <span id='DefaultStyleHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='DefaultStyleHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_DefaultStyle');?>' class='ufo-id-link'/>
          <input type='text' id='DefaultStyle' value='<?php echo $obj->get('DefaultStyle');?>' class='textinput ufo-text ufo-formvalue' style='width:230px'/>
          <input type='hidden' value='var c = {};c.id = "DefaultStyle";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("50");c.max="50";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
          <div id='DefaultStyle-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
        </div>
        <div>
          <label for='DefaultStyle2'>
            <?php echo EasyContactFormsT::get('AdminPartDefaultStyle');?>
            <span id='DefaultStyle2Hin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
          </label>
          <input type='hidden' id='DefaultStyle2Hint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_AdminPartDefaultStyle');?>' class='ufo-id-link'/>
          <input type='text' id='DefaultStyle2' value='<?php echo $obj->get('DefaultStyle2');?>' class='ufo-text ufo-formvalue' style='width:230px'/>
        </div>
      </div>
      <div id='TinyMCESettings' class='ufo-tabs ufo-tab'>
        <label for='UseTinyMCE'>
          <input type='checkbox' id='UseTinyMCE' value='<?php echo $obj->UseTinyMCE;?>' <?php echo $obj->UseTinyMCEChecked;?> class='ufo-cb checkbox ufo-formvalue' style='margin-top:0;margin-bottom:0' onchange='this.value=(this.checked)?"on":"off";'/>
          <?php echo EasyContactFormsT::get('UseTinyMCE');?>
          <span id='UseTinyMCEHin' class='ufo-label-hint ufo-id-link'>[<a>?</a>]</span>
        </label>
        <input type='hidden' id='UseTinyMCEHint' value='<?php echo EasyContactFormsT::get('Hint_ApplicationSettings_UseTinyMCE');?>' class='ufo-id-link'/>
        <label for='TinyMCEConfig' class='ufo-label-top'><?php echo EasyContactFormsT::get('TinyMCEConfig');?></label>
        <textarea id='TinyMCEConfig' class='textinput ufo-textarea ufo-formvalue' style='width:100%;height:330px'><?php echo $obj->get('TinyMCEConfig');?></textarea>
      </div>
    </div>
  </div>
  <div>
    <div class='ufo-float-left'>
      <?php echo EasyContactFormsIHTML::getButton(
        array(
          'id' => "Apply",
          'label' => EasyContactFormsT::get('Apply'),
          'events' => " onclick='ufo.apply($obj->jsconfig)'",
          'iclass' => " class='icon_button_apply ufo-id-link' ",
          'bclass' => "button internalimage",
        )
      );?>
      <input type='hidden' value='var c = {};c.id = "Apply";AppMan.addSubmit(c);' class='ufo-eval'/>
    </div>
    <div style='clear:left'></div>
  </div><?php

EasyContactFormsLayout::getFormBodyFooter();
