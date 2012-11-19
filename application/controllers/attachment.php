<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Attachment extends MY_Controller {
	public function __construct() {
		parent::__construct();
		
		$this->output->enable_profiler(false);
	}
	
	public function delete($attachment_id) {
		// load libraries
		$this->load->model('Attachment_model');
		
		// get attachment data
		$attachment = $this->Attachment_model->find_one(array('id' => $attachment_id));
		if(!$attachment) {
			$result = array(
				'result' => false, 
				'message' => '해당 파일이 없습니다.'
			);
		} else {
			$this->Attachment_model->update(array('status' => 'N'), array('id' => $attachment_id));
			//$this->Team_model->delete(array('id' => $team_id));
			$result = array(
				'result' => true
			);
		}
		
		$this->output->set_content_type('application/json');
		$this->output->set_output(json_encode($result));
	}
		
	public function download($attachment_id) {
		// load libraries
		$this->load->model('Attachment_model');
		$this->load->helper('download');
		$this->load->helper('file');
		
		// get attachment data
		$attachment = $this->Attachment_model->find_one(array('id' => $attachment_id, 'status' => 'Y'));
		if($attachment) {
			$attachment_path = $this->_get_attachment_path($attachment);
			$attachment_file_name = $this->_get_attachment_file_name($attachment);
			if(file_exists($attachment_path . '/' . $attachment_file_name)) {
				force_download(
					$attachment->orig_name, 
					read_file($attachment_path . '/' . $attachment_file_name)
				);
				exit;
			} else {
				show_error('존재하지 않는 파일입니다. [0]', 404, '다운로드 오류');
			}
		} else {
			show_error('존재하지 않는 파일입니다. [1]', 404, '다운로드 오류');
		}
	}
	
	public function thumbnail($attachment_id) {
		// TODO 정해진 규격만 썸네일 생성하도록 수정해야함.
		// load libraries
		$this->load->library('image_lib');
		$this->load->model('Attachment_model');
		$this->load->helper('download');
		$this->load->helper('file');
		
		// get attachment data
		$attachment = $this->Attachment_model->find_one(array('id' => $attachment_id, 'status' => 'Y', 'is_image' => 'Y'));
		if(!$attachment) show_error('존재하는 이미지가 아닙니다.', 404, '썸네일 오류');
		
		// width, height, manipulation mode
		$old_width = $attachment->width;
		$old_height = $attachment->height;
		$new_width = $this->input->get('w');
		$new_height = $this->input->get('h');
		$ratio = $this->input->get('ratio');
		
		if($new_width && $new_height) {
			$dest_width = $new_width;
			$dest_height = $new_height;
		} else if($new_width && !$new_height) {
			if($ratio) {
				$dest_width = $new_width;
				$dest_height = round(($new_width * $old_height) / $old_width);
			} else {
				$dest_width = $new_width;
				$dest_height = $old_height;
			}
		} else if(!$new_width && $new_height) {
			if($ratio) {
				$dest_width = round(($old_width * $new_height) / $old_height);
				$dest_height = $new_height;
			} else {
				$dest_width = $old_width;
				$dest_height = $new_height;
			}
		}
		
		// 최정 결정된 가로, 세로 크기가 원본보다 크면 원본으로 재조정
		if($dest_width > $old_width
			|| $dest_height > $old_height) {
			if($ratio) {
				$dest_width = $old_width;
				$dest_height = $old_height;
			} else {
				$dest_width = $dest_width > $old_width ? $old_width : $dest_width;
				$dest_height = $dest_height > $old_height ? $old_height : $dest_height;
			}
		}
		
		$thumbnail_path = $this->_get_thumbnail_path($attachment, $dest_width, $dest_height);
		$thumbnail_file_name = $this->_get_thumbnail_file_name($attachment, $dest_width, $dest_height);
		
		// if already file exists
		if(file_exists($thumbnail_path . '/' . $thumbnail_file_name)) {
			force_download(
				$thumbnail_file_name,
				read_file($thumbnail_path . '/' . $thumbnail_file_name)
			);
			exit;
		}
		
		// make resized thumbnail
		if(!file_exists($thumbnail_path)
			&& !mkdir($thumbnail_path)) {
			show_error('썸네일 생성에 실패했습니다. [0]', 404, '썸네일 오류');
		}
			
		$config['source_image'] = $this->_get_attachment_path($attachment) . '/' . $this->_get_attachment_file_name($attachment);
		$config['new_image'] = $thumbnail_path . '/' . $thumbnail_file_name;
		$config['maintain_ratio'] = $ratio;
		$config['width'] = $dest_width;
		$config['height'] = $dest_height;
		$this->image_lib->initialize($config);
		
		if($this->image_lib->resize()) {
			force_download(
				$thumbnail_file_name,
				read_file($thumbnail_path . '/' . $thumbnail_file_name)
			);
			exit;
		} else {
			show_error('썸네일 생성에 실패했습니다. [1]', 404, '썸네일 오류');
		}
	}
	
	private function _get_attachment_path($attachment) {
		return $this->config->item('attachment_path') . '/' . $attachment->local_path;
	}
	
	private function _get_attachment_file_name($attachment) {
		return $attachment->local_name . $attachment->local_extention;
	}
	
	private function _get_thumbnail_path($attachment, $width, $height) {
		return $this->config->item('thumbnail_path') . '/' . $attachment->local_path;
	}
	
	private function _get_thumbnail_file_name($attachment, $width, $height) {
		return $attachment->local_name . '-w' . $width . '-h' . $height . $attachment->local_extention;
	}
}
/* EOF */