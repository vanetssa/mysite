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

				$decrypt_token = $this->naverapi->decryptToken($tk);					
				$userInfo = $this->naverapi->getUserInfo($decrypt_token,$tt);

				$param = $this->naverapi->parseState($cookie_state);

				$rd   = !empty($param['rd'])?urlencode($param['rd']):'';
				$mode = !empty($param['m'])?$param['m']:'';

				error_log(print_r($userInfo,true));
			}
		}

		$this->movePage('/','잘못된 접근 입니다');
	}
}