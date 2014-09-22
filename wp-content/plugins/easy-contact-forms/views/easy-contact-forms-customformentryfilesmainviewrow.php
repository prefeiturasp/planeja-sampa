<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormEntryFiles main view row html function
 *
 * 	@see EasyContactFormsCustomFormEntryFiles::getMainView()
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
 * 	Displays a EasyContactFormsCustomFormEntryFiles main view record
 *
 * @param object $view
 * 	the EasyContactFormsCustomFormEntryFiles main view object
 * @param object $obj
 * 	a db object
 * @param int $i
 * 	record index
 * @param array $map
 * 	request data
 */
function getCustomFormEntryFilesMainViewRow($view, $obj, $i, $map) {

		$obj->File = array(
				'doctype' => 'CustomFormEntryFiles',
				'docid' => $obj->get('id'),
				'field' => 'File',
				'tag' => 'a',
				'content' => EasyContactFormsT::get('Download'),
			);

  ?>
  <tr class='ufohighlight <?php EasyContactFormsIHTML::getTrSwapClassName($i);?>'>
    <td class='firstcolumn'>
      <input type='checkbox' id='<?php echo $view->idJoin('cb', $obj->getId());?>' value='off' class='ufo-deletecb' onchange='this.value=(this.checked)?"on":"off";'/>
    </td>
    <td>
      <?php echo $obj->get('id');?>
    </td>
    <td>
      <?php EasyContactFormsIHTML::echoDate($obj->get('Date'), EasyContactFormsApplicationSettings::getInstance()->getDateFormat('PHP'), 0);?>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('CustomForms');?>", t:"CustomForms"})'>
        <?php echo $obj->get('CustomFormsDescription');?>
      </a>
    </td>
    <td>
      <a onclick='ufo.redirect({m:"show", oid:"<?php echo $obj->get('CustomFormsEntries');?>", t:"CustomFormsEntries"})'>
        <?php echo $obj->get('CustomFormsEntriesDescription');?>
      </a>
    </td>
    <td>
      <?php echo $obj->get('Description');?>
    </td>
    <td>
      <?php EasyContactFormsIHTML::getFileDownloadLink($obj->File);?>
    </td>
  </tr>
	<?php
}
