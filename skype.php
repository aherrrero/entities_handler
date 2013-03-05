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
		$this->_debug = false;
		// check if being used as a WordPress plugin
		$this->isWP = defined("WP_PLUGIN_URL");
		// find absolute path to this file
		$this->dirPath = dirname(__FILE__);
		if($this->isWP){
			// if WP plugin, use the WP_PLUGIN_URL constant for accuracy
			$this->dirURL = WP_PLUGIN_URL . '/WPSkypeStatus/';
		} else {
			// otherwise use $_SERVER values and remove the filename
			$_path = str_replace($_SERVER['DOCUMENT_ROOT'], "", $_SERVER['SCRIPT_FILENAME']);
			$_path = explode('/', $_path);
			array_pop($_path);
			$this->dirURL = '/'.implode('/', $_path);
		}
		// include conf file for default arrays
		include $this->dirPath.implode(DIRECTORY_SEPARATOR, array('', 'conf.php'));
		$this->_attr = $_defaults;
		$this->_rules = $_rules;
		$this->_prio = $_prio;
		// use $dirPath and $dirURL to get the paths for the images folder
		$this->imgPath = array(
			'abs'=>$this->dirPath.implode(DIRECTORY_SEPARATOR, array('', 'images', '')),
			'uri'=>$this->dirURL."/images/"
		);
		// if WordPress plugin, register the shortcode
		if($this->isWP){
			add_shortcode('skype', array($this, 'skype'));
		}
	}

	// build and return Skype Call link
	public function skype($attributes = null){
		// get parameters
		extract($this->filter_atts($this->_attr, $attributes));
		// get status of the initial username
		$_icon = $this->_rules[$this->get_skype_status($username)];
		// if initial user isn't 'online' and backups are provided, check them
		// defaults to user with lowest weighted status (see $_prio in conf.php)
		if($backups && $_icon !== 'online'){
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
		// check size of icon
		$size = ((!file_exists($this->imgPath['abs'].$_icon.".".$size.'.png')) ? '16' : $size);
		// build image URL
		$_icon = $this->imgPath['uri'].$_icon.".".$size.".png";
		// get user-specific class for elements
		$_class_user = str_replace('.', '', strip_tags(trim($username)))."_".$size;
		// return the HTML of the link
		return "<div class='skype $_class_user'><a class='skype_link $_class_user' href='skype:$username?call'><span class='skype_icon $_class_user'><img src='$_icon' width='$size' height='$size' /></span><span class='skype_name $_class_user'>$name</span></a></div>";
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
		return $match[1];
	}

	// prioritise backup accounts, return account with lowest weighted status
	private function check_status_priority($users){
		// only work on arrays
		if(!is_array($users)){ return false; }
		// loop through all backup users
		foreach ($users as $key => $value) {
			// get status weight
			$priority = array_search($value[1], $this->_prio);
			// dump if debug is enabled
			if($this->_debug){ var_dump($priority); }
			if($priority === 0 || $priority === 1){
				// returns first backup account to be set to 'skypeme' or 'online'
				return array($value[0], $value[1]);
			}
			array_push($users[$key], $priority);
		}
		// otherwise, sort by status weight and return the lowest
		usort($users, array($this, 'sort_priority'));
		// dump if debug is enabled
		if($this->_debug){ var_dump($users); }
		return array($users[0][0], $users[0][1]);
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
		// return the result
		return $array;
	}

	// function for usort() to organise array by subarray members
	private function sort_priority($a, $b) {
		return strcmp($a[2], $b[2]);
	}

	// enable debugging
	public function debug(){
		$this->_debug = true;
		// return print_r($this, true);
	}
}

?>