<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Mypage extends MY_Controller {
	public function __construct() {
		parent::__construct();
		
		// load libraries
		$this->load->helper('form');
		
		// default value
		$this->user_id = $this->otd_user->get('id');
		$this->type_code_key = $this->otd_user->get('type_code_key');
		$this->serial_number = $this->otd_user->get('serial_number');
		$this->stream_key = $this->type_code_key . '_' . $this->serial_number;
		
		$this->otd_view->set_partial('header', 'modules/gnb');
		$this->otd_view->set_partial('footer', 'modules/footer');
	}
	
	public function index($method_name, $id = null) {
		$method_name = $this->type_code_key . '_' . $method_name;
		if(!method_exists($this, $method_name)) show_error('비정상적인 접근입니다.', 404, '비정상 접근');

		// load libraries
		$this->load->model('Attachment_model');
		$this->load->library('otd_milestone');
		$this->load->library('otd_favorite');
		
		// user
		$user = $this->User_model->with('type_codes', 'type_code_id')->columns('users.*, type_codes.key AS type_code_key, type_codes.name AS type_code_name')->find_one(array('users.id' => $this->user_id));
		$user->attachment = $this->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $this->user_id, 'status' => 'Y', 'is_image' => 'Y'));

		// milestone
		$milestones = $this->otd_milestone->get_milestones($this->user_id);
		
		// favorites
		$favorites = array();
		if (in_array($this->type_code_key, array('preparatory', 'startup'))) {
			foreach($this->otd_favorite->get_my_fans($this->otd_user->get('id')) as $favorite) {
				$favorite->user = $this->User_model->with('type_codes', 'type_code_id')->columns('users.serial_number AS serial_number, type_codes.key AS type_code_key')->find_one(array('users.id' => $favorite->owner_id));
				$favorites[] = $favorite;
			}
			$this->otd_view->set_partial('side', 'timeline/side_startup');

			// funding
			$this->load->model('Funding_model');
			$funding = $this->Funding_model->with('type_codes', 'type_code_id')->columns('fundings.*, type_codes.name AS type_code_name, type_codes.key AS type_code_key')->find_one('fundings.user_id = ' . $user->id . ' AND fundings.state IN (\'CONFIRMED\', \'DONE\', \'REQUESTED\') AND type_codes.reference = \'fundings\'');
			if($funding) {
				$this->load->model('Funding_participating_model');
				$funding_participating = $this->Funding_participating_model->query('SELECT SUM(funding_amount) AS funding_amount FROM funding_participatings WHERE funding_id = ?', array($funding->id))->row();
				$funding->funding_participating_percent = round(($funding_participating->funding_amount / $funding->funding_amount) * 100);			
				
				$funding_end_at_unixtime = strtotime($funding->end_at);
				if((time() > mktime(23, 59, 59, date('n', $funding_end_at_unixtime), date('j', $funding_end_at_unixtime), date('Y', $funding_end_at_unixtime))
						|| $funding->funding_participating_percent >= 100)
					&& $funding->state == 'CONFIRMED') {
					$this->Funding_model->update(array('state' => 'DONE'), array('id' => $funding->id));
					$funding->state = 'DONE';
				}
			} else {
				$funding_participating = false;
			}
			$this->otd_view->set_value('funding', $funding);
			$this->otd_view->set_value('funding_participating', $funding_participating);
		} else {
			foreach($this->otd_favorite->get_my_favorites($user->id) as $favorite) {
				$favorite->user = $this->User_model->with('type_codes', 'type_code_id')->columns('users.serial_number AS serial_number, type_codes.key AS type_code_key')->find_one(array('users.id' => $favorite->target_id));
				$favorites[] = $favorite;
			}
			$this->otd_view->set_partial('side', 'timeline/side_investor');
		}
		
		$this->otd_view->set_value(array(
			'user' => $user,
			'stream_key' => $this->stream_key,
			'milestones' => $milestones,
			'favorites' => $favorites
		));
		
		$this->{$method_name}($id);
	}
	
	private function preparatory_userinfo() {				
		/**
		 * 
		 *  스타트업과 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->startup_userinfo();
	}
	
	private function preparatory_bm() {
		/**
		 * 
		 *  스타트업과 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->startup_bm();
	}
	
	private function preparatory_team() {
		/**
		 * 
		 *  스타트업과 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->startup_team();
	}
	
	private function preparatory_financial() {
		/**
		 * 
		 *  스타트업과 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->startup_financial();
	}
	
	private function preparatory_unique() {
		/**
		 * 
		 *  스타트업과 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->startup_unique();
	}
	
	private function preparatory_milestone() {		
		/**
		 * 
		 *  스타트업과 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->startup_milestone();
	}
	
	private function preparatory_funding() {
		set_error('error', '올바른 접근이 아닙니다.');
		redirect('/mypage/userinfo');
	}

	private function preparatory_delete_team($team_id) {
		/**
		 * 
		 *  스타트업과 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->startup_delete_team($team_id);
	}
	
	private function startup_userinfo() {
		if($this->input->post()) {
			$users = $this->input->post('users');
			$user_metadatas = $this->input->post('user_metadatas');
			$attachments = $this->input->post('attachments');
			$password = $this->input->post('password');
			$password_confirm = $this->input->post('password_confirm');
			
			if(trim($password . $password_confirm) != '') {
				if($password == $password_confirm) {
					$users['password'] = md5($password);
				} else {
					set_error('error', '비밀번호와 비밀번호 확인이 일치하지 않습니다.');
					redirect('/mypage/userinfo');
				}
			}
			
			if(!error_exists()) {
				$this->db->trans_start();
					// users
					$this->User_model->update($users, array('id' => $this->user_id));
					
					// user metadatas
					$this->otd_user->set_metadata($this->user_id, $user_metadatas);
					
					// attachments
					if($attachments
						&& is_array($attachments)) {
						$attachment = end($attachments);
						$this->otd_user->set_profile_image($this->otd_user->get('id'), $attachment);
					}
				$this->db->trans_complete();
				
				if($this->db->trans_status()) {
					set_error('success', '성공적으로 수정하였습니다.');
					redirect('/mypage/userinfo');
				} else {
					set_error('error', '회원정보 수정에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
					redirect('/mypage/userinfo');
				}
			}
		}
		
		$this->otd_view->set_value('user_metadata', $this->otd_user->get_metadata($this->user_id));
		
		// render
		$this->otd_view->set_frame('mypage_startup');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_startup_userinfo');
		$this->otd_view->render();
	}
	
	private function startup_bm() {
		// load libraries
		$this->load->model('Team_model');
		$this->load->library('otd_tag');

		if($this->input->post()) {
			$user_metadatas = $this->input->post('user_metadatas');
			$tags = $this->input->post('tags');
			
			$this->db->trans_start();
				// user metadatas
				$this->otd_user->set_metadata($this->user_id, $user_metadatas);
				// tags
				foreach($tags as $tag_key => $tag_name) {
					$this->otd_tag->set_tag($tag_name, 'user_metadatas:' . $tag_key, $this->user_id);
				}
			$this->db->trans_complete();
			
			if($this->db->trans_status()) {
				//set_error('success', '성공적으로 저장하였습니다.');
				redirect('/mypage/bm');
			} else {
				set_error('error', '비지니스 모델 저장에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		}

		// tags
		$tags = array();
		$sql = 'SELECT tags.name, tag_relations.reference
				FROM tags
				INNER JOIN tag_relations ON tag_relations.tag_id = tags.id
				WHERE tag_relations.reference LIKE \'user_metadatas:%\'
				AND tag_relations.reference_id = ?';
		$result = $this->Tag_model->query($sql, array($this->user_id));
		if($result
			&& $result->num_rows() > 0) {
			foreach($result->result() as $tag) {
				$tags[split_getter($tag->reference, ':', 1)][] = $tag->name;
			}
		}
		
		$this->otd_view->set_value(array(
			'user_metadata' => $this->otd_user->get_metadata($this->user_id),
			'tags' => $tags
		));
				
		$this->otd_view->set_frame('mypage_startup');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_startup_bm');
		$this->otd_view->render();
	}

	private function startup_team() {
		// load libraries
		$this->load->model('Team_model');
		$this->load->model('Type_code_model');
		
		if($this->input->post()) {
			$teams = $this->input->post('teams');
			$this->db->trans_start();
				// teams
				foreach($this->Type_code_model->find(array('reference' => 'teams')) as $type_code) {
					${$type_code->key . '_type_code_id'} = $type_code->id;
				}
				
				// teams:ceo
				if($teams['ceo']['name']) {
					$team_ceo_data = array(
						'user_id' => $this->user_id,
						'type_code_id' => $ceo_type_code_id,
						'name' => $teams['ceo']['name'],
						'gender' => $teams['ceo']['gender'],
						'education' => $teams['ceo']['education_1'] . '|#!|'. $teams['ceo']['education_2'] . '|#!|' . $teams['ceo']['education_3'],
						'phone' => $teams['ceo']['phone'],
						'email' => $teams['ceo']['email'],
						'career' => $teams['ceo']['career']
					);
					if($this->Team_model->with('type_codes', 'type_code_id')->is_exists(array('type_codes.reference' => 'teams', 'type_codes.key' => 'ceo'))) {
						// update
						$team_ceo = $this->Team_model->with('type_codes', 'type_code_id')->columns('teams.id')->find_one(array('type_codes.reference' => 'teams', 'type_codes.key' => 'ceo'));
						$this->Team_model->update($team_ceo_data, array('id' => $team_ceo->id));
					} else {
						// create
						$this->Team_model->create($team_ceo_data);
					}
				}
				
				// teams:teams
				foreach($teams['teams'] as $action_type => $team_team) {
					list($action_mode, $team_id) = explode(':', $action_type);
					$team_team_data = array(
						'user_id' => $this->user_id,
						'type_code_id' => $team_type_code_id,
						'name' => $team_team['name'],
						'gender' => $team_team['gender'],
						'education' => $team_team['education_1'] . '|#!|' . $team_team['education_2'] . '|#!|' . $team_team['education_3'],
						'branch' => $team_team['branch'],
						'career' => $team_team['career']
					);
					
					if($team_team['name']) {
						if($action_mode == 'old') {
							// update
							$this->Team_model->update($team_team_data, array('id' => $team_id));
						} else {
							// create
							$this->Team_model->create($team_team_data);
						}
					}
				}
			$this->db->trans_complete();
			
			if($this->db->trans_status()) {
				set_error('success', '성공적으로 저장하였습니다.');
				redirect('/mypage/team');
			} else {
				set_error('error', '팀 정보 저장에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		}

		// teams:ceo
		$teams['ceo'] = $this->Team_model->with('type_codes', 'type_code_id')->columns('teams.*')->find_one(array('type_codes.reference' => 'teams', 'type_codes.key' => 'ceo', 'teams.user_id' => $this->user_id));
		if($teams['ceo']) { 
			$teams['ceo']->jumin_number = $this->encrypt->decode($teams['ceo']->jumin_number);
		}
		
		// teams:team
		$teams['teams'] = $this->Team_model->with('type_codes', 'type_code_id')->columns('teams.*')->find(array('type_codes.reference' => 'teams', 'type_codes.key' => 'team', 'teams.user_id' => $this->user_id));
		$team_education = array(1 => '재학', 2 => '졸업', 3 => '휴학', 4 => '중퇴');
				
		$this->otd_view->set_value(array(
			'teams' => $teams,
			'team_education' => $team_education
		));
		
		// render
		$this->otd_view->set_frame('mypage_startup');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_startup_team');
		$this->otd_view->render();
	}

	private function startup_financial() {
		// load libraries
		$this->load->model('Financial_model');
		
		if($this->input->post()) {
			$financials = $this->input->post('financials');
			$financials['user_id'] = $this->user_id;
			$attachments = $this->input->post('attachments');

			$this->db->trans_start();
				// financial
				$financial_id = $this->Financial_model->create($financials);
				
				// attachments
				if($attachments) {
					foreach($attachments as $attachment_id) {
						$this->Attachment_model->update(array('reference' => 'financials', 'reference_id' => $financial_id), array('id' => $attachment_id));
					}
				}
			$this->db->trans_complete();
			
			if($this->db->trans_status()) {
				set_error('success', '성공적으로 저장하였습니다.');
				redirect('/mypage/financial');
			} else {
				set_error('error', 'Financial 데이터 입력에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}		
		}
		
		// financial history
		$financials = array();
		foreach($this->Financial_model->with('users', 'user_id')->columns('financials.*')->find(array('users.id' => $this->user_id), 'financials.id DESC') as $financial) {
			$financial->upload = $this->Attachment_model->find_one(array('reference' => 'financials', 'reference_id' => $financial->id));
			$financials[] = $financial;
		}
        
		$this->otd_view->set_value('financials', $financials);
		
		// render
		$this->otd_view->set_frame('mypage_startup');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_startup_financial');
		$this->otd_view->render();
	}

	private function startup_unique() {
		if($this->input->post()) {
			$user_metadatas = $this->input->post('user_metadatas');
			$attachments = $this->input->post('attachments');
			$user_metadatas['team_patent'] = $this->input->post('team_patent_0') . '|#!|' . $this->input->post('team_patent_1') . '|#!|' . $this->input->post('team_patent_2');

			$this->db->trans_start();
				// user metadatas
				$this->otd_user->set_metadata($this->user_id, $user_metadatas);
				
				// attachments
				if($attachments) {
					foreach($attachments as $attachment_id) {
							$this->Attachment_model->update(array('reference' => 'user_metadatas:team_unique', 'reference_id' => $this->user_id), array('id' => $attachment_id));
					}
				}
			$this->db->trans_complete();
			
			if($this->db->trans_status()) {
				set_error('success', '성공적으로 저장하였습니다.');
				redirect('/mypage/unique');
			} else {
				set_error('error', '저장에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		}

		// team unique attachment
		$team_unique_attachments = $this->Attachment_model->find(array('reference' => 'user_metadatas:team_unique', 'reference_id' => $this->user_id, 'status' => 'Y'));
			
		$this->otd_view->set_value(array(
			'user_metadata' => $this->otd_user->get_metadata($this->user_id),
			'team_unique_attachments' => $team_unique_attachments
		));
		
		// render
		$this->otd_view->set_frame('mypage_startup');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_startup_unique');
		$this->otd_view->render();
	}

	private function startup_milestone() {
		if($this->input->post()) {
			$milestone_requests = $this->input->post('milestone_requests');
			$attachments = $this->input->post('attachments');
			
			if($milestone_requests['milestone_id'] != 'no_unconfirmed_milestone') {
				$this->db->trans_start();
					// request
					$milestone_request_id = $this->Milestone_request_model->create($milestone_requests);
					
					// attachments
					if($attachments) {
						foreach($attachments as $attachment_id) {
								$this->Attachment_model->update(array('reference' => 'milestone_requests', 'reference_id' => $milestone_request_id), array('id' => $attachment_id));
						}
					}
				$this->db->trans_complete();
				
				if($this->db->trans_status()) {
					set_error('success', '성공적으로 저장하였습니다.');
				} else {
					set_error('error', '마일스톤 승인 요청에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
				}
			} else {
				set_error('error', '신청할 Milestone 단계가 없습니다.');
			}
			redirect('/mypage/milestone');
		}
				
		// 최근 승인된 마일스톤
		$latest_confirmed_milestone = $this->otd_milestone->get_latest_confirmed_milestone($this->user_id);
		
		// 신청할 마일스톤 단계
		$unconfirmed_milestones = array();
		foreach($this->otd_milestone->get_unconfirmed_milestones($this->user_id) as $unconfirmed_milestone) {
			$unconfirmed_milestones[$unconfirmed_milestone->id] = $unconfirmed_milestone->position . '단계 : ' . $unconfirmed_milestone->title;
		}
		
		if(count($unconfirmed_milestones) == 0) {
			$unconfirmed_milestones = array('no_unconfirmed_milestone' => '없음');
		}
		
		// 마일스톤 요청들
		$milestone_requests = array();
		foreach($this->otd_milestone->get_milestone_requests($this->user_id) as $milestone_request) {
			$milestone_request->attachment = $this->Attachment_model->find_one(array('reference' => 'milestone_requests', 'reference_id' => $milestone_request->id));
			$milestone_requests[] = $milestone_request;			
		}
				
		$this->otd_view->set_value(array(
			'latest_confirmed_milestone' => $latest_confirmed_milestone,
			'unconfirmed_milestones' => $unconfirmed_milestones,
			'milestone_requests' => $milestone_requests
		));
				
		// render
		$this->otd_view->set_frame('mypage_startup');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_startup_milestone');
		$this->otd_view->render();
	}
	
	private function startup_funding() {
		// load libraries
		$this->load->model('Type_code_model');
		$this->load->model('Funding_model');
		$this->load->model('Funding_participating_model');
		
		if($this->input->post()) {
			$fundings = $this->input->post('fundings');
			$fundings['start_at'] = $fundings['start_at'] . ' 00:00:00';
			$fundings['end_at'] = $fundings['end_at'] . ' 00:00:00';
			$fundings['state'] = 'REQUESTED';
			$fundings['user_id'] = $this->user_id;
			
			if($fundings['type_code_id'] != 'no_available_funding') {
				if($this->Funding_model->create($fundings)) {
					set_error('success', '성공적으로 저장하였습니다.');
					redirect('/mypage/funding');
				} else {
					set_error('error', '펀딩 요청에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
					redirect('/mypage/funding');
				}
			} else {
				set_error('error', '진행 가능한 펀딩 단계가 없습니다.');
				redirect('/mypage/funding');
			}
		}

		// funding series
		$type_codes = array();
		$cur_state = $this->Funding_model->find_one(array('user_id' => $this->user_id), 'id DESC');
		if ($cur_state) {
			if (in_array($cur_state->state, array('REJECTED', 'SUCCEEDED', 'FAILED'))) {
				// 성공한 펀딩의 다음단계 또는 거부/실패 및 진행되지 않은 단계의 펀딩 신청 가능
				$sql = 'SELECT *
				FROM type_codes
				WHERE reference = \'fundings\'
				AND id > IFNULL(
					(
						SELECT MAX(type_code_id)
						FROM fundings
						WHERE state NOT IN (\'FAILED\', \'REJECTED\')
						AND user_id = ?
					), 0
				)
				ORDER BY id ASC';
				$result = $this->Funding_model->query($sql, array($this->user_id));
				if($result
					&& $result->num_rows() > 0) {
					foreach($result->result() as $row) {
						$type_codes[$row->id] = $row->name;
					}
				} else {
					$type_codes['no_available_funding'] = '진행 가능한 펀딩 단계가 없습니다.';
				}
			} else {
				$type_codes['no_available_funding'] = '펀딩 진행중에는 새로운 펀딩을 진행할 수 없습니다.';
			}
		}  else {
			//$seed = $this->Type_code_model->find_one(array('reference' => 'fundings', 'key' => 'seed_funding'));
			//$type_codes[$seed->id] = $seed->name;
			// 모든 종류의 펀딩 신청 가능
			foreach($this->Type_code_model->find(array('reference' => 'fundings'), 'id ASC') as $type_code) {
				$type_codes[$type_code->id] = $type_code->name;
			}
		}

		// fundings
		$fundings = array();
		foreach($this->Funding_model->with('type_codes', 'type_code_id')->columns('fundings.*, type_codes.name AS type_code_name')->find(array('fundings.user_id' => $this->user_id), 'fundings.id DESC') as $funding) {
			$funding_participating = $this->Funding_participating_model->query('SELECT SUM(funding_amount) AS funding_amount FROM funding_participatings WHERE funding_id = ?', array($funding->id))->row();
			$funding->funding_participating_amount = $funding_participating->funding_amount;
			$funding->funding_participating_percent = round(($funding->funding_participating_amount / $funding->funding_amount) * 100);
			$fundings[] = $funding;
		}
		
		$this->otd_view->set_value(array(
			'type_codes' => $type_codes,
			'fundings' => $fundings
		));
		
		// render
		$this->otd_view->set_frame('mypage_startup');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_startup_funding');
		$this->otd_view->render();
	}
	
	private function startup_delete_team($team_id) {
		// load librareis
		$this->load->model('Team_model');
		
		// get team
		$team = $this->Team_model->find_one(array('id' => $team_id));
		if(!$team) {
			$result = array(
				'result' => false, 
				'message' => '존재하는 구성원이 아닙니다.'
			);
		} else if($team->user_id != $this->user_id) {
			$result = array(
				'result' => false,
				'message' => '자신에게 소속된 구성원이 아닙니다.'
			);
		} else {
			$this->Team_model->delete(array('id' => $team_id));
			$result = array(
				'result' => true
			);
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));
	}
/*	
	private function startup_message() {
		// load libraries
		$this->load->library('otd_timeline');
		$this->load->library('pagination');

		// pagination
		$page = ifempty($this->input->get('p'), 1);		
		$config = $this->config->item('pagination_default');
		$config['query_string_segment'] = 'p';
		$config['base_url'] = '/mypage/message?';
		$config['total_rows'] = $this->Timeline_model->count_records(array('owner_id' => $this->user_id, 'privacy' => 'MESSAGE'));
		$this->pagination->initialize($config);
		$this->otd_view->set_value('pagination', $this->pagination->create_links());
		
		// messages
		$messages = $this->Timeline_model->with('users', 'writer_id')->columns('timelines.*, users.name AS user_name, users.serial_number AS user_serial_number')->find(array('timelines.owner_id' => $this->user_id, 'timelines.privacy' => 'MESSAGE'), 'timelines.id DESC', (($page - 1) * $config['per_page']), $config['per_page']);
		$this->otd_view->set_value('messages', $messages);
		
		// render
		$this->otd_view->set_frame('mypage_startup');
		$this->otd_view->set_partial('mypage', 'mypage_startup_message');
		$this->otd_view->render();
	}
*/	
	private function startup_stockholder_request() {
		// load libraries
		$this->load->model('Portfolio_model');
		$this->load->library('pagination');
		
		if($this->input->post()) {
			$portfolio_id = $this->input->post('portfolio_id');
			$request_confirm = $this->input->post('request_confirm') == 'Y' ? 'CONFIRMED' : 'REJECTED';
			
			if($this->Portfolio_model->update(array('state' => $request_confirm), array('company_id' => $this->user_id, 'id' => $portfolio_id))) {
				set_error('success', '성공적으로 수정하였습니다.');
				redirect('/mypage/stockholder_request');
			} else {
				set_error('error', '신청 승인에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		}
		
		// pagination
		$page = ifempty($this->input->get('p'), 1);		
		$config = $this->config->item('pagination_default');
		$config['query_string_segment'] = 'p';
		$config['base_url'] = '/mypage/stockholder_request?';
		$config['total_rows'] = $this->Portfolio_model->count_records(array('company_id' => $this->user_id));
		$this->pagination->initialize($config);
		$this->otd_view->set_value('pagination', $this->pagination->create_links());
		
		// portfolios
		$portfolios = $this->Portfolio_model->with('users', 'investor_id')->columns('portfolios.*, users.name AS user_name, users.serial_number AS user_serial_number')->find(array('portfolios.company_id' => $this->user_id), 'portfolios.id DESC', (($page - 1) * $config['per_page']), $config['per_page']);
		$this->otd_view->set_value('portfolios', $portfolios);
		
		// render
		$this->otd_view->set_frame('mypage_startup');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_startup_stockholder_request');
		$this->otd_view->render();
	}
	
	private function angel_investor_userinfo() {
		if($this->input->post()) {
			$users = $this->input->post('users');
			$user_metadatas = $this->input->post('user_metadatas');
			$attachments = $this->input->post('attachments');
			$password = $this->input->post('password');
			$password_confirm = $this->input->post('password_confirm');
			
			if(trim($password . $password_confirm) != '') {
				if($password == $password_confirm) {
					$users['password'] = md5($password);
				} else {
					set_error('error', '비밀번호와 비밀번호 확인이 일치하지 않습니다.');
					redirect('/mypage/userinfo');
				}
			}
			
			if(!error_exists()) {
				$this->db->trans_start();
					// users
					$this->User_model->update($users, array('id' => $this->user_id));
					
					// user metadatas
					$this->otd_user->set_metadata($this->user_id, $user_metadatas);
					
					// attachments
					if($attachments
						&& is_array($attachments)) {
						$attachment = end($attachments);
						$this->otd_user->set_profile_image($this->otd_user->get('id'), $attachment);
					}
				$this->db->trans_complete();
				
				if($this->db->trans_status()) {
					set_error('success', '성공적으로 수정하였습니다.');
					redirect('/mypage/userinfo');
				} else {
					set_error('error', '회원정보 수정에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
					redirect('/mypage/userinfo');
				}
			}
		}
		
		$this->otd_view->set_value('user_metadata', $this->otd_user->get_metadata($this->user_id));
		
		// render
		$this->otd_view->set_frame('mypage_investor');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_investor_userinfo');
		$this->otd_view->render();
	}
	
	private function angel_investor_portfolio($category_id = null) {
		// load libraries
		$this->load->model('Portfolio_model');
		$this->load->model('Financial_model');
		$this->load->model('Milestone_model');
		$this->load->model('User_metadata_model');
		$this->load->model('Category_model');
		$this->load->library('pagination');
		
		$where_clause = 'portfolios.investor_id = '.$this->user_id; // AND 'portfolios.state' = 'CONFIRMED'
		if ($category_id) {
			$where_clause .= ' AND portfolios.category_id = '.$category_id;
		}
		
		// pagination
		$page = ifempty($this->input->get('p'), 1);		
		$config = $this->config->item('pagination_default');
		$config['query_string_segment'] = 'p';
		$config['base_url'] = '/mypage/portfolio?';
		$config['total_rows'] = $this->Portfolio_model->count_records($where_clause);
		$this->pagination->initialize($config);
		$this->otd_view->set_value('pagination', $this->pagination->create_links());

		// portfolios
		$portfolios = array();
		foreach($this->Portfolio_model->find($where_clause, 'id DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $portfolio) {
			$portfolio->user = $this->User_model->with('type_codes', 'type_code_id')->columns('users.*, type_codes.key AS type_code_key')->find_one(array('users.id' => $portfolio->company_id));
			$portfolio->financial = $this->Financial_model->find_one(array('user_id' => $portfolio->company_id), 'position DESC');
			$portfolio->milestone = $this->Milestone_model->find_one(array('user_id' => $portfolio->company_id, 'state' => 'CONFIRMED'), 'position DESC');
			$portfolios[] = $portfolio;
		}
		
		// 프리미엄 서비스 현황 조회
		$is_premium = $this->User_metadata_model->with('user_metadata_field_definitions', 'user_metadata_field_definition_id')->find_one(array('user_metadata_field_definitions.key' => 'premium_level', 'user_metadatas.user_id' => $this->user_id))->data;
		// favorite categories
		$portfolio_categories = array();
		if ($is_premium) {
			$portfolio_categories['-1'] = '카테고리 없음';
			foreach($this->Category_model->with('type_codes', 'type_code_id')->columns('categories.*')->find(array('type_codes.reference' => 'categories', 'type_codes.key' => 'portfolio', 'categories.investor_id' => $this->user_id), 'categories.id ASC') as $category) {
				$portfolio_categories[$category->id] = $category->name;
			}
		}
		$this->otd_view->set_value(array(
			'portfolios' => $portfolios,
			'is_premium' => $is_premium,
			'portfolio_categories' => $portfolio_categories,
			'category_id' => $category_id
		));
		
		// render
		$this->otd_view->set_frame('mypage_investor');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_investor_portfolio');
		$this->otd_view->render();
	}
	
	private function angel_investor_portfolio_search_company() {
		$name = $this->input->get('company_name');
		if($name) {
			$result = array(
				'result' => true,
				'data' => $this->User_model->with('type_codes', 'type_code_id')->columns('users.id, users.name, users.serial_number')->find('users.name LIKE \'%' . $name . '%\' AND type_codes.reference = \'users\' AND type_codes.key = \'startup\'', 'users.name ASC')
			);
		} else {
			$result = array(
				'result' => false,
				'data' => array()
			);
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));
	}
	
	private function angel_investor_portfolio_add() {
		// load libraries
		$this->load->model('Portfolio_model');
		
		if($this->input->post()) {
			$portfolios = $this->input->post('portfolios');
			$portfolios['company_id'] = $this->input->post('company_id');
			$portfolios['investor_id'] = $this->user_id;
			$portfolios['state'] = 'REQUESTED';
			$portfolios['category_id'] = 0;
			
			if($this->Portfolio_model->is_exists(array('investor_id' => $portfolios['investor_id'], 'company_id' => $portfolios['company_id']))) {
				set_error('error', '이미 신청 되었거나 추가된 포트폴리오 기업입니다.');
				redirect('/mypage/portfolio');
			} else if($this->Portfolio_model->count_records(array('investor_id' => $portfolios['investor_id'])) >= 30) {
				set_error('error', '포트폴리오를 30개 이상 등록하시려면 프리미엄 서비스를 신청해주세요');
				redirect('/mypage/portfolio');
			}
			
			if(!error_exists()) {
				if($this->Portfolio_model->create($portfolios)) {
					// TODO 대상 기업에 타임라인에 기록하고 마이페이지에서 관리
					set_error('success', '포트폴리오 기업 신청이 완료되었습니다.<br />기업에서 승인이 이뤄지면 포트폴리오로 등록이 완료됩니다.');
					redirect('/mypage/portfolio');
				} else {
					set_error('error', '포트폴리오 기업 신청에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
					redirect('/mypage/portfolio');
				}
			}
		}
		
		// render
		$this->otd_view->set_frame('mypage_investor');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_investor_portfolio_add');
		$this->otd_view->render();
	}

	private function angel_investor_portfolio_user() {
		$id = $this->input->get('id');
		if($id) {
			$result = array(
				'result' => true,
				'data' => $this->User_model->find_one(array('id' => $id))
			);
		} else {
			$result = array(
				'result' => false,
				'data' => array()
			);
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));		
	}
	
	private function angel_investor_portfolio_delete() {
		if($this->input->post()
			&& $this->input->is_ajax_request()) {
			// load libraries
			$this->load->model('Portfolio_model');
			
			if($portfolio_ids = $this->input->post('portfolio_ids')) {
				if(!is_array($portfolio_ids)) $portfolio_ids = array($portfolio_ids);
				
				$this->db->trans_start();
					foreach($portfolio_ids as $portfolio_id) {
						$this->Portfolio_model->delete(array('id' => $portfolio_id));
					}
				$this->db->trans_complete();
				
				if($this->db->trans_status()) {
					$result = array(
						'result' => true,
						'message' => '삭제되었습니다.'
					);
				} else {
					$result = array(
						'result' => false,
						'message' => '삭제에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.'
					);
				}
			} else {
				$result = array(
					'result' => false,
					'message' => '삭제할 포트폴리오를 선택하여 주십시오.'
				);
			}
			
			// render
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($result));
		}
	}
	
	private function angel_investor_favorite($category_id = null) {
		// load libraries
		$this->load->model('Favorite_model');
		$this->load->model('Financial_model');
		$this->load->model('Milestone_model');
		$this->load->model('User_metadata_model');
		$this->load->model('Category_model');
		$this->load->library('pagination');
		
		$where_clause = 'favorites.owner_id = '.$this->user_id;
		if ($category_id) {
			$where_clause .= ' AND favorites.category_id = '.$category_id;
		}
		
		// pagination
		$page = ifempty($this->input->get('p'), 1);
		$config = $this->config->item('pagination_default');
		$config['query_string_segment'] = 'p';
		$config['base_url'] = '/mypage/favorite?';
		$config['total_rows'] = $this->Favorite_model->count_records($where_clause);
		$this->pagination->initialize($config);
		$this->otd_view->set_value('pagination', $this->pagination->create_links());
		
		$favorite_lists = array();
		foreach($this->Favorite_model->find($where_clause, 'created_at DESC', (($page - 1) * $config['per_page']), $config['per_page']) as $favorite_list) {
			$favorite_list->user = $this->User_model->with('type_codes', 'type_code_id')->columns('users.*, type_codes.key AS type_code_key')->find_one(array('users.id' => $favorite_list->target_id));
			$favorite_list->financial = $this->Financial_model->find_one(array('user_id' => $favorite_list->target_id), 'position DESC');
			$favorite_list->milestone = $this->Milestone_model->find_one(array('user_id' => $favorite_list->target_id, 'state' => 'CONFIRMED'), 'position DESC');
			$favorite_lists[] = $favorite_list;
		}
		
		// 프리미엄 서비스 현황 조회
		$is_premium = $this->User_metadata_model->with('user_metadata_field_definitions', 'user_metadata_field_definition_id')->find_one(array('user_metadata_field_definitions.key' => 'premium_level', 'user_metadatas.user_id' => $this->user_id))->data;
		// favorite categories
		$favorite_categories = array();
		if ($is_premium) {
			$favorite_categories['-1'] = '카테고리 없음';
			foreach($this->Category_model->with('type_codes', 'type_code_id')->columns('categories.*')->find(array('type_codes.reference' => 'categories', 'type_codes.key' => 'favorite', 'categories.investor_id' => $this->user_id), 'categories.id ASC') as $category) {
				$favorite_categories[$category->id] = $category->name;
			}
		}
		$this->otd_view->set_value(array(
			'favorite_lists' => $favorite_lists,
			'is_premium' => $is_premium,
			'favorite_categories' => $favorite_categories,
			'category_id' => $category_id
		));
		
		// render
		$this->otd_view->set_frame('mypage_investor');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_investor_favorite');
		$this->otd_view->render();
	}

	private function angel_investor_favorite_delete() {
		if($this->input->post()
			&& $this->input->is_ajax_request()) {
			// load libraries
			$this->load->model('Favorite_model');
			
			if($favorite_ids = $this->input->post('favorite_ids')) {
				if(!is_array($favorite_ids)) $favorite_ids = array($favorite_ids);
				
				$this->db->trans_start();
					foreach($favorite_ids as $favorite_id) {
						$this->Favorite_model->delete(array('id' => $favorite_id));
					}
				$this->db->trans_complete();
				
				if($this->db->trans_status()) {
					$result = array(
						'result' => true,
						'message' => '삭제되었습니다.'
					);
				} else {
					$result = array(
						'result' => false,
						'message' => '삭제에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.'
					);
				}
			} else {
				$result = array(
					'result' => false,
					'message' => '삭제할 관심기업을 선택하여 주십시오.'
				);
			}
			
			// render
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($result));
		}
	}
	
	private function angel_investor_message() {
		// load libraries
		$this->load->library('otd_timeline');
		$this->load->library('pagination');

		// pagination
		$page = ifempty($this->input->get('p'), 1);		
		$config = $this->config->item('pagination_default');
		$config['query_string_segment'] = 'p';
		$config['base_url'] = '/mypage/message?';
		$config['total_rows'] = $this->Timeline_model->count_records(array('owner_id' => $this->user_id, 'privacy' => 'MESSAGE'));
		$this->pagination->initialize($config);
		$this->otd_view->set_value('pagination', $this->pagination->create_links());
		
		// messages
		$messages = $this->Timeline_model->with('users', 'writer_id')->columns('timelines.*, users.name AS user_name, users.serial_number AS user_serial_number')->find(array('timelines.owner_id' => $this->user_id, 'timelines.privacy' => 'MESSAGE'), 'timelines.id DESC', (($page - 1) * $config['per_page']), $config['per_page']);
		$this->otd_view->set_value('messages', $messages);
		
		// render
		$this->otd_view->set_frame('mypage_investor');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_investor_message');
		$this->otd_view->render();
	}
	
	private function angel_investor_categories() {
		// load libraries
		$this->load->model('Type_code_model');
		$this->load->model('User_metadata_model');
		$this->load->model('Category_model');
		$this->load->model('Favorite_model');
		$this->load->model('Portfolio_model');
		
		if($this->input->post()) {
			$category = $this->input->post('category');
			$category['investor_id'] = $this->user_id;
			$category['type_code_id'] = $this->Type_code_model->find_one(array('reference' => 'categories', 'key' => $this->input->post('type_code_key')))->id;
			if($this->Category_model->is_exists(array('investor_id' => $category['investor_id'], 'name' => $category['name']))) {
				set_error('error', '이미 등록된 카테고리 입니다.');
				redirect('/mypage/categories');
			}
			
			if(!error_exists()) {
				if($this->Category_model->create($category)) {
					set_error('success', '카테고리 추가가 완료되었습니다.');
					redirect('/mypage/categories');
				} else {
					set_error('error', '카테고리 추가에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
					redirect('/mypage/categories');
				}
			}
		}
		
		// 프리미엄 서비스 현황 조회
		$is_premium = $this->User_metadata_model->with('user_metadata_field_definitions', 'user_metadata_field_definition_id')->find_one(array('user_metadata_field_definitions.key' => 'premium_level', 'user_metadatas.user_id' => $this->user_id))->data;		
		$portfolios = array();
		$favorites = array();
		// 프리미엄 회원일 경우 각 카테고리 조회
		if ($is_premium) {
			foreach($this->Category_model->with('type_codes', 'type_code_id')->columns('categories.*')->find(array('categories.investor_id' => $this->user_id, 'type_codes.reference' => 'categories', 'type_codes.key' => 'portfolio'), 'categories.id ASC') as $portfolio) {
				$portpolio->inclusion = $this->Portfolio_model->count_records(array('category_id' => $portfolio->id));
				$portfolios[] = $portfolio;
			}
			foreach($this->Category_model->with('type_codes', 'type_code_id')->columns('categories.*')->find(array('categories.investor_id' => $this->user_id, 'type_codes.reference' => 'categories', 'type_codes.key' => 'favorite'), 'categories.id ASC') as $favorite) {
				$favorite->inclusion = $this->Favorite_model->count_records(array('category_id' => $favorite->id));
				$favorites[] = $favorite;
			}
		}
		
		// set value
		$this->otd_view->set_value(array(
			'is_premium' => $is_premium,
			'portfolio_categories' => $portfolios,
			'favorite_categories' => $favorites
		));
		
		// render
		$this->otd_view->set_frame('mypage_investor');
		$this->otd_view->set_partial('mypage', 'timeline/mypage_investor_categories');
		$this->otd_view->render();
	}
	
	private function angel_investor_set_category($type) {
		if($this->input->post()
			&& $this->input->is_ajax_request()) {
			// load libraries
			$this->load->model('Favorite_model');
			$this->load->model('Portfolio_model');
			
			if($category_id = $this->input->post('category_id')) {
				$this->db->trans_start();
					if($type == 'favorite') $this->Favorite_model->update(array('category_id' => $category_id), array('id' => $this->input->post('favorite_id')));
					else if($type == 'portfolio') $this->Portfolio_model->update(array('category_id' => $category_id), array('id' => $this->input->post('portfolio_id')));
				$this->db->trans_complete();
				
				if($this->db->trans_status()) {
					$result = array(
						'result' => true,
						'message' => '카테고리가 변경되었습니다.'
					);
				} else {
					$result = array(
						'result' => false,
						'message' => '카테고리 변경에 실패하였습니다. 잠시 후 다시 시도해 주십시오.'
					);
				}
			} else {
				$result = array(
					'result' => false,
					'message' => '올바른 카테고리가 아닙니다.'
				);
			}
			
			// render
			$this->output->set_content_type('application/json');
			$this->output->set_output(json_encode($result));
		}
	}
	
	private function angel_investor_category_delete($category_id) {
		// load libraries
		$this->load->model('Type_code_model');
		$this->load->model('Category_model');
		$this->load->model('Portfolio_model');
		$this->load->model('Favorite_model');
		
		if(!$this->Category_model->is_exists(array('investor_id' => $this->otd_user->get('id'), 'id' => $category_id))) {
			$result = array(
				'result' => false,
				'message' => '자신의 카테고리만 삭제할 수 있습니다.'
			);
		} else if($this->Favorite_model->is_exists(array('category_id' => $category_id))) {
			$result = array(
				'result' => false,
				'message' => '카테고리에 포함된 기업이 없어야 삭제할 수 있습니다.'
			);
		} else if($this->Portfolio_model->is_exists(array('category_id' => $category_id))) {
			$result = array(
				'result' => false,
				'message' => '카테고리에 포함된 기업이 없어야 삭제할 수 있습니다.'
			);
		} else {
			$this->db->trans_start();
				$this->Category_model->delete(array('id' => $category_id));
			$this->db->trans_complete();
			
			if($this->db->trans_status()) {
				$result = array('result' => true, 'message' => '삭제되었습니다.');
			} else {
				$result = array('result' => false, 'message' => '삭제에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
			}
		}
					
		// render
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));
	}
	
	private function corporative_investor_userinfo() {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_userinfo();
/*		
		if($this->input->post()) {
			$users = $this->input->post('users');
			$user_metadatas = $this->input->post('user_metadatas');
			$attachments = $this->input->post('attachments');
			$password = $this->input->post('password');
			$password_confirm = $this->input->post('password_confirm');
			
			if(trim($password . $password_confirm) != '') {
				if($password == $password_confirm) {
					$users['password'] = md5($password);
				} else {
					set_error('error', '비밀번호와 비밀번호 확인이 일치하지 않습니다.');
				}
			}
				
			if(!error_exists()) {
				$this->db->trans_start();
					// users
					$this->User_model->update($users, array('id' => $this->user_id));
					
					// user metadatas
					$this->otd_user->set_metadata($this->user_id, $user_metadatas);
					
					// attachments
					if($attachments
						&& is_array($attachments)) {
						$attachment = end($attachments);
						$this->otd_user->set_profile_image($this->otd_user->get('id'), $attachment);
					}
				$this->db->trans_complete();
				
				if($this->db->trans_status()) {
					set_error('success', '성공적으로 수정하였습니다.');
					redirect('/mypage/userinfo');
				} else {
					set_error('error', '회원정보 수정에 실패하였습니다. 잠시 뒤 다시 시도하여 주십시오.');
				}
			}
		}
		
		$this->otd_view->set_value('user_metadata', $this->otd_user->get_metadata($this->user_id));
		
		// render
		$this->otd_view->set_frame('mypage_vc');
		$this->otd_view->set_partial('content', 'mypage_corporative_investor_userinfo');
		$this->otd_view->render();
*/
	}

	private function corporative_investor_portfolio() {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_portfolio();
	}
	
	private function corporative_investor_portfolio_add() {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_portfolio_add();
	}
	
	private function corporative_investor_portfolio_search_company() {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_portfolio_search_company();
	}
	
	private function corporative_investor_portfolio_user() {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_portfolio_user();
	}
	
	private function corporative_investor_portfolio_delete() {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_portfolio_delete();
	}
	
	private function corporative_investor_favorite($category_id = null) {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_favorite($category_id);
	}
	
	private function corporative_investor_favorite_delete() {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_favorite_delete();
	}
	
	private function corporative_investor_message() {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_message();
	}
	
	private function corporative_investor_set_category($type) {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_set_category($type);
	}
	
	private function corporative_investor_categories() {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_categories();
	}
	
	private function corporative_investor_category_delete($category_id) {
		/**
		 * 
		 *  이 부분은 엔젤투자자와 완전히 동일하므로 예비창업자의 루틴을 그대로 가져다 씁니다.
		 * 
		 */
		$this->angel_investor_category_delete($category_id);
	}
}
/* EOF */