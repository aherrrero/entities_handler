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
	<p>Use this page to change the default Skype Link attributes without having to manually edit the <code>conf.php</code> file!</p>
	<p>
		The values provided here will be used whenever the shortcode is used and the respective field is omitted. This allows<br />
		you to omit any or all shortcode parameters and still have a working link.
	<p>
		Group chat/call <span style='text-transform: italics;'>should</span> work but I haven't tested it, or provided a status-lookup workaround for it, so don't be surprised<br />
		if it doesn't work exactly as expected when you provide multiple accounts in the <code>username</code> field.
	</p>
	<form name='skype_settings' id='skype_settings' action='' method='POST'>
		<table class='form-table'>
			<tbody>
				<tr valign='top'>
					<th scope='row'>
						<label for='username'><strong>Username</strong></label>
					</th>
					<td>
						<input type='text' class='regular-text ltr' name='username' id='username' value='<?php echo $_attr->username; ?>' />
						<p class='description'>
							The Skype account to link to by default.<br />
							May or may not support a list of usernames for a group chat/call.
						</p>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='name'><strong>Display Name</strong></label>
					</th>
					<td>
						<input type='text' class='regular-text ltr' name='name' id='name' value='<?php echo $_attr->username; ?>' />
						<p class='description'>The name as displayed in the link text (e.g. Sales, Support, Dave)</p>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='type'><strong>Link Type</strong></label>
					</th>
					<td>
						<select name='type' id='type'>
							<option value='call'<?php echo (strtolower($_attr->type) === 'call' ? ' selected="selected"' : ''); ?>>Call</option>
							<option value='chat'<?php echo (strtolower($_attr->type) === 'chat' ? ' selected="selected"' : ''); ?>>Chat</option>
							<option value='video'<?php echo (strtolower($_attr->type) === 'video' ? ' selected="selected"' : ''); ?>>Video</option>
						</select>
						<p class='description'>
							Whether to initiate a Chat or Call from the link<br />
							Please see the caveats of attempting a <a href='https://dev.skype.com/skype-uri/reference#uriCallVideoExplicit'>group video call</a>.
						</p>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='size'><strong>Icon Size</strong></label>
					</th>
					<td>
						<input type='text' class='regular-text ltr' name='size' id='size' value='<?php echo $_attr->size; ?>' maxlength='2' />
						<p class='description'>
							The default icon size to use for the link.<br />
							The size must be in the icon filename. The pattern for this should be editable in the next release.
						</p>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='backups'><strong>Backup Accounts</strong></label>
					</th>
					<td>
						<input type='text' class='regular-text ltr' name='backups' id='backups' value='<?php echo $_attr->backups; ?>' />
						<p class='description'>A comma-separated list of accounts to try if the <code>username</code> account is not 'online'.</p>
					</td>
				</tr>
				<!-- <tr valign='top'>
					<th scope='row'>
						<label for=''></label>
					</th>
					<td>
						<input type='text' class='regular-text ltr' name='' id='' value='<?php  ?>' />
						<p class='description'></p>
					</td>
				</tr> -->
			</tbody>
		</table>
		<p class='submit settings'>
			<input type='submit' name='settings_save _attr' id='settings_save _attr' class='button-primary' value='Save Changes' />
			<input type='button' name='cancel_save' id='cancel_save' class='button cancel' value='Cancel' />
		</p>
	</form>
	<?php
}

function skype_rules_page(){
	global $_settings;
	$_rules = $_settings['rules'];
	?>
	<p>Use this page to change the default </p>
	<form name='skype_settings' id='skype_settings' action='' method='POST'>
		<table class='form-table'>
			<tbody>
				<!-- <tr valign='top'>
					<th scope='row'>
						<label for=''></label>
					</th>
					<td>
						<input type='text' class='regular-text ltr' name='' id='' value='<?php  ?>' />
						<p class='description'></p>
					</td>
				</tr> -->
			</tbody>
		</table>
		<p class='submit settings'>
			<input type='submit' name='settings_save _rules' id='settings_save _rules' class='button-primary' value='Save Changes' />
			<input type='button' name='cancel_save' id='cancel_save' class='button cancel' value='Cancel' />
		</p>
	</form>
	<?php
}

function skype_prio_page(){
	global $_settings;
	$_prio = $_settings['weighting'];
	?>
	<p>Use this page to change the default </p>
	<form name='skype_settings' id='skype_settings' action='' method='POST'>
		<table class='form-table'>
			<tbody>
				<!-- <tr valign='top'>
					<th scope='row'>
						<label for=''></label>
					</th>
					<td>
						<input type='text' class='regular-text ltr' name='' id='' value='<?php  ?>' />
						<p class='description'></p>
					</td>
				</tr> -->
			</tbody>
		</table>
		<p class='submit settings'>
			<input type='submit' name='settings_save _prio' id='settings_save _prio' class='button-primary' value='Save Changes' />
			<input type='button' name='cancel_save' id='cancel_save' class='button cancel' value='Cancel' />
		</p>
	</form>
	<?php
}

?>