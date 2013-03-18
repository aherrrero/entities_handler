<?php

class WPSkypeStatus{
	protected $_debug;		// whether or not to show debugging info
	protected $isWP;		// is this being used as a WordPress plugin?
	protected $_attr;		// default parameters
	protected $_rules;		// status mapping
	protected $_prio;		// status weighting
	protected $dirPath;		// absolute path to file's directory
	protected $dirURL;		// URL path to file's directory
	protected $imgPath;		// absolute and URL paths to images folder

	// define values for all class variables
	public function __construct(){
		// debugging is off by default
		$this->_debug = array(false, false, true);
		// check if being used as a WordPress plugin
		$this->isWP = defined("WPLANG");
		// find absolute path to this file
		$this->dirPath = dirname(__FILE__) . DIRECTORY_SEPARATOR;
		if($this->isWP){
			// use the WP_PLUGIN_URL constant for accuracy
			$this->dirURL = WP_PLUGIN_URL . '/WPSkypeStatus/';
			// get settings from database
			$this->get_wp_settings();
			// register the shortcode
			add_shortcode('skype', array($this, 'skype'));
		} else {
			// include conf file for default settings
			include $this->dirPath.implode(DIRECTORY_SEPARATOR, array('_admin', 'conf.php'));
			$this->dirURL = $_dirURL;
			$this->_attr = $_defaults;
			$this->_rules = $_rules;
			$this->_prio = $_prio;
		}
		// use $dirPath and $dirURL to get the paths for the images folder
		$this->imgPath = array(
			'abs'=>$this->dirPath.implode(DIRECTORY_SEPARATOR, array('images', '')),
			'uri'=>$this->dirURL."images/"
		);
	}

	// build and return Skype Call link
	public function skype($attributes = null){
		$_alt = "This link will try to initiate a %s on Skype with %s, whose status is currently %s.";
		// dump if debug is enabled
		$this->debug(array('attributes'=>$attributes, 'filtered'=>$this->filter_atts($this->_attr, $attributes)));
		// get parameters
		extract($this->filter_atts($this->_attr, $attributes));
		// check if a group of users
		if(count(explode(';', $username)) > 1){
			$_alt = "This link will try to initiate a group %s on Skype with %s. It is not guaranteed that any of the participants will be online.";
			$_icon = 'group';
		} else {
			// get status of the initial username
			$_icon = $this->_rules[$this->get_skype_status($username)];
			// if initial user isn't 'online' and backups are provided, check them
			// defaults to user with lowest weighted status (see $_prio in conf.php)
			if($backups !== "false" && $_icon !== 'online'){
				// dump if debug is enabled
				$this->debug($backups);
				$backups = $this->array_trim(explode(',', $backups));
				$_back = array();
				// loop through all backup accounts getting their status
				foreach ($backups as $key => $value) {
					$icon = $this->_rules[$this->get_skype_status($value)];
					$_back[] = array($value, $icon);
				}
				// get backup account with lowest weighted status
				$_back = $this->check_status_priority($_back);
				if($_back){
					$username = $_back[0];
					$_icon = $_back[1];
				}
			}
		}
		// dump if debug is enabled
		$this->debug(array('username'=>$username, 'status'=>$_icon));
		// check size of icon
		$size = ((!file_exists($this->imgPath['abs'].$_icon.".".$size.'.png')) ? $this->_attr['size'] : $size);
		// get variable classes for elements
		$_class_user = str_replace('.', '', strip_tags(trim($username)));
		$_class_size = "size_".$size;
		// get type of link to build
		$type = ((strtolower($type) === 'call' || strtolower($type) === 'chat') ? strtolower($type) : ((strtolower($type) === 'video') ? 'call&video=true' : 'call'));
		// complete link alt text
		$_alt = (($type === 'call&video=true') ? $this->str_replace_first('%s', 'video call', $_alt) : $this->str_replace_first('%s', $type, $_alt));
		$_alt = ((count(explode(';', $username)) > 1) ? $this->str_replace_first('%s', implode(', ', explode(';', $username)), $_alt) : $this->str_replace_first('%s', $username, $_alt));
		if($_icon !== 'group'){
			$_alt = $this->str_replace_first('%s', $_icon, $_alt);
		}
		// build image URL
		$_icon = $this->imgPath['uri'].$_icon.".".$size.".png";
		// return the HTML of the link
		return "<div class='skype $type $_class_user $_class_size'><a class='skype_link $_class_user $_class_size' href='skype:$username?$type' alt='$_alt' title='$_alt'><span class='skype_icon $_class_user $_class_size'><img src='$_icon' width='$size' height='$size' /></span><span class='skype_name $_class_user $_class_size'>$name</span></a></div>";
	}

	// make a cURL request to the API
	private function get_skype_status($username){
		/*
		 * Available Languages:
		 * - en
		 * - fr
		 * - de
		 * - ja
		 * - zh-cn
		 * - zh-tw
		 * - pt
		 * - pt-br
		 * - it
		 * - es
		 * - pl
		 * - se
		 *
		 * see the following URL for examples:
		 * http://mystatus.skype.com/echo123.xml
		 */

		// make a cURL connection to the API
		$url = "http://mystatus.skype.com/".$username.".xml";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		// return data rather than display it
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		curl_close($curl);

		// check data for status code and return
		$pattern = '/xml:lang="NUM">(.*)</';
		preg_match($pattern, $data, $match);
		// dump if debug is enabled
		$this->debug(array('data'=>$data, 'matches'=>$match));
		return $match[1];
	}

	// prioritise backup accounts, return account with lowest weighted status
	private function check_status_priority($users){
		// only work on arrays
		if(!is_array($users)){ return false; }
		// dump if debug is enabled
		$this->debug($users);
		// loop through all backup users
		foreach ($users as $key => $value) {
			// get status weight
			$priority = array_search($value[1], $this->_prio);
			if($priority === 0 || $priority === 1){
				// returns first backup account to be set to 'skypeme' or 'online'
				return array($value[0], $value[1]);
			}
			array_push($users[$key], $priority);
		}
		// otherwise, sort by status weight and return the lowest
		usort($users, array($this, 'sort_priority'));
		// dump if debug is enabled
		$this->debug($users);
		return array($users[0][0], $users[0][1]);
	}

	// make settings table and insert defaults if it doesn't exist
	private function create_wp_settings(){
		global $wpdb;
		// check for table
		$sql = "SHOW TABLES LIKE 'skype_settings';";
		$wpdb->query($sql);
		if($wpdb->num_rows > 0){
			// include conf.php to get defaults
			include $this->dirPath.implode(DIRECTORY_SEPARATOR, array('_admin', 'conf.php'));
			// make the table
			$sql = $wpdb->prepare("CREATE TABLE IF NOT EXISTS `skype_settings` ("
				." `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,"
				." `setting_name` VARCHAR(10) NOT NULL,"
				." `setting_format` VARCHAR(10) NOT NULL DEFAULT 'json',"
				." `setting_value` TEXT NOT NULL,"
				." PRIMARY KEY (`id`),"
				." UNIQUE INDEX `name` (`setting_name`)"
				." ) ENGINE=InnoDB;");
			$result = $wpdb->query($sql);
			// insert the settings
			$value = json_encode($_defaults);
			$sql = "INSERT IGNORE INTO `skype_settings` (`setting_name`, `setting_format`, `setting_value`) VALUES ('_attr', 'json', '".$value."');";
			$result = $wpdb->query($sql);
			$value = json_encode($_rules);
			$sql = "INSERT IGNORE INTO `skype_settings` (`setting_name`, `setting_format`, `setting_value`) VALUES ('_rules', 'json', '".$value."');";
			$result = $wpdb->query($sql);
			$value = json_encode($_prio);
			$sql = "INSERT IGNORE INTO `skype_settings` (`setting_name`, `setting_format`, `setting_value`) VALUES ('_prio', 'json', '".$value."');";
			$result = $wpdb->query($sql);
			$value = null;
			$sql = null;
			$result = null;
		}
	}

	// get the settings from the database
	private function get_wp_settings(){
		global $wpdb;
		// make sure the settings table exists
		$this->create_wp_settings();
		// pull all settings
		$sql = "SELECT `setting_name`, `setting_format`, `setting_value` FROM `skype_settings`;";
		$results = $wpdb->get_results($sql);
		if($results){
			// parse results adding to Class object
			foreach ($results as $row) {
				$this->{$row->setting_name} = $this->parse_wp_settings($row);
				$this->debug(array('raw'=>$row->setting_value, 'parsed'=>$this->{$row->setting_name}));
			}
			// return true, because settings were found
			$sql = null;
			$results = null;
			return true;
		}
		// return false because for some reason the settings were empty
		$sql = null;
		$results = null;
		return false;
	}

	// parse the settings depending on their type
	private function parse_wp_settings($row = null){
		if(!is_null($row)){
			// switch on the row format
			switch ($row->setting_format) {
				case 'json':
					// return an object if the setting is JSON
					return json_decode($row->setting_value);
					break;
				case 'int':
					// cast to integer
					return (int)$row->setting_value;
				case 'string':
				case 'float':
				case 'decimal':
				default:
					// return as extracted
					return $row->setting_value;
					break;
			}
		}
		// return false if no row given
		return false;
	}

	// update settings - currently doesn't allow adding new settings
	public function set_wp_settings($opts = null){
		global $wpdb;
		// check an array was passed
		if(is_array($opts)){
			// make sure the table exists with data
			$this->create_wp_settings();
			// get names of all current settings
			$sql = "SELECT `setting_name` FROM `skype_settings`;";
			$results = $wpdb->get_results($sql);
			if($results){
				// new array for return values
				$res = array();
				foreach ($results as $row) {
					// loop setting names, check if exists in keys of given array
					if(in_array($row->setting_name, array_keys($opts))){
						// add result of the update query to the return array
						array_push($res, $wpdb->update("skype_settings", array("setting_value"=>json_encode($opts[$row->setting_name])), array("setting_name"=>$row->setting_name)));
					}
				}
				// return the array of results
				return $res;
			}
		}
		// return false if parameter wasn't an array or no settings found
		return false;
	}

	// filter out non-standard arguments and use default values where necessary
	private function filter_atts($pairs, $atts){
		if($this->isWP){
			// if WP plugin, use WP function
			return shortcode_atts($pairs, $atts);
		} else {
			// if not WP plugin, filter in the same way
			$atts = (array)$atts;
			$out = array();
			// loop through default parameters so any others are ignored
			foreach($pairs as $name => $default) {
				// use value if given, otherwise default
				if(array_key_exists($name, $atts)){
					$out[$name] = $atts[$name];
				} else {
					$out[$name] = $default;
				}
			}
			// return resulting array
			return $out;
		}
	}

	// use trim() on all members of an array
	private function array_trim($array){
		// ensure value is an array
		$array = (is_array($array) ? $array : array($array));
		// loop array
		foreach ($array as $key => $value) {
			// trim every value
			$array[$key] = trim($value);
		}
		// dump if debug is enabled
		$this->debug($array);
		// return the result
		return $array;
	}

	// function for usort() to organise array by subarray members
	private function sort_priority($a, $b) {
		return strcmp($a[2], $b[2]);
	}

	// only replace the first occurrence of $search rather than all of them
	// taken from here: http://stackoverflow.com/a/2606638/1734964
	private function str_replace_first($search, $replace, $subject) {
		$pos = strpos($subject, $search);
		if ($pos !== false) {
			$subject = substr_replace($subject, $replace, $pos, strlen($search));
		}
		return $subject;
	}

	// enable debugging
	public function set_debug($state = false, $return = false, $json = false){
		// ensure params are only either true or false
		$state = ($state === false ? false : true);
		$return = ($return === false ? false : true);
		$json = ($json === false ? false : true);
		// set class debug state
		$this->_debug = array($state, $return, $json);
		// output the stuff
		if($state){
			$stuff = array(
				'isWP'=>$this->isWP,
				'path'=>$this->dirPath,
				'url'=>$this->dirURL,
				'images'=>$this->imgPath,
				'defaults'=>$this->_attr,
				'rules'=>$this->_rules,
				'weighting'=>$this->_prio
			);
			if($return){
				// return array or JSON object
				return ($json) ? json_encode($stuff) : $stuff;
			} else {
				// echo the array
				$this->debug($stuff);
			}
		}
	}

	// return current settings, immediately turning debugging off again
	public function get_current_settings(){
		$_set = $this->set_debug(true, true);
		$this->set_debug(false);
		return $_set;
	}

	// output debug data from class functions
	private function debug($data){
		if($this->_debug[0]){
			echo "<pre>" . (($this->_debug[2]) ? print_r(json_encode($data), true) : print_r($data, true)) . "</pre>";
		}
	}
}

?>