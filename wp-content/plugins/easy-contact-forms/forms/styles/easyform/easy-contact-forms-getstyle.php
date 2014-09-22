<?php
/**
 * @file
 * easyform style loader.
 */
  $prodversion = defined('EASYCONTACTFORMS__prodVersion') ? '?ver=' . EASYCONTACTFORMS__prodVersion : '';
?><link href='<?php echo EASYCONTACTFORMS__engineWebAppDirectory;?>/forms/styles/easyform/css/std.css<?php echo $prodversion;?>' rel='stylesheet' type='text/css'/>