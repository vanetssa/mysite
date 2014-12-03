<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Board extends MY_Controller {
	
	public function __construct(){
		parent::__construct();

		$this->load->model('content/board_m','board');
	}

	public function index(){
		$this->lists();
	}

	public function write(){		
		$this->_footScript[] = 'admin/board/write.js';

		$this->load->helper('form');

		$data = array();
		$data['boardTypeOpt'] = makeOptionTagByList($this->board->BOARD_TYPE);
		$data['fileSizeOpt']  = makeOptionTagByList($this->board->FILE_SIZE_LIST);
		$data['fileCountOpt'] = makeOptionTagByList($this->board->FILE_COUNT_LIST);

		$this->_inlineHeadScript = 'var _saveMode = "write";';

		$this->load_view('admin/board/write',$data);
	}

	public function view($boardID,$type=''){
		$this->_footScript[] = 'admin/board/view.js';

		$data = array();
		$data['boardData']  = $this->board->getBoardDetail($boardID);
		$data['searchType'] = $type;

		$this->load_view('admin/board/view',$data);	
	}

	public function lists(){
		$this->_footScript[] = 'admin/board/list.js';
		
		$type = $this->input->get('type');

		$data = array();
		$data['listData'] = $this->board->getBoardList($type);
		$data['searchType'] = $type;

		$this->load_view('admin/board/list',$data);
	}

	public function modify($boardID){		
		$this->_footScript[] = 'admin/board/write.js';

		$this->load->helper('form');

		$type = $this->input->get('type');

		$data = array();
		$data['boardData']      = $this->board->getBoardDetail($boardID);
		$data['searchType']     = $type;
		$data['boardTypeOpt']   = makeOptionTagByList($this->board->BOARD_TYPE,$data['boardData']['type']);
		$data['fileSizeOpt']    = makeOptionTagByList($this->board->FILE_SIZE_LIST,$data['boardData']['fileSize']);
		$data['fileCountOpt']   = makeOptionTagByList($this->board->FILE_COUNT_LIST,$data['boardData']['fileCount']);
		$data['boardStatusOpt'] = makeOptionTagByList($this->board->BOARD_STATUS,$data['boardData']['status']);

		$this->_inlineHeadScript = 'var _saveMode = "modify"; var _boardID = "'.$boardID.'"; var _searchType = "'.$type.'"';

		$this->load_view('admin/board/modify',$data);
	}

	public function save($boardID=null){
		$type         = $this->input->post('type');
		$name         = $this->input->post('name');
		$desc         = $this->input->post('desc');
		$useComment   = $this->input->post('useComment');
		$useRecommend = $this->input->post('useRecommend');
		$useFile      = $this->input->post('useFile');
		$fileSize     = $this->input->post('fileSize');
		$fileCount    = $this->input->post('fileCount');

		$desc         = !empty($desc)?trim($desc):'';
		$useComment   = !empty($useComment)?$useComment:'N';
		$useRecommend = !empty($useRecommend)?$useRecommend:'N';
		$useFile      = !empty($useFile)?$useFile:'N';
		$fileSize     = !empty($fileSize)?$fileSize:0;
		$fileCount    = !empty($fileCount)?$fileCount:0;

		if($boardID){
			$status = $this->input->post('status');
			$this->board->modifyBoard($boardID,$type,$name,$desc,$useComment,$useRecommend,$useFile,$fileSize,$fileCount,$status);
		}else{
			$boardID = $this->board->saveBoard($type,$name,$desc,$useComment,$useRecommend,$useFile,$fileSize,$fileCount);
		}

		$rstData = array();
		$rstData['boardID'] = $boardID;

		$this->json_view($rstData);
	}

	public function tofile(){
		$this->board->saveToFile();
		$this->movePage('/admin/board');
	}
}