<?php
/**
 * lavaTableField
 * 
 * @package Lava
 * @subpackage lavaTableField
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaTableField extends lavaBase
{
    public $type = "varchar";
    public $slug = "undefined";
    public $notNull = false;
    public $autoIncrement = false;
    public $noDefault = false;

    function lavaConstruct( $slug ) {
        $this->slug = $slug;
    }

    function setType( $type ) {
        $this->type = $type;

        return $this->_tables( false );
    }

    function setMaxLength( $max ) {
        $this->maxLength = $max;


        return $this->_tables( false );
    }

    function getType( $format = true ) {
        if( $format ):
            switch( $this->type ){
                case 'varchar':
                    return " varchar({$this->getMaxLength()})";
                case 'mediumint':
                    return " mediumint({$this->getMaxLength()})";
                default:
                    return " {$this->type}";
            }
        endif;
        return " " . $this->type;

    }

    function getMaxLength() {
        if( isset( $this->maxLength ) ) {
            return $this->maxLength;
        }
        switch( $this->getType( false ) ){
            case "varchar":
                return 255;
                break;
            case "text":
                return 1000;
                break;

        }
        return 30;
    }

    function getDefault() {
        if( $this->noDefault ) {
            return;
        }
        if( isset( $this->default ) ) {
            return " DEFAULT $this->default";
        } else {
            switch( $this->type ) {
                case "int":
                case "mediumint":
                case "bigint":
                case "tinyint":
                    return " DEFAULT 0";
                    break;
                case "varchar":
                    return " DEFAULT ''";
            }
        }
    }

    function setDefault( $default ) {
        switch( $this->type ) {
            case 'varchar':
                $this->default = "'$default'";
                break;
            case "int":
            case "mediumint":
            case "bigint":
            case "tinyint":
                default:
                $this->default = $default;
                break;
        }

        return $this->_tables( false );
    }

    function getNotNull() {
        if( $this->notNull ) {
            return " NOT NULL";
        }
    }

    function setAutoIncrement( $bool = true ) {
        $this->autoIncrement = $bool;

        if( $bool ) {
            $this->noDefault = true;
        }

        return $this->_tables( false );
    }

    function getAutoIncrement() {
        if( $this->autoIncrement ) {
            return " AUTO_INCREMENT";
        }
    }

    function sqlShit() {
        return "{$this->slug}{$this->getType()}{$this->getDefault()}{$this->getNotNull()}{$this->getAutoIncrement()}";
        return "{$this->slug}{$this->getType()}{$this->getDefault()}{$this->getNotNull()}{$this->getAutoIncrement()}";
    }
}
?>