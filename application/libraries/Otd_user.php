<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_user {
	public $CI;
	private $roles = null;
	private $metadata = null;
	
	public function __construct() {
		$this->CI =& get_instance();
		
		// 익명사용자 id는 1
		if($this->get('id') == '') {
			$this->set('id', 1);
		}
	}
	
	public function get($key) {
		return ($value = $this->CI->session->userdata($key)) ? $value : '';
	}
	
	public function set($key, $value = null) {
		return $this->CI->session->set_userdata($key, $value);
	}
	
	public function set_flashdata($key, $value = null) {
		return $this->CI->session->set_flashdata($key, $value);
	}
	
	public function delete($key) {
		return $this->CI->session->unset_userdata($key);
	}
	
	public function set_profile_image($user_id, $attachment_id) {
		$this->CI->load->model('Attachment_model');
		
		$this->CI->db->trans_start();
			// 기존 설정된 첨부파일 이미지의 reference를 날림
			$this->CI->Attachment_model->update(array('reference' => null, 'reference_id' => null), array('reference' => 'users', 'reference_id' => $user_id));
			
			// 새로운 첨부파일 이미지 reference 등록
			$this->CI->Attachment_model->update(array('reference' =>'users', 'reference_id' => $user_id), array('id' => $attachment_id));
		$this->CI->db->trans_complete();
		
		return $this->CI->db->trans_status();
	}
	
	public function get_profile_image($user_id = null) {
		if (is_null($user_id)) $user_id = $this->get('id');
		$this->CI->load->model('Attachment_model');
		return $this->CI->Attachment_model->find_one(array('reference' => 'users', 'reference_id' => $user_id, 'status' => 'Y', 'is_image' => 'Y'));
	}
	
	public function has_role($key) {
		if(is_null($this->roles)) {
			$this->CI->load->model('User_role_model');
			$this->roles = array();
			foreach($this->CI->User_role_model->with('roles', 'role_id')->columns('roles.key')->find(array('user_roles.user_id' => $this->get('id'))) as $role) {
				$this->roles[] = $role->key;
			}
		}
		
		return in_array($key, $this->roles);
	}
	
	public function get_metadata($id = null) {
		if(is_null($id)) $id = $this->get('id');
		if($id == 1) return false;
		if(is_null($this->metadata)) $this->metadata = array();
		if(!isset($this->metadata[$id])) {
			// load 
			$this->CI->load->model('Type_code_model');
			$this->CI->load->model('User_metadata_model');
			
			// initialize
			$metadata = (object) null;
			
			// get field type code
			$user_type_code = $this->CI->User_model->with('type_codes', 'type_code_id')->columns('type_codes.key')->find_one(array('users.id' => $id));
			$type_code = $this->CI->Type_code_model->find_one(array('key' => $user_type_code->key, 'reference' => 'user_metadata_field_definitions'));
			
			$result = $this->CI->User_metadata_model->query('SELECT umfd.name, umfd.key, um.data, um.state FROM (SELECT * FROM user_metadata_field_definitions WHERE type_code_id = ?) AS umfd LEFT JOIN (SELECT * FROM user_metadatas WHERE user_id = ?) AS um ON umfd.id = um.user_metadata_field_definition_id', array($type_code->id, $id));			
			foreach($result->num_rows() > 0 ? $result->result() : array() as $user_metadata) {
				$metadata->{$user_metadata->key} = $user_metadata->data ? $user_metadata->data : '';
			}
			
			$this->metadata[$id] = $metadata;
		} else {
			$metadata = $this->metadata[$id];
		}
		
		return $metadata;
	}

	public function set_metadata($user_id, $data) {
		if($user_id
			&& !$this->CI->User_model->is_exists(array('id' => $user_id))) return false; 
		
		// load libraries
		$this->CI->load->model('Type_code_model');
		$this->CI->load->model('User_metadata_model');
		$this->CI->load->model('User_metadata_field_definition_model');
		
		// metadata type code
		$user = $this->CI->User_model->with('type_codes', 'type_code_id')->columns('type_codes.key')->find_one(array('users.id' => $user_id));
		$type_code = $this->CI->Type_code_model->find_one(array('key' => $user->key, 'reference' => 'user_metadata_field_definitions'));
		
		$this->CI->db->trans_start();
			foreach($data as $k => $v) {
				$user_metadata_field_definition = $this->CI->User_metadata_field_definition_model->find_one(array('type_code_id' => $type_code->id, 'key' => $k));
				if($this->CI->User_metadata_model->is_exists(array('user_id' => $user_id, 'user_metadata_field_definition_id' => $user_metadata_field_definition->id))) {
					$this->CI->User_metadata_model->update(array(
						'data' => $v
					), array('user_id' => $user_id, 'user_metadata_field_definition_id' => $user_metadata_field_definition->id));
				} else {
					$this->CI->User_metadata_model->create(array(
						'user_id' => $user_id,
						'user_metadata_field_definition_id' => $user_metadata_field_definition->id,
						'data' => $v,
						'state' => 'SUBMITTED'
					));
				}
			}
		$this->CI->db->trans_complete();
		
		return $this->CI->db->trans_status();
	}
}
/* EOF */