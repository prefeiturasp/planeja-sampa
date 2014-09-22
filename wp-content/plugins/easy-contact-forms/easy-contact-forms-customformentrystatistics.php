<?php

/**
 * @file
 *
 * 	EasyContactFormsCustomFormEntryStatistics class definition
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

require_once 'easy-contact-forms-baseclass.php';

/**
 * 	EasyContactFormsCustomFormEntryStatistics
 *
 */
class EasyContactFormsCustomFormEntryStatistics extends EasyContactFormsBase {

	/**
	 * 	EasyContactFormsCustomFormEntryStatistics class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'CustomFormEntryStatistics';

		$this->fieldmap = array(
				'id' => NULL,
				'PageName' => '',
				'TotalEntries' => 0,
				'IncludeIntoReporting' => 0,
				'CustomForms' => 0,
				'Impressions' => 0,
			);

		if ($objdata) {
			$this->init($new_id);
		}

	}

	/**
	 * 	getDeleteStatements
	 *
	 * 	prepares delete statements to be executed to delete a
	 * 	customformentrystatistics record
	 *
	 * @param int $id
	 * 	object id
	 *
	 * @return array
	 * 	the array of statements
	 */
	function getDeleteStatements($id) {

		$stmts[] = "DELETE FROM #wp__easycontactforms_customformentrystatistics WHERE id='$id';";

		return $stmts;

	}

	/**
	 * 	update. Overrides EasyContactFormsBase::update()
	 *
	 * 	updates an object with request data
	 *
	 * @param array $request
	 * 	request data
	 * @param int $id
	 * 	object id
	 */
	function update($request, $id) {

		$request = EasyContactFormsUtils::parseRequest($request, 'TotalEntries', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'IncludeIntoReporting', 'boolean');
		$request = EasyContactFormsUtils::parseRequest($request, 'CustomForms', 'int');
		$request = EasyContactFormsUtils::parseRequest($request, 'Impressions', 'int');

		parent::update($request, $id);

	}

	/**
	 * 	getEmptyObject. Overrides EasyContactFormsBase::getEmptyObject()
	 *
	 * 	creates and initializes a new CustomFormEntryStatistics
	 *
	 * @param array $map
	 * 	request data
	 * @param array $fields
	 * 	a field array
	 *
	 * @return object
	 * 	the initialized instance
	 */
	function getEmptyObject($map, $fields = NULL) {

		$fields = (object) array();
		$fields->CustomForms = EasyContactFormsDB::getFirst('CustomForms');

		return parent::getEmptyObject($map, $fields);

	}

	/**
	 * 	getShowHideButton
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function getShowHideButton($map) {

		$this->jsconfig = $this->getJSConfig($map);
		?>

		
<span class='ufo-imagebutton'>
  <a title='<?php echo EasyContactFormsT::get('Hide'); ?>' href='javascript:;' class='icon_button_close_dis'></a>
</span>

		<?php

	}

	/**
	 * 	increaseConversions
	 *
	 * @param  $formid
	 * 
	 * @param  $pagename
	 * 
	 *
	 * @return
	 * 
	 */
	function increaseConversions($formid, $pagename) {

		$this->increase('TotalEntries', $formid, $pagename);

	}

	/**
	 * 	increaseImpressions
	 *
	 * @param  $form
	 * 
	 *
	 * @return
	 * 
	 */
	function increaseImpressions($form) {

		$this->increase('Impressions', $form->get('id'), $form->pageName);

	}

	/**
	 * 	increase
	 *
	 * @param  $fieldname
	 * 
	 * @param  $formid
	 * 
	 * @param  $pagename
	 * 
	 *
	 * @return
	 * 
	 */
	function increase($fieldname, $formid, $pagename) {

		$query = "SELECT
				CustomFormEntryStatistics.id
			FROM
				#wp__easycontactforms_customformentrystatistics AS CustomFormEntryStatistics
			WHERE
				CustomFormEntryStatistics.CustomForms=%d
				AND CustomFormEntryStatistics.PageName LIKE %s";

		global $wpdb;
		$query = $wpdb->prepare($query, $formid, $pagename);
		$objid = EasyContactFormsDB::getValue($query);
		if (!$objid) {
			$obj = EasyContactFormsClassLoader::getObject($this->type, true);
			$obj->set('PageName', $pagename);
			$obj->set('CustomForms', $formid);
		}
		else {
			$obj = EasyContactFormsClassLoader::getObject($this->type, true, $objid);
		}
		$obj->set($fieldname, $obj->get($fieldname) + 1);
		$obj->save();

	}

	/**
	 * 	resetFormPageStatistics
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function resetFormPageStatistics($map) {

		$objid = intval($map['oid']);
		$obj = EasyContactFormsClassLoader::getObject('CustomFormEntryStatistics', true, $objid);
		$obj->set('Impressions', 0);
		$obj->set('TotalEntries', 0);
		$obj->save();
		$map['m'] = 'viewDetailed';
		EasyContactFormsRoot::processEvent($map);

	}

	/**
	 * 	setFormPageStatisticsShowOnDashboard
	 *
	 * @param  $map
	 * 
	 *
	 * @return
	 * 
	 */
	function setFormPageStatisticsShowOnDashboard($map) {

		$objid = intval($map['oid']);
		$obj = EasyContactFormsClassLoader::getObject('CustomFormEntryStatistics', true, $objid);
		$bval = $map['a'] == 'true' ? true : false;
		$obj->set('IncludeIntoReporting', $bval);
		$obj->save();
		$map['m'] = 'viewDetailed';
		EasyContactFormsRoot::processEvent($map);

	}

	/**
	 * 	getCustomFormsList
	 *
	 *
	 * @return
	 * 
	 */
	function getCustomFormsList() {

		$query="SELECT CustomForms.id, CustomForms.Description FROM #wp__easycontactforms_customforms AS CustomForms WHERE CustomForms.IncludeIntoReporting = TRUE";

		return $this->getList($query);
	}

	/**
	 * 	dispatch. Overrides EasyContactFormsBase::dispatch()
	 *
	 * 	invokes requested object methods
	 *
	 * @param array $dispmap
	 * 	request data
	 */
	function dispatch($dispmap) {

		$dispmap = parent::dispatch($dispmap);
		if ($dispmap == NULL) {
			return NULL;
		}

		$dispmethod = $dispmap["m"];
		switch ($dispmethod) {

			case 'resetFormPageStatistics':
				$this->resetFormPageStatistics($dispmap);
				return NULL;

			case 'setFormPageStatisticsShowOnDashboard':
				$this->setFormPageStatisticsShowOnDashboard($dispmap);
				return NULL;

			default : return $dispmap;
		}

	}

	/**
	 * 	getViews. Overrides EasyContactFormsBase::getViews()
	 *
	 * 	selects an object view to show on the client side
	 *
	 * @param array $vmap
	 * 	request data
	 */
	function getViews($vmap) {

		$viewname = parent::getViews($vmap);

		switch ($viewname) {

			case 'detailedmain':
				return $this->getDetailedMainView($vmap);
				break;

			default:return '';
		}

	}

	/**
	 * 	getDetailedMainView
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $viewmap
	 * 	request data
	 */
	function getDetailedMainView($viewmap) {

		$orderby = ' ORDER BY CustomFormEntryStatistics.CustomForms, CustomFormEntryStatistics.PageName';

		$rparams = $this->getFilter($viewmap);
		$viewfilters = array();

		$viewfilters = EasyContactFormsDB::getFilter($viewfilters, $rparams, 'CustomFormEntryStatistics.', 'IncludeIntoReporting', '=', 'boolean', (object) array('values' => array(FALSE)));

		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormEntryStatistics.', 'PageName');

		$viewfilters = EasyContactFormsDB::getSignFilter($viewfilters, $rparams, 'CustomFormEntryStatistics.', 'CustomForms', 'int');

		$query = "SELECT
				CustomFormEntryStatistics.id,
				CustomFormEntryStatistics.CustomForms,
				CustomFormEntryStatistics.PageName,
				CustomFormEntryStatistics.Impressions,
				CustomFormEntryStatistics.TotalEntries,
				CustomFormEntryStatistics.TotalEntries/CustomFormEntryStatistics.Impressions*100 AS Conversion,
				CustomFormEntryStatistics.IncludeIntoReporting,
				CustomForms.Description AS CustomFormsDescription
			FROM
				#wp__easycontactforms_customformentrystatistics AS CustomFormEntryStatistics
				INNER JOIN(
				SELECT
					CustomForms.id AS id,
					CustomForms.Description AS Description
				FROM
					#wp__easycontactforms_customforms AS CustomForms
				WHERE
					CustomForms.IncludeIntoReporting=TRUE) AS CustomForms
					ON
						CustomFormEntryStatistics.CustomForms=CustomForms.id";

		$resultset = EasyContactFormsDB::select($query, $viewfilters, $orderby);

		$this->CustomForms = $this->getCustomFormsList();
		$obj = $this;
		?><input type='hidden' name='t' id='t' value='CustomFormEntryStatistics'><?php

		include 'views/easy-contact-forms-customformentrystatisticsdetailedmainview.php';

	}

}
