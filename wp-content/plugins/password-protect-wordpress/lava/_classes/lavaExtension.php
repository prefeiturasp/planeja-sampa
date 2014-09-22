<?php
class lavaExtension extends lavaBase {
	function lavaConstruct() {
		$this->_misc()->_addAutoMethods( $this );
		$this->registerActions();
	}

	function registerActions() {
		//should be overloaded
	}
}
?>