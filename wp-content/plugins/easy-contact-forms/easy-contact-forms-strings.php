<?php

/**
 * @file
 *
 * 	EasyContactFormsStrings class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

/**
 * 	EasyContactFormsStrings
 *
 * 	string operations
 *
 */
class EasyContactFormsStrings {

	/**
	 * 	getInstance
	 *
	 * 	returns a single instance of the EasyContactFormsStrings object
	 *
	 *
	 * @return object
	 * 	the instance
	 */
	function getInstance() {

		static $singinstance;
			if (!isset($singinstance)) {
				$singinstance = new EasyContactFormsT();
			}
		return $singinstance;

	}

	/**
	 * 	get
	 *
	 * @param string $id
	 * 
	 *
	 * @return
	 * 
	 */
	function get($id) {

		$inst = EasyContactFormsStrings::getInstance();
		return $inst->{$id};

	}

}
