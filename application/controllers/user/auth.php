<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('common/user_m','user');
	}

	public function join(){
		$this->_styleSheet[] = 'sns/google.css';
		$this->_headScript[] = 'common/gg_connect.js';
		$this->_footScript[] = 'user/join.js';

		$this->load_view('user/auth/join');
	}

	public function login(){
		$this->_styleSheet[] = 'sns/google.css';
		$this->_headScript[] = 'common/gg_connect.js';
		$this->_footScript[] = 'user/login.js';

		if($this->_user){
			$this->movePage('/');
		}
		$this->load_view('user/auth/login');
	}

	public function save($userID=''){
		$email = $this->input->post('email');
		$passwd = $this->input->post('password');
		$passwdConfirm = $this->input->post('passwordConfirm');
		$name = $this->input->post('name');

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
			$userID = $this->user->saveUser($email,$name,'','AA');
			if($userID){
				$this->user->setSNSAccount($userID,$snsID,$email,$snsType);
				$rstData = array();
				$rstData['userID'] = $userID;

				$this->json_view($rstData);
			}
		}
		
		$this->json_view(array(),500,'정확한 정보를 입력 하세요');
	}

	public function getauth(){
		$email   = $this->input->post('email');
		$passwd  = $this->input->post('passwd');
		$snsID   = $this->input->post('snsID');
		$snsType = $this->input->post('snsType');

		if($snsID && $snsType){
			$userInfo = $this->user->getUserBySNS($snsType,$snsID);
		}else{
			if(empty($email)){				
				$this->json_view(array(),500,'이메일 주소를 입력 하세요.');
			}

			if(empty($passwd)){				
				$this->json_view(array(),500,'비밀번호를 입력 하세요.');
			}
			$userInfo = $this->user->getLoginInfo($email,$passwd);
		}

		$result = $this->user->login($userInfo);
		if($result){
			$this->json_view();
		}else{
			$this->json_view(array(),500,'이메일과 비밀번호를 확인해바');
		}
	}

	public function logout(){
		$this->user->logout();
		$this->movePage('/');
	}
}
