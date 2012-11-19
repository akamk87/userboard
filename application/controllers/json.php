<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Json extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		// render
		$this->otd_view->set_frame('empty');
		$this->otd_view->set_partial('content', 'json');
		$this->otd_view->render();
	}
}
