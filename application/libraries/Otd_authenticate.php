<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
class Otd_authenticate {
	public $CI;
	
	public function __construct() {
		$this->CI =& get_instance();
		
		// load libraries
		$this->CI->load->library('encrypt');
	}

	/**
	 * 로그인 유지 쿠키값 가져오기
	 * 
	 * 로그인 유지가 설정된 계정의 쿠키값(이메일)을 반환한다. 쿠키가 없으면 false 리턴
	 * 
	 * @access	public
	 * @return	mixed	성공시 (string) email값, 실패시 false
	 */	
	public function get_autologin() {
		if($cookie = get_cookie('ot_autologin', true)) {
			// load libraries
			$this->CI->load->library('encrypt');
			
			if($cookie = @unserialize($this->CI->encrypt->decode($cookie))) {
				if(isset($cookie['expire'])
					&& $cookie['expire'] > time()) {
					return $cookie['email'];
				}
			}
		}
		
		return false;
	}

	/**
	 * 로그인 유지 쿠키값 설정
	 * 
	 * 로그인 유지 쿠키를 설정한다. 쿠키값은 email과 expire를 키로 갖는 배열의 직렬화한 string을 암호화한 값이다.
	 * 
	 * @access	public
	 * @param	object	Otd_user 라이브러리 인스턴스
	 * @param	integer	로그인 유지 쿠키를 유지할 시간 (시간값, 기본 2주 336시간)
	 * @return	void
	 */		
	public function set_autologin(Otd_user $user, $expire = 336) { // $expire (시간)
		// load libraries
		$this->CI->load->library('encrypt');
		
		// expire
		$expire = $expire * 60 * 60;
		set_cookie(
			'ot_autologin',
			$this->CI->encrypt->encode(@serialize(array('email' => $user->get('email'), 'expire' => time() + $expire))),
			$expire
		);
	}

	/**
	 * 로그인 유지 쿠키 해제
	 * 
	 * 설정된 로그인 유지 쿠키를 삭제한다.
	 * 
	 * @access	public
	 * @return	void
	 */		
	public function unset_autologin() {
		delete_cookie('ot_autologin');
	}

	/**
	 * 로그인 루틴
	 * 
	 * 사용자가 입력한 이메일, 패스워드로 일치하는 사용자 정보가 있는지 검사해서 로그인 루틴을 수행.
	 * 
	 * @access	public
	 * @param	string	사용자 입력 이메일
	 * @param	string	비밀번호
	 * @return	boolean
	 */
	public function login($email, $password) {
		if($this->CI->otd_user->get('id') != 1
			&& (empty($email) || empty($password))) return false;
		
		if($result = $this->CI->User_model->with('type_codes', 'type_code_id')->columns('users.*, type_codes.key AS type_code_key, type_codes.name AS type_code_name')->find_one(array('users.email' => $email, 'users.status' => 'Y'))) {
			if($result->password == md5($password)) {
				$this->CI->otd_user->set(array(
					'id' => $result->id,
					'type_code_id' => $result->type_code_id,
					'type_code_key' => $result->type_code_key,
					'type_code_name' => $result->type_code_name,
					'email' => $result->email,
					'name' => $result->name,
					'serial_number' => $result->serial_number
				));
				$this->CI->otd_user->delete('password');
				
				// update last login time
				$this->update_last_login($result->id);

				return true;
			} else {
				$this->CI->otd_user->set(array(
					'id' => 1
				));
				$this->CI->otd_user->delete(array_keys(array('email', 'password')));
				
				return false;
			}			
		} else {
			$this->CI->otd_user->set(array(
				'id' => 1
			));
			$this->CI->otd_user->delete(array_keys(array('email', 'password')));
			
			return false;
		}
	}

	/**
	 * 최종 로그인 시간 업데이트
	 * 
	 * 최종 로그인 시간을 업데이트한다.
	 * 
	 * @access	public
	 * @param	integer	사용자 아이디
	 * @return	boolean
	 */		
	public function update_last_login($user_id) {
		return $this->CI->User_model->update(array('last_login_at' => date('Y-m-d H:i:s')), array('id' => $user_id));
	}

	/**
	 * 로그아웃 루틴
	 * 
	 * 사용자 세션을 파괴
	 * 
	 * @access	public
	 * @return	void
	 */		
	public function logout() {
		$this->unset_autologin();
		$this->CI->session->sess_destroy();
	}
}
/* EOF */