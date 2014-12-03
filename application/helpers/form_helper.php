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

	/**
	 * 셀렉트 박스를 dropdown형식으로 만듬
	 * 
	 * @param string $name 셀렉트박스의 이름
	 * @param array $option option리스트 array(array(key,val))
	 * @param string $emptyTitle 없는 value(셀렉트박스의 타이틀)
	 * @param string $selected 셀렉티드 시킬 value값
	 * @param array $attr 기타 attribute array(key=>val)
	 * @return string
	 */
	if(!function_exists('makeDropdownSelect')){
		function makeDropdownSelect($name,$option,$emptyTitle='-선택-',$selected='',$attr=array()){
			$selValue = '';
			if($selected){
				foreach($option as $opt){
					if($opt[0] == $selected){
						$selValue = $opt[1];
						break;
					}
				}
			}

			$showSelected = ($selValue)?$selValue:$emptyTitle;

			$optionList = '
				<li role="presentation" data-name="actionBtn" data-act="selectDropdown" data-grp="'.$name.'" data-val="">
					<a role="menuitem" tabindex="-1" href="javascript:void(0)">'.$emptyTitle.'</a>
				</li>
			';
			foreach($option as $opt){
				$optionList .= '
				<li role="presentation" data-name="actionBtn" data-act="selectDropdown" data-grp="'.$name.'" data-val="'.$opt[0].'">
					<a role="menuitem" tabindex="-1" href="javascript:void(0)">'.$opt[1].'</a>
				</li>
				';
			}

			$attribute = array();
			foreach($attr as $key=>$val){
				$attribute[] = $key.'="'.$val.'"';
			}

			if($attribute){ $attribute = ' '.implode(' ',$attribute); }
			else{ $attribute = ''; }

			$dropdown = '
				<div class="dropdown" id="'.$name.'_dropdown">
					<input type="hidden" name="'.$name.'" id="'.$name.'_id"'.$attribute.'>
					<button class="btn btn-default dropdown-toggle" type="button" id="'.$name.'_dropdownMenu" data-toggle="dropdown" aria-expanded="true">
				    	'.$showSelected.'
				    	<span class="caret"></span>
				  	</button>
				  	<ul class="dropdown-menu" role="menu" aria-labelledby="'.$name.'_dropdownMenu">
				    	'.$optionList.'
				  	</ul>
				</div>
			';

			return $dropdown;
		}
	}