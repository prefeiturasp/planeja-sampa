<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormEntryStatistics detailedMain view row html
 * 	function
 *
 * 	@see EasyContactFormsCustomFormEntryStatistics::getDetailedMainView()
 * 	@see EasyContactFormsLayout::getRows()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

/**
 * 	Displays a EasyContactFormsCustomFormEntryStatistics detailedMain
 * 	view record
 *
 * @param object $view
 * 	the EasyContactFormsCustomFormEntryStatistics detailedMain view
 * 	object
 * @param object $obj
 * 	a db object
 * @param int $i
 * 	record index
 * @param array $map
 * 	request data
 */
function getCustomFormEntryStatisticsDetailedMainViewRow($view, $obj, $i, $map) { ?>
  <tr class='ufohighlight <?php EasyContactFormsIHTML::getTrSwapClassName($i);?>'>
    <td class='firstcolumn'>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('CustomForms');?>", t:"CustomForms"})'>
        <?php echo $obj->get('CustomFormsDescription');?>
      </a>
    </td>
    <td>
      <?php echo $obj->get('PageName');?>
    </td>
    <td>
      <?php echo $obj->get('Impressions');?>
    </td>
    <td>
      <?php echo $obj->get('TotalEntries');?>
    </td>
    <td>
      <?php echo round($obj->get('Conversion'), 2);?>&nbsp;%
    </td>
    <td>
      <?php $obj->getShowHideButton($view->map); ?>
    </td>
    <td>
      <?php echo EasyContactFormsIHTML::getButton(
        array(
          'title' => EasyContactFormsT::get('Reset'),
          'id' => $obj->elId('Reset', $obj->getId()),
          'events' => " onclick='ufo.resetFormPageStatistics(this, $obj->jsconfig)'",
          'iclass' => " class='icon_reset_dis ufo-id-link' ",
          'bclass' => "ufo-imagebutton",
        )
      );?>
    </td>
  </tr>
	<?php
}
