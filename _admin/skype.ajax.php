<?php

if(isset($_POST['skype_set']) && (isset($_POST['data']) && !empty($_POST['data']))){
	require_once dirname(__FILE__) . implode(DIRECTORY_SEPARATOR, array('', '..', '..', '..', '..', 'wp-config.php'));
	require_once '../skype.php';
	$WPSS = new WPSkypeStatus();
	echo json_encode($WPSS->update_wp_settings($_POST['data']));
}
die();

?>