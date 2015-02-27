<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('common/user_m','user');
		$this->load->library('naverapi');

		$this->useFacebookAPI = true;
		$this->useGoogleAPI   = true;
	}

	public function join(){		
		$this->_footScript[] = 'user/join.js';

		$data = array();
		$data['snsType'] = '';
		$data['snsID']   = '';
		$data['email']   = '';
		$data['name']    = '';
		$data['nvurl']   = $this->naverapi->getLoginUrl('m=join');

		$accessToken = $this->input->get('tk');
		$tokenType   = $this->input->get('tt');

		if($accessToken){
			$decryptToken = $this->naverapi->decryptToken($accessToken);
			$userInfo = $this->naverapi->getUserInfo($decryptToken,$tokenType);
			if($userInfo['rst']){
				$data['snsType'] = SNS_TYPE_NAVER;
				$data['snsID']   = $userInfo['enc_id'];
				$data['email']   = $userInfo['email'];
				$data['name']    = $userInfo['nickname'];
			}
		}

		$this->load_view('user/auth/join',$data);
	}

	public function login(){		
		$this->_footScript[] = 'user/login.js';

		if($this->_user){
			$this->movePage('/');
		}

		$data = array();		
		$data['nvurl'] = $this->naverapi->getLoginUrl('m=login');

		$this->load_view('user/auth/login',$data);
	}

	public function save($userID=''){
		$email = $this->input->post('email');
		$passwd = $this->input->post('password');
		$passwdConfirm = $this->input->post('passwordConfirm');
		$name = $this->input->post('name');

		$userInfo = $this->user->getUserDetail('',$email);
		if($userInfo){
			$this->json_view(array(),500,'가입된 이메일 주소 입니다.');
		}

		if($passwd == $passwdConfirm){
			if($userID){
				$this->user->modUser($userID,$name,$passwd);
			}else{
				$userID = $this->user->saveUser($email,$name,$passwd,'AA');
			}
		}else{
			$this->json_view(array(),500,'패스워드가 일치하지 않습니다.');
		}
		
		$rstData = array();
		$rstData['userID'] = $userID;

		$this->json_view($rstData);
	}

	public function setsns(){
		$snsType = $this->input->post('snsType');
		$snsID   = $this->input->post('snsID');
		$email   = $this->input->post('email');
		$name    = $this->input->post('name');

		if($snsType && $snsID && $email && $name){
			$userInfo = $this->user->getUserDetail('',$email);
			if($userInfo){
				$userID = $userInfo['userID'];
			}else{
				$userID = $this->user->saveUser($email,$name,'','AA');
			}
			if($userID){
				$rst = $this->user->setSNSAccount($userID,$snsID,$email,$snsType);

				$rstData = array();
				$rstData['userID'] = $userID;

				$this->json_view($rstData);
			}
		}
		
		$this->json_view(array(),500,'정확한 정보를 입력 하세요');
	}

	public function getauth(){
		$email    = $this->input->post('email');
		$password = $this->input->post('password');
		$snsID    = $this->input->post('snsID');
		$snsType  = $this->input->post('snsType');

		if($snsID && $snsType){
			$userInfo = $this->user->getUserBySNS($snsType,$snsID);
			if(empty($userInfo)){
				$this->json_view(array(),500,'가입된 SNS 계정이 아닙니다.');
			}
		}else if($snsType){
			$this->json_view(array(),500,'가입된 SNS 계정이 아닙니다.');
		}else{
			if(empty($email)){				
				$this->json_view(array(),500,'이메일 주소를 입력 하세요.');
			}

			if(empty($password)){				
				$this->json_view(array(),500,'비밀번호를 입력 하세요.');
			}
			$userInfo = $this->user->getLoginInfo($email,$password);
		}

		$result = $this->user->login($userInfo);
		if($result){
			$this->json_view();
		}else{
			$this->json_view(array(),500,'이메일과 비밀번호를 확인해바');
		}
	}

	public function checksns(){
		$snsType = $this->input->post('snsType');
		$snsID   = $this->input->post('snsID');

		if(!$snsID){
			$this->json_view(array(),500,'SNS계정 확인!!!');
		}

		$userInfo = $this->user->getUserBySNS($snsType,$snsID);
		if($userInfo){
			$this->user->login($userInfo);
			$this->json_view(array(),201);
		}

		$this->json_view();
	}

	public function logout(){
		$this->user->logout();
		$this->movePage('/');
	}
}
