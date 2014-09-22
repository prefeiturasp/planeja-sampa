<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormFields detailedMain view row html function
 *
 * 	@see EasyContactFormsCustomFormFields::getDetailedMainView()
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
 * 	Displays a EasyContactFormsCustomFormFields detailedMain view record
 *
 * @param object $view
 * 	the EasyContactFormsCustomFormFields detailedMain view object
 * @param object $obj
 * 	a db object
 * @param int $i
 * 	record index
 * @param array $map
 * 	request data
 */
function getCustomFormFieldsDetailedMainViewRow($view, $obj, $i, $map) {

		$view->getFieldsList($view, $obj, $i, $map);

  
}
