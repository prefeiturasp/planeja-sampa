<?php 
/**
 * lavaTables
 * 
 * @package Lava
 * @subpackage lavaTables
 * @author Daniel Chatfield
 * 
 * @since 1.0.0
 */
class lavaTables extends lavaBase
{
	public $tables = array();

	function getThis() {
		if( !is_null( $this->getContext() ) ) {
			return $this->getContext();
		}
		return $this;
	}

	function addTable( $slug ) {
		if( !array_key_exists( $slug, $this->tables ) ) {
			$args = array(
				$slug
			);
			$this->tables[ $slug ] = $this->_new( "lavaTable", $args );
		}
		$this->lavaContext( $this->tables[ $slug ] );
		return $this->tables[ $slug ]->withinContext( $this ); //return table object but tell it to remember who its parent is and pass on any method calls not intented for it
	}

	function fetchTable( $slug ) {
		$this->clearContext();
		if( array_key_exists($slug, $this->tables) ) {
			$this->setContext( $this->tables[ $slug ] );
		}

		return $this;
	}

	function tableExists( $slug ) {
		if( array_key_exists($slug, $this->tables) ) {
			return true;
		}
		return false;
	}
}
?>