<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_audit {
	public $CI;
	private $is_user_logging_allowed = false;
	private $is_admin_logging_allowed = false;
	private $is_system_logging_allowed = false;
	
	public function __construct() {
		$this->CI =& get_instance();
		
		// load libraries
		$this->CI->load->model('Audit_model');
		
		// log level
		$this->is_user_logging_allowed = $this->CI->config->item('audit_user_logging');
		$this->is_admin_logging_allowed = $this->CI->config->item('audit_admin_logging');
		$this->is_system_logging_allowed = $this->CI->config->item('audit_system_logging');
	}
	
	private function _log($namespace, $user_id, $message, $reference, $reference_id) {
		$this->CI->db->query('INSERT INTO audits (namespace, user_id, message, reference, reference_id, ip_addr, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)', array($namespace, $user_id, $message, $reference, $reference_id, ip2long($this->CI->input->ip_address()), date('Y-m-d H:i:s')));
		
		return true;
	}
	
	public function user_log($namespace, $user_id, $message, $reference = null, $reference_id = null) {
		if(!$this->is_user_logging_allowed) return true;
		
		return $this->_log($this->_get_namespace('user', $namespace), $user_id, $message, $reference, $reference_id);
	}
	
	public function admin_log($namespace, $user_id, $message, $reference = null, $reference_id = null) {
		if(!$this->is_admin_logging_allowed) return true;
		
		return $this->_log($this->_get_namespace('admin', $namespace), $user_id, $message, $reference, $reference_id);
	}
	
	public function system_log($namespace, $message, $reference = null, $reference_id = null) {
		if(!$this->is_system_logging_allowed) return true;
		
		return $this->_log($this->_get_namespace('system', $namespace) , null, $message, $reference, $reference_id);
	}
	
	private function _get_namespace($root, $namespace) {
		return $root . '/' . implode('/' , (is_array($namespace) ? $namespace : array($namespace)));
	}
}
/* EOF */