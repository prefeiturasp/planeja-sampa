<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormsEntries main form html template
 *
 * 	@see EasyContactFormsCustomFormsEntries::getMainForm()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */


EasyContactFormsLayout::getFormHeader('ufo-formpage ufo-mainform ufo-' . strtolower($obj->type));
echo EasyContactFormsUtils::getTypeFormDescription($obj->getId(), 'CustomFormsEntries', 'id', 'Entry id:%d');
EasyContactFormsLayout::getFormHeader2Body();

?>
  <div>
    <?php EasyContactFormsLayout::getTabHeader(array('GeneralInfo', 'CustomFormEntryFiles'), 'top');?>
    <div class='ufo-tab-wrapper ufo-tab-top'>
      <div id='GeneralInfo' class='ufo-tabs ufo-tab ufo-active'>
        <div>
          <div style='width:100%'>
            <label><?php echo EasyContactFormsT::get('CustomForm');?></label>
            <span>
              <?php echo $obj->get('CustomFormsDescription');?>
            </span>
          </div>
        </div>
        <div>
          <div class='ufo-float-left ufo-width50'>
            <div style='width:100%'>
              <label><?php echo EasyContactFormsT::get('Date');?></label>
              <?php EasyContactFormsIHTML::echoDate($obj->get('Date'), EasyContactFormsApplicationSettings::getInstance()->getDateFormat('PHP', TRUE), 0);?>
            </div>
          </div>
          <div class='ufo-float-right ufo-width50'>
            <div style='width:100%'>
              <label><?php echo EasyContactFormsT::get('PageName');?></label>
              <?php echo $obj->get('PageName');?>
            </div>
          </div>
          <div style='clear:left'></div>
        </div>
        <div>
          <div>
            <label class='ufo-label-top'><?php echo EasyContactFormsT::get('Content');?></label>
            <div class='ufo-y-overflow'>
              <div style='width:100%'><?php echo $obj->get('Content');?></div>
            </div>
          </div>
        </div>
      </div>
      <div id='CustomFormEntryFiles' class='ufo-tabs ufo-tab'>
        <input type='hidden' value='AppMan.initRedirect("CustomFormEntryFiles", {specialfilter:"[{\"property\":\"CustomFormsEntries\", \"value\":{\"values\":[<?php echo $obj->get('id');?>]}}]", viewTarget:"CustomFormEntryFilesDiv", t:"CustomFormEntryFiles", m:"viewDetailed"}, [{property:"CustomFormsEntries", value:{values:[<?php echo $obj->get('id');?>]}}])' class='ufo-eval'/>
        <div id='CustomFormEntryFilesDiv' class='innerview'></div>
      </div>
    </div>
  </div>
  <div>
    <div class='ufo-float-left'>
      <?php echo EasyContactFormsIHTML::getButton(
        array(
          'label' => EasyContactFormsT::get('OK'),
          'events' => " onclick='ufo.save($obj->jsconfig)'",
          'iclass' => " class='icon_button_save' ",
          'bclass' => "button internalimage",
        )
      );?>
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
    <div class='ufo-float-left'>
      <?php 
        $query = "SELECT Options.Value FROM #wp__easycontactforms_options AS Options WHERE Options.Description = 'customformsentries_main_form_buttons'";
        $plugs = EasyContactFormsDB::getObjects($query);
        foreach ($plugs as $plug) {
          include ABSPATH . $plug->Value;
        }
      ?>
    </div>
    <div style='clear:left'></div>
  </div><?php

EasyContactFormsLayout::getFormBodyFooter();
