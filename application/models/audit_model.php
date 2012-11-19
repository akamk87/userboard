<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Audit_model extends MY_Model {
	public $table = 'audits';

	public function __construct() {
		parent::__construct();
	}
}
/* EOF */