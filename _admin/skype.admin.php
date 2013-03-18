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
							The Skype account to link to by default. Separate usernames for a group cal/chat with semicolons - no spaces!.<br />
							Status check is not performed if the link is a group call/chat, and any accounts given as backups will be ignored.
						</p>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='name'><strong>Display Name</strong></label>
					</th>
					<td>
						<input type='text' class='regular-text ltr' name='name' id='name' value='<?php echo $_attr->name; ?>' />
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
						<input type='text' class='regular-text ltr' name='backups' id='backups' value='<?php echo ($_attr->backups !== "false" ? $_attr->backups : ""); ?>' />
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
			<input type='submit' name='skype_save' id='skype_save' class='button-primary _attr' value='Save Changes' />
			<input type='button' name='skype_cancel' id='skype_cancel' class='button cancel _attr' value='Cancel' />
		</p>
	</form>
	<?php
}

function skype_rules_page(){
	global $_settings;
	$_rules = $_settings['rules'];
	?>
	<p>Use this page to change the default status display rules.</p>
	<p>
		There are 8 (eight) possible statuses according to the Skype API, as outlined below in order. The values of the 'Rule Name' column<br />
		should be used when naming image files to represent each status.
	</p>
	<table width='600'>
		<thead>
			<tr>
				<th style='text-align: left;'>Return Code</th>
				<th style='text-align: left;'>Status</th>
				<th style='text-align: left;'>Rule Name</th>
				<th style='text-align: left;'>Default Rule</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>0</td>
				<td>Unknown</td>
				<td>unknown</td>
				<td>offline</td>
			</tr>
			<tr>
				<td>1</td>
				<td>Offline</td>
				<td>offline</td>
				<td>offline</td>
			</tr>
			<tr>
				<td>2</td>
				<td>Online</td>
				<td>online</td>
				<td>online</td>
			</tr>
			<tr>
				<td>3</td>
				<td>Away</td>
				<td>away</td>
				<td>away</td>
			</tr>
			<tr>
				<td>4</td>
				<td>Not Available</td>
				<td>na</td>
				<td>offline</td>
			</tr>
			<tr>
				<td>5</td>
				<td>Do Not Disturb</td>
				<td>dnd</td>
				<td>dnd</td>
			</tr>
			<tr>
				<td>6</td>
				<td>Invisible</td>
				<td>invisible</td>
				<td>offline</td>
			</tr>
			<tr>
				<td>7</td>
				<td>Skype Me</td>
				<td>skypeme</td>
				<td>online</td>
			</tr>
		</tbody>
	</table>
	<br /><br />
	<p>Define below how to represent each status:</p>
	<form name='skype_settings' id='skype_settings' action='' method='POST'>
		<table class='form-table'>
			<tbody>
				<tr valign='top'>
					<th scope='row'>
						<label for='unknown'><strong>0: Unknown</strong></label>
					</th>
					<td>
						<select name='unknown' id='unknown'>
							<option value='unknown'<?php echo ($_rules[0] === 'unknown' ? ' selected="selected"' : ''); ?>>Unknown</option>
							<option value='offline'<?php echo ($_rules[0] === 'offline' ? ' selected="selected"' : ''); ?>>Offline</option>
							<option value='online'<?php echo ($_rules[0] === 'online' ? ' selected="selected"' : ''); ?>>Online</option>
							<option value='away'<?php echo ($_rules[0] === 'away' ? ' selected="selected"' : ''); ?>>Away</option>
							<option value='na'<?php echo ($_rules[0] === 'na' ? ' selected="selected"' : ''); ?>>Not Available</option>
							<option value='dnd'<?php echo ($_rules[0] === 'dnd' ? ' selected="selected"' : ''); ?>>Do Not Disturb</option>
							<option value='invisible'<?php echo ($_rules[0] === 'invisible' ? ' selected="selected"' : ''); ?>>Invisible</option>
							<option value='skypeme'<?php echo ($_rules[0] === 'skypeme' ? ' selected="selected"' : ''); ?>>Skype Me</option>
						</select>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='offline'><strong>1: Offline</strong></label>
					</th>
					<td>
						<select name='offline' id='offline'>
							<option value='unknown'<?php echo ($_rules[1] === 'unknown' ? ' selected="selected"' : ''); ?>>Unknown</option>
							<option value='offline'<?php echo ($_rules[1] === 'offline' ? ' selected="selected"' : ''); ?>>Offline</option>
							<option value='online'<?php echo ($_rules[1] === 'online' ? ' selected="selected"' : ''); ?>>Online</option>
							<option value='away'<?php echo ($_rules[1] === 'away' ? ' selected="selected"' : ''); ?>>Away</option>
							<option value='na'<?php echo ($_rules[1] === 'na' ? ' selected="selected"' : ''); ?>>Not Available</option>
							<option value='dnd'<?php echo ($_rules[1] === 'dnd' ? ' selected="selected"' : ''); ?>>Do Not Disturb</option>
							<option value='invisible'<?php echo ($_rules[1] === 'invisible' ? ' selected="selected"' : ''); ?>>Invisible</option>
							<option value='skypeme'<?php echo ($_rules[1] === 'skypeme' ? ' selected="selected"' : ''); ?>>Skype Me</option>
						</select>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='online'><strong>2: Online</strong></label>
					</th>
					<td>
						<select name='online' id='online'>
							<option value='unknown'<?php echo ($_rules[2] === 'unknown' ? ' selected="selected"' : ''); ?>>Unknown</option>
							<option value='offline'<?php echo ($_rules[2] === 'offline' ? ' selected="selected"' : ''); ?>>Offline</option>
							<option value='online'<?php echo ($_rules[2] === 'online' ? ' selected="selected"' : ''); ?>>Online</option>
							<option value='away'<?php echo ($_rules[2] === 'away' ? ' selected="selected"' : ''); ?>>Away</option>
							<option value='na'<?php echo ($_rules[2] === 'na' ? ' selected="selected"' : ''); ?>>Not Available</option>
							<option value='dnd'<?php echo ($_rules[2] === 'dnd' ? ' selected="selected"' : ''); ?>>Do Not Disturb</option>
							<option value='invisible'<?php echo ($_rules[2] === 'invisible' ? ' selected="selected"' : ''); ?>>Invisible</option>
							<option value='skypeme'<?php echo ($_rules[2] === 'skypeme' ? ' selected="selected"' : ''); ?>>Skype Me</option>
						</select>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='away'><strong>3: Away</strong></label>
					</th>
					<td>
						<select name='away' id='away'>
							<option value='unknown'<?php echo ($_rules[3] === 'unknown' ? ' selected="selected"' : ''); ?>>Unknown</option>
							<option value='offline'<?php echo ($_rules[3] === 'offline' ? ' selected="selected"' : ''); ?>>Offline</option>
							<option value='online'<?php echo ($_rules[3] === 'online' ? ' selected="selected"' : ''); ?>>Online</option>
							<option value='away'<?php echo ($_rules[3] === 'away' ? ' selected="selected"' : ''); ?>>Away</option>
							<option value='na'<?php echo ($_rules[3] === 'na' ? ' selected="selected"' : ''); ?>>Not Available</option>
							<option value='dnd'<?php echo ($_rules[3] === 'dnd' ? ' selected="selected"' : ''); ?>>Do Not Disturb</option>
							<option value='invisible'<?php echo ($_rules[3] === 'invisible' ? ' selected="selected"' : ''); ?>>Invisible</option>
							<option value='skypeme'<?php echo ($_rules[3] === 'skypeme' ? ' selected="selected"' : ''); ?>>Skype Me</option>
						</select>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='na'><strong>4: Not Available</strong></label>
					</th>
					<td>
						<select name='na' id='na'>
							<option value='unknown'<?php echo ($_rules[4] === 'unknown' ? ' selected="selected"' : ''); ?>>Unknown</option>
							<option value='offline'<?php echo ($_rules[4] === 'offline' ? ' selected="selected"' : ''); ?>>Offline</option>
							<option value='online'<?php echo ($_rules[4] === 'online' ? ' selected="selected"' : ''); ?>>Online</option>
							<option value='away'<?php echo ($_rules[4] === 'away' ? ' selected="selected"' : ''); ?>>Away</option>
							<option value='na'<?php echo ($_rules[4] === 'na' ? ' selected="selected"' : ''); ?>>Not Available</option>
							<option value='dnd'<?php echo ($_rules[4] === 'dnd' ? ' selected="selected"' : ''); ?>>Do Not Disturb</option>
							<option value='invisible'<?php echo ($_rules[4] === 'invisible' ? ' selected="selected"' : ''); ?>>Invisible</option>
							<option value='skypeme'<?php echo ($_rules[4] === 'skypeme' ? ' selected="selected"' : ''); ?>>Skype Me</option>
						</select>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='dnd'><strong>5: Do Not Disturb</strong></label>
					</th>
					<td>
						<select name='dnd' id='dnd'>
							<option value='unknown'<?php echo ($_rules[5] === 'unknown' ? ' selected="selected"' : ''); ?>>Unknown</option>
							<option value='offline'<?php echo ($_rules[5] === 'offline' ? ' selected="selected"' : ''); ?>>Offline</option>
							<option value='online'<?php echo ($_rules[5] === 'online' ? ' selected="selected"' : ''); ?>>Online</option>
							<option value='away'<?php echo ($_rules[5] === 'away' ? ' selected="selected"' : ''); ?>>Away</option>
							<option value='na'<?php echo ($_rules[5] === 'na' ? ' selected="selected"' : ''); ?>>Not Available</option>
							<option value='dnd'<?php echo ($_rules[5] === 'dnd' ? ' selected="selected"' : ''); ?>>Do Not Disturb</option>
							<option value='invisible'<?php echo ($_rules[5] === 'invisible' ? ' selected="selected"' : ''); ?>>Invisible</option>
							<option value='skypeme'<?php echo ($_rules[5] === 'skypeme' ? ' selected="selected"' : ''); ?>>Skype Me</option>
						</select>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='invisible'><strong>6: Invisible</strong></label>
					</th>
					<td>
						<select name='invisible' id='invisible'>
							<option value='unknown'<?php echo ($_rules[6] === 'unknown' ? ' selected="selected"' : ''); ?>>Unknown</option>
							<option value='offline'<?php echo ($_rules[6] === 'offline' ? ' selected="selected"' : ''); ?>>Offline</option>
							<option value='online'<?php echo ($_rules[6] === 'online' ? ' selected="selected"' : ''); ?>>Online</option>
							<option value='away'<?php echo ($_rules[6] === 'away' ? ' selected="selected"' : ''); ?>>Away</option>
							<option value='na'<?php echo ($_rules[6] === 'na' ? ' selected="selected"' : ''); ?>>Not Available</option>
							<option value='dnd'<?php echo ($_rules[6] === 'dnd' ? ' selected="selected"' : ''); ?>>Do Not Disturb</option>
							<option value='invisible'<?php echo ($_rules[6] === 'invisible' ? ' selected="selected"' : ''); ?>>Invisible</option>
							<option value='skypeme'<?php echo ($_rules[6] === 'skypeme' ? ' selected="selected"' : ''); ?>>Skype Me</option>
						</select>
					</td>
				</tr>
				<tr valign='top'>
					<th scope='row'>
						<label for='skypeme'><strong>7: Skype Me</strong></label>
					</th>
					<td>
						<select name='dnd' id='dnd'>
							<option value='unknown'<?php echo ($_rules[7] === 'unknown' ? ' selected="selected"' : ''); ?>>Unknown</option>
							<option value='offline'<?php echo ($_rules[7] === 'offline' ? ' selected="selected"' : ''); ?>>Offline</option>
							<option value='online'<?php echo ($_rules[7] === 'online' ? ' selected="selected"' : ''); ?>>Online</option>
							<option value='away'<?php echo ($_rules[7] === 'away' ? ' selected="selected"' : ''); ?>>Away</option>
							<option value='na'<?php echo ($_rules[7] === 'na' ? ' selected="selected"' : ''); ?>>Not Available</option>
							<option value='dnd'<?php echo ($_rules[7] === 'dnd' ? ' selected="selected"' : ''); ?>>Do Not Disturb</option>
							<option value='invisible'<?php echo ($_rules[7] === 'invisible' ? ' selected="selected"' : ''); ?>>Invisible</option>
							<option value='skypeme'<?php echo ($_rules[7] === 'skypeme' ? ' selected="selected"' : ''); ?>>Skype Me</option>
						</select>
					</td>
				</tr>
				<!-- <tr valign='top'>
					<th scope='row'>
						<label for=''></label>
					</th>
					<td>
						
					</td>
				</tr> -->
			</tbody>
		</table>
		<p class='submit settings'>
			<input type='submit' name='skype_save' id='skype_save' class='button-primary _rules' value='Save Changes' />
			<input type='button' name='skype_cancel' id='skype_cancel' class='button cancel _rules' value='Cancel' />
		</p>
	</form>
	<?php
}

function skype_prio_page(){
	global $_settings;
	$_prio = $_settings['weighting'];
	$_labels = array(
		'skypeme' => 'Skype Me',
		'online' => 'Online',
		'away' => 'Away',
		'dnd' => 'Do Not Disturb',
		'na' => 'Not Available',
		'invisible' => 'Invisible',
		'offline' => 'Offline',
		'unknown' => 'Unknown'
	);
	?>
	<p>
		Use this page to change the default weighting of the possible Skype statuses. The nearer the top of the list a status is<br />
		the more preferred it will be. This weighting is used when parsing backup accounts in order to provide your users with a<br />
		as close to 'online' - and thus more available to talk - as possible.
	</p>
	<form name='skype_settings' id='skype_settings' action='' method='POST'>
		<ul id='sortable'>
			<?php
			foreach ($_prio as $status) {
				echo "<li class='ui-state-default'>";
				echo "<span class='label'>".$_labels[$status]."</span>";
				echo "<span class='input' style='display: none;'><input type='hidden' value='$status' name='$status' id='$status' /></span>";
				echo "</li>";
			}
			?>
		</ul>
		<p class='submit settings'>
			<input type='submit' name='skype_save' id='skype_save' class='button-primary _prio' value='Save Changes' />
			<input type='button' name='skype_cancel' id='skype_cancel' class='button cancel _prio' value='Cancel' />
		</p>
	</form>
	<?php
}

?>