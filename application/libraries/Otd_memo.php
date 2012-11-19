<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_memo {
	public $CI;
	
	public function __construct() {
		$this->CI =& get_instance();
		
		// load libraries
		$this->CI->load->model('Memo_model');
	}
	
	public function set_memo($user_id, $message, $reference, $reference_id) {
		return $this->CI->Memo_model->create(array(
			'user_id' => $user_id,
			'message' => $message,
			'reference' => $reference,
			'reference_id' => $reference_id
		));		
	}
	
	private function _get_memo($where) {
		return $this->CI->Memo_model->find(array($where), 'id DESC');
	}
	
	public function get_memo($id) {
		$result = $this->_get_memo(array('id' => $id));
		
		return $result->num_rows() > 0 ? $result->row() : false;
	}
	
	public function get_memo_by_user_id($user_id) {
		$result = $this->_get_memo(array('user_id' => $user_id));
		
		return $result->num_rows() > 0 ? $result->result() : array();
	}
	
	public function get_memo_by_reference($reference, $reference_id = null) {
		if(is_null($reference_id)) {
			$result = $this->_get_memo(array('reference' => $reference));
		} else {
			$result = $this->_get_memo(array('reference' => $reference, 'reference_id' => $reference_id));
		}
		
		return $result->num_rows() > 0 ? $result->result() : array();
	}
}
/* EOF */