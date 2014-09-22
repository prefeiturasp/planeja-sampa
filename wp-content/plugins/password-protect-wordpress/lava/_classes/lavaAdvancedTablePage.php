
<?php
class lavaAdvancedTablePage extends lavaPage
{
    public $dataSource;
    public $dataCache;
    public $displayOrder = array();


    function loadPage() {
        $this->addAction( "toolbarButtons" );
        $this->_pages()->addScript( $this->_slug( "lavaTableScripts" ), "lava/_static/table_scripts.js", array( "jquery" ) );
    }
    
    function setDataSource( $dataSource )
    {
        $this->dataSource = $dataSource;
        return $this->_pages( false );
    }

    function setDisplayOrder( $displayString )
    {
        $this->displayOrder = explode( ";", $displayString );
        $dataSourceSlug = $this->dataSource;
        $hookTag = "_dataSourceAjax_row/dataSource:{$dataSourceSlug}";
        $this->addFilter( $hookTag, "doDisplayOrder" );
        return $this->_pages( false );
    }

    function setOrderBy( $order ) {
        $this->_tables()->fetchTable( $this->dataSource )->setOrderBy( $order );
        return $this->_pages( false );
    }

    function displayPage() {
        if( is_null( $this->dataSource ) ) {
            $this->dieWith( "No data source specified for this page" );
        }
    	$this->doTablePage();
    }

    function doTablePage() {
        //$this->setHiddenField( ""  )
        ?>
        <div class="lava-layout lava-layout-full-width bleed-left bleed-right">
            <div class="lava-inset-module lava-filters">
                
            </div>
        </div>
        <?php
    }


    function toolbarButtons()
    {
        ?>
        <div class="toolbar-block toolbar-overground">
            <button class="lava-btn lava-btn-action lava-btn-inline lava-btn-action-white lava-table-loader-refresh-button" data-clicked-text="<?php _e( "Refreshing", $this->_framework() ) ?>"><?php _e( "Refresh", $this->_framework() ) ?></button>
            <button class="lava-btn lava-btn-action lava-btn-inline lava-btn-action-white lava-btn-form-submit lava-btn-confirmation not-implemented" data-form="lava-table-reset" data-clicked-text="<?php _e( "Resetting", $this->_framework() ) ?>"><?php _e( "Reset Settings", $this->_framework() ) ?></button>
        </div>
        <?php
    }

}
?>