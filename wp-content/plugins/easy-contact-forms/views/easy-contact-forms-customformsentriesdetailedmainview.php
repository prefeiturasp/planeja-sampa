<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormsEntries detailedMain view html template
 *
 * 	@see EasyContactFormsCustomFormsEntries ::getDetailedMainView()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

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
      <div class='ufo-float-left'>
        <a href='http://championforms.com/champion-forms/view' style='margin-top:4px;display:block;padding-left:4px'>
          Data Export
        </a>
      </div>
      <div style='clear:left'></div>
    </div>
  </div>
  <div>
    <div id='divCustomFormsEntriesFilter' class='ufo-filter'>
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
            <label for='<?php echo $obj->sId('Date');?>'><?php echo EasyContactFormsT::get('Date');?></label>
            <select id='<?php echo $obj->sId('Date');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('date');?>
            </select>
            <div class='ufo-input-wrapper' style='width:108px'>
              <input type='text' id='Date' READONLY class='ufo-date datebox ufo-internal ufo-filtervalue'/>
              <a id='Date-Trigger' href='javascript:;' class='ufo-triggerbutton icon_trigger_calendar'>&nbsp;&nbsp;</a>
            </div>
            <input type='hidden' value='ufo.setupCalendar("Date", {ifFormat:"<?php echo EasyContactFormsApplicationSettings::getInstance()->getDateFormat('JS', TRUE); ?>", firstDay:0, align:"Bl", singleClick:true});' class='ufo-eval'/>
          </div>
          <div>
            <label for='<?php echo $obj->sId('Content');?>'><?php echo EasyContactFormsT::get('Content');?></label>
            <select id='<?php echo $obj->sId('Content');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Content' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
        </div>
        <div>
          <div>
            <label for='<?php echo $obj->sId('Users');?>'><?php echo EasyContactFormsT::get('User');?></label>
            <select id='<?php echo $obj->sId('Users');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('ref');?>
            </select>
            <?php EasyContactFormsIHTML::getAS($obj->Users);?>
          </div>
          <div>
            <label for='<?php echo $obj->sId('SiteUser');?>'><?php echo EasyContactFormsT::get('SiteUser');?></label>
            <select id='<?php echo $obj->sId('SiteUser');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('ref');?>
            </select>
            <?php EasyContactFormsIHTML::getAS($obj->SiteUser);?>
          </div>
          <div>
            <label for='<?php echo $obj->sId('PageName');?>'><?php echo EasyContactFormsT::get('PageName');?></label>
            <select id='<?php echo $obj->sId('PageName');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='PageName' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
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
          <th style='width:18px'>
            <?php echo EasyContactFormsT::get('Empty');?>
          </th>
          <th style='width:30px'>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "id"));?>
          </th>
          <th style='width:110px'>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "Date"));?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(
              array(
                 'view' => $obj,
                 'field' => "UsersDescription",
                 'label' => EasyContactFormsT::get('User'),
              )
            );?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "PageName"));?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "SiteUser"));?>
          </th>
        </tr>
        <?php EasyContactFormsLayout::getRows(
          $resultset,
          'EasyContactFormsCustomFormsEntries',
          $obj,
          'easy-contact-forms-customformsentriesdetailedmainviewrow.php',
          'getCustomFormsEntriesDetailedMainViewRow',
          $viewmap
        );?>
      </table>
    </div>
  </div>
