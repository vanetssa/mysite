<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sns extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('common/user_m','user');
		$this->load->library('naverapi');
	}

	public function naver(){
		$code  = $this->input->get('code');
		$state = $this->input->get('state');

		if(!empty($code) && !empty($state)){
			$cookie_state = $this->naverapi->getStateCookie();
			$this->naverapi->clearStateCookie();

			if($state == $cookie_state){
				$tokenData = $this->naverapi->getToken($code,$state);

				$access_token = $tokenData['access_token'];
				$token_type   = $tokenData['token_type'];

				$tk = $this->naverapi->encryptToken($access_token);
				$tt = $token_type;

				$param = $this->naverapi->parseState($cookie_state);

				$rd   = !empty($param['rd'])?urlencode($param['rd']):'';
				$mode = !empty($param['m'])?$param['m']:'';

				if($mode == 'join'){
					$param = array();
					$param['rd'] = $rd;
					$param['tk'] = $tk;
					$param['tt'] = $tt;
					$url = '/user/auth/join?'.http_build_query($param);
					$this->movePage($url);
				}else if($mode == 'connect'){
					$userInfo = $this->naverapi->getUserInfo($access_token,$token_type);
					if($userInfo['rst']){
						$this->setInfo(SNS_TYPE_NAVER,$userInfo['enc_id'],$userInfo['email']);
					}else{
						$this->movePage('/user/info/view','네이버 로그인을 확인하세요.');
					}
				}else if($mode == 'login'){
					$userInfo = $this->naverapi->getUserInfo($access_token,$token_type);
					if($userInfo['rst']){
						$userInfo = $this->user->getUserBySNS(SNS_TYPE_NAVER,$userInfo['enc_id']);						
						$rst = $this->user->login($userInfo);
						if($rst){
							$this->movePage('/');
						}else{
							$this->movePage('/user/auth/login','가입된 네이버 계정이 아닙니다.');
						}
					}else{
						$this->movePage('/user/auth/login','네이버 로그인을 확인하세요.');
					}
				}
			}
		}

		$this->movePage('/','잘못된 접근 입니다');
	}

	public function connect(){
		$snsType  = $this->input->post('snsType');
		$snsID    = $this->input->post('snsID');
		$snsEmail = $this->input->post('email');
		$this->setInfo($snsType,$snsID,$snsEmail);
	}

	public function disconnect(){
		$type = $this->input->post('type');
		$this->user->disconnectSNSAccount($this->_user->userID,$type);
		$this->json_view();
	}

	private function setInfo($type,$id,$email){
		$rst = $this->user->setSNSAccount($this->_user->userID,$id,$email,$type);
		if($rst['rst'] == 'new' || $rst['rst'] == 'recovery'){
			$status = 200;
			$msg    = '연결 되었습니다.';
		}else if($rst['rst'] == 'exist'){
			$status = 500;
			$msg    = '이미 연결된 계정입니다.';
		}else if($rst['rst'] == 'other'){
			$status = 500;
			$msg    = '다른 사용자와 연결된 계정입니다.';
		}

		if($this->input->is_ajax_request()){
			$this->json_view(array(),$status,$msg);
		}else{
			$this->movePage('/user/info/view',$msg);
		}
	}
}