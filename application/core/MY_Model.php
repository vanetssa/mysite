<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model {
	var $CI;

	function __construct() {
		parent::__construct();
		$this->CI =& get_instance();
	}
}