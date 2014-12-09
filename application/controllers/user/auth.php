<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('common/user_m','user');
	}

	public function join(){
		$this->_footScript[] = 'user/join.js';

		$this->load_view('user/auth/join');
	}

	public function login(){
		if($this->_user){
			$this->movePage('/');
		}
		$this->load_view('user/auth/login');
	}

	public function save($userID=''){
		$email = $this->input->post('email');
		$passwd = $this->input->post('passwd');
		$passwdConfirm = $this->input->post('passwdConfirm');
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

	public function getauth(){
		$email  = $this->input->post('email');
		$passwd = $this->input->post('passwd');

		if(empty($email)){
			$this->movePage('/','이메일 주소를 입력 하세요.');
		}

		if(empty($passwd)){
			$this->movePage('/','비밀번호를 입력 하세요.');
		}

		$result = $this->user->login($email,$passwd);
		if($result){
			$this->movePage('/');
		}else{
			$this->movePage('/','이메일과 비밀번호를 확인해바');
		}
	}

	public function logout(){		
		$this->user->logout();
		$this->movePage('/');
	}
}