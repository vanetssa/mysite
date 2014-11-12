<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * @brief 파일명을 확장자와 순수 파일이름으로 나눔
	 *
	 * @param $fileName string xxxx.jpg xxx.zip ...
	 * @return array(name, ext)
	**/
	if (!function_exists('divideFileName')){
		function divideFileName($fileName){
			$tmp = explode(".",$fileName);
			$ext = $tmp[count($tmp)-1];
			unset($tmp[count($tmp)-1]);

			$name = implode('',$tmp);
			return array($name,$ext);
		}
	}

	/**
	 * 파일저장
	 * @param String $file 저장되어 있는 파일경로($uploadFile['tmp_name'])
	 * @param String $path 파일저장경로
	 * @param Boolean $opt 원본 삭제 여부
	 * @return String
	 */
	if (!function_exists('fileSave')){
		function fileSave($file,$path,$opt=true){
			$copy = copy($file, $path);
			if($copy){
				if($opt){
					$unlink = unlink($file);
					if($unlink){
						return 'success';
					}else{
						return 'unlink_err';
					}
				}else{
					return 'success';
				}
			}else{
				return 'copy_err';
			}
		}
	}

	/**
	 * 폴더 체크
	 * 해당 폴더가 없으면 생성
	 * @access private
	 * @param String $path 체크 및 생성할 폴더 경로
	 * @param String $addPath 해당값이 존재할경우 $path의 폴더설정한뒤 그 하위에 $addPath의 폴더를 추가로 생성한다
	 * @return Boolean
	 */
	if (!function_exists('setFolder')){
		function setFolder($path,$addPath=null){
			if(!file_exists($path)){
				if(!mkdir($path, 0777, true)) { return false; }
			}

			if(!empty($addPath)){
				if(!file_exists($path.$addPath)){
					if (!mkdir($path.$addPath, 0777, true)) { return false; }
				}
			}
			return true;
		}
	}