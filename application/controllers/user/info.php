<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends MY_Controller {
	public function __construct(){
		parent::__construct();

		$this->checkLogin();

		$this->load->model('common/user_m','user');
	}

	public function view(){
		$this->_styleSheet[] = 'sns/google.css';
		$this->_headScript[] = 'common/gg_connect.js';
		$this->_headScript[] = 'common/fb_connect.js';
		$this->_headScript[] = 'common/fb_function.js';
		$this->_footScript[] = 'user/view.js';

		$this->load_view('user/info/view');
	}
}