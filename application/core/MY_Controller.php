<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class MY_Controller extends CI_Controller{
		
		var $_styleSheet = array();
		var $_headScript = array();
		var $_footScript = array();
		var $_inlineHeadScript = '';
		var $_inlineFootScript = '';
		var $_title = '';
		var $_meta = array();

		function __construct() {
			parent::__construct();

			$this->_styleSheet[] = 'bootstrap/bootstrap.min.css';
			
			$this->_headScript[] = 'common/jquery.js';
			$this->_headScript[] = 'bootstrap/bootstrap.min.js';			

			$this->_title = 'van site';

			$this->_meta[] = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
			$this->_meta[] = '<meta http-equiv="X-UA-Compatible" content="IE=edge">';
    		$this->_meta[] = '<meta name="viewport" content="width=device-width, initial-scale=1">';
    		$this->_meta[] = '<meta name="description" content="">';
    		$this->_meta[] = '<meta name="author" content="">';
		}

		protected function load_view($template,$param=array(),$h=true,$f=true){
			if($h){ $this->load->view('common/header'); }
			$this->load->view($template,$param);
			if($h){ $this->load->view('common/footer'); }
		}
	}