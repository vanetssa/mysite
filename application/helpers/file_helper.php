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
	 * @param String $filePath 저장되어 있는 파일경로($uploadFile['tmp_name'])
	 * @param String $path 파일저장경로
	 * @param Boolean $opt 원본 삭제 여부
	 * @return String
	 */
	if (!function_exists('fileSave')){
		function fileSave($filePath,$path,$opt=true){
			$copy = copy($filePath, $path);
			if($copy){
				if($opt){
					$unlink = unlink($filePath);
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
	 * 
	 * @param String $path 체크 및 생성할 폴더 경로
	 * @param String $addPath 해당값이 존재할경우 $path의 폴더설정한뒤 그 하위에 $addPath의 폴더를 추가로 생성한다
	 * @return Boolean
	 */
	if (!function_exists('setFolder')){
		function setFolder($path,$addPath=null){
			if(!file_exists($path)){
				if(!mkdir($path, 0775, true)) { return false; }
			}

			if(!empty($addPath)){
				if(!file_exists($path.'/'.$addPath)){
					if (!mkdir($path.'/'.$addPath, 0775, true)) { return false; }
				}
			}
			return true;
		}
	}

	/**
	 * 파일내용 작성(기존내용 삭제 후 작성)
	 * 
	 * @param String $filePath 파일경로
	 * @param String $fileData 파일내용
	 * @return void
	 */
	if(!function_exists('writeFile')){
		function writeFile($filePath,$fileData){
			if(!file_exists($filePath)){
				$fp = fopen($filePath,"w+");
				fwrite ( $fp, "$fileData" );
				fclose( $fp );
			}else{
				unlink($filePath);
				writeFile($filePath,$fileData);
			}
		}
	}

	/**
	 * 파일에 내용쓰기
	 * 파일이 없을시 새롭게 생성 이후 추가되는 내용을 줄바꿈후 저장함
	 * 
	 * @param String $filePath 파일의 경로
	 * @param String $fileData 저장할 내용
	 * @return Boolean
	 */
	if(!function_exists('addWriteFile')){
		function addWriteFile($filePath,$fileData){
			if(!file_exists($filePath)){
				$fp = fopen($filePath,"w+");
				fwrite ( $fp, "$fileData" );
				fclose( $fp );
			}else{
				$fileData = chr(13).chr(10).$fileData;
				$fp = fopen($filePath,"a+");
				fwrite ( $fp, "$fileData" );
				fclose( $fp );
			}
		}
	}

	/**
	 * 파일 내용 읽기
	 * 
	 * @param string $filePath 파일 경로
	 * @return array
	 */
	if(!function_exists('readFileInfo')){
		function readFileInfo($filePath){
			$data = array();
			if(file_exists($filePath)){
				$content = @file($filePath);
				if(!empty($content[0])){
					$data = json_decode($content[0],true);
				}
			}
			return $data;
		}
	}		