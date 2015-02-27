<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class MY_Controller extends CI_Controller{

		const EOL = "\r\n";
		
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
		var $_user     = array();

		var $useFacebookAPI = false;
		var $useGoogleAPI   = false;

		function __construct() {
			parent::__construct();

			$this->_styleSheet[] = '/bootstrap/css/bootstrap.min.css';
			$this->_styleSheet[] = '/bootstrap/css/bootstrap-theme.min.css';
			$this->_styleSheet[] = '/bootstrap/css/docs.min.css';
			$this->_styleSheet[] = '/bootstrap/css/theme.css';

			$this->_headScript[] = 'common/jquery.js';
			$this->_headScript[] = 'common/jquery.form.min.js';
			$this->_headScript[] = '/bootstrap/js/bootstrap.min.js';
			$this->_headScript[] = 'common/ie10-viewport-bug-workaround.js';
			$this->_headScript[] = 'const.js';
			$this->_headScript[] = 'common.js';

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
			$this->load->model('common/user_m','user');
			
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

			$cookie = $this->user->getCookieInfo();			
			if($cookie){
				$this->_user = $cookie;
			}

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
			echo file_get_contents($imagePath) . $this::EOL;
			exit;
		}

		protected function displayScript($script){
			$script = '<script type="text/javascript" language="JavaScript">'.$this::EOL
				. '//<![CDATA['.$this::EOL
				. $script.$this::EOL
				. '//]]>'.$this::EOL
				.'</script>'.$this::EOL;

			echo $script;
		}

		protected function displayAlert($msg){
			$script = 'alert("'.$msg.'")';
			$this->displayScript($script);
		}

		protected function movePage($url,$msg='',$set301=false){
			if(!empty($msg)){ $this->displayAlert($msg); }
			if($set301){ @header("HTTP/1.1 301 Moved Permanently"); }
			$this->displayScript("location.href='".$url."';");
			//@header('Location : '.$url);
			exit;
		}

		protected function checkLogin(){
			if(empty($this->_user)){
				$this->user->logout();
				$this->movePage('/','로그인이 필요 합니다.');
			}
		}
	}