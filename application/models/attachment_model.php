<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Attachment_model extends MY_Model {
	public $table = 'attachments';

	public function __construct() {
		parent::__construct();
	}
}
/* EOF */