<?php

/**
 * 네이버 API 클래스
 * @author tourtipsvan@tourtips.com
 * @date 2014-06-10
 * @version 1.0
 */

class NaverApi extends DataBaseAction{	
	
	const COOKIE_NAME   = 'TTNV';
	const COOKIE_KEY    = '0814401c61a40e2c33b8ae0ff6e50e544a1ea0df';//sha1('@xndjxlqtmspdlqj');
	const COOKIE_DOMAIN = 'tourtips.com';
	const COOKIE_STATE  = 'TTNVSTATE';
	const STATE_KEY     = 'f8d80e2193d10898a008e3a4ed719ccf5ac1ce95';//sha1('@xnxlqspdlqjtmxpdlxm');
	const TOKEN_KEY     = '6013cbe6307a6c21682419332dcc0c91f8d8aadf';//sha1('@xnxlqxhzmszl');
	
	//const CLIENT_ID        = 'jcmCRhIgOlqCEqgbsozu';
	//const CLIENT_SECRET    = 'BNc2vCQIiw';

	const CLIENT_ID        = '35FSWJjWjonhoekalxSc';
	const CLIENT_SECRET    = 'vf63fcjdBR';
	
	const AUTH_LOGIN_URL   = 'https://nid.naver.com/oauth2.0/authorize';
	const GET_TOKEN_URL    = 'https://nid.naver.com/oauth2.0/token';	
	const GET_USER_XML_URL = 'https://apis.naver.com/nidlogin/nid/getUserProfile.xml';
	
	const TOURTIPS_CALLBACK_URL   = 'https://www.tourtips.com/ap/members/nv/';
	const TOURTIPS_CALLBACK_URL_M = 'https://m.tourtips.com/ap/members/nv/';
	
	/**
	 * 연동할 email의 유효성 체크
	 * 
	 * @access public
	 * @param String $email
	 * @return Boolean
	 * */
	public static function checkEmailValidation($email){
		if(strpos($email,'@naver.com') === false){
			return false;
		}else{
			$tmp = explode('@',$email);
			$domain = $tmp[1];
			if($domain == 'naver.com'){
				return true;
			}else{
				return false;
			}
		}
	}
	
	/**
	 * state값 생성
	 * 
	 * @access public
	 * @param String $param 로그인 및 인증을 위한 페이지 이동시 포함할 데이터
	 * @return String
	 * */
	public static function makeState($param){
		$mt   = microtime();
		$rand = mt_rand();
		$head = md5($mt.$rand);
		
		$str = $head.';'.$param;
		$state = base64_encode(MCryptUtil::encrypt($str, self::STATE_KEY));
		$state = preg_replace('/\+/','-',$state);
		//$state = MCryptUtil::encrypt($str, self::STATE_KEY);
		return $state;
	}
	
	/**
	 * state값 parse
	 * 
	 * @access public
	 * @param String $state
	 * @return String
	 * */
	public static function parseState($state){
		$state = preg_replace('/\-/','+',$state);
		$state = MCryptUtil::decrypt(base64_decode($state), self::STATE_KEY);
		//$state = MCryptUtil::decrypt($state, self::STATE_KEY);

		$pos = strpos($state,';');		
		$str = substr($state,$pos+1);
		
		$rst = array();
		$tmp = explode('|',$str);
		$cnt = count($tmp);
		for($i=0;$i<$cnt;$i++){
			$pos = strpos($tmp[$i],'=');
			if($pos === false){
				//pass
			}else{
				$key = substr($tmp[$i],0,$pos);
				$val = substr($tmp[$i],$pos+1);				
				$rst[$key] = !empty($val)?$val:'';
			}
		}
		
		return $rst;
	}
	
	/**
	 * 토큰 암호화
	 * 
	 * @access public
	 * @param String $str 암호화할 데이터
	 * @return String
	 * */
	public static function encryptToken($str){
		$data = base64_encode(MCryptUtil::encrypt($str, self::TOKEN_KEY));
		$data = preg_replace('/\+/','-',$data);
		return $data;
	}
	
	/**
	 * 토큰 암호화 해제
	 * 
	 * @access public
	 * @param String $str 암호 해제할 데이터
	 * @return String
	 * */
	public static function decryptToken($str){
		$data = preg_replace('/\-/','+',$str);
		$data = MCryptUtil::decrypt(base64_decode($data), self::TOKEN_KEY);
		return $data;
	}
	
	/**
	 * 네이버 로그인 연동을 위한 로그인 url생성
	 * 
	 * @access public
	 * @param String $param 인증후 돌아올 url에 첨부할 데이터 ex)rd=www.tourtips.com&step=join
	 * @param Boolean $isMobile 모바일 여부	 
	 * @return String
	 * */
	public static function getLoginUrl($param=null,$isMobile=false){
		if(empty($param)){ $param = 'rd=';}		
		
		$state = self::makeState($param);
				
		self::bakeStateCookie($state);
				
		$callBackUrl = self::TOURTIPS_CALLBACK_URL;
		if(!empty($isMobile)){ $callBackUrl = self::TOURTIPS_CALLBACK_URL_M; }
		$callUrl = self::AUTH_LOGIN_URL.'?client_id='.self::CLIENT_ID.'&state='.$state.'&response_type=code&redirect_uri='.$callBackUrl;
		
		return $callUrl;
	}
	
	/**
	 * 로그인(인증)코드 가져오기
	 * 
	 * @access public	  
	 * @param String $param 인증후 돌아올 url에 첨부할 데이터 ex)rd=www.tourtips.com&step=join
	 * @param Boolean $isMobile 모바일 여부
	 * */
	public static function getAuthCode($param=null,$isMobile=false){
		$loginUrl = self::getLoginUrl($param,$isMobile);
		header('Location: ' . $loginUrl);
		exit;
	}
	
	/**
	 * STATE Cookie 생성
	 * 
	 * @access public
	 * */
	public static function bakeStateCookie($state){
		Cookie::bakeCookie(self::COOKIE_STATE, $state, self::COOKIE_DOMAIN);		
	}
	
	/**
	 * STATE Cookie값 가져오기
	 * 
	 * @access public
	 * @return String
	 * */
	public static function getStateCookie(){
		return Cookie::getCookie(self::COOKIE_STATE);
	}
	
	/**
	 * STAT Cookie값 초기화
	 * 
	 * @access public
	 * */
	public static function clearStateCookie(){
		Cookie::bakeCookie(self::COOKIE_STATE, '', self::COOKIE_DOMAIN);
	}
	
	/**
	 * 코드값으로 TOKEN가져오기
	 * 
	 * @access public
	 * @param String $code 로그인(인증)완료시 얻는 코드값
	 * @param String $state 로그인(인증) url생성시 만든 state값
	 * @return Array 
	 * */
	public static function getToken($code,$state){
		$callUrl = self::GET_TOKEN_URL.'?grant_type=authorization_code&client_id='.self::CLIENT_ID.'&client_secret='.self::CLIENT_SECRET.'&code='.$code.'&state='.$state;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $callUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);

		curl_close($ch);

		$data = json_decode($output,true);
		
		$rdata = array();
		
		if(empty($data['error'])){
			$rdata['rst']  = true;
			$rdata['access_token']      = $data['access_token'];
			$rdata['refresh_token']     = $data['refresh_token'];
			$rdata['token_type']        = $data['token_type'];
			$rdata['expires_in']        = $data['expires_in'];
			$rdata['expire_time']       = time()+$data['expires_in'];
			$rdata['error']             = '';
			$rdata['error_description'] = '';			
		}else{
			$rdata['rst']               = false;
			$rdata['access_token']      = '';
			$rdata['refresh_token']     = '';
			$rdata['token_type']        = '';
			$rdata['expires_in']        = '';
			$rdata['expire_time']       = 0;
			$rdata['error']             = !empty($data['error'])?$data['error']:'';
			$rdata['error_description'] = !empty($data['error_description'])?$data['error_description']:'';
		}
		
		return $rdata;
	}
	
	/**
	 * 토큰으로 사용자 정보 가져오기
	 * 
	 * @access public
	 * @param String $token 토큰 값
	 * @param String $type 토큰 타입
	 * @return Array
	 * */
	public static function getUserInfo($token,$type){
		$calUrl = self::GET_USER_XML_URL;
		
		$ch = curl_init();
			
		curl_setopt($ch, CURLOPT_URL, $calUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.$type.' '.$token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		
		$xmlObj = new XMLObjectParser('UTF-8', $output);
		$obj = $xmlObj->getObject();
		
		$rdata = array();		
		$rdata['code']    = $obj->DATA->RESULT->RESULTCODE;
		$rdata['message'] = $obj->DATA->RESULT->MESSAGE;
		
		if($rdata['code'] == '00'){
			$rdata['rst']           = true;
			$rdata['email']         = $obj->DATA->RESPONSE->EMAIL;
			$rdata['nickname']      = $obj->DATA->RESPONSE->NICKNAME;
			$rdata['enc_id']        = $obj->DATA->RESPONSE->ENC_ID;
			$rdata['profile_image'] = str_replace('?type=s80','',$obj->DATA->RESPONSE->PROFILE_IMAGE);
			$rdata['age']           = $obj->DATA->RESPONSE->AGE;
			$rdata['birthday']      = $obj->DATA->RESPONSE->BIRTHDAY;
			$rdata['gender']        = $obj->DATA->RESPONSE->GENDER;
		}else{
			$rdata['rst']           = false;
			$rdata['email']         = '';
			$rdata['nickname']      = '';
			$rdata['enc_id']        = '';
			$rdata['profile_image'] = '';
			$rdata['age']           = '';
			$rdata['birthday']      = '';
			$rdata['gender']        = '';
		}

		return $rdata;
	}
	
	/**
	 * 토큰 및 refresh토큰 쿠키에 저장
	 * 
	 * @access public
	 * @param String $token 토큰값
	 * @param String $r_token 토큰 갱신에 필요한 토큰값
	 * @param String $type 토큰 타입
	 * @param Integer $expire 토큰 유효시간(초 time()+expires_in)
	 * */
	public static function saveToCookie($token,$r_token,$type,$expire){
		$str = $token.'|'.$r_token.'|'.$type.'|'.$expire;
		$info = base64_encode(MCryptUtil::encrypt($str, self::COOKIE_KEY));
		Cookie::bakeCookie(self::COOKIE_NAME, $info, self::COOKIE_DOMAIN);
	}
	
	/**
	 * 쿠키에서 토큰 정보 가져오기
	 * 
	 * @access public
	 * @return Array;
	 * */
	public static function loadFromCookie(){
		$cookie_var = Cookie::getCookie(self::COOKIE_NAME);
		$data = MCryptUtil::decrypt(base64_decode($cookie_var), self::COOKIE_KEY);
		
		$rdata = array();
		$rdata['rst']     = false;
		$rdata['token']   = '';
		$rdata['r_token'] = '';
		$rdata['type']    = '';
		$rdata['expire']  = '';
		
		if(!empty($data)){
			$data = explode('|',$data);
			
			if(count($data) === 4){
				$rdata['rst']     = true;
				$rdata['token']   = $data[0];
				$rdata['r_token'] = $data[1];
				$rdata['type']    = $data[2];
				$rdata['expire']  = $data[3];
			}
		}
		
		return $rdata;
	}
	
	/**
	 * 네이버 로그인 진행하기
	 * 
	 * @access public
	 * @param String $redirect 로그인 완료후 이동할 url
	 * */
	public static function doLogin($redirect=''){
		$code  = Request::getGet('code');
		$state = Request::getGet('state');
		
		if(empty($code) || empty($state)){
			self::getAuthCode($redirect);
		}else if(!empty($code) && !empty($state)){
			$cookie_state = self::getStateCookie();
			self::clearStateCookie();
			if($state == $cookie_state){
				$tokenData = self::getToken($code,$state);
				if($tokenData['rst']){
					$userInfo = self::getUserInfo($tokenData['access_token'],$tokenData['token_type']);
					
					$param = self::parseState($cookie_state);
					
					$rd = !empty($param['rd'])?$param['rd']:'';
					
					$location = '/ap/members/landing/?lt=NV&ni='.$userInfo['enc_id'].'&tk='.$tokenData['access_token'].'&t_type='.$tokenData['token_type'].'&redirect='.$rd;
					header('Location : '.$location);
					exit;			
				}
			}
		}
	}	
}
?>