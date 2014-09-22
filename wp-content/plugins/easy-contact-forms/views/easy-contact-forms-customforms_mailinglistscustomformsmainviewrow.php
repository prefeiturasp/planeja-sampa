<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomForms_MailingLists CustomFormsMain view row
 * 	html function
 *
 * 	@see
 * 	EasyContactFormsCustomForms_MailingLists::getCustomFormsMainView()
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
 * 	Displays a EasyContactFormsCustomForms_MailingLists CustomFormsMain
 * 	view record
 *
 * @param object $view
 * 	the EasyContactFormsCustomForms_MailingLists CustomFormsMain view
 * 	object
 * @param object $obj
 * 	a db object
 * @param int $i
 * 	record index
 * @param array $map
 * 	request data
 */
function getCustomForms_MailingListsCustomFormsMainViewRow($view, $obj, $i, $map) { ?>
  <tr>
    <td class='firstcolumn'>
      <input type='checkbox' id='<?php echo $view->idJoin('cb', $obj->getId());?>' value='off' class='ufo-deletecb' onchange='this.value=(this.checked)?"on":"off";'/>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('Contacts');?>", t:"Users"})'>
        <?php echo $obj->get('ContactsDescription');?>
      </a>
    </td>
  </tr>
	<?php
}
