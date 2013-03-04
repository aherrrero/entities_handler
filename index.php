<?php
/**
 * Plugin Name: WPSkypeStatus
 * Description: A simple way to add status-aware Skype links to your site.
 * Author: Dan Bennett
 * Version: 0.1.0
 */

include 'skype.php';
$WPSS = new WPSkypeStatus();

echo $WPSS->skype(array('username'=>'dabennet-intergral'));

?>