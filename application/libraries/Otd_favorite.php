<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_favorite {
	public $CI;
	
	public function __construct() {
		$this->CI =& get_instance();
		
		// load libraries
		$this->CI->load->model('Favorite_model');
	}
	
	public function set_favorite($owner_id, $target_id) {
		if(!$this->CI->Favorite_model->is_exists(array('owner_id' => $owner_id, 'target_id' => $target_id))) {
			return $this->CI->Favorite_model->create(array('owner_id' => $owner_id, 'target_id' => $target_id));
		}
		
		return true;
	}
	
	public function get_my_fans($target_id) {
		return $this->CI->Favorite_model->find(array('target_id' => $target_id));
	}
	
	public function get_my_favorites($owner_id) {
		return $this->CI->Favorite_model->find(array('owner_id' => $owner_id));
	}
}
/* EOF */