<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$this->checkLogin();

		$this->load->model('common/user_m','user');
		$this->load->library('naverapi');

		$this->useFacebookAPI = true;
		$this->useGoogleAPI   = true;
	}

	public function view(){
		$this->_footScript[] = 'user/view.js';

		$userID = $this->_user->userID;

		$userInfo = $this->user->getUserDetail($userID);

		$data = array();
		$data['userInfo'] = $userInfo;
		$data['nvurl'] = $this->naverapi->getLoginUrl('m=connect');

		$this->load_view('user/info/view',$data);
	}

	public function modify(){
		$userID             = $this->input->post('userID');
		$password           = $this->input->post('password');
		$passwordNew        = $this->input->post('passwordNew');
		$passwordConfirmNew = $this->input->post('passwordConfirmNew');
		$name               = $this->input->post('name');

		$userInfo = $this->user->getUser($userID,'',$password);

		if(empty($userInfo)){
			$this->json_view(array(),500,'기존 패스워드 확인!');
		}else{
			if($passwordNew && $passwordNew != $passwordConfirmNew){
				$this->json_view(array(),500,'새 비밀번호 맞는지 확인');
			}
		}

		$this->user->modUser($userID,$name,$passwordNew);
		$this->json_view();
	}
}