<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormEntryFiles main view html template
 *
 * 	@see EasyContactFormsCustomFormEntryFiles ::getMainView()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */


EasyContactFormsLayout::getFormHeader('ufo-formpage ufo-mainview ufo-' . strtolower($obj->type));
echo EasyContactFormsUtils::getViewDescriptionLabel(EasyContactFormsT::get('CustomFormEntryFiles'));
EasyContactFormsLayout::getFormHeader2Body();

?>
  <div>
    <div class='buttons'>
      <div class='ufo-float-left'>
        <?php EasyContactFormsIHTML::getScroller($obj);?>
      </div>
      <div class='ufo-float-left'>
        <?php echo EasyContactFormsIHTML::getButton(
          array(
            'title' => EasyContactFormsT::get('Delete'),
            'events' => " onclick='ufo.mdelete($obj->jsconfig, $obj->mdeleteconfig)'",
            'iclass' => " class='icon_button_delete' ",
            'bclass' => "ufo-imagebutton",
          )
        );?>
      </div>
      <div class='ufo-float-left'>
        <?php echo EasyContactFormsIHTML::getButton(
          array(
            'title' => EasyContactFormsT::get('Search'),
            'events' => " onclick='ufo.doFilter($obj->jsconfig, this)'",
            'iclass' => " class='icon_filter' ",
            'bclass' => "ufo-imagebutton",
          )
        );?>
      </div>
      <div style='clear:left'></div>
    </div>
  </div>
  <div>
    <div id='divCustomFormEntryFilesFilter' class='ufo-filter'>
      <div class='ufofilterbutton'>
        <?php echo EasyContactFormsIHTML::getButton(
          array(
            'label' => EasyContactFormsT::get('Filter'),
            'events' => " onclick='ufo.filter($obj->jsconfig);'",
            'iclass' => " class='icon_filter_pane' ",
            'bclass' => "button internalimage",
          )
        );?>
      </div>
      <div class='ufo-clear-both'></div>
      <div>
        <div>
          <div>
            <label for='<?php echo $obj->sId('id');?>'><?php echo EasyContactFormsT::get('id');?></label>
            <select id='<?php echo $obj->sId('id');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('general');?>
            </select>
            <input type='text' id='id' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Description');?>'><?php echo EasyContactFormsT::get('Field');?></label>
            <select id='<?php echo $obj->sId('Description');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Description' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Date');?>'><?php echo EasyContactFormsT::get('Date');?></label>
            <select id='<?php echo $obj->sId('Date');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('general');?>
            </select>
            <div class='ufo-input-wrapper' style='width:108px'>
              <input type='text' id='Date' READONLY class='ufo-date datebox ufo-internal ufo-filtervalue'/>
              <a id='Date-Trigger' href='javascript:;' class='ufo-triggerbutton icon_trigger_calendar'>&nbsp;&nbsp;</a>
            </div>
            <input type='hidden' value='ufo.setupCalendar("Date", {ifFormat:"<?php echo EasyContactFormsApplicationSettings::getInstance()->getDateFormat('JS'); ?>", firstDay:0, align:"Bl", singleClick:true});' class='ufo-eval'/>
          </div>
        </div>
        <div>
          <div>
            <label for='<?php echo $obj->sId('CustomFormsEntries');?>'><?php echo EasyContactFormsT::get('EntryId');?></label>
            <select id='<?php echo $obj->sId('CustomFormsEntries');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('ref');?>
            </select>
            <input type='text' id='CustomFormsEntries' class='inputselect ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('CustomForms');?>'><?php echo EasyContactFormsT::get('CustomForm');?></label>
            <select id='<?php echo $obj->sId('CustomForms');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('ref');?>
            </select>
            <select id='CustomForms' class='inputselect ufo-select ufo-filtervalue' style='width:130px'>
              <?php echo $obj->getListHTML(NULL, NULL, FALSE, 'CustomForms');?>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class='viewtable'>
      <table class='vtable'>
        <tr>
          <th style='width:8px'>
            <input type='checkbox' class='ufo-id-link' style='margin:0' onchange='ufo.checkAll(this)'/>
          </th>
          <th style='width:30px'>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "id"));?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "Date"));?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(
              array(
                 'view' => $obj,
                 'field' => "CustomFormsDescription",
                 'label' => EasyContactFormsT::get('CustomForm'),
              )
            );?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(
              array(
                 'view' => $obj,
                 'field' => "CustomFormsEntriesDescription",
                 'label' => EasyContactFormsT::get('EntryId'),
              )
            );?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(
              array(
                 'view' => $obj,
                 'field' => "Description",
                 'label' => EasyContactFormsT::get('Field'),
              )
            );?>
          </th>
          <th>
            <?php echo EasyContactFormsT::get('File');?>
          </th>
        </tr>
        <?php EasyContactFormsLayout::getRows(
          $resultset,
          'EasyContactFormsCustomFormEntryFiles',
          $obj,
          'easy-contact-forms-customformentryfilesmainviewrow.php',
          'getCustomFormEntryFilesMainViewRow',
          $viewmap
        );?>
      </table>
    </div>
  </div><?php

EasyContactFormsLayout::getFormBodyFooter();
