<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Member extends MY_Controller {
	public function __construct() {
		parent::__construct();

		// default
		$this->otd_view->set_frame('admin');
	}

	public function index() {
		// load libraries
		$this->load->library('pagination');
		$this->load->helper('form');
				
		$where_clause = 'status = \'Y\'';
		$search_value = $this->input->get('sv');
		if ($search_value) {
			$where_clause .= ' AND name LIKE \'%' . $search_value . '%\'';
		}
		$periods = $this->input->get('periods');
		if ($periods > 0) {
			$where_clause .= 'AND period = \'' . $periods . '\'';	
		}

		// pagination
		$page = ifempty($this->input->get('p'), 1);
		$config = $this->config->item('pagination_default');
		$config['query_string_segment'] = 'p';
		$config['base_url'] = '/admin/member?';
		if ($search_value) {
			$config['base_url'] .= '&sv='.$search_value;
		}
		$config['total_rows'] = $this->User_model->count_records($where_clause);
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		$this->otd_view->set_value('pagination', $this->pagination->create_links());

		// total users
		$users = $this->User_model->find($where_clause, 'id DESC', (($page - 1) * $config['per_page']), $config['per_page']);
		$this->otd_view->set_value('users', $users);
		
		// search values
		$period_list = array();
		//foreach($this->User_model->query('SELECT DISTINCT period FROM users WHERE status=\'Y\'') as $period) {
		$period_list[0] = '전체';
		foreach($this->User_model->columns('DISTINCT period')->find(array('status' => 'Y'), 'period ASC') as $period) {
			$period_list[$period->period] = $period->period;
		}
		$this->otd_view->set_value('period_list', $period_list);
		$this->otd_view->set_value('periods', $periods);
		$this->otd_view->set_value('sv', $search_value);

		// render
		$this->otd_view->set_partial('content', 'admin/member/index');
		$this->otd_view->render();
	}

	public function edit($id) {
		if(!is_numeric($id)) show_error('수정할 사용자의 id가 입력되지 않았습니다. <a href="#" onclick="history.back(); return false;">Back</a>', 404, '비정상 Request');
		if(!$this->User_model->is_exists(array('id' => $id))) show_error('수정할 사용자가 존재하지 않습니다. <a href="#" onclick="history.back(); return false;">Back</a>', 404, '비정상 Request');
		
		if($this->input->post()) {
			$users = $this->input->post('users');
			if(!error_exists()) {
				$this->db->trans_start();
					$this->User_model->update($users, array('id' => $id));
				$this->db->trans_complete();
				
				if($this->db->trans_status()) {
					set_error('success', '정상적으로 수정하였습니다.');
					redirect('/admin/member');
				} else {
					set_error('error', '데이터베이스 오류로 사용자 수정에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
					redirect('/admin/member');
				}
			}
		}
		
		// load libraries
		$this->load->helper('form');
		
		$users = $this->User_model->find_one(array('id' => $id));
		$this->otd_view->set_value(array(
			'id' => $id,
			'users' => $users
		));

		// render
		$this->otd_view->set_partial('content', 'admin/member/edit');
		$this->otd_view->render();
	}
	
	public function delete($id) {
		if(!is_numeric($id)) show_error('삭제할 사용자의 id가 입력되지 않았습니다. <a href="#" onclick="history.back(); return false;">Back</a>', 404, '비정상 Request');
		if(!$this->User_model->is_exists(array('id' => $id))) show_error('삭제할 사용자가 존재하지 않습니다. <a href="#" onclick="history.back(); return false;">Back</a>', 404, '비정상 Request');
		
		if($this->User_model->is_exists(array('status' => 'N', 'id' => $id))) {
			$result = array('result' => false, 'message' => '이미 삭제된 회원입니다.');
		} else {
			$this->db->trans_start();
				$user_id = $this->User_model->update(array('status' => 'N'), array('id' => $id));
			$this->db->trans_complete();
			
			if($this->db->trans_status()) {
				$result = array('result' => true, 'message' => '정상적으로 삭제하였습니다.');
			} else {
				$result = array('result' => false, 'message' => '데이터베이스 오류로 사용자 추가에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		}
		
		// render
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));
	}
}
/* EOF */