<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomForms_MailingLists UsersMain view html template
 *
 * 	@see EasyContactFormsCustomForms_MailingLists ::getUsersMainView()
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
            'events' => " onclick='ufo.mtmdelete($obj->jsconfig)'",
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
    <div id='divCustomForms_MailingListsFilter' class='ufo-filter'>
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
          <th>
            <?php EasyContactFormsIHTML::getColumnHeader(
              array(
                 'view' => $obj,
                 'field' => "CustomFormsDescription",
                 'label' => EasyContactFormsT::get('CustomForm'),
              )
            );?>
          </th>
        </tr>
        <?php EasyContactFormsLayout::getRows(
          $resultset,
          'EasyContactFormsCustomForms_MailingLists',
          $obj,
          'easy-contact-forms-customforms_mailinglistsusersmainviewrow.php',
          'getCustomForms_MailingListsUsersMainViewRow',
          $viewmap
        );?>
      </table>
    </div>
  </div>
