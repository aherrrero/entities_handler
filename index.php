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
	
	// setup args from querystring
	$args = array();
	if(isset($_GET['username']) && !empty($_GET['username'])){
		$args['username'] = $_GET['username'];
	}
	if(isset($_GET['name']) && !empty($_GET['name'])){
		$args['name'] = $_GET['name'];
	}
	if(isset($_GET['type']) && !empty($_GET['type'])){
		$args['type'] = $_GET['type'];
	}
	if(isset($_GET['size']) && !empty($_GET['size'])){
		$args['size'] = $_GET['size'];
	}
	if(isset($_GET['backups']) && !empty($_GET['backups'])){
		$args['backups'] = $_GET['backups'];
	}
	?>
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset='utf-8' />
		<style>
		body{
			font-family: Calibri, Helvetica, sans-serif;
		}
		a{
			text-decoration: none;
			font-weight: bold;
		}
		#container{
			max-width: 900px;
			margin: 0 auto;
		}
		.skype_icon img{
			border: 0;
		}
		.call .skype_name:before{
			content: "Call ";
		}
		.chat .skype_name:before{
			content: "Chat With ";
		}
		.skype_name.size_16{
			font-size: 1em;
			margin: 0 0 0 0.4em;
			position: relative;
			top: -0.2em;
		}
		.skype_name.size_32{
			font-size: 2em;
			margin: 0 0 0 0.4em;
			position: relative;
			top: -0.2em;
		}
		.skype_name.size_64{
			font-size: 4em;
			margin: 0 0 0 0.4em;
			position: relative;
			top: -0.2em;
		}
		</style>
		<title>Status-Aware Skype Link</title>
	</head>
		<body>
			<div id="container">
				<?php
				echo $WPSS->skype($args);
				?>
			</div>
		</body>
	</html>
	<?php
}
?>