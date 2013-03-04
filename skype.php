<?php

class WPSkypeStatus{
	protected $dirPath;
	protected $dirURL;
	protected $imgPath;

	public function __construct(){
		$this->dirPath = dirname(__FILE__);
		if(defined("WP_PLUGIN_URL")){
			$this->dirURL = WP_PLUGIN_URL . '/WPSkypeStatus/';
		} else {
			$_path = str_replace($_SERVER['DOCUMENT_ROOT'], "", $_SERVER['SCRIPT_FILENAME']);
			$_path = explode('/', $_path);
			array_pop($_path);
			$this->dirURL = '/'.implode('/', $_path);
		}
		if(function_exists("get_template_directory")){
			$this->imgPath = array(
				'abs'=>$this->dirPath.implode(DIRECTORY_SEPARATOR, array('', 'images', '')),
				'uri'=>$this->dirURL."/images/"
			);
		} else {
			$this->imgPath = array(
				'abs'=>$this->dirPath.implode(DIRECTORY_SEPARATOR, array('', 'images', '')),
				'uri'=>$this->dirURL."/images/"
			);
		}
		if(function_exists("add_shortcode")){
			add_shortcode('skype', array($this, 'skype'));
		}
	}

	public function skype($attributes){
		$defaults = array(
			'name'=>'Echo',
			'username'=>'echo123',
			'size'=>'16'
		);
		extract($this->filter_atts($defaults, $attributes));
		include $this->dirPath.implode(DIRECTORY_SEPARATOR, array('', 'conf.php'));
		$_icon = $_rules[self::get_skype_status($username)];
		$size = ((!file_exists($this->imgPath['abs'].$_icon.".".$size.'.png')) ? '16' : $size);
		$_icon = $this->imgPath['uri'].$_icon.".".$size.".png";
		$_class_user = str_replace('.', '', strip_tags(trim($username)))."_".$size;
		return "<div class='skype $_class_user'><a class='skype_link $_class_user' href='skype:$username?call'><span class='skype_icon $_class_user'><img src='$_icon' width='$size' height='$size' /></span><span class='skype_name $_class_user'>$name</span></a></div>";
	}

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
		
		$url = "http://mystatus.skype.com/".$username.".xml";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		curl_close($curl);
		
		$pattern = '/xml:lang="NUM">(.*)</';
		preg_match($pattern, $data, $match);
		return $match[1];
	}

	private function filter_atts($pairs, $atts){
		if(function_exists("shortcode_atts")){
			return shortcode_atts($pairs, $atts);
		} else {
			$atts = (array)$atts;
			$out = array();
			foreach($pairs as $name => $default) {
				if(array_key_exists($name, $atts)){
					$out[$name] = $atts[$name];
				} else {
					$out[$name] = $default;
				}
			}
			return $out;
		}
	}

	/*public function debug(){
		echo "<pre>" . print_r($this, true) . "</pre>";
	}*/
}

?>