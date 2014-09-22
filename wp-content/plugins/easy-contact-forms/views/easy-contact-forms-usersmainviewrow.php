<?php
/**
 * @file
 *
 * 	EasyContactFormsUsers main view row html function
 *
 * 	@see EasyContactFormsUsers::getMainView()
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
 * 	Displays a EasyContactFormsUsers main view record
 *
 * @param object $view
 * 	the EasyContactFormsUsers main view object
 * @param object $obj
 * 	a db object
 * @param int $i
 * 	record index
 * @param array $map
 * 	request data
 */
function getUsersMainViewRow($view, $obj, $i, $map) {

		$obj->Description = array();
		$obj->Description[] = $obj->get('Name');
		$obj->Description[] = $obj->get('Description');
		$obj->Description = EasyContactFormsUtils::vImplode(' ', $obj->Description);


		$usrname = EasyContactFormsDB::getValue("SELECT display_name  FROM #wp__users WHERE ID = '" . $obj->get('CMSId') . "'");

	if ($usrname) {
		$obj->CMSId = $usrname;
	}
	else {
		$obj->CMSId = '&nbsp;';
	}

  ?>
  <tr class='ufohighlight <?php EasyContactFormsIHTML::getTrSwapClassName($i);?>'>
    <td class='firstcolumn'>
      <input type='checkbox' id='<?php echo $view->idJoin('cb', $obj->getId());?>' value='off' class='ufo-deletecb' onchange='this.value=(this.checked)?"on":"off";'/>
    </td>
    <td>
      <?php echo $obj->get('id');?>
    </td>
    <td>
      <a id='<?php echo $obj->elId('Description', $obj->getId());?>' class='ufo-id-link' onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('id');?>", t:"Users"})' onmouseover='ufo.showInfo({t:"Users", m2:"getUserASList", oid:<?php echo $obj->get('id');?>, m:"ajaxsuggest"}, this)'>
        <?php echo $obj->Description;?>
      </a>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('ContactType');?>", t:"ContactTypes"})'>
        <?php echo $obj->get('ContactTypeDescription');?>
      </a>
    </td>
    <td>
      <?php EasyContactFormsIHTML::echoDate($obj->get('Birthday'), EasyContactFormsApplicationSettings::getInstance()->getDateFormat('PHP'), 0);?>
    </td>
    <td>
      <?php echo $obj->get('RoleDescription');?>
    </td>
    <td>
      <?php echo $obj->CMSId;?>
    </td>
    <td>
      <?php $a = $obj->get('email');
      if (!empty($a)) { ?>
        <a href='mailto:<?php echo $a;?>'><?php echo $a;?></a>
      <?php 
      }
      else {
        echo '&nbsp;';
      }?>
    </td>
  </tr>
	<?php
}
