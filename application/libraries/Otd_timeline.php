<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_timeline {
	private $CI;
	private $advertiser_role_id = 6; // TODO 광고주 전용 Role 생성 후 role_id = 6 <- 이 아이디값을 수정해야함
	
	public function __construct() {
		$this->CI =& get_instance();
		
		// load libraries
		$this->CI->load->model('Timeline_model');
		$this->CI->load->model('Attachment_model');
	}

	/**
	 * 본인 타임라인 열람
	 * 
	 * 본인이 본인의 타임라인을 열람할 때의 타임라인 상태를 리턴
	 * 
	 * @access	public
	 * @param	integer	타임라인 소유자의 사용자 아이디
	 * @param	integer	가져올 타임라인 메세지의 갯수
	 * @param	integer	offset 
	 * @return	mixed	active record 결과셋, 레코드가 없을경우 빈 array 리턴
	 */		
	public function set_message($writer_id, $owner_id, $message, $attachments = null, $privacy = 'OPEN') {
		$this->CI->db->trans_start();		
			foreach((is_array($owner_id) ? $owner_id : array($owner_id)) as $_owner_id) {
				// make timeline
				$timeline_id = $this->CI->Timeline_model->create(array(
					'owner_id' => $_owner_id,
					'writer_id' => $writer_id,
					'message' => $message,
					'privacy' => $privacy
				));
				
				if(!is_null($attachments)) {
					foreach((is_array($attachments) ? $attachments : array($attachments)) as $attachment_id) {
						$this->CI->Attachment_model->update(array(
							'reference' => 'timelines',
							'reference_id' => $timeline_id
						), array('id' => $attachment_id));
					}
				}
			}
		$this->CI->db->trans_complete();
		
		return $this->CI->db->trans_status();
	}
	
	public function send_system_message($sender_id, $receiver_id, $message, $attachments = null) {
		return $this->set_message($sender_id, $receiver_id, $message, $attachments, 'SYSTEM_MESSAGE');
	}
	
	public function send_admin_message($sender_id, $receiver_id, $message, $attachments = null) {
		return $this->set_message($sender_id, $receiver_id, $message, $attachments, 'ADMIN_MESSAGE');
	}
	
	public function send_message($sender_id, $receiver_id, $message, $attachments = null) {
		return $this->set_message($sender_id, $receiver_id, $message, $attachments, 'MESSAGE');
	}
	
	public function publish_advertisement($advertiser_id, $message, $attachments = null) {
		return $this->set_message($advertiser_id, $advertiser_id, $message, $attachments, 'AD');
	} 
}
/* EOF */