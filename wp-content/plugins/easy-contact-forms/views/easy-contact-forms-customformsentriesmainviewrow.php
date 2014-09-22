<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormsEntries main view row html function
 *
 * 	@see EasyContactFormsCustomFormsEntries::getMainView()
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
 * 	Displays a EasyContactFormsCustomFormsEntries main view record
 *
 * @param object $view
 * 	the EasyContactFormsCustomFormsEntries main view object
 * @param object $obj
 * 	a db object
 * @param int $i
 * 	record index
 * @param array $map
 * 	request data
 */
function getCustomFormsEntriesMainViewRow($view, $obj, $i, $map) {

		$usrname = EasyContactFormsDB::getValue("SELECT display_name  FROM #wp__users WHERE ID = '" . $obj->get('SiteUser') . "'");

	if ($usrname) {
		$obj->SiteUser = $usrname;
	}
	else {
		$obj->SiteUser = '&nbsp;';
	}

  ?>
  <tr class='ufohighlight <?php EasyContactFormsIHTML::getTrSwapClassName($i);?>'>
    <td class='firstcolumn'>
      <input type='checkbox' id='<?php echo $view->idJoin('cb', $obj->getId());?>' value='off' class='ufo-deletecb' onchange='this.value=(this.checked)?"on":"off";'/>
    </td>
    <td>
      <?php
        $usr = $obj->get('Users');
        if (empty($usr)) {
          $cff = EasyContactFormsClassLoader::getObject('CustomFormFields');
          $cff->getSettingsFormButton('entry-add', EasyContactFormsT::get('CF_ProcessEntry'), "onclick='ufoCf.processEntry(" . $obj->get('id') . ", " . $view->jsconfig . ");'", 'icon_button_add');
        }
      ?>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('id');?>", t:"CustomFormsEntries"})' onmouseover='ufo.showInfo({t:"CustomFormsEntries", m2:"getASList", oid:<?php echo $obj->get('id');?>, m:"ajaxsuggest"}, this)'>
        <?php EasyContactFormsIHTML::echoStr($obj->get('id'));?>
      </a>
    </td>
    <td>
      <?php EasyContactFormsIHTML::echoDate($obj->get('Date'), EasyContactFormsApplicationSettings::getInstance()->getDateFormat('PHP', TRUE), 0);?>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('CustomForms');?>", t:"CustomForms"})'>
        <?php echo $obj->get('CustomFormsDescription');?>
      </a>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('Users');?>", t:"Users"})'>
        <?php echo $obj->get('UsersDescription');?>
      </a>
    </td>
    <td>
      <?php echo $obj->get('PageName');?>
    </td>
    <td>
      <?php echo $obj->SiteUser;?>
    </td>
  </tr>
	<?php
}
