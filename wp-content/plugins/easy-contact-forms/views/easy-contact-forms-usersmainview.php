<?php
/**
 * @file
 *
 * 	EasyContactFormsUsers main view html template
 *
 * 	@see EasyContactFormsUsers ::getMainView()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */


EasyContactFormsLayout::getFormHeader('ufo-formpage ufo-mainview ufo-' . strtolower($obj->type));
echo EasyContactFormsUtils::getViewDescriptionLabel(EasyContactFormsT::get('Users'));
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
            'events' => " onclick='ufo.mdelete($obj->jsconfig)'",
            'iclass' => " class='icon_button_delete' ",
            'bclass' => "ufo-imagebutton",
          )
        );?>
      </div>
      <div class='ufo-float-left'>
        <?php echo EasyContactFormsIHTML::getButton(
          array(
            'title' => EasyContactFormsT::get('Add'),
            'events' => " onclick='ufo.newObject($obj->jsconfig, this)'",
            'iclass' => " class='icon_button_add' ",
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
    <div id='divUsersFilter' class='ufo-filter'>
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
            <label for='<?php echo $obj->sId('Description');?>'><?php echo EasyContactFormsT::get('LastName');?></label>
            <select id='<?php echo $obj->sId('Description');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Description' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Name');?>'><?php echo EasyContactFormsT::get('FirstName');?></label>
            <select id='<?php echo $obj->sId('Name');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Name' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('ContactType');?>'><?php echo EasyContactFormsT::get('ContactType');?></label>
            <select id='<?php echo $obj->sId('ContactType');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('ref');?>
            </select>
            <select id='ContactType' class='inputselect ufo-select ufo-filtervalue' style='width:130px'>
              <?php echo $obj->getListHTML(NULL, NULL, FALSE, 'ContactTypes');?>
            </select>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Birthday');?>'><?php echo EasyContactFormsT::get('Birthday');?></label>
            <select id='<?php echo $obj->sId('Birthday');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('general');?>
            </select>
            <div class='ufo-input-wrapper' style='width:108px'>
              <input type='text' id='Birthday' READONLY class='ufo-date datebox ufo-internal ufo-filtervalue'/>
              <a id='Birthday-Trigger' href='javascript:;' class='ufo-triggerbutton icon_trigger_calendar'>&nbsp;&nbsp;</a>
            </div>
            <input type='hidden' value='ufo.setupCalendar("Birthday", {ifFormat:"<?php echo EasyContactFormsApplicationSettings::getInstance()->getDateFormat('JS'); ?>", firstDay:0, align:"Bl", singleClick:true});' class='ufo-eval'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Role');?>'><?php echo EasyContactFormsT::get('Role');?></label>
            <select id='<?php echo $obj->sId('Role');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('ref');?>
            </select>
            <select id='Role' class='inputselect ufo-select ufo-filtervalue' style='width:130px'>
              <?php echo $obj->getRoleListHTML('Role', FALSE);?>
            </select>
          </div>
          <div>
            <label for='<?php echo $obj->sId('CMSId');?>'><?php echo EasyContactFormsT::get('CMSId');?></label>
            <select id='<?php echo $obj->sId('CMSId');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('ref');?>
            </select>
            <?php EasyContactFormsIHTML::getAS($obj->CMSId);?>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Notes');?>'><?php echo EasyContactFormsT::get('Notes');?></label>
            <select id='<?php echo $obj->sId('Notes');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Notes' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('email');?>'><?php echo EasyContactFormsT::get('email');?></label>
            <select id='<?php echo $obj->sId('email');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='email' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('email2');?>'><?php echo EasyContactFormsT::get('email2');?></label>
            <select id='<?php echo $obj->sId('email2');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='email2' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Cell');?>'><?php echo EasyContactFormsT::get('Cell');?></label>
            <select id='<?php echo $obj->sId('Cell');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Cell' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Phone1');?>'><?php echo EasyContactFormsT::get('Phone1');?></label>
            <select id='<?php echo $obj->sId('Phone1');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Phone1' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
        </div>
        <div>
          <div>
            <label for='<?php echo $obj->sId('Phone2');?>'><?php echo EasyContactFormsT::get('Phone2');?></label>
            <select id='<?php echo $obj->sId('Phone2');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Phone2' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Phone3');?>'><?php echo EasyContactFormsT::get('Phone3');?></label>
            <select id='<?php echo $obj->sId('Phone3');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Phone3' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('SkypeId');?>'><?php echo EasyContactFormsT::get('SkypeId');?></label>
            <select id='<?php echo $obj->sId('SkypeId');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='SkypeId' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Website');?>'><?php echo EasyContactFormsT::get('Website');?></label>
            <select id='<?php echo $obj->sId('Website');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Website' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('ContactField3');?>'><?php echo EasyContactFormsT::get('ContactField3');?></label>
            <select id='<?php echo $obj->sId('ContactField3');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='ContactField3' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('ContactField4');?>'><?php echo EasyContactFormsT::get('ContactField4');?></label>
            <select id='<?php echo $obj->sId('ContactField4');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='ContactField4' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Country');?>'><?php echo EasyContactFormsT::get('Country');?></label>
            <select id='<?php echo $obj->sId('Country');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Country' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Address');?>'><?php echo EasyContactFormsT::get('Address');?></label>
            <select id='<?php echo $obj->sId('Address');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Address' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('City');?>'><?php echo EasyContactFormsT::get('City');?></label>
            <select id='<?php echo $obj->sId('City');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='City' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('State');?>'><?php echo EasyContactFormsT::get('State');?></label>
            <select id='<?php echo $obj->sId('State');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='State' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Zip');?>'><?php echo EasyContactFormsT::get('Zip');?></label>
            <select id='<?php echo $obj->sId('Zip');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Zip' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('History');?>'><?php echo EasyContactFormsT::get('History');?></label>
            <select id='<?php echo $obj->sId('History');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='History' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
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
            <?php EasyContactFormsIHTML::getColumnHeader(
              array(
                 'view' => $obj,
                 'field' => "Description",
                 'label' => EasyContactFormsT::get('Name'),
              )
            );?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(
              array(
                 'view' => $obj,
                 'field' => "ContactTypeDescription",
                 'label' => EasyContactFormsT::get('ContactType'),
              )
            );?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "Birthday"));?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(
              array(
                 'view' => $obj,
                 'field' => "RoleDescription",
                 'label' => EasyContactFormsT::get('Role'),
              )
            );?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "CMSId"));?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "email"));?>
          </th>
        </tr>
        <?php EasyContactFormsLayout::getRows(
          $resultset,
          'EasyContactFormsUsers',
          $obj,
          'easy-contact-forms-usersmainviewrow.php',
          'getUsersMainViewRow',
          $viewmap
        );?>
      </table>
    </div>
  </div><?php

EasyContactFormsLayout::getFormBodyFooter();
