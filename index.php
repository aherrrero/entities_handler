<?php
/**
 * Plugin Name: WPSkypeStatus
 * Description: A simple way to add status-aware Skype links to your site.
 * Author: Dan Bennett
 * Version: 0.3.1
 */

include 'skype.php';
$WPSS = new WPSkypeStatus();
$_settings = $WPSS->set_debug(true, true, false);
$WPSS->set_debug(false);

function skype_admin_menu(){
	add_management_page( 'Skype Options', 'Skype Options', 'manage_options', 'skype-options', 'skype_admin_page' );
}
if($_settings['isWP']){
	add_action('admin_menu', 'skype_admin_menu');
}

function skype_admin_page(){
	global $_settings;
	$_attr = $_settings['defaults'];
	$_rules = $_settings['rules'];
	$_prio = $_settings['weighting'];
	?>
	<div class='wrap'>
		<div id="icon-tools" class="icon32"><br /></div>
		<h2>Status-Aware Skype Link Settings</h2>
		<div id='ajax-response'></div>
		<br class="clear">
		<p>Use this page to change the default settings for the Skype Link plugin without having to manually edit the <code>conf.php</code> file!</p>
		<form name='skype_settings' id='skype_settings' action='' method='POST'>
			<table class='form-table'>
				<tbody>
					<tr valign='top'>
						<th scope='row'>
							<label><strong>Default Attributes</strong></label>
						</th>
						<td>
							<input type='text' class='regular-text ltr' name='_attr_username' id='_attr_username' <?php echo "value='".$_attr->username."'"; ?> />
							<div id="_names">
								<input type='text' class='regular-text ltr' name='_attr_name' id='_attr_name' <?php echo "value='".$_attr->name."'"; ?> />
							</div>
							<select id='_attr_type' name='_attr_type'>
								<option value = 'call'<?php echo (strtolower($_attr->type) === 'call') ? " selected='selected'" : ""; ?>>Call</option>
								<option value = 'chat'<?php echo (strtolower($_attr->type) === 'chat') ? " selected='selected'" : ""; ?>>Chat</option>
							</select>
							<div id="_backups">
								<input type='text' class='regular-text ltr' name='_attr_backups' id='_attr_backups' <?php echo "value='".$_attr->backups."'"; ?> />
							</div>
						</td>
					</tr>
					<!-- <tr valign='top'>
						<th scope='row'>
							<label for=''></label>
						</th>
						<td>
							<input type='text' class='regular-text ltr' name='' id='' value='<?php  ?>' />
						</td>
					</tr> -->
				</tbody>
			</table>
			<p class='submit settings'>
				<input type='submit' name='settings_save' id='settings_save' class='button-primary' value='Save Changes' />
				<input type='button' name='cancel_save' id='cancel_save' class='button cancel' value='Cancel' />
			</p>
		</form>
	</div>
	<?php
}

// example
if(!$_settings['isWP']){
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
		echo "<pre>" . print_r($WPSS->set_debug(true, true), true) . "</pre>";
		$WPSS->set_debug(false);
	}
}
?>