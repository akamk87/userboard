<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Controller extends CI_Controller {
	public function __construct() {
		parent::__construct();
		
		/**
		 * enable profiler
		 */
		if($this->input->get('enable_profiler')) {
			$this->output->enable_profiler(true);
		}

		/**
		 * acl routine
		 */
		// get request
		$request = (object) null;
		$request->directory = ifempty(trim($this->router->fetch_directory(), '/'), '0');
		$request->controller = $this->router->fetch_class();
		$request->action = $this->router->fetch_method();
		
		//$this->output->enable_profiler(true);
	}
}
/* EOF */