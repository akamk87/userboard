<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Api extends MY_Controller {
	public function __construct() {
		parent::__construct();
		
		// is ajax request?
		if(!$this->input->is_ajax_request()) show_error('잘못된 접근입니다.', 404, '비정상 Request');
	}
	
	public function index() {
		
	}
	
	public function user() {
		$id = $this->input->get('id');
		
		if($id) {
			$result = array(
				'result' => true,
				'data' => $this->User_model->find_one(array('id' => $id))
			);
		} else {
			$result = array(
				'result' => true,
				'data' => array()
			);
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));
	}
	
	public function financial($id = null) {		
		// load libraries
		$this->load->model('Financial_model');
		$this->load->model('Type_code_model');
		$this->load->model('Attachment_model');
		$this->load->helper('number');
		
		if($financial = $this->Financial_model->with('users', 'user_id')->columns('financials.*, users.name, users.serial_number, users.type_code_id')->find_one(array('financials.id' => $id))) {
			$financial->user_type_code_name = $this->Type_code_model->find_one(array('id' => $financial->type_code_id))->name;
			$financial->attachments = $this->Attachment_model->find(array('reference' => 'financials', 'reference_id' => $financial->id), 'id DESC');
		}
		
		$this->otd_view->set_value('financial', $financial);
		
		// render
		$this->otd_view->set_frame('empty');
		$this->otd_view->set_partial('content', 'admin/api/financial');
		$this->otd_view->render();
	}
	
	public function investors() {
		if($name = $this->input->get('name')) {
			if($name) {
				$result = array(
					'result' => true,
					'data' => $this->User_model->with('type_codes', 'type_code_id')->columns('users.id, users.name, users.serial_number')->find('users.name LIKE \'%' . $name . '%\' AND users.status = \'Y\' AND type_codes.reference = \'users\' AND type_codes.key IN (\'angel_investor\', \'corporative_investor\')', 'users.name ASC')
				);
			} else {
				$result = array(
					'result' => true,
					'data' => array()
				);
			}
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($result));
		} else {		
			// load libraries
			$this->load->helper('form');
			
			// render
			$this->otd_view->set_frame('empty');
			$this->otd_view->set_partial('content', 'admin/api/search_investor');
			$this->otd_view->render();
		}
	}
	
	public function companies() {
		if($name = $this->input->get('name')) {
			if($name) {
				$result = array(
					'result' => true,
					'data' => $this->User_model->with('type_codes', 'type_code_id')->columns('users.id, users.name, users.serial_number')->find('users.name LIKE \'%' . $name . '%\' AND users.status = \'Y\' AND type_codes.reference = \'users\' AND type_codes.key IN (\'preparatory\', \'startup\')', 'users.name ASC')
				);
			} else {
				$result = array(
					'result' => true,
					'data' => array()
				);
			}
			
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($result));
		} else {		
			// load libraries
			$this->load->helper('form');
			
			// render
			$this->otd_view->set_frame('empty');
			$this->otd_view->set_partial('content', 'admin/api/search_company');
			$this->otd_view->render();
		}
	}
}
/* EOF */