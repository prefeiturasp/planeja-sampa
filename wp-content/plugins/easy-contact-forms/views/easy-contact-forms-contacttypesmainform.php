<?php
/**
 * @file
 *
 * 	EasyContactFormsContactTypes main form html template
 *
 * 	@see EasyContactFormsContactTypes::getMainForm()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */


EasyContactFormsLayout::getFormHeader('ufo-formpage ufo-simple ufo-mainform ufo-' . strtolower($obj->type));
echo EasyContactFormsUtils::getTypeFormDescription($obj->getId(), 'ContactTypes');
EasyContactFormsLayout::getFormHeader2Body();

?>
  <div>
    <div>
      <label for='Description'>
        <?php echo EasyContactFormsT::get('Description');?>
        <span class='mandatoryast'>*</span>
      </label>
      <input type='text' id='Description' value='<?php echo $obj->get('Description');?>' class='textinput ufo-text ufo-formvalue' style='width:100%'/>
      <input type='hidden' value='var c = {};c.id = "Description";c.events = {};c.events.blur = [];c.minmax={};c.minmax.msg=AppMan.resources.ValueLengthShouldBeLessThan;c.minmax.args=[];c.minmax.args.push("200");c.max="200";c.events.blur.push("minmax");c.required={};c.required.msg=AppMan.resources.ThisFieldIsRequired;c.events.blur.push("required");c.InvalidCSSClass = "ufo-fields-invalid-field";AppMan.addValidation(c);' class='ufo-eval'/>
      <div id='Description-invalid' class='ufo-fields-invalid-value ufo-id-link' style='position:absolute;display:none'></div>
    </div>
    <div>
      <label for='Notes' class='ufo-label-top'><?php echo EasyContactFormsT::get('Notes');?></label>
      <?php if (EasyContactFormsApplicationSettings::getInstance()->get('UseTinyMCE')) : 
        EasyContactFormsIHTML::getTinyMCE('Notes');
      endif; ?>
      <textarea id='Notes' class='ufo-formvalue' style='width:100%'><?php echo $obj->get('Notes');?></textarea>
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
