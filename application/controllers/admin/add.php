<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Add extends MY_Controller {
	public function __construct() {
		parent::__construct();

		// default
		$this->otd_view->set_frame('admin');
	}
	
	public function index() {
		if($this->input->post()) {
			$users = $this->input->post('users');
			$users['status'] = 'Y';
			
			if(!error_exists()) {
				$this->db->trans_start();
					$user_id = $this->User_model->create($users);
				$this->db->trans_complete();
				
				if($this->db->trans_status()) {
					set_error('success', '정상적으로 추가하였습니다.');
					redirect('/admin/member');
				} else {
					set_error('error', '데이터베이스 오류로 사용자 추가에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
				}
			}
		}

		// load libraries
		$this->load->helper('form');
		
		// render
		$this->otd_view->set_partial('content', 'admin/member/add');
		$this->otd_view->render();
	}
}
/* EOF */