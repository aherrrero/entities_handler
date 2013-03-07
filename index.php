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
	if(!isset($_GET['json'])){
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
				padding: 1em 0.5em 0 0.5em;
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
			<title>Status-Aware Skype Links</title>
		</head>
			<body>
				<div id="container">
					<h2>Status-Aware Skype Links!</h2>
					<div id="example">
						<?php echo $WPSS->skype($args); ?>
					</div>
					<div id="instructions">
						<h3>How to Use This Example</h3>
						<ul>Form a query string with any combination of:
							<li>name - the name shown in the link (e.g. Sales or Dan)</li>
							<li>username - the username of the Skype account you want to link to</li>
							<li>type - whether the link should initiate a call or a chat</li>
							<li>size - the image size to use (default images are 16, 32 or 64px squares)</li>
							<li>backups - a comma-separated list of alternatives to 'username' that are used if 'username' is offline</li>
							Example: <a href="?username=echo123&name=Echo%20Test&size=32">?username=echo123&name=Echo%20Test&size=32</a>
						</ul>
						<p>
							For your Skype status to be visible to the API you'll need to check the following option:
							<br /><br />
							<img src="images/status-settings.png" width="723" height="521" />
						</p>
					</div>
				</div>
			</body>
		</html>
		<?php
	} else {
		echo "<pre>" . print_r($WPSS->debug(true, true), true) . "</pre>";
		$WPSS->debug(false);
	}
}
?>