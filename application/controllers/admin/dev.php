<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dev extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->_styleSheet[] = 'bootstrap/blog.css';

		$this->load->model('content/board','board');
	}

	public function index(){
		$this->lists();
	}

	public function lists(){
		$this->load_view('admin/dev/list');
	}

	public function write(){
		$this->_footScript[] = 'module/smart_editor/js/HuskyEZCreator.js';
		$this->_footScript[] = 'uploader/swfupload.js';
		$this->_footScript[] = 'uploader/swfupload.plugin.js';
		$this->_footScript[] = 'editor.js';
		$this->_footScript[] = 'common/jquery.form.min.js';
		$this->_footScript[] = 'admin/dev/write.js';

		$this->load_view('admin/dev/write');
	}

	public function view(){
		
	}

	public function modify(){
		
	}

	public function save(){
		$title   = $this->input->post('title');
		$content = $this->input->post('content');
		$imgfile = $this->input->post('imgfile');

		$boardID    = 1;
		$categoryID = 1;
		$userID     = 1000000000;

		$this->board->saveContent($boardID,$categoryID,$userID,$title,$content,$imgfile);

		$this->json_view();
	}
}