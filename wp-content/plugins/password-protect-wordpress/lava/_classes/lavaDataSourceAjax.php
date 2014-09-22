<?php
/**
 * The Data Source ajax class
 * 
 * 
 * @package Lava
 * @subpackage lavaDataSourceAjax
 * 
 * @author Daniel Chatfield
 * @copyright 2012
 * @version 1.0.0
 */
class lavaDataSourceAjax extends lavaAjax {
	public $targetAction = "data_source";

	function doAjax() {
		$dataSourceSlug = $_REQUEST['data-source'];//no need to santize it here as that is handled later

		$offset = 0;
		if(array_key_exists('offset', $_REQUEST)) {
			$offset = $_REQUEST['offset'];
		}

		if( !$this->_tables()->tableExists( $dataSourceSlug ) ) {
			$this->returnError( "The specified data source could not be retrieved :(" );
			$this->doReturn();
		}

		$dataSource = $this->_tables()->fetchTable( $dataSourceSlug );

		$result = $dataSource->getResults();

		$result = $this->runFilters( "_dataSourceAjax_result", $result );
		$result = $this->runFilters( "_dataSourceAjax_result/dataSource:{$dataSourceSlug}", $result );
		$newResult = array();
		foreach( $result as $i => $row ) {
			$row = $this->runFilters( "_dataSourceAjax_row", $row );
			$row = $this->runFilters( "_dataSourceAjax_row/dataSource:{$dataSourceSlug}", $row );
			$newRow = array();

			foreach( $row as $col => $column ) {
				$newColumn = $this->runFilters( "_dataSourceAjax_column", $column );
				$newColumn = $this->runFilters( "_dataSourceAjax_column/dataSource:{$dataSourceSlug}", $newColumn );
				$newColumn = $this->runFilters( "_dataSourceAjax_column/dataSource:{$dataSourceSlug}/col:$col", $newColumn );

				$argArray = array(
					"original" => $column,
					"new" => $newColumn
				);

				$classes = $this->runFilters( "_dataSourceAjax_columnClasses/dataSource:{$dataSourceSlug}/col:$col", "", $argArray );
				$title = $this->runFilters( "_dataSourceAjax_columnTitle/dataSource:{$dataSourceSlug}/col:$col", $newColumn, $argArray );

				$newRow[$col] = array(
					"data" => $newColumn,
					"classes" => $classes,
					"title" => $title
				);
			}

			$newResult[$i] = $newRow;
		}

		$limit = 50;

		$newResult = array_slice($newResult, $offset * $limit, $limit);

		

		$return = array(
			"data" => $newResult
		);

		$this->returnData( $return );
		$this->doReturn();
	}
}
?>