<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Category extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('content/board_m','board');
	}

	public function index(){
		
	}

	public function add(){
		$boardID      = $this->input->post('boardID');
		$categoryName = $this->input->post('categoryName');

		$categoryID = $this->board->addCategory($boardID,$categoryName);

		$rstData = array();
		$rstData['categoryID'] = $categoryID;

		$this->json_view($rstData);
	}

	public function modify(){
		$categoryID    = $this->input->post('categoryID');
		$categoryName  = $this->input->post('categoryName');
		$categoryOrder = $this->input->post('categoryOrder');

		$this->board->modifyCategory($categoryID,$categoryName,$categoryOrder);

		$this->json_view();
	}
}