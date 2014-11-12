<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->_styleSheet[] = 'bootstrap/blog.css';

		$this->load->model('content/board_m','board');
	}

	public function index(){
		$this->lists();
	}

	public function lists(){

		$this->load_view('admin/category/main');
	}
}