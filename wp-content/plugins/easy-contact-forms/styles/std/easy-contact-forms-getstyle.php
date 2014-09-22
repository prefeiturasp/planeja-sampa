<?php
/**
 * @file
 * std style loader.
 */
  $prodversion = defined('EASYCONTACTFORMS__prodVersion') ? '?ver=' . EASYCONTACTFORMS__prodVersion : '';
?><link href='<?php echo EASYCONTACTFORMS__engineWebAppDirectory;?>/styles/std/css/std.css<?php echo $prodversion;?>' rel='stylesheet' type='text/css'/><link href='<?php echo EASYCONTACTFORMS__engineWebAppDirectory;?>/styles/std/css/icons.css<?php echo $prodversion;?>' rel='stylesheet' type='text/css'/><link href='<?php echo EASYCONTACTFORMS__engineWebAppDirectory;?>/styles/std/css/sizes.css<?php echo $prodversion;?>' rel='stylesheet' type='text/css'/>