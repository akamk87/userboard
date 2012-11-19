<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Home extends MY_Controller {
	public function __construct() {
		parent::__construct();
		
		// load libraries
		$this->load->model('Type_code_model');
		$this->load->model('Milestone_model');
		$this->load->model('Funding_model');
		//$this->load->model('Buying_model');
		$this->load->model('Timeline_model');
		$this->load->model('Attachment_model');
		$this->load->model('Recommend_model');
		
		// 오른쪽 사이드 영역 공통 부분 값 처리
		
		// 현재 펀딩 진행중; Stage / 회사명 / 투자요청금액 / 기간
		$now_fundings = array();
		foreach ($this->Funding_model->with('type_codes', 'type_code_id')->columns('type_codes.key AS stage, fundings.user_id AS user_id, fundings.funding_amount, fundings.end_at')->find(array('fundings.state' => 'CONFIRMED', 'type_codes.reference' => 'fundings'), 'fundings.id DESC', 0, 5) as $now_funding) {
			$now_funding->user = $this->User_model->with('type_codes', 'type_code_id')->columns('users.*, type_codes.key AS type_code_key')->find_one(array('users.id' => $now_funding->user_id, 'users.status' => 'Y'));
			$now_funding->profile = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $now_funding->user->id, 'status' => 'Y', 'is_image' => 'Y'));
			$now_fundings[] = $now_funding;
		}
		$this->otd_view->set_value('now_fundings', $now_fundings);
		
		// 최근 마일스톤 달성; 프로필 / 회사명 / Milestone
		$complete_milestones = array();
		foreach ($this->Milestone_model->with('users', 'user_id')->columns('users.*, milestones.position, milestones.title, milestones.content')->find(array('milestones.state' => 'CONFIRMED'), 'milestones.updated_at DESC', 0, 5) as $complete_milestone) {
			$complete_milestone->type_code_key = $this->Type_code_model->find_one(array('id' => $complete_milestone->type_code_id))->key;
			$complete_milestone->profile = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $complete_milestone->id, 'status' => 'Y', 'is_image' => 'Y'));
			$complete_milestones[] = $complete_milestone;
		}
		$this->otd_view->set_value('complete_milestones', $complete_milestones);
		
		// 성공창업가이드
		// TODO 벤처스퀘어 서버 다운시 타임아웃 처리
		$this->load->library('RSSParser', array('url' => 'http://venturesquare.net/rss/category/Startup%20Guide', 'life' => 2));
		$feed_data = $this->rssparser->getFeed(6);
		$this->otd_view->set_value('startup_guides', $feed_data);
		
		// set view frame
		$this->otd_view->set_frame('home');
		$this->otd_view->set_partial('header', 'modules/gnb');
		$this->otd_view->set_partial('footer', 'modules/footer');
		$this->otd_view->set_partial('side', 'list/side_list');
	}
	
	public function index() {
		/* 최근 타임라인
		 * 스타트업 및 예비창업자, 엔젤 및 법인투자자 (이름, 분류, 소개문구)
		 */
		$recent_startups = array();
		foreach($this->User_model->with('type_codes', 'type_code_id')->columns('users.*, type_codes.key AS type_code_key, type_codes.name AS type_code_name')->find('users.status = \'Y\' AND type_codes.reference = \'users\' AND type_codes.key IN (\'preparatory\', \'startup\')', 'users.updated_at DESC', 0, 5) as $company) {
			$company->profile = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $company->id, 'status' => 'Y', 'is_image' => 'Y'));
			$recent_startups[] = $company;
		}
		$recent_investors = array();
		foreach($this->User_model->with('type_codes', 'type_code_id')->columns('users.*, type_codes.key AS type_code_key, type_codes.name AS type_code_name')->find('users.status = \'Y\' AND type_codes.reference = \'users\' AND type_codes.key IN (\'angel_investor\', \'corporative_investor\')', 'users.updated_at DESC', 0, 5) as $company) {
			$company->profile = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $company->id, 'status' => 'Y', 'is_image' => 'Y'));
			$recent_investors[] = $company;
		}
		$this->otd_view->set_value(array(
			'recent_startups' => $recent_startups,
			'recent_investors' => $recent_investors
		));
		// render
		$this->otd_view->set_partial('list', 'list/home_list');
		$this->otd_view->render();
	}
	
	public function search() {
		// load libararies
		$this->load->helper('form');
		$this->load->library('pagination');
		
		$where_clause = 'users.type_code_id IN (1, 2, 3, 4) AND users.status=\'Y\'';
		$search_value = $this->input->get('sv');
		if ($search_value) {
			$where_clause .= ' AND (users.name LIKE \'%' . $search_value . '%\' OR users.serial_number LIKE \'%' . $search_value . '%\')';
		}
		
		// pagination
		$page = ifempty($this->input->get('p'), 1);	
		$config = $this->config->item('pagination_default');
		$config['query_string_segment'] = 'p';
		$config['base_url'] = '/home/search?';
		if ($search_value) {
			$config['base_url'] .= 'sv='.$search_value;
		}
		$config['total_rows'] = $this->User_model->count_records($where_clause);
		$config['per_page'] = 10;
		$this->pagination->initialize($config);
		$this->otd_view->set_value('pagination', $this->pagination->create_links());
		
		// set value
		$search_lists = array();
		foreach($this->User_model->find($where_clause, 'id DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $search_list) {
			$search_list->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $search_list->id));
			$search_list->type_code = $this->Type_code_model->find_one(array('id' => $search_list->type_code_id));
			$search_list->timeline = $this->Timeline_model->find_one(array('writer_id' => $search_list->id, 'owner_id' => $search_list->id, 'privacy' => 'OPEN', 'visibility' => 'Y'), 'id DESC');
			$search_lists[] = $search_list;
		}
		$this->otd_view->set_value('search_lists', $search_lists);
		$this->otd_view->set_value('sv', $search_value);
		
		$this->otd_view->set_partial('list', 'list/search_list');
		$this->otd_view->render();
	}
	
	public function gnb($index) {
		switch ($index) {
			case 'preparatory':
				// load libraries
				$this->load->library('pagination');
				$this->load->helper('form');
				
				$where_clause = 'users.type_code_id = \'1\' AND users.status=\'Y\'';
				$search_value = $this->input->get('sv');
				if ($search_value) {
					$where_clause .= ' AND (users.name LIKE \'%' . $search_value . '%\' OR users.serial_number LIKE \'%' . $search_value . '%\')';
				}
				
				// pagination
				$page = ifempty($this->input->get('p'), 1);	
				$config = $this->config->item('pagination_default');
				$config['query_string_segment'] = 'p';
				$config['base_url'] = '/home/gnb/preparatory?';
				if ($search_value) {
					$config['base_url'] .= '&sv='.$search_value;
				}
				$config['total_rows'] = $this->User_model->count_records($where_clause);
				$config['per_page'] = 6;
				$this->pagination->initialize($config);
				$this->otd_view->set_value('pagination', $this->pagination->create_links());
				
				// recommendation
				$recommend = $this->Recommend_model->find_one(array('type' => '예비창업자'));
				$recommend_lists = array();
				for($i = 0, $max = 5; $i < $max; $i++) {
					$test = 'user'.$i;
					$recommend_list = $this->User_model->find_one(array('serial_number' => $recommend->$test, 'type_code_id' => '1'));
					if($recommend_list) {
						$recommend_list->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $recommend_list->id));
						$recommend_list->type_code = $this->Type_code_model->find_one(array('id' => $recommend_list->type_code_id));
						$recommend_lists[] = $recommend_list;
					}
				}
				$this->otd_view->set_value('recommend_lists', $recommend_lists);
				
				// set value
				$preparatories = array();
				foreach($this->User_model->find($where_clause, 'id DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $preparatory) {
					$preparatory->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $preparatory->id));
					$preparatory->type_code = $this->Type_code_model->find_one(array('id' => $preparatory->type_code_id));
					$preparatory->timeline = $this->Timeline_model->find_one(array('writer_id' => $preparatory->id, 'owner_id' => $preparatory->id, 'privacy' => 'OPEN', 'visibility' => 'Y'), 'id DESC');
					$preparatories[] = $preparatory;
				}
				
				$this->otd_view->set_value('preparatories', $preparatories);
				$this->otd_view->set_value('sv', $search_value);
				
				$this->otd_view->set_partial('list', 'list/preparatory_list');
				$this->otd_view->render();
				break;
				
			case 'startup':
				// load libraries
				$this->load->library('pagination');
				$this->load->helper('form');
				
				$where_clause = 'users.type_code_id = \'2\' AND users.status=\'Y\'';
				$search_value = $this->input->get('sv');
				if ($search_value) {
					$where_clause .= ' AND (users.name LIKE \'%' . $search_value . '%\' OR users.serial_number LIKE \'%' . $search_value . '%\')';
				}
				
				// pagination
				$page = ifempty($this->input->get('p'), 1);	
				$config = $this->config->item('pagination_default');
				$config['query_string_segment'] = 'p';
				$config['base_url'] = '/home/gnb/startup?';
				if ($search_value) {
					$config['base_url'] .= '&sv='.$search_value;
				}
				$config['total_rows'] = $this->User_model->count_records($where_clause);
				$config['per_page'] = 6;
				$this->pagination->initialize($config);
				$this->otd_view->set_value('pagination', $this->pagination->create_links());
				
				// recommendation
				$recommend = $this->Recommend_model->find_one(array('type' => '스타트업'));
				$recommend_lists = array();
				for($i = 0, $max = 5; $i < $max; $i++) {
					$test = 'user'.$i;
					$recommend_list = $this->User_model->find_one(array('serial_number' => $recommend->$test, 'type_code_id' => '2'));
					if($recommend_list) {
						$recommend_list->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $recommend_list->id));
						$recommend_list->type_code = $this->Type_code_model->find_one(array('id' => $recommend_list->type_code_id));
						$recommend_lists[] = $recommend_list;
					}
				}
				$this->otd_view->set_value('recommend_lists', $recommend_lists);
				
				// set value
				$startups = array();
				foreach($this->User_model->find($where_clause, 'id DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $startup) {
					$startup->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $startup->id));
					$startup->type_code = $this->Type_code_model->find_one(array('id' => $startup->type_code_id));
					$startup->timeline = $this->Timeline_model->find_one(array('writer_id' => $startup->id, 'owner_id' => $startup->id, 'privacy' => 'OPEN', 'visibility' => 'Y'), 'id DESC');
					$startups[] = $startup;
				}
				$this->otd_view->set_value('startups', $startups);
				$this->otd_view->set_value('sv', $search_value);
				
				$this->otd_view->set_partial('list', 'list/startup_list');
				$this->otd_view->render();
				break;
				
			case 'angel_investor':
				// load libraries
				$this->load->library('pagination');
				$this->load->helper('form');
				
				$where_clause = 'users.type_code_id = \'3\' AND users.status=\'Y\'';
				$search_value = $this->input->get('sv');
				if ($search_value) {
					$where_clause .= ' AND (users.name LIKE \'%' . $search_value . '%\' OR users.serial_number LIKE \'%' . $search_value . '%\')';
				}
				
				// pagination
				$page = ifempty($this->input->get('p'), 1);	
				$config = $this->config->item('pagination_default');
				$config['query_string_segment'] = 'p';
				$config['base_url'] = '/home/gnb/angel_investor?';
				if ($search_value) {
					$config['base_url'] .= '&sv='.$search_value;
				}
				$config['total_rows'] = $this->User_model->count_records($where_clause);
				$config['per_page'] = 6;
				$this->pagination->initialize($config);
				$this->otd_view->set_value('pagination', $this->pagination->create_links());
				
				// recommendation
				$recommend = $this->Recommend_model->find_one(array('type' => '엔젤투자자'));
				$recommend_lists = array();
				for($i = 0, $max = 5; $i < $max; $i++) {
					$test = 'user'.$i;
					$recommend_list = $this->User_model->find_one(array('serial_number' => $recommend->$test, 'type_code_id' => '3'));
					if($recommend_list) {
						$recommend_list->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $recommend_list->id));
						$recommend_list->type_code = $this->Type_code_model->find_one(array('id' => $recommend_list->type_code_id));
						$recommend_lists[] = $recommend_list;
					}
				}
				$this->otd_view->set_value('recommend_lists', $recommend_lists);
								
				// set value
				$angels = array();
				foreach($this->User_model->find($where_clause, 'id DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $angel) {
					$angel->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $angel->id));
					$angel->type_code = $this->Type_code_model->find_one(array('id' => $angel->type_code_id));
					$angel->timeline = $this->Timeline_model->find_one(array('writer_id' => $angel->id, 'owner_id' => $angel->id, 'privacy' => 'OPEN', 'visibility' => 'Y'), 'id DESC');
					$angels[] = $angel;
				}
				$this->otd_view->set_value('angels', $angels);
				$this->otd_view->set_value('sv', $search_value);
				
				$this->otd_view->set_partial('list', 'list/angel_investor_list');
				$this->otd_view->render();
				break;
							
			case 'corporative_investor':
				// load libraries
				$this->load->library('pagination');
				$this->load->helper('form');
				
				$where_clause = 'users.type_code_id = \'4\' AND users.status=\'Y\'';
				$search_value = $this->input->get('sv');
				if ($search_value) {
					$where_clause .= ' AND (users.name LIKE \'%' . $search_value . '%\' OR users.serial_number LIKE \'%' . $search_value . '%\')';
				}
				
				// pagination
				$page = ifempty($this->input->get('p'), 1);	
				$config = $this->config->item('pagination_default');
				$config['query_string_segment'] = 'p';
				$config['base_url'] = '/home/gnb/corporative_investor?';
				if ($search_value) {
					$config['base_url'] .= '&sv='.$search_value;
				}
				$config['total_rows'] = $this->User_model->count_records($where_clause);
				$config['per_page'] = 6;
				$this->pagination->initialize($config);
				$this->otd_view->set_value('pagination', $this->pagination->create_links());
				
				// recommendation
				$recommend = $this->Recommend_model->find_one(array('type' => '법인투자자'));
				$recommend_lists = array();
				for($i = 0, $max = 5; $i < $max; $i++) {
					$test = 'user'.$i;
					$recommend_list = $this->User_model->find_one(array('serial_number' => $recommend->$test, 'type_code_id' => '4'));
					if($recommend_list) {
						$recommend_list->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $recommend_list->id));
						$recommend_list->type_code = $this->Type_code_model->find_one(array('id' => $recommend_list->type_code_id));
						$recommend_lists[] = $recommend_list;
					}
				}
				$this->otd_view->set_value('recommend_lists', $recommend_lists);
				
				// set value
				$corporatives = array();
				foreach($this->User_model->find($where_clause, 'id DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $corporative) {
					$corporative->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $corporative->id));
					$corporative->type_code = $this->Type_code_model->find_one(array('id' => $corporative->type_code_id));
					$corporative->timeline = $this->Timeline_model->find_one(array('writer_id' => $corporative->id, 'owner_id' => $corporative->id, 'privacy' => 'OPEN', 'visibility' => 'Y'), 'id DESC');
					$corporatives[] = $corporative;
				}
				$this->otd_view->set_value('corporatives', $corporatives);
				$this->otd_view->set_value('sv', $search_value);
				
				$this->otd_view->set_partial('list', 'list/corporative_investor_list');
				$this->otd_view->render();
				break;
				
			case 'milestones':
                // load libraries
                $this->load->library('pagination');
                // pagination
                $page = ifempty($this->input->get('p'), 1);     
                $config = $this->config->item('pagination_default');
                $config['query_string_segment'] = 'p';
                $config['base_url'] = '/home/gnb/milestones?';
                $config['total_rows'] = $this->Milestone_model->count_records(array('state' => 'CONFIRMED'));
                $config['per_page'] = 6;
                $this->pagination->initialize($config);
                $this->otd_view->set_value('pagination', $this->pagination->create_links());
                
                // set value
                $milestones = array();
	            foreach ($this->Milestone_model->with('users', 'user_id')->columns('users.*, milestones.position, milestones.title, milestones.content, milestones.updated_at AS milestone_update')->find(array('users.status' => 'Y', 'milestones.state' => 'CONFIRMED'), 'milestones.updated_at DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $milestone) {
                	$milestone->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $milestone->id, 'status' => 'Y', 'is_image' => 'Y'));
                    $milestone->type_code = $this->Type_code_model->find_one(array('id' => $milestone->type_code_id));
                    $milestones[] = $milestone;
                }
                $this->otd_view->set_value('milestones', $milestones);
                
                $this->otd_view->set_partial('list', 'list/milestone_list');
				$this->otd_view->render();
				break;			
			case 'fundings':
				// load libraries
                $this->load->library('pagination');
				// pagination
                $page = ifempty($this->input->get('p'), 1);     
                $config = $this->config->item('pagination_default');
                $config['query_string_segment'] = 'p';
                $config['base_url'] = '/home/gnb/fundings?';
                $config['total_rows'] = $this->Funding_model->count_records('state IN (\'DONE\', \'SUCCEEDED\')');
                $config['per_page'] = 6;
                $this->pagination->initialize($config);
                $this->otd_view->set_value('pagination', $this->pagination->create_links());
				
				// set value
				$histories = array();
				foreach ($this->Funding_model->with('type_codes', 'type_code_id')->columns('type_codes.name AS stage, fundings.*')->find('fundings.state IN (\'DONE\', \'SUCCEEDED\') AND type_codes.reference = \'fundings\'', 'fundings.id DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $history) {
				//foreach ($this->Funding_model->with('users', 'user_id')->with('type_codes', 'type_code_id')->columns('type_codes.key AS stage, users.name AS user_name, fundings.*')->find('fundings.state IN (\'DONE\', \'SUCCEEDED\') AND type_codes.reference = \'fundings\'', 'fundings.id DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $history) {
					$history->user = $this->User_model->with('type_codes', 'type_code_id')->columns('users.*, type_codes.key AS type_code_key, type_codes.name AS type_code_name')->find_one(array('users.id' => $history->user_id, 'users.status' => 'Y'));
					$history->user->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $history->user_id, 'status' => 'Y', 'is_image' => 'Y'));
					$histories[] = $history;
				}
				
				$fundings = array();
				foreach ($this->Funding_model->with('type_codes', 'type_code_id')->columns('type_codes.name AS stage, fundings.*')->find('fundings.state IN (\'CONFIRMED\') AND type_codes.reference = \'fundings\'', 'fundings.id DESC') as $funding) {
					$funding->user = $this->User_model->with('type_codes', 'type_code_id')->columns('users.*, type_codes.key AS type_code_key, type_codes.name AS type_code_name')->find_one(array('users.id' => $funding->user_id, 'users.status' => 'Y'));
					$funding->user->upload = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $funding->user_id, 'status' => 'Y', 'is_image' => 'Y'));
					$fundings[] = $funding;
				}
				
				$this->otd_view->set_value(array(
					'histories' => $histories,
					'fundings' => $fundings
				));
				
				$this->otd_view->set_partial('list', 'list/funding_list');
				$this->otd_view->render();
				break;	
			default:
				show_error("no index error", 404, "gnb_error");
				break;
		}
	}
	
	public function premium_alert() {
		$this->otd_view->set_frame('empty');
		$this->otd_view->set_partial('content', 'modules/error_premium');
		$this->otd_view->render();
		return;
	}
}
