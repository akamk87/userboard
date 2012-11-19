<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_statistic {
	public $CI;
	
	public function __construct() {
		$this->CI =& get_instance();
		
		// load libraries
		$this->CI->load->model('Statistic_model');
		$this->CI->load->model('Statistic_group_model');
	}
	
	public function get_statistic($key, $date) {
		$parent_group = $this->CI->Statistic_group_model->find_one(array('parent_id' => 0, 'key' => $key));
		if(!$parent_group) return false;
			
		$data = (object) null;
		$data->is_empty = true;
		foreach($this->CI->Statistic_model->with('statistic_groups', 'statistic_group_id')->columns('statistics.*, statistic_groups.name, statistic_groups.key')->find(array_merge($date, array('statistic_groups.parent_id' => $parent_group->id)), 'statistic_groups.id DESC') as $statistic) {
			
			$data->{$statistic->key} = $statistic;
			$data->is_empty = false;
		}
		
		return $data;
	}
	
	public function set_statistic($key, $data) {
		$statistic_group = $this->CI->Statistic_group_model->find_one(array('key' => $key));
		if(!$statistic_group
			|| $statistic_group->parent_id == 0) return false;
		
		return $this->CI->Statistic_group_model->create(array_merge($data, array('statistic_group_id' => $statistic_group->id)));
	}
}
/* EOF */