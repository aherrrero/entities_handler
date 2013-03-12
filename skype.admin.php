<?php

function skype_admin_setup(){
	$tabs = array(
		'attr'=>'Link Parameters',
		'rules'=>'Status Rules',
		'prio'=>'Status Weighting'
	);
	$current = (isset($_GET['tab']) && !empty($_GET['tab'])) ? $_GET['tab'] : 'attr';
	?>
	<div id="icon-tools" class="icon32"><br /></div>
	<h2 class="nav-tab-wrapper">
	<?php
	foreach ($tabs as $tab => $name) {
		$class = ($tab == $current) ? ' nav-tab-active' : '';
		echo "<a class='nav-tab$class' href='?page=skype-options&tab=$tab'>$name</a>";
		$class = null;
	}
	?>
	</h2>
	<div id='ajax-response'></div>
	<br class="clear">
	<?php
	switch ($current) {
		case 'rules':
			skype_rules_page();
			break;
		case 'prio':
			skype_prio_page();
			break;
		case 'attr':
		default:
			skype_attr_page();
			break;
	}
	$tabs = null;
	$current = null;
}

function skype_attr_page(){
	global $_settings;
	$_attr = $_settings['defaults'];
	?>
	<p>Use this page to change the default settings for the Skype Link plugin without having to manually edit the <code>conf.php</code> file!</p>
	<form name='skype_settings' id='skype_settings' action='' method='POST'>
		<table class='form-table'>
			<tbody>
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
	<?php
}

function skype_rules_page(){
	
}

function skype_prio_page(){
	
}

?>