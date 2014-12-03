<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Draw extends MY_Controller {

	const IMAGE_FOLDER = '/webdata/img';

	const FOR_PC_WIDTH     = 800;
	const FOR_MOBILE_WIDTH = 600;
	
	public function __construct(){
		parent::__construct();

		$this->load->library('thumbnail');		
		$this->load->helper('file');
	}

	public function board($date,$fileName){
		$year  = substr($date,0,4);
		$month = substr($date,4,2);

		$folderPath = $this::IMAGE_FOLDER.'/board/'.$year.'/'.$month.'/';
		$filePath   = $folderPath.$fileName;

		if($this->_isMobile){
			$this->drawMobile($filePath);
		}else{
			$this->drawPC($filePath);
		}
	}

	private function drawPC($filePath){
		$drawPath = $this->makeThumbnail($filePath,'P');
		$this->draw($drawPath);
	}

	private function drawMobile($filePath){
		$drawPath = $this->makeThumbnail($filePath,'M');
		$this->draw($drawPath);
	}

	private function makeThumbnail($filePath,$drawType='P'){
		$pathInfo = pathInfo($filePath);

		$folderPath = $pathInfo['dirname'];

		$imgInfo = getimagesize($filePath);

		if($drawType == 'P'){
			$newPath  = $folderPath.'/'.$pathInfo['filename'].'_P.'.$pathInfo['extension'];
			$maxWidth = $this::FOR_PC_WIDTH;
		}else{
			$newPath  = $folderPath.'/'.$pathInfo['filename'].'_M.'.$pathInfo['extension'];
			$maxWidth = $this::FOR_MOBILE_WIDTH;
		}
		
		$width  = $imgInfo[0];
		$height = $imgInfo[1];

		$makeWidth  = null;
		$makeHeight = null;

		if(!file_exists($newPath)){
			if($width > $maxWidth){
				$makeWidth = $maxWidth;
			}else{
				$makeWidth  = $width;
				$makeHeight = $height;
			}
		}

		try{
			$this->thumbnail->create($filePath,$makeWidth,$makeHeight,'exactfit',array('savepath'=>$newPath));
		}catch(Exception $e){
			throw new Exception($e->getMessage().'|'.__LINE__, 99);
		}

		return $newPath;
	}
}