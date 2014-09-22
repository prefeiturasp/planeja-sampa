<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomForms_MailingLists UsersMain view row html
 * 	function
 *
 * 	@see EasyContactFormsCustomForms_MailingLists::getUsersMainView()
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
 * 	Displays a EasyContactFormsCustomForms_MailingLists UsersMain view
 * 	record
 *
 * @param object $view
 * 	the EasyContactFormsCustomForms_MailingLists UsersMain view object
 * @param object $obj
 * 	a db object
 * @param int $i
 * 	record index
 * @param array $map
 * 	request data
 */
function getCustomForms_MailingListsUsersMainViewRow($view, $obj, $i, $map) { ?>
  <tr>
    <td class='firstcolumn'>
      <input type='checkbox' id='<?php echo $view->idJoin('cb', $obj->getId());?>' value='off' class='ufo-deletecb' onchange='this.value=(this.checked)?"on":"off";'/>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('CustomForms');?>", t:"CustomForms"})'>
        <?php echo $obj->get('CustomFormsDescription');?>
      </a>
    </td>
  </tr>
	<?php
}
