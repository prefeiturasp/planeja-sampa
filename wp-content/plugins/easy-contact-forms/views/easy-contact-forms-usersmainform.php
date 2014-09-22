<?php
/**
 * @file
 *
 * 	EasyContactFormsUsers main form html template
 *
 * 	@see EasyContactFormsUsers::getMainForm()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */


EasyContactFormsLayout::getFormHeader('ufo-formpage ufo-mainform ufo-' . strtolower($obj->type));
echo EasyContactFormsUtils::getTypeFormDescription($obj->getId(), 'Users', 'Description,Name', '%s, %s');
EasyContactFormsLayout::getFormHeader2Body();

?>
  <div>
    <?php EasyContactFormsLayout::getTabHeader(array('GeneralInfo', 'ContactInfo', 'History', 'More'), 'top');?>
    <div class='ufo-tab-wrapper ufo-tab-top'>
      <div id='GeneralInfo' class='ufo-tabs ufo-tab ufo-active'>
        <div>
          <div class='ufo-float-left ufo-width50'>
            <div>
              <label for='Description'>
                 <?php echo EasyContactFormsT::get('LastName');?>
                 <span class='mandatoryast'>*</span>
              </label>
              <input type='text' id='Description' value='<?php echo $obj->get('Description');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
              <input type='hidden' value='var c = {};c.id = "Description";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.required={};c.required.msg=AppMan.resources.ThisFieldIsRequired;c.events.blur.push("required");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
              <div id='Description-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
            </div>
            <div>
              <label for='ContactType'><?php echo EasyContactFormsT::get('ContactType');?></label>
              <select id='ContactType' class='inputselect ufo-select ufo-formvalue' style='width:100%'>
                 <?php echo $obj->getListHTML(NULL, 'ContactType', TRUE, 'ContactTypes');?>
              </select>
            </div>
            <div>
              <label for='Role'><?php echo EasyContactFormsT::get('Role');?></label>
              <select id='Role' class='inputselect ufo-select ufo-formvalue' style='width:100%'>
                 <?php echo $obj->getRoleListHTML('Role', TRUE);?>
              </select>
            </div>
          </div>
          <div class='ufo-float-right ufo-width50'>
            <div>
              <label for='Name'><?php echo EasyContactFormsT::get('FirstName');?></label>
              <input type='text' id='Name' value='<?php echo $obj->get('Name');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
              <input type='hidden' value='var c = {};c.id = "Name";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("100");c.max="100";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
              <div id='Name-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
            </div>
            <div>
              <label for='CMSId'><?php echo EasyContactFormsT::get('CMSId');?></label>
              <?php EasyContactFormsIHTML::getAS($obj->CMSId);?>
            </div>
          </div>
          <div style='clear:left'></div>
        </div>
        <div>
          <div>
            <label for='Notes' class='ufo-label-top'><?php echo EasyContactFormsT::get('Notes');?></label>
            <?php if (EasyContactFormsApplicationSettings::getInstance()->get('UseTinyMCE')) : 
              EasyContactFormsIHTML::getTinyMCE('Notes');
            endif; ?>
            <textarea id='Notes' class='ufo-formvalue' style='width:100%;height:200px'><?php echo $obj->get('Notes');?></textarea>
          </div>
        </div>
      </div>
      <div id='ContactInfo' class='ufo-tabs ufo-tab'>
        <div class='ufo-float-left ufo-width50'>
          <div>
            <label for='Birthday'><?php echo EasyContactFormsT::get('Birthday');?></label>
            <div class='ufo-input-wrapper'>
              <input type='text' id='Birthday' value='<?php echo $obj->Birthday;?>' READONLY class='ufo-date datebox ufo-internal ufo-formvalue'/>
              <a id='Birthday-Trigger' href='javascript:;' class='ufo-triggerbutton icon_trigger_calendar'>&nbsp;&nbsp;</a>
            </div>
            <input type='hidden' value='ufo.setupCalendar("Birthday", {ifFormat:"<?php echo EasyContactFormsApplicationSettings::getInstance()->getDateFormat('JS'); ?>", firstDay:0, align:"Bl", singleClick:true});' class='ufo-eval'/>
          </div>
          <div>
            <label for='Country'><?php echo EasyContactFormsT::get('Country');?></label>
            <input type='text' id='Country' value='<?php echo $obj->get('Country');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "Country";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("300");c.max="300";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='Country-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='City'><?php echo EasyContactFormsT::get('City');?></label>
            <input type='text' id='City' value='<?php echo $obj->get('City');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "City";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("300");c.max="300";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='City-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='State'><?php echo EasyContactFormsT::get('State');?></label>
            <input type='text' id='State' value='<?php echo $obj->get('State');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "State";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("300");c.max="300";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='State-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='Zip'><?php echo EasyContactFormsT::get('Zip');?></label>
            <input type='text' id='Zip' value='<?php echo $obj->get('Zip');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "Zip";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("20");c.max="20";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='Zip-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='Address' class='ufo-label-top'><?php echo EasyContactFormsT::get('Address');?></label>
            <textarea id='Address' class='textinput ufo-textarea ufo-formvalue' style='width:100%;height:110px'><?php echo $obj->get('Address');?></textarea>
          </div>
        </div>
        <div class='ufo-float-right ufo-width50'>
          <div>
            <label for='email'><?php echo EasyContactFormsT::get('email');?></label>
            <input type='text' id='email' value='<?php echo $obj->get('email');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "email";c.events = {};c.events.blur = [];c.email={};c.email.msg=AppMan.resources.EmailFormatIsExpected;c.events.blur.push("email");c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("100");c.max="100";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='email-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='email2'><?php echo EasyContactFormsT::get('email2');?></label>
            <input type='text' id='email2' value='<?php echo $obj->get('email2');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "email2";c.events = {};c.events.blur = [];c.email={};c.email.msg=AppMan.resources.EmailFormatIsExpected;c.events.blur.push("email");c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("100");c.max="100";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='email2-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='Cell'><?php echo EasyContactFormsT::get('Cell');?></label>
            <input type='text' id='Cell' value='<?php echo $obj->get('Cell');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "Cell";c.events = {};c.events.blur = [];c.phonenumber={};c.phonenumber.msg=AppMan.resources.ThisIsAPhoneNumber;c.events.blur.push("phonenumber");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='Cell-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='Phone1'><?php echo EasyContactFormsT::get('Phone1');?></label>
            <input type='text' id='Phone1' value='<?php echo $obj->get('Phone1');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "Phone1";c.events = {};c.events.blur = [];c.phonenumber={};c.phonenumber.msg=AppMan.resources.ThisIsAPhoneNumber;c.events.blur.push("phonenumber");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='Phone1-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='Phone2'><?php echo EasyContactFormsT::get('Phone2');?></label>
            <input type='text' id='Phone2' value='<?php echo $obj->get('Phone2');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "Phone2";c.events = {};c.events.blur = [];c.phonenumber={};c.phonenumber.msg=AppMan.resources.ThisIsAPhoneNumber;c.events.blur.push("phonenumber");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='Phone2-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='Phone3'><?php echo EasyContactFormsT::get('Phone3');?></label>
            <input type='text' id='Phone3' value='<?php echo $obj->get('Phone3');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "Phone3";c.events = {};c.events.blur = [];c.phonenumber={};c.phonenumber.msg=AppMan.resources.ThisIsAPhoneNumber;c.events.blur.push("phonenumber");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='Phone3-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='SkypeId'><?php echo EasyContactFormsT::get('SkypeId');?></label>
            <input type='text' id='SkypeId' value='<?php echo $obj->get('SkypeId');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "SkypeId";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("100");c.max="100";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='SkypeId-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
          <div>
            <label for='Website'><?php echo EasyContactFormsT::get('Website');?></label>
            <input type='text' id='Website' value='<?php echo $obj->get('Website');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
            <input type='hidden' value='var c = {};c.id = "Website";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
            <div id='Website-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
          </div>
        </div>
        <div style='clear:left'></div>
      </div>
      <div id='History' class='ufo-tabs ufo-tab'>
        <div>
          <label for='Comment' class='ufo-label-top'><?php echo EasyContactFormsT::get('Comment');?></label>
          <?php if (EasyContactFormsApplicationSettings::getInstance()->get('UseTinyMCE')) : 
            EasyContactFormsIHTML::getTinyMCE('Comment');
          endif; ?>
          <textarea id='Comment' class='ufo-formvalue' style='width:100%;height:160px'><?php echo $obj->get('Comment');?></textarea>
        </div>
        <div>
          <label class='ufo-label-top'><?php echo EasyContactFormsT::get('History');?></label>
          <div class='ufo-y-overflow'>
            <div style='width:100%;height:207px'><?php echo $obj->get('History');?></div>
          </div>
        </div>
      </div>
      <div id='More' class='ufo-tabs ufo-tab'>
        <div>
          <div>
            <label for='ContactField3' class='ufo-label-top'><?php echo EasyContactFormsT::get('ContactField3');?></label>
            <?php if (EasyContactFormsApplicationSettings::getInstance()->get('UseTinyMCE')) : 
              EasyContactFormsIHTML::getTinyMCE('ContactField3');
            endif; ?>
            <textarea id='ContactField3' class='ufo-formvalue' style='width:100%'><?php echo $obj->get('ContactField3');?></textarea>
          </div>
          <div>
            <label for='ContactField4' class='ufo-label-top'><?php echo EasyContactFormsT::get('ContactField4');?></label>
            <textarea id='ContactField4' class='textinput ufo-textarea ufo-formvalue' style='width:100%;height:212px'><?php echo $obj->get('ContactField4');?></textarea>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class='ufo-float-left'>
      <?php echo EasyContactFormsIHTML::getButton(
        array(
          'id' => "OK",
          'label' => EasyContactFormsT::get('OK'),
          'events' => " onclick='ufo.save($obj->jsconfig)'",
          'iclass' => " class='icon_button_save ufo-id-link' ",
          'bclass' => "button internalimage",
        )
      );?>
      <input type='hidden' value='var c = {};c.id = "OK";AppMan.addSubmit(c);' class='ufo-eval'/>
    </div>
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
    <div class='ufo-float-left'>
      <?php echo EasyContactFormsIHTML::getButton(
        array(
          'label' => EasyContactFormsT::get('Back'),
          'events' => " onclick='ufo.back()'",
          'iclass' => " class='icon_button_back' ",
          'bclass' => "button internalimage",
        )
      );?>
    </div>
    <div style='clear:left'></div>
  </div><?php

EasyContactFormsLayout::getFormBodyFooter();
