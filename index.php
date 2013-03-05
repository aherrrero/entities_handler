<?php
/**
 * Plugin Name: WPSkypeStatus
 * Description: A simple way to add status-aware Skype links to your site.
 * Author: Dan Bennett
 * Version: 0.1.0
 */

include 'skype.php';
$WPSS = new WPSkypeStatus();

// example
if(!defined("WP_PLUGIN_URL")){
	// $WPSS->debug(true);
	echo $WPSS->skype();
}

?>