<?php

/**
 * @file
 *
 * 	EasyContactFormsIHTML class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

/**
 * 	EasyContactFormsIHTML
 *
 * 	html objects
 *
 */
class EasyContactFormsIHTML {

	/**
	 * 	echoStr
	 *
	 * 	Prints a string
	 *
	 * @param string $value
	 * 	a value to echo
	 * @param string $suffix
	 * 	a suffix to add
	 * @param int $length
	 * 	if set indicates a maximum string length
	 *
	 * @return string
	 * 	html text
	 */
	function echoStr($value, $suffix = '', $length = NULL) {

		$value = empty($value) ? '&nbsp;' : $value . $suffix;
		$cut = isset($length) && strlen($value) > $length;
		$value = $cut ? substr($value, 0, $length) . '...' : $value;
		echo $value;

	}

	/**
	 * 	echoDate
	 *
	 * 	prints a date
	 *
	 * @param int $date
	 * 	the date to print
	 * @param string $format
	 * 	a date format to use
	 * @param int $emptydate
	 * 	empty date value
	 * @param boolean $weekday
	 * 	include a week day
	 *
	 * @return string
	 * 	html text
	 */
	function echoDate($date, $format, $emptydate, $weekday = FALSE) {

		if (!isset($date) || $date == $emptydate) {
			echo '&nbsp;';
			return;
		}
		if ($weekday) {
			$format = 'D ' . $format;
		}
		echo	date($format, $date);

	}

	/**
	 * 	getScrollerStepsList
	 *
	 * @param int $limit
	 * 	a pre-set limit value
	 * @param array $options
	 * 	a list of possible list options
	 *
	 * @return string
	 * 	An html string
	 */
	function getScrollerStepsList($limit, $options) {

		$text = '';
		foreach ($options as $option) {
			$selected = ($option == $limit)? ' selected' : '';
			$text = $text . "<option$selected>$option</option>\n";
		}
		return $text;

	}

	/**
	 * 	getScroller
	 *
	 * 	Returns a view scroller
	 *
	 * @param object $obj
	 * 	a page scroller configuration object
	 *
	 * @return string
	 * 	html text
	 */
	function getScroller($obj) {

		$scrpos = $obj->start;
		$showlist = isset($obj->showlist) ? $obj->showlist : TRUE;
		$count = min($obj->limit + $obj->start, $obj->rowCount);
		$options = isset($obj->scrolleroptions) ? $obj->scrolleroptions : array(5, 10, 25, 50);
		?>
		<div class = 'scrollerpanel'>
			<input
				type = 'hidden'
				class = 'ufo-viewscrollervalues'
				id = 'start'
				value = '<?php echo $obj->start;?>'>
			<input
				type = 'hidden'
				class = 'ufo-viewscrollervalues'
				id = 'rowcount'
				value = '<?php echo $obj->rowCount;?>'>
			<div>
				<span class = 'label'>
					<?php echo EasyContactFormsT::get('ScrollerRows');?>
				</span>
			</div>
			<?php if ($showlist) { ?>
				<div>
					<select
						class = 'scrollerlist ufo-viewscrollervalues'
						id = 'limit'
						onchange = 'ufo.filter(<?php echo $obj->jsconfig;?>)'>
						<?php echo EasyContactFormsIHTML::getScrollerStepsList($obj->limit, $options); ?>
					</select>
				</div>
			<?php }
						else { ?>
				<input
					type = 'hidden'
					class = 'ufo-viewscrollervalues'
					id = 'limit'
					value = '<?php echo $obj->limit;?>'>
			<?php } ?>
			<div style = 'white-space:nowrap;'>
				<span class = 'label'>

					(<?php echo ($obj->start + 1); ?>-<?php echo $count . ' ' . EasyContactFormsT::get('Of') . ' ' . $obj->rowCount;?>)

				</span>
			</div>
			<div>

				<?php echo EasyContactFormsIHTML::getButton(
					array(
						'title' => EasyContactFormsT::get('ScrollerFirst'),
						'events' => ' onclick = \'ufo.scroll(' . $obj->jsconfig . ', -2)\'',
						'bclass' => 'ufo-imagebutton',
						'iclass' => ' class = "icon_scroller_first" ',
					)
		);?>

			</div>
			<div>

				<?php echo EasyContactFormsIHTML::getButton(
					array(
						'title' => EasyContactFormsT::get('ScrollerBack'),
						'events' => ' onclick = \'ufo.scroll(' . $obj->jsconfig . ', -1)\'',
						'bclass' => 'ufo-imagebutton',
						'iclass' => ' class = "icon_scroller_prev" ',
					)
		);?>

			</div>
			<div>

				<?php echo EasyContactFormsIHTML::getButton(
					array(
						'title' => EasyContactFormsT::get('ScrollerForward'),
						'events' => ' onclick = \'ufo.scroll(' . $obj->jsconfig . ', 1)\'',
						'bclass' => 'ufo-imagebutton',
						'iclass' => ' class = "icon_scroller_next" ',
					)
		);?>

			</div>
			<div>

				<?php echo EasyContactFormsIHTML::getButton(
					array(
						'title' => EasyContactFormsT::get('ScrollerLast'),
						'events' => ' onclick = \'ufo.scroll(' . $obj->jsconfig . ', 2)\'',
						'bclass' => 'ufo-imagebutton',
						'iclass' => ' class = "icon_scroller_last" ',
					)
		);?>

			</div>
		</div>
		<?php
	}

	/**
	 * 	getButton
	 *
	 * 	Returns an html button
	 *
	 * @param array $params
	 * 	Button configuration parameters
	 *
	 * @return string
	 * 	html text
	 */
	function getButton($params) {

		$p = (object) $params;
		$bclass = isset($p->bclass) ? $p->bclass : 'button';
		$iclass = isset($p->iclass) ? $p->iclass : ' class = "internalbutton" ';
		$label = isset($p->label) ? $p->label : '';
		$removeevents = isset($p->removeevents) && $p->removeevents === TRUE;
		$title = isset($p->title) && !$removeevents ? ' title = "' . $p->title . '"' : '';
		$events = isset($p->events) && !$removeevents ? ' ' . $p->events : '';
		$disabledclass = $removeevents ? ' button-disabled' : '';
		$id = isset($p->id) ? ' id = "' . $p->id . '"' : '';
		?>
		<span class = '<?php echo $bclass . $disabledclass;?>'>
			<span>
				<a<?php echo $id . $iclass . $title . $events;?>>
					<?php echo $label;?>
				</a>
			</span>
		</span>
		<?php

	}

	/**
	 * 	getLPMover
	 *
	 * 	list position handler
	 *
	 * @param array $params
	 * 	config parameters
	 *
	 * @return string
	 * 	html text
	 */
	function getLPMover($params) {

		$p = (object) $params;
		?>
		<div class = 'listpositionmover'>
			<div class = 'commonbutton'>
				<div
					class = 'icon_trigger_up'
					onclick = 'ufo.moveRow(<?php echo $p->jsconfig;?>, 1,"<?php echo $p->id;?>")'>
				</div>
			</div>
			<div class = 'commonbutton'>
				<div
					class = 'icon_trigger_down'
					onclick = 'ufo.moveRow(<?php echo $p->jsconfig;?>, -1,"<?php echo $p->id;?>")'>
				</div>
			</div>
		</div>
		<?php

	}

	/**
	 * 	getTinyMCE
	 *
	 * 	Returns a tinyMCE setup js
	 *
	 * @param string $id
	 * 	an id of a textarea item to be used for tinyMCE object
	 *
	 * @return string
	 * 	html text
	 */
	function getTinyMCE($id) {

		$tmceconf = EasyContactFormsApplicationSettings::getInstance()->get('TinyMCEConfig');
		?>
		<input
			type = 'hidden'
			class = 'ufo-eval'
			value = 'AppMan.TMCEFactory.create("<?php echo $id;?>",<?php echo $tmceconf;?>);'>
		<?php

	}

	/**
	 * 	getFileUpload
	 *
	 * 	An ajax upload object wrapper
	 *
	 * @param int $id
	 * 	AjaxUpload button id
	 * @param object $object
	 * 	AjaxUpload configuration object
	 *
	 * @return string
	 * 	html text
	 */
	function getFileUpload($id, $object) {

		$jsrequest = EasyContactFormsUtils::toJs($object->request);
		$jsoncomplete = isset($object->oncomplete) ?
			', function(file, ext) {' . $object->oncomplete->func . '(' . $object->oncomplete->args . ')}'
			: '';
		$label = isset($object->value) ? EasyContactFormsT::get('Update') : EasyContactFormsT::get('Upload');
		?>
		<input
			type = 'hidden'
			class = 'ufo-eval'
			value = 'AppMan.AjaxUpload.create("<?php echo $id;?>",<?php echo $jsrequest;?><?php echo $jsoncomplete;?>);'>
		<span
			class = 'button internalimage ufo-upload'
			id = '<?php echo $id;?>'>
			<span>
				<a
					class = 'icon_button_upload'>
					<?php echo $label;?>
				</a>
			</span>
		</span>
		<?php if (isset($object->value)) : ?>
		<span
			class = 'button internalimage'
			onclick = 'ufo.deleteFile("<?php echo $id;?>",<?php echo $jsrequest;?>, this<?php echo $jsoncomplete;?>);'>
			<span>
				<a
					class = 'icon_button_delete'>
					<?php echo EasyContactFormsT::get('Delete');?>
				</a>
			</span>
		</span>
		<?php
					endif;

	}

	/**
	 * 	getFileDownloadLink
	 *
	 * 	File download link component
	 *
	 * @param array $config
	 * 	a file download link configuration object
	 *
	 * @return string
	 * 	html text
	 */
	function getFileDownloadLink($config) {

		$config = (object) $config;

		require_once 'easy-contact-forms-files.php';

		$url = EasyContactFormsFiles::getFileDownloadLink(
			$config->doctype,
			$config->field,
			$config->docid
		);

		if (!$url) {
			echo '';
			return;
		}

		$tag = $config->tag;
		$href = $config->tag == 'img' ? 'src' : 'href';
		$style = isset($config->style) ? $config->style : '';
		$content = isset($config->content) ? $config->content : '';

		echo "<$tag $style $href = '$url'>$content</$tag>";

	}

	/**
	 * 	getAS
	 *
	 * 	Ajax Suggest wrapper
	 *
	 * @param object $p
	 * 	ajax suggest configuration object
	 *
	 * @return string
	 * 	html text
	 */
	function getAS($p) {

		$view = $p->view;
		$fieldname = $p->field;
		$value = '';

		if (isset($fieldname) && !$view->isEmpty($fieldname)) {
			$value = $view->get($fieldname);
		}

		$valueclass = ( isset($p->filter) && $p->filter == TRUE ) ?
			'ufo-filtervalue' :
			'ufo-formvalue';

		$elid = isset($p->elid) ? $p->elid :	$fieldname;
		$infofieldid = $elid . 'info';
		$inputid = $elid . 'input';

		$md = isset($p->md) ? ', ' . $p->md : '';

		$inpstyle = isset($p->inpstyle) ? $p->inpstyle : '';
		$config = json_encode($p->config);
		$asparams = isset($p->asparams) ? json_encode($p->asparams) : '{}';

		$div = isset($p->type) && !$view->isEmpty('id');

		if ($div) {?>
			<div class = 'ufo-input-wrapper'<?php echo $inpstyle;?>>
		<?php
			$inpstyle = '';
		}?>
				<input
					type = 'hidden'
					id = '<?php echo $elid;?>'
					class = 'ufo-as textinput <?php echo $valueclass;?>'
					value = '<?php echo $value;?>'>
				<input
					type = 'hidden'
					class = 'ufo-eval'
					value = 'AppMan.AutoSuggest.create("<?php echo $elid;?>", <?php echo $asparams;?>, <?php echo $config;?>)'>
				<input
					type = 'text'
					id = '<?php echo $inputid;?>'
					class = 'ufo-asinput'<?php echo $inpstyle;?>
					onblur = "AppMan.AutoSuggest.blur(this, '<?php echo $elid;?>'<?php echo $md;?>);">
		<?php if ($div) { ?>
				<a
					id = '<?php echo $elid;?>-Trigger'
					href = 'javascript:;'
					class = 'ufo-triggerbutton icon_trigger_open'
					onclick = 'AppMan.AutoSuggest.redirect(this, "<?php echo $elid;?>", "<?php echo $p->type;?>");'>&nbsp;&nbsp;&nbsp;
				</a>
			</div>
		<?php }

	}

	/**
	 * 	getColumnHeader
	 *
	 * 	Column header
	 *
	 * @param array $params
	 * 	a column header configuration object
	 *
	 * @return string
	 * 	html text
	 */
	function getColumnHeader($params) {

		$p = (object) $params;
		$view = $p->view;
		$id = $view->oId($p->field);
		$label = isset($p->label) ? $p->label : EasyContactFormsT::get($p->field);
		if (isset($view->map['r'])) {
			echo $label;
			return;
		}
		?>
			<button type = 'button' class = 'ufo-tableheader thnoorder'
				id = '<?php echo $id;?>'
				onclick = 'ufo.sort(<?php echo $view->jsconfig;?>, "<?php echo $p->field;?>"); return false;'>
				<span class = 'thtext'>
					<?php echo $label;?>
				</span>
				<span class = 'thimage'>&nbsp;</span>
			</button>
		<?php

	}

	/**
	 * 	getTrSwapClassName
	 *
	 * 	row background color
	 *
	 * @param int $i
	 * 	row index
	 *
	 * @return string
	 * 	html text
	 */
	function getTrSwapClassName($i) {

		$classname = ($i / 2 == round($i / 2, 0)) ? 'ufoodd' : 'ufoeven';
		echo $classname;

	}

	/**
	 * 	showMessage
	 *
	 * 	Prints a message
	 *
	 * @param string $message
	 * 	message text
	 * @param string $cssclass
	 * 	message css class
	 *
	 * @return string
	 * 	html text
	 */
	function showMessage($message, $cssclass = 'notificationMessage') {

		echo "<div class='$cssclass'>$message</div>";

	}

	/**
	 * 	getNotLoggedInHTML
	 *
	 * 	prints a 'please log in' string
	 *
	 *
	 * @return string
	 * 	html text
	 */
	function getNotLoggedInHTML() {

		echo EasyContactFormsApplicationSettings::getInstance()->get('NotLoggenInText');

	}

	/**
	 * 	getRedirectHTML
	 *
	 * @param array $currmap
	 * 	Request data
	 */
	function getRedirectHTML($currmap) {

	}

}
