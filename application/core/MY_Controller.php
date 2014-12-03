<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class MY_Controller extends CI_Controller{
		
		var $_styleSheet = array();
		var $_headScript = array();
		var $_footScript = array();
		var $_boardInfo  = array();
		var $_community  = array();

		var $_inlineHeadScript = '';
		var $_inlineFootScript = '';
		
		var $_title = '';
		var $_meta = array();

		var $_isMobile = false;
		var $_agent    = '';

		function __construct() {
			parent::__construct();

			$this->_styleSheet[] = 'bootstrap/bootstrap.min.css';
			$this->_styleSheet[] = 'bootstrap/bootstrap-theme.min.css';
			$this->_styleSheet[] = 'bootstrap/docs.min.css';
			$this->_styleSheet[] = 'bootstrap/theme.css';

			$this->_footScript[] = 'common/jquery.js';
			$this->_footScript[] = 'common/jquery.form.min.js';
			$this->_footScript[] = 'bootstrap/bootstrap.min.js';
			$this->_footScript[] = 'common/ie10-viewport-bug-workaround.js';
			$this->_footScript[] = 'common.js';

			$this->_title = 'van site';

			$this->_meta[] = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			$this->_meta[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    		$this->_meta[] = '<meta name="viewport" content="width=device-width, initial-scale=1">';
    		$this->_meta[] = '<meta name="description" content="">';
    		$this->_meta[] = '<meta name="author" content="">';

			$this->load->library('user_agent');

    		$this->dbm = $this->load->database('DBM',true);
			$this->dbs = $this->load->database('DBS',true);

			$this->load->model('content/board_m','board');

			$boardList = $this->board->getBoardFromFile();
			$boardInfo = array();
			$community = array();

			if(!empty($boardList)){
				foreach($boardList as $board){
					$boardInfo[$board['id']] = $board;
					if($board['type'] == 'NM'){
						$community[] = array($board['name'],'/board/community/lists/'.$board['id']);
					}
				}
			}

			$this->_boardInfo = $boardInfo;
			$this->_community = $community;

			$this->checkAgent();
		}

		private function checkAgent(){
			if($this->agent->is_browser()){
				$isMobile = false;
				$agent = $this->agent->browser().' '.$this->agent->version();
			}elseif ($this->agent->is_robot()){
				$isMobile = false;
		    	$agent = $this->agent->robot();
			}elseif ($this->agent->is_mobile()){
				$isMobile = true;
		    	$agent = $this->agent->mobile();
		    }else{
		    	$isMobile = false;
		    	$agent = 'Unidentified User Agent';
			}

			$this->_isMobile = $isMobile;
			$this->_agent    = $agent;
		}

		protected function load_view($template,$param=array(),$h=true,$f=true){
			if($h){ $this->load->view('common/header'); }
			$this->load->view($template,$param);
			if($f){ $this->load->view('common/footer'); }
		}

		protected function json_view($data=array(),$status=200,$msg='success'){
			$rstData = array();
			$rstData['status'] = $status;
			$rstData['msg']    = $msg;
			$rstData['data']   = $data;

			@header('Content-type: application/json');
			echo json_encode($rstData);
			exit;
		}

		protected function draw($imagePath,$ext=''){
			$path = pathinfo($imagePath);
			if (empty($ext)){ $ext = strtolower($path['extension']); }
			$file_size = filesize($imagePath);

			switch ($ext) {
				case "gif": $ctype="image/gif"; break;
				case "png": $ctype="image/png"; break;
				case "jpeg":
				case "jpg": $ctype="image/jpeg"; break;
				default: $ctype="application/force-download";
			}

			@header('Content-type: '.$ctype);
			@header('Content-Length: '.$file_size);
			echo file_get_contents($imagePath) . "\r\n";
			exit;
		}

		protected function movePage($url,$set301=flase){
			if($set301){ @header("HTTP/1.1 301 Moved Permanently"); }
			@header('Location : '.$url);
			exit;
		}
	}