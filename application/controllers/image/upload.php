<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends MY_Controller {
	
	public function __construct(){
		parent::__construct();
		$this->IMAGE_TEMP_DIR = getenv('DOCUMENT_ROOT').'/img/board_tmp/';

		$this->load->helper('file');
	}

	public function temp(){
		$file = $_FILES['Filedata'];
		$tmpinfo = pathinfo($file['tmp_name']);

		$nameInfo = divideFileName($file['name']);

		$orgName = $nameInfo[0];
		$extName = $nameInfo[1];

		$newName = uniqid().date('YmdHis').'.'.$extName;

		fileSave($file['tmp_name'],$this->IMAGE_TEMP_DIR.$newName);

		$fileName = $file['name'];
		$fileSize = $file['size'];
		$fileExt  = $nameInfo[1];
		$fileUrl  = '/img/board_tmp/'.$newName;

		$rstData = array();
		$rstData['name'] = $fileName;
		$rstData['size'] = $fileSize;
		$rstData['temp'] = $newName;
		$rstData['ext']  = $fileExt;
		$rstData['url']  = $fileUrl;

		$this->json_view($rstData);
	}
}