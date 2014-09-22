<?php
/**
 * @file
 *
 * 	EasyContactFormsContactTypes main view row html function
 *
 * 	@see EasyContactFormsContactTypes::getMainView()
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
 * 	Displays a EasyContactFormsContactTypes main view record
 *
 * @param object $view
 * 	the EasyContactFormsContactTypes main view object
 * @param object $obj
 * 	a db object
 * @param int $i
 * 	record index
 * @param array $map
 * 	request data
 */
function getContactTypesMainViewRow($view, $obj, $i, $map) { ?>
  <tr class='ufohighlight <?php EasyContactFormsIHTML::getTrSwapClassName($i);?>'>
    <td class='firstcolumn'>
      <input type='checkbox' id='<?php echo $view->idJoin('cb', $obj->getId());?>' value='off' class='ufo-deletecb' onchange='this.value=(this.checked)?"on":"off";'/>
    </td>
    <td>
      <?php echo $obj->get('id');?>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('id');?>", t:"ContactTypes"})'>
        <?php EasyContactFormsIHTML::echoStr($obj->get('Description'));?>
      </a>
    </td>
  </tr>
	<?php
}
