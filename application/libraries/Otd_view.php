<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_view {
	private $frame_path = 'frame';
	private $partial_path = 'partials';
	private $values = array();
	private $frame = 'default';
	private $partials = array();

	public function __construct() {}

	/**
	 * 뷰 데이터 설정
	 * 
	 * 뷰파일로 넘길 데이터 설정 ($key, $value)형태나 (array($key => $value))형태 둘 다 사용 가능
	 * 
	 * @access	public
	 * @param	mixed	뷰파일 키
	 * @param	string	비밀번호
	 * @return	object	self instance
	 */
	public function set_value($key, $value = null) {
		if(is_null($value)) {
			$this->values = array_merge($this->values, $key);
		} else {
			$this->values[$key] = $value;
		}

		return $this;
	}

	/**
	 * 뷰 프레임 설정
	 * 
	 * 프레임으로 사용할 뷰파일 설정
	 * 
	 * @access	public
	 * @param	string	뷰파일명
	 * @return	object	self instance
	 */
	public function set_frame($frame) {
		$this->frame = $frame;

		return $this;
	}

	/**
	 * 뷰 파셜 설정
	 * 
	 * 프레임에 삽입될 파셜로 사용할 뷰파일 설정
	 * 
	 * @access	public
	 * @param	mixed	프레임에서 파셜의 위치로 사용할 키 이름 (프레임에는 자동적으로 partial_ 이라는 prefix가 붙음) ($placeholder, $partial)형태 및 (array($placeholder => $partial))형태로 사용 가능
	 * @param	string	뷰파일명
	 * @return	object	self instance
	 */
	public function set_partial($placeholder, $partial = null) {
		if(is_null($partial)) {
			$this->partials = array_merge($this->partials, $placeholder);
		} else {
			$this->partials[$placeholder] = $partial;
		}

		return $this;
	}

	/**
	 * 뷰 렌더링
	 * 
	 * 설정된 값들로 실제 페이지를 렌더링함
	 * 
	 * @access	public
	 * @param	string	output type (http, ajax)
	 * @return	void
	 */
	public function render($request_type = 'http') {
		$CI =& get_instance();

		switch($request_type) {
			case 'ajax':
				$CI->output->set_content_type('application/json');
				$CI->output->set_output(json_encode($this->values));
			break;
			case 'http':
			default:
				$rendered = array();
				foreach($this->partials as $placeholder => $partial) {
					
					if(isset($this->values)) {
						$rendered['partial_' . $placeholder] = $CI->load->view($this->partial_path . '/' . $partial, $this->values, true);
						unset($this->values);
					} else {
						$rendered['partial_' . $placeholder] = $CI->load->view($this->partial_path . '/' . $partial, '', true);
					}
				}

				$CI->load->view($this->frame_path . '/' . $this->frame, $rendered);
			break;
		}
	}
}
/* EOF */