<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sns extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$this->load->model('common/user_m','user');
	}

	public function getGoogleAccount(){
		$snsID = $this->input->post('snsID');
		$userID = $this->_user->userID;

		$this->user->
	}
}