<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_milestone {
	public $CI;
	
	public function __construct() {
		$this->CI =& get_instance();
		
		// load libraries
		$this->CI->load->model('Milestone_model');
		$this->CI->load->model('Milestone_request_model');
	}
	
	public function get_milestone_requests($user_id) {
		return $this->CI->Milestone_request_model->with('milestones', 'milestone_id')->columns('milestone_requests.*, milestones.position AS milestone_position, milestones.title AS milestone_title')->find(array('milestones.user_id' => $user_id), 'milestone_requests.id DESC');
	}
	
	public function get_latest_confirmed_milestone($user_id) {
		return $this->CI->Milestone_model->with('type_codes', 'type_code_id')->columns('milestones.*, type_codes.key')->find_one(array('milestones.user_id' => $user_id, 'state' => 'CONFIRMED'), 'milestones.position DESC');
	}
	
	public function get_unconfirmed_milestones($user_id) {
		return $this->CI->Milestone_model->with('type_codes', 'type_code_id')->columns('milestones.*, type_codes.key')->find(array('milestones.user_id' => $user_id, 'state' => 'SUBMITTED'), 'milestones.position ASC');
	}
	
	public function get_confirmed_milestones($user_id) {
		return $this->CI->Milestone_model->with('type_codes', 'type_code_id')->columns('milestones.*, type_codes.key')->find(array('milestones.user_id' => $user_id, 'state' => 'CONFIRMED'), 'milestones.position ASC');
	}
	
	public function get_milestone($milestone_id) {
		return $this->CI->Milestone_model->with('type_codes', 'type_code_id')->columns('milestones.*, type_codes.key')->find_one(array('milestones.id' => $milestone_id));
	}
	
	public function get_milestones($user_id) {
		return $this->CI->Milestone_model->with('type_codes', 'type_code_id')->columns('milestones.*, type_codes.key')->find(array('milestones.user_id' => $user_id), 'milestones.position ASC');
	}
	
	public function set_milestones($data) {
		return $this->CI->Milestone_model->create($data);
	}
	
	public function update_milestone($milestone_id, $data) {
		$position = null;
		
		if(isset($data['position'])
			&& !empty($data['position'])) {
			$result = $this->CI->Milestone_model->find_one(array('id' => $milestone_id));
			if($result->position != $data['position']) {
				$position = $data['position'];
				unset($data['position']);
			}
		}
		
		$this->CI->db->trans_start();
			$this->CI->Milestone_model->update($data, array('id' => $milestone_id));
			if(!is_null($position)) {
				$this->CI->Milestone_model->change_position($milestone_id, $position);
			}
		$this->CI->db->trans_complete();
		
		return $this->CI->db->trans_status();
	}
	
	public function request_confirming($milestone_id) {
		/**
		 * state 필드는 다음의 3가지 상태로 구분된다.
		 * 
		 * 'SUBMITTED' => 막 사용자가 마일스톤을 작성한 상태
		 * 'CONFIRM_REQUESTED' => 사용자가 마일스톤 달성에 대한 확인을 요청한 상태
		 * 'REJECTED' => 확인 거절 상태
		 * 'CONFIRMED' => 마일스톤 달성이 확인된 상태 
		 */
		return $this->update_milestone($milestone_id, array('state' => 'CONFIRM_REQUESTED'));
	}
	
	public function reject_milestone($milestone_id) {
		return $this->update_milestone($milestone_id, array('state' => 'REJECTED'));
	}
	
	public function confirm_milestone($milestone_id) {
		return $this->update_milestone($milestone_id, array('state' => 'CONFIRMED'));
	}
	
}
/* EOF */