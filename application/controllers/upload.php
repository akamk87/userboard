<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Upload extends MY_Controller {
	public function __construct() {
		parent::__construct();
	}
	
	public function index() {
		// load libraries
		$this->load->library('upload');
		$this->load->helper('file');
		$this->load->model('Attachment_model');
		
		// settings
		$local_path = date('Ymd');
		$config['upload_path'] = $this->config->item('attachment_path') . '/' . $local_path;
		$config['allowed_types'] = '*';
		$config['encrypt_name'] = true;
		$this->upload->initialize($config);
		
		// directory check
		if(!is_really_writable($config['upload_path'])) {
			mkdir($config['upload_path']);
		}
		
		if($this->upload->do_upload('qqfile')) {
			$upload_data = $this->upload->data();
			$attachment = array(
				'orig_name' => $upload_data['orig_name'],
				'local_path' => $local_path,
				'local_name' => $upload_data['raw_name'],
				'local_extention' => $upload_data['file_ext'],
				'file_size' => $upload_data['file_size'] * 1024,
				'mime' => $upload_data['file_type'],
				'width' => $upload_data['image_width'],
				'height' => $upload_data['image_height'],
				'status' => 'Y',
				'is_image' => $upload_data['is_image'] ? 'Y' : 'N'
			);
			
			if($attachment_id = $this->Attachment_model->create($attachment)) {
				$result = array(
					'result' => true,
					'message' => '',
					'data' => array(
						'attachment_id' => $attachment_id,
						'is_image' => $upload_data['is_image'] ? true : false
					)
				);
			} else {
				$result = array(
					'result' => false,
					'message' => '업로드 데이터 처리 중 오류가 발생했습니다. 다시 시도해 주십시오.'
				);
			}
		} else {			
			$result = array(
				'result' => false,
				'message' => strip_tags($this->upload->display_errors())
			);			
		}

		//$this->output->set_content_type('application/json');
		//$this->output->set_output(htmlspecialchars(json_encode($result), ENT_NOQUOTES));
		$this->output->set_output(json_encode($result));
	}
}
/* EOF */