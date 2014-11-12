<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	/**
	 * @brief 배열을 HTML에서 사용할 수 있는 option tag로 만듬
	 *
	 * @param $array array(array(1,'a'),array(2,'b')...) $selected string 2
	 * @return $option string <option value="1">a</option><option value='2' selected>b</option>
	**/
	if(!function_exists('makeOptionTagByList')){
		function makeOptionTagByList($array,$selected=null){
			$option = '';

			for($i=0;$i<count($array);$i++){
				if($selected){
					if($selected == $array[$i][0]){
						$sel = 'selected';
					}else{
						$sel = '';
					}
				}else{
					$sel = '';
				}
				$option .= '<option value="'.$array[$i][0].'" '.$sel.'>'.$array[$i][1].'</option>';
			}

			return $option;
		}
	}