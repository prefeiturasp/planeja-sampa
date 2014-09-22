<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormFields detailedMain view html template
 *
 * 	@see EasyContactFormsCustomFormFields ::getDetailedMainView()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

?>
  <table style='border:0;height:100%;border-collapse:collapse;width:100%'>
    <tr>
      <td style='vertical-align:top;padding:5px;padding-bottom:100px;width:150px'>
        <?php if (!isset($viewmap['cfa'])) { ?><input type='hidden' class='ufo-eval' value='ufoCf.newPage();'><?php } ?>
        <?php $obj->getFieldTypes($viewmap);?>
      </td>
      <td style='width:230px;vertical-align:top'>
        <div>
          <div class='buttons' style='margin:7px 8px 0 0'>
            <div class='ufo-float-left'>
              <?php echo EasyContactFormsIHTML::getButton(
                 array(
                   'title' => EasyContactFormsT::get('Refresh'),
                   'events' => " onclick='ufo.filter($obj->jsconfig)'",
                   'iclass' => " class='icon_button_refresh' ",
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
              <?php echo EasyContactFormsIHTML::getButton(
                 array(
                   'title' => EasyContactFormsT::get('Preview'),
                   'events' => " onclick='ufoCf.preview()'",
                   'iclass' => " class='icon_preview' ",
                   'bclass' => "ufo-imagebutton",
                 )
              );?>
            </div>
            <div style='clear:left'></div>
          </div>
        </div>
        <div>
          <div id='divCustomFormFieldsFilter' class='ufo-filter' style='width:200px'>
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
              <label for='<?php echo $obj->sId('Description');?>' style='width:40px !important'><?php echo EasyContactFormsT::get('Label');?></label>
              <select id='<?php echo $obj->sId('Description');?>' class='ufo-select ufo-filtersign'>
                 <?php echo $obj->sList('string');?>
              </select>
              <input type='text' id='Description' class='textinput ufo-text ufo-filtervalue' style='width:100px'/>
            </div>
            <div>
              <label for='<?php echo $obj->sId('Type');?>' style='width:40px !important'><?php echo EasyContactFormsT::get('Type');?></label>
              <select id='<?php echo $obj->sId('Type');?>' class='ufo-select ufo-filtersign'>
                 <?php echo $obj->sList('ref');?>
              </select>
              <select id='Type' class='inputselect ufo-select ufo-filtervalue' style='width:102px'>
                 <?php echo $obj->getListHTML(NULL, NULL, FALSE, 'CustomFormFieldTypes');?>
              </select>
            </div>
            <div>
              <label for='<?php echo $obj->sId('FieldSet');?>' style='width:40px !important'><?php echo EasyContactFormsT::get('FieldSet');?></label>
              <select id='<?php echo $obj->sId('FieldSet');?>' class='ufo-select ufo-filtersign'>
                 <?php echo $obj->sList('ref');?>
              </select>
              <select id='FieldSet' class='inputselect ufo-select ufo-filtervalue' style='width:102px'>
                 <?php echo $obj->getListHTML($obj->FieldSet,NULL, FALSE); ?>
              </select>
            </div>
          </div>
        </div>
        <div>
          <div>
            <?php EasyContactFormsLayout::getRows(
              $resultset,
              'EasyContactFormsCustomFormFields',
              $obj,
              'easy-contact-forms-customformfieldsdetailedmainviewrow.php',
              'getCustomFormFieldsDetailedMainViewRow',
              $viewmap
            );?>
          </div>
        </div>
      </td>
      <td style='background:#fff;vertical-align:top;border:1px solid #777;padding:5px'>
        <div style='height:1000px'><div id='settings-form' class='ufo-id-link ufo-customform-settings-form'></div></div>
      </td>
    </tr>
  </table>
