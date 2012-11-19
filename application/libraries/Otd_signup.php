<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_signup {
	public $CI;
	public $data;
	
	public function __construct() {
		$this->CI =& get_instance();
		
		$this->namespace = $this->CI->otd_user->get('namespace');
		$this->data = ifempty($this->CI->otd_user->get('signup/' . $this->namespace), array());
	}
	
	public function set_namespace($namespace) {
		$this->CI->otd_user->set('namespace', $namespace);
	}
	
	public function set_data($data) {
		if(!$this->namespace
			||!is_array($data)) return false;
		
		foreach($data as $key => $entries) {
			if(is_array($entries)) {
				foreach($entries as $sub_key => $sub_entry) {
					$this->data[$key][$sub_key] = $sub_entry;
				}
			} else {
				$this->data[$key] = $entries;
			}
		}
				
		$this->CI->otd_user->set('signup/' . $this->namespace, $this->data);
	}
	
	public function destroy() {
		$this->CI->otd_user->delete('signup/' . $this->namespace);
	}
}
/* EOF */