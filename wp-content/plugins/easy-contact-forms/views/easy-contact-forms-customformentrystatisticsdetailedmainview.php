<?php
/**
 * @file
 *
 * 	EasyContactFormsCustomFormEntryStatistics detailedMain view html
 * 	template
 *
 * 	@see EasyContactFormsCustomFormEntryStatistics
 * 	::getDetailedMainView()
 */

/*  Copyright championforms.com, 2012-2013 | http://championforms.com  
 * -----------------------------------------------------------
 * Easy Contact Forms
 *
 * This product is distributed under terms of the GNU General Public License. http://www.gnu.org/licenses/gpl-2.0.txt.
 * 
 */

?>
  <div>
    <div class='viewtable'>
      <table class='vtable'>
        <tr>
          <th>
            <?php echo EasyContactFormsT::get('CustomForm');?>
          </th>
          <th>
            <?php echo EasyContactFormsT::get('PageName');?>
          </th>
          <th>
            <?php echo EasyContactFormsT::get('Impressions');?>
          </th>
          <th>
            <?php echo EasyContactFormsT::get('TotalEntries');?>
          </th>
          <th>
            <?php echo EasyContactFormsT::get('Conversion');?>
          </th>
          <th>
            <?php echo EasyContactFormsT::get('Empty');?>
          </th>
          <th>
            &nbsp;
          </th>
        </tr>
        <?php EasyContactFormsLayout::getRows(
          $resultset,
          'EasyContactFormsCustomFormEntryStatistics',
          $obj,
          'easy-contact-forms-customformentrystatisticsdetailedmainviewrow.php',
          'getCustomFormEntryStatisticsDetailedMainViewRow',
          $viewmap
        );?>
      </table>
    </div>
  </div>
