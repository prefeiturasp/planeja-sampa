<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormsEntries AS form html template
 *
 * 	@see EasyContactFormsCustomFormsEntries::getASForm()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

?>
  <div class='ufo-as-form ufo-customformsentries'>
    <div>
      <?php EasyContactFormsIHTML::echoDate($obj->get('Date'), EasyContactFormsApplicationSettings::getInstance()->getDateFormat('PHP', TRUE), 0);?>
    </div>
    <div class='ufo-as-list-hidden'>
      <?php EasyContactFormsIHTML::echoStr($obj->get('Content'), '', 3000);?>
    </div>
    <div>
      <?php echo $obj->get('PageName');?>
    </div>
  </div>
