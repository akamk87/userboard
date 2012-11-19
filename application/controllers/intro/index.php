<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Index extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		if($this->input->post()) {
			$user_id = $this->input->post('id');
			$user_pw = $this->input->post('pw');
			
			if($user_id == 'admin' && $user_pw == '1234') {
				redirect('/admin/member', 'location');
			} else {
				show_error('로그인에 실패했습니다. <a href="/">Login</a>', 404, '로그인 실패');
			}
		}
		
		// load libraries
		$this->load->helper('form');
				
		// render
		$this->otd_view->set_frame('intro');
		$this->otd_view->set_partial('content', 'intro/index');
		$this->otd_view->render();
	}
}
/* EOF */
