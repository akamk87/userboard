<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Statistic_member extends MY_Controller {
	public function __construct() {
		parent::__construct();

		// default
		$this->otd_view->set_frame('admin');
	}
	
	public function index() {
		$this->otd_view->set_partial('content', 'admin/statistic/member');
		$this->otd_view->render();
	}
}
/* EOF */