<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Community extends MY_Controller {

	var $_thisBoard   = array();
	var $_searchParam = array();

	public function __construct(){
		parent::__construct();

		$this->load->model('content/board_m','board');
		$this->load->helper('form');
		
		$s_categoryID  = $this->input->get('s_categoryID')?$this->input->get('s_categoryID'):$this->input->post('s_categoryID');
		$s_searchType  = $this->input->get('s_searchType')?$this->input->get('s_searchType'):$this->input->post('s_searchType');
		$s_searchValue = $this->input->get('s_searchValue')?$this->input->get('s_searchValue'):$this->input->post('s_searchValue');
		$s_pageNumber  = $this->input->get('s_pageNumber')?$this->input->get('s_pageNumber'):$this->input->post('s_pageNumber');

		$searchParam = array();		
		$searchParam['s_categoryID']  = $s_categoryID;
		$searchParam['s_searchType']  = $s_searchType;
		$searchParam['s_searchValue'] = $s_searchValue;
		$searchParam['s_pageNumber']  = $s_pageNumber;

		$this->_searchParam = $searchParam;
	}

	private function setBoardData($boardID){
		try{
			$data = array();
			$this->_thisBoard = $this->_boardInfo[$boardID];
		}catch(Exception $e){
			$this->redirect('/');
		}
	}

	public function lists($boardID){
		$this->_footScript[] = 'board/list.js';		

		$search = array();
		if($this->_searchParam['s_searchType'] && $this->_searchParam['s_searchValue']){
			$search = array($this->_searchParam['s_searchType']=>$this->_searchParam['s_searchValue']);
		}

		$this->setBoardData($boardID);
		$template = $this->board->getCodeValue('BOARD_TEMPLATE',$this->_thisBoard['type']);
		$listData = $this->board->getContent($boardID,$this->_searchParam['s_categoryID'],'',$search);

		$data = array();
		$data['board']    = $this->_thisBoard;
		$data['listData'] = $listData;

		$this->load_view('board/'.$template.'/list',$data);
	}

	public function write($boardID){
		if(empty($this->_user)){
			$this->displayScript('alert("로그인이 필요함");history.back(-1);');
		}

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
		$inlineScript[] = 'var _boardID = "'.$boardID.'"';
		$inlineScript[] = 'var _boardGroup = "community"';
		$inlineScript[] = 'var _templateName = "'.$template.'"';

		$this->_inlineFootScript = implode(';',$inlineScript);

		$this->load_view('board/'.$template.'/write',$data);
	}

	public function drawData($dataID,$type='view'){
		$message = $this->board->getContentDetail($dataID);

		if(empty($message)){
			$this->returnToList('삭제된 게시글 입니다.');
		}

		$this->setBoardData($message['boardID']);
		$template = $this->board->getCodeValue('BOARD_TEMPLATE',$this->_thisBoard['type']);

		$inlineScript = array();
		$inlineScript[] = 'var _boardID = "'.$message['boardID'].'"';
		$inlineScript[] = 'var _boardGroup = "community"';
		$inlineScript[] = 'var _templateName = "'.$template.'"';

		$data = array();
		$data['board']   = $this->_thisBoard;
		$data['message'] = $message;

		$this->_inlineFootScript = implode(';',$inlineScript);

		if($type == 'modify'){
			$this->_footScript[] = 'module/smart_editor/js/HuskyEZCreator.js';
			$this->_footScript[] = 'uploader/swfupload.js';
			$this->_footScript[] = 'uploader/swfupload.plugin.js';
			$this->_footScript[] = 'editor.js';
			$this->_footScript[] = 'common/jquery.form.min.js';
			$this->_footScript[] = 'board/write.js';

			$categoryList = array();
			foreach($this->_thisBoard['category'] as $cate){
				$categoryList[] = array($cate['id'],$cate['name']);
			}

			$data['categorySel'] = makeDropdownSelect('category',$categoryList,'-머릿말-',$message['categoryID']);
		}else{
			$this->_footScript[] = 'board/view.js';
		}

		$this->load_view('board/'.$template.'/'.$type,$data);
	}

	public function view($dataID){
		$this->drawData($dataID,'view');
	}

	public function modify($dataID){
		$this->drawData($dataID,'modify');
	}

	public function save($boardID){
		$categoryID = $this->input->post('category');
		$title      = $this->input->post('title');
		$content    = $this->input->post('content');
		$imgfile    = $this->input->post('imgfile');
		$dataID     = $this->input->post('dataID');
		$userID     = $this->_user->userID;

		if($dataID){
			$rst = $this->board->modifyContent($dataID,$boardID,$categoryID,$title,$content,$imgfile);			
		}else{
			$dataID = $this->board->saveContent($boardID,$categoryID,$userID,$title,$content,$imgfile);
		}

		$rstData = array('dataID'=>$dataID);

		$this->json_view($rstData);
	}

	public function remove($boardID){
		$dataID = $this->input->post('dataID');
		$userID = $this->input->post('userID');

		if($userID != $this->_user->userID){
			$this->board->deleteContent($dataID);
		}

		$this->setBoardData($boardID);

		$this->returnToList();
	}

	private function returnToList($msg=''){
		$this->movePage('/board/community/lists/'.$this->_thisBoard['id'].'?'.http_build_query($this->_searchParam),$msg);
	}
}