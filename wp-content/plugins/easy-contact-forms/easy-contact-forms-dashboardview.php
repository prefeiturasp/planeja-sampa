<?php

/**
 * @file
 *
 * 	EasyContactFormsDashBoardView class definition
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
 * 	EasyContactFormsDashBoardView
 *
 */
class EasyContactFormsDashBoardView extends EasyContactFormsForms {

	/**
	 * 	EasyContactFormsDashBoardView class constructor
	 *
	 * @param boolean $objdata
	 * 	TRUE if the object should be initialized with db data
	 * @param int $new_id
	 * 	object id. If id is not set or empty a new db record will be created
	 */
	function __construct($objdata = FALSE, $new_id = NULL) {

		$this->type = 'DashBoardView';

	}

	/**
	 * 	getEntryStatistics
	 *
	 *
	 * @return
	 * 
	 */
	function getEntryStatistics() {

		$query = "SELECT
				CustomForms.id,
				CustomForms.Description,
				COUNT(CustomFormsEntries.id) AS EntryCount,
				ProcessedEntries.ProcessedCount
			FROM
				#wp__easycontactforms_customformsentries AS CustomFormsEntries
			INNER JOIN
				#wp__easycontactforms_customforms AS CustomForms
					ON
						CustomFormsEntries.CustomForms=CustomForms.id
				LEFT JOIN(
				SELECT
					COUNT(CustomFormsEntries.id) AS ProcessedCount,
					CustomForms.id AS id,
					CustomForms.Description AS Description
				FROM
					#wp__easycontactforms_customformsentries AS CustomFormsEntries
				INNER JOIN
					#wp__easycontactforms_customforms AS CustomForms
						ON
							CustomFormsEntries.CustomForms=CustomForms.id
				WHERE
					CustomForms.id<>2
					AND CustomFormsEntries.Users<>0
				GROUP BY CustomForms.id) AS ProcessedEntries
					ON
						ProcessedEntries.id=CustomForms.id
			WHERE
				CustomForms.IncludeIntoReporting=TRUE
			GROUP BY CustomForms.id";

		$objs = EasyContactFormsDB::getObjects($query);

			?>

		
<table class='ufo-formstatistics vtable'>
  <tr>
    <th>
      <?php echo EasyContactFormsT::get('CustomForm');?>
    </th>
    <th>
      <?php echo EasyContactFormsT::get('EntryCount');?>
    </th>
    <th>
      <?php echo EasyContactFormsT::get('ProcessedEntryCount');?>
    </th>
  </tr>
  <?php foreach($objs as $obj) { ?>
    <tr>
      <td>
        <a onclick='ufo.redirect({m:"show", oid:<?php echo $obj->id;?>, t:"CustomForms"})'><?php echo $obj->Description;?></a>
      </td>
      <td>
        <?php echo $obj->EntryCount;?>
      </td>
      <td>
        <?php echo $obj->ProcessedCount;?>
      </td>
    </tr>
  <?php } ?>
</table>

		<?php 

	}

	/**
	 * 	getFormPageStatistics
	 *
	 *
	 * @return
	 * 
	 */
	function getFormPageStatistics() {

		$pfs = EasyContactFormsClassLoader::getObject('CustomFormEntryStatistics');
		$map = array();
		$map['viewTarget'] = 'CustomFormEntryStatisticsDiv';
		$map['t'] = 'CustomFormEntryStatistics';
		$map['m'] = 'viewDetailed';
		$pfs->map = $map;
		$pfs->jsconfig = $pfs->getJSConfig($map);
		echo "<div id='CustomFormEntryStatisticsDiv'>";
		$pfs->getDetailedMainView($map);
		echo "</div>";

	}

	/**
	 * 	getFormStatistics
	 *
	 *
	 * @return
	 * 
	 */
	function getFormStatistics() {

		$query = "SELECT
				CustomForms.id,
				CustomForms.Description,
				CustomForms.Impressions,
				CustomForms.TotalEntries,
				CustomForms.TotalProcessedEntries
			FROM
				#wp__easycontactforms_customforms AS CustomForms
			WHERE
				CustomForms.IncludeIntoReporting=TRUE";

		$objs = EasyContactFormsDB::getObjects($query);

			?>

		
<table class='ufo-formstatistics vtable'>
  <tr>
    <th>
      <?php echo EasyContactFormsT::get('CustomForm');?>
    </th>
    <th>
      <?php echo EasyContactFormsT::get('Impressions');?>
    </th>
    <th>
      <?php echo EasyContactFormsT::get('TotalEntries');?>
    </th>
    <th>
      <?php echo EasyContactFormsT::get('Conversion1');?>
    </th>
    <th>
      <?php echo EasyContactFormsT::get('TotalProcessedEntries');?>
    </th>
    <th>
      <?php echo EasyContactFormsT::get('Conversion2');?>
    </th>
  </tr>
  <?php foreach($objs as $obj) { ?>
    <tr>
      <td>
        <a onclick='ufo.redirect({m:"show", oid:<?php echo $obj->id;?>, t:"CustomForms"})'><?php echo $obj->Description;?></a>
      </td>
      <td>
        <?php echo $obj->Impressions;?>
      </td>
      <td>
        <?php echo $obj->TotalEntries;?>
      </td>
      <td>
        <?php $conv1 = $obj->Impressions == 0 ? 0 : ($obj->TotalEntries / $obj->Impressions) * 100; echo sprintf('%01.2f', $conv1) . '%';?>
      </td>
      <td>
        <?php echo $obj->TotalProcessedEntries;?>
      </td>
      <td>
        <?php $conv2 = $obj->TotalEntries == 0 ? 0 : ($obj->TotalProcessedEntries / $obj->TotalEntries) * 100; echo sprintf('%01.2f', $conv2) . '%';?>
      </td>
    </tr>
  <?php } ?>
</table>

		<?php 

	}

	/**
	 * 	getUserStatistics
	 *
	 *
	 * @return
	 * 
	 */
	function getUserStatistics() {

		$users = EasyContactFormsDB::getValue('SELECT COUNT(Users.id) AS Count FROM #wp__easycontactforms_users AS Users');
		$wpusers = EasyContactFormsDB::getValue('SELECT COUNT(Users.ID) AS Count FROM #wp__users AS Users');

		$query = "SELECT
				COUNT(Users.id) AS Count
			FROM
				#wp__easycontactforms_users AS Users
			WHERE
				Users.CMSId<>0";

		$regusers = EasyContactFormsDB::getValue($query);

			?>

		
<table class='ufo-userstatistics'>
  <tr>
    <td>
      <?php echo EasyContactFormsT::get('SiteUserCount');?>
    </td>
    <td>
      <?php echo $wpusers;?>
    </td>
  </tr>
  <tr>
    <td>
      <?php echo EasyContactFormsT::get('EasyContactUserCount');?>
    </td>
    <td>
      <?php echo $users;?>
    </td>
  </tr>
  <tr>
    <td>
      <?php echo EasyContactFormsT::get('SiteUserCountInEasyContactCount');?>
    </td>
    <td>
      <?php echo $regusers;?>
    </td>
  </tr>
</table>

		<?php 

	}

	/**
	 * 	getDashBoardViewForm
	 *
	 * 	prepares the view data and finally passes it to the html template
	 *
	 * @param array $formmap
	 * 	request data
	 */
	function getDashBoardViewForm($formmap) {

		$obj = $this;
		$obj->fields = (object) $formmap;

		require_once 'views/easy-contact-forms-dashboardviewform.php';

	}

}
