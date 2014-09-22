<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomForms main view row html function
 *
 * 	@see EasyContactFormsCustomForms::getMainView()
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
 * 	Displays a EasyContactFormsCustomForms main view record
 *
 * @param object $view
 * 	the EasyContactFormsCustomForms main view object
 * @param object $obj
 * 	a db object
 * @param int $i
 * 	record index
 * @param array $map
 * 	request data
 */
function getCustomFormsMainViewRow($view, $obj, $i, $map) { ?>
  <tr class='ufohighlight <?php EasyContactFormsIHTML::getTrSwapClassName($i);?>'>
    <td class='firstcolumn'>
      <input type='checkbox' id='<?php echo $view->idJoin('cb', $obj->getId());?>' value='off' class='ufo-deletecb' onchange='this.value=(this.checked)?"on":"off";'/>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('id');?>", t:"CustomForms"})'>
        <?php EasyContactFormsIHTML::echoStr($obj->get('id'));?>
      </a>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('id');?>", t:"CustomForms"})'>
        <?php EasyContactFormsIHTML::echoStr($obj->get('Description'));?>
      </a>
    </td>
    <td>
      <?php echo $obj->get('ShortCode');?>
    </td>
  </tr>
	<?php
}
