<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomForms manageMain view html template
 *
 * 	@see EasyContactFormsCustomForms ::getManageMainView()
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
    <div id='divCustomFormsFilter' class='ufo-filter'>
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
            <label for='<?php echo $obj->sId('Description');?>'><?php echo EasyContactFormsT::get('Description');?></label>
            <select id='<?php echo $obj->sId('Description');?>' class='ufo-select ufo-filtersign'>
              <?php echo $obj->sList('string');?>
            </select>
            <input type='text' id='Description' class='textinput ufo-text ufo-filtervalue' style='width:130px'/>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class='viewtable'>
      <table class='vtable'>
        <tr>
          <th style='width:18px'>
            &nbsp;
          </th>
          <th style='width:30px'>
            <?php EasyContactFormsIHTML::getColumnHeader(array('view' => $obj, 'field' => "id"));?>
          </th>
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(
              array(
                 'view' => $obj,
                 'field' => "Description",
                 'label' => EasyContactFormsT::get('AvailableContactForms'),
              )
            );?>
          </th>
        </tr>
        <?php EasyContactFormsLayout::getRows(
          $resultset,
          'EasyContactFormsCustomForms',
          $obj,
          'easy-contact-forms-customformsmanagemainviewrow.php',
          'getCustomFormsManageMainViewRow',
          $viewmap
        );?>
      </table>
    </div>
  </div>
