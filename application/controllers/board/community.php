<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Community extends MY_Controller {

	var $_thisBoard = array();

	public function __construct(){
		parent::__construct();

		$this->load->model('content/board_m','board');
		$this->load->helper('form');
	}

	private function setBoardData($boardID){		
		try{
			$data = array();
			$this->_thisBoard = $this->_boardInfo[$boardID];
		}catch(Exception $e){
			$this->redirect('/');
		}
	}

	public function write($boardID){
		$this->_footScript[] = 'module/smart_editor/js/HuskyEZCreator.js';
		$this->_footScript[] = 'uploader/swfupload.js';
		$this->_footScript[] = 'uploader/swfupload.plugin.js';
		$this->_footScript[] = 'editor.js';
		$this->_footScript[] = 'common/jquery.form.min.js';
		$this->_footScript[] = 'board/write.js';

		$this->setBoardData($boardID);
		$template = $this->board->getCodeValue('BOARD_TEMPLATE',$this->_thisBoard['type']);

		$categoryList = array();
		foreach($this->_thisBoard['category'] as $cate){
			$categoryList[] = array($cate['id'],$cate['name']);
		}

		$data = array();
		$data['board'] = $this->_thisBoard;
		$data['categorySel'] = makeDropdownSelect('category',$categoryList,'-머릿말-');

		$inlineScript = array();
		$inlineScript[] = 'var _boardGroup = "community"';
		$inlineScript[] = 'var _templateName = "'.$template.'"';

		$this->_inlineFootScript = implode(';',$inlineScript);

		$this->load_view('board/'.$template.'/write',$data);
	}

	public function view($dataID){
		$data = $this->board->getContent('','',$dataID);
	}

	public function lists($boardID){
		$s_categoryID  = $this->input->get('s_categoryID');
		$s_searchType  = $this->input->get('s_searchType');
		$s_searchValue = $this->input->get('s_searchValue');
		$s_pageNumber  = $this->input->get('s_pageNumber');

		$searchParam = array();
		$searchParam['s_categoryID']  = $s_categoryID;
		$searchParam['s_searchType']  = $s_searchType;
		$searchParam['s_searchValue'] = $s_searchValue;
		$searchParam['s_pageNumber']  = $s_pageNumber;

		$search = array();
		if($s_searchType && $s_searchValue){ $search = array($s_searchType=>$s_searchValue); }

		$this->setBoardData($boardID);
		$template = $this->board->getCodeValue('BOARD_TEMPLATE',$this->_thisBoard['type']);
		$listData = $this->board->getContent($boardID,$s_categoryID,'',$search);

		$data = array();
		$data['board']       = $this->_thisBoard;
		$data['listData']    = $listData;
		$data['searchParam'] = $searchParam;

		$this->load_view('board/'.$template.'/list',$data);
	}

	public function modify($boardID){
	}

	public function save($boardID){
		$categoryID = $this->input->post('category');
		$title      = $this->input->post('title');
		$content    = $this->input->post('content');
		$imgfile    = $this->input->post('imgfile');

		$userID     = 1000000000;

		$this->board->saveContent($boardID,$categoryID,$userID,$title,$content,$imgfile);

		$this->json_view();
	}
}