<?php

/**
 * @file
 *
 * 	EasyContactFormsLayout class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

/**
 * 	Basic layout functions.
 *
 * 	Tabular layout: both left and top. Form page wrapper
 * 	Table rows
 *
 */
class EasyContactFormsLayout {

	/**
	 * 	getFormHeader
	 *
	 * 	a header part of a default page layout
	 *
	 * @param string $class
	 * 	a class to add to a page
	 */
	function getFormHeader($class) {

		$as = EasyContactFormsApplicationSettings::getInstance();
		$as->showMessages();
		?>
			<div class = 'ufoccontpage ufo-split <?php echo $class;?>'>
				<div class = 'ufosp_header'>
					<div class = 'ufospl'>
						<h3>
		<?php

	}

	/**
	 * 	getFormHeader2Body
	 *
	 * 	a middle part of a default page layout
	 *
	 */
	function getFormHeader2Body() {

		?>
					</h3>
			</div>
		</div>
		<div class = 'ufosp_body'>
			<div class = 'ufospl'><div class = 'ufospl'><div class = 'ufospl'>
				<div class = 'ufoccontheight'>
		<?php

	}

	/**
	 * 	getFormBodyFooter
	 *
	 * 	a footer part
	 *
	 */
	function getFormBodyFooter() {

		?>
						</div>
					</div></div></div>
				</div>
			</div>
		<?php

	}

	/**
	 * 	getTabHeader
	 *
	 * 	Shows page tabs
	 *
	 * @param array $names
	 * 	a list of tab names
	 * @param string $position
	 * 	indicates tab position
	 * @param int $index
	 * 	indicates a tab set index in case if there are several tab sets on
	 * 	the same page
	 * @param boolean $noactive
	 * 	TRUE if there is no need to activate a tab page by default
	 */
	function getTabHeader($names, $position = 'top', $index = '', $noactive = FALSE) {

		?>
		<ul class = 'ufo-tab-header ufo-tab-<?php echo $position;?>'>
		<?php
		$active = $noactive ? '' : ' ufo-active';
		foreach ($names as $name) {
			?>
			<li>

				<a class = 'ufo-tab<?php echo $index;?>-menu<?php echo $active;?>' id = 'ufo-tab<?php echo $index;?>-menu-<?php echo $name;?>' href = 'javascript:void(0)' onclick = 'AppMan.switchtab(this, "ufo-tab<?php echo $index;?>","<?php echo $name . $index;?>")'>

					<span>
						<?php echo EasyContactFormsT::get($name);?>
					</span>
				</a>
			</li>
		<?php
			$active = '';
		}
		?>
		</ul>
		<?php if ($position == 'top') { ?>
			<div style = 'clear:left'></div>
		<?php }

	}

	/**
	 * 	this is a function indended for repeatable execution for each
	 * 	retreived db record
	 *
	 * @param array $rs
	 * 	array of fetched rows
	 * @param string $type
	 * 	object type to load
	 * @param object $view
	 * 	view object, containing all the necessary environment data, like
	 * 	userid, etc
	 * @param string $rowview
	 * 	php file to include
	 * @param string $function
	 * 	row function to execute
	 * @param array $map
	 * 	request values
	 */
	function getRows($rs, $type, $view, $rowview, $function, $map) {

		$counter = 0;
		foreach ($rs as $item) {
			$counter++;
			$obj = new $type();
			$obj->setData($item);
			require_once "views/$rowview";
			$function($view, $obj, $counter, $map);
		}

	}

}
