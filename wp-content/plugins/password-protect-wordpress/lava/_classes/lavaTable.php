<?php
/**
 * lavaTable
 * 
 * @package Lava
 * @subpackage lavaTable
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaTable extends lavaBase
{
    public $slug;
    public $fields = array();
    public $autoMethods = true;
    public $uniqueKeyAdded = false;
    public $uniquekey = null;
    public $previousRows = array();

    function lavaConstruct( $slug ) {
        $this->slug = $slug;
    }

    function adminInit() {
        $this->consistencyCheck();
    }

    function addField( $slug ) {
        if( !array_key_exists( $slug, $this->fields ) ) {
            $args = array(
                $slug
            );
            $this->fields[ $slug ] = $this->_new( "lavaTableField", $args );
        }
        if( ! $this->uniqueKeyAdded ) {
            $this->uniqueKey = $this->fields[ $slug ];
            $this->uniqueKeyAdded = true;
        }
        $this->lavaContext( $this->fields[ $slug ] );
        return $this;
    }

    function getVersion() {
        $tables = $this->_settings()->getCache( "tables" );
        if( array_key_exists( $this->slug, $tables ) ) {
            $table = $tables[ $this->slug ];

            if( array_key_exists( "version", $table ) ) {
                return $table['version'];
            }
        }

        return '0';
    }

    function setVersion( $version = null ) {
        if( is_null( $version ) ) {
            $version = $this->_version();
        }
        $tables = $this->_settings()->getCache( "tables" );

        $tables[ $this->slug ]['version'] = $version;

        $this->_settings()->putCache( "tables", $tables )->updateCache();
    }

    function consistencyCheck() {
        $version = $this->getVersion();
        if( !$this->_misc()->versionMatch( $version ) ) {
            //version mismatch - run upgrade/install functions
            if( $this->doInstall() ) {
                $this->setVersion();
            }
        }

    }

    function getTableName() {
        global $wpdb;
        return $wpdb->prefix . $this->_slug( $this->slug );
    }

    function setOrderBy( $key ) {
        $this->orderBy = $key;
    }

    function getOrderBy() {
        if( isset( $this->orderBy ) ) {
            return $this->orderBy;
        } else {
            return $this->uniqueKey->slug;
        }
    }

    function doInstall() {
        global $wpdb;

        if( count( $this->fields ) == 0 ) {
            echo count( $this->fields );
            die('NO FIELDS IN TABLE: ' . $this->slug );
            return false;
        }

        $tableName = $this->getTableName();

        $sql = "CREATE TABLE $tableName (";

        $count = count( $this->fields );

        foreach( $this->fields as $i => $field ) {
            $sql .= "\n" . $field->sqlShit() . ",";
        }

        $sql .= "\nUNIQUE KEY id ({$this->uniqueKey->slug})";

        $sql .= "\n);";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');


        dbDelta($sql);
        return true;
    }

    function insertRow( $row, $nonce = null ) {
        global $wpdb;
        if( !is_null( $nonce ) ) {
            $hookTag = "insertRow/nonce-{$nonce}";
            $row = $this->runFilters( $hookTag, $row );
        }
        if( ! is_null( $nonce ) and array_key_exists($nonce, $this->previousRows) ) {
            $tableName = $this->getTableName();
            $uniqueId = $this->uniqueKey->slug;
            $rowToDelete = $this->previousRows[ $nonce ];
            $sql = "
                DELETE FROM {$tableName}
                WHERE {$uniqueId} = {$rowToDelete}
            ";
            //$wpdb->query( $sql );
        }
        $affected_rows = $wpdb->insert( $this->getTableName(), $row );
        $row_id = $wpdb->insert_id;

        if( !is_null( $nonce ) ) {
            $this->previousRows[ $nonce ] = $row_id;
        }
    }

    function getResults( $where = null, $orderBy = null, $startFrom = 0, $numberOfResults = 100 ) {
        global $wpdb;
        $tableName = $this->getTableName();

        if( is_null( $orderBy ) ) {
            $orderBy = $this->getOrderBy();
        }

        $sql = 
        "SELECT *
        FROM {$tableName}
        ";

        if( !is_null( $where ) ) {
            $sql .= "WHERE {$where}
            ";
        }

        if( !is_null( $orderBy ) ) {
            $sql .= "ORDER BY {$orderBy}
            ";
        }


        $result = $wpdb->get_results( $sql, ARRAY_A );

        return $result;
    }

    function deleteTable() {
        global $wpdb;
        $tableName = $this->getTableName();

        $sql = "DELETE FROM {$tableName}";

        $wpdb->query($sql);
    }
}
?>