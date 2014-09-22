<?php
/*
	Serves media files
*/

// include wordpress

$current = __file__;
$junction = true;
for($i = 0; $i < 5; $i++) {
	$current = dirname($current);
	if(file_exists($current.'/wp-load.php')) {
		require_once($current.'/wp-load.php');
		$junction = false;
		break;
	}
}

if($junction) {
	require_once('G:\Projects\_workflow_\WordPress 3.4\wp-load.php');
}


$thePlugin = lava::fetchPlugin('Private Blog');

$thePlugin->pluginCallbacks->doMedia();
?>