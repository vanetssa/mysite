<?php

/**
 * 네이버 API 클래스 
 * @date 2014-12-26
 * @version 1.0
 */

class Naverapi{
	
	const COOKIE_NAME   = 'VANNV';
	const COOKIE_KEY    = '172a601b8042e4d685cdba73bdfdbc7d748a9aca';//sha1('vansitespdlqjznzl1!');
	const COOKIE_DOMAIN = 'vanetssa.com';
	const COOKIE_STATE  = 'VANNVSTATE';
	const STATE_KEY     = '5ba5836274f88f6a91eef1b5dba3948bddefdcaa';//sha1('vansitetmxpdlxmzl1!');
	const TOKEN_KEY     = 'fce2d8f9a886522e7c44cae756a5a8ca1dcaa8ff';//sha1('vansitexhzmszl1!');
	
	const CLIENT_ID        = 'jcmCRhIgOlqCEqgbsozu';
	const CLIENT_SECRET    = 'QqM2StE2P7';
	
	const AUTH_LOGIN_URL   = 'https://nid.naver.com/oauth2.0/authorize';
	const GET_TOKEN_URL    = 'https://nid.naver.com/oauth2.0/token';	
	const GET_USER_XML_URL = 'https://apis.naver.com/nidlogin/nid/getUserProfile.xml';

	var $_CALLBACK_URL = '';
	
	function __construct(){
		$this->_CALLBACK_URL = 'http://'.DOMAIN_URL.'/user/sns/naver';

		$CI =& get_instance();
		$CI->load->library('encrypt');
		$this->input = $CI->input;
		$this->encrypt = $CI->encrypt;
	}
	
	/**
	 * state값 생성
	 * 
	 * @access public
	 * @param String $param 로그인 및 인증을 위한 페이지 이동시 포함할 데이터
	 * @return String
	 * */
	public function makeState($param){
		$mt   = microtime();
		$rand = mt_rand();
		$head = md5($mt.$rand);
		
		$str = $head.';'.$param;
		$state = base64_encode($this->encrypt->encode($str, $this::STATE_KEY));
		$state = preg_replace('/\+/','-',$state);
		return $state;
	}
	
	/**
	 * state값 parse
	 * 
	 * @access public
	 * @param String $state
	 * @return String
	 * */
	public function parseState($state){
		$state = preg_replace('/\-/','+',$state);
		$state = $this->encrypt->decode(base64_decode($state), $this::STATE_KEY);

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
	public function encryptToken($str){
		$data = base64_encode($this->encrypt->encode($str, $this::TOKEN_KEY));
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
	public function decryptToken($str){
		$data = preg_replace('/\-/','+',$str);
		$data = $this->encrypt->decode(base64_decode($data), $this::TOKEN_KEY);
		return $data;
	}
	
	/**
	 * 네이버 로그인 연동을 위한 로그인 url생성
	 * 
	 * @access public
	 * @param String $param 인증후 돌아올 url에 첨부할 데이터 ex)rd=www.tourtips.com&step=join
	 * @return String
	 * */
	public function getLoginUrl($param=null){
		if(empty($param)){ $param = 'rd=';}
		
		$state = $this->makeState($param);
				
		$this->bakeStateCookie($state);

		$callBackUrl = $this->_CALLBACK_URL;
		$callUrl = $this::AUTH_LOGIN_URL.'?client_id='.$this::CLIENT_ID.'&state='.$state.'&response_type=code&redirect_uri='.$callBackUrl;

		return $callUrl;
	}
	
	/**
	 * 로그인(인증)코드 가져오기
	 * 
	 * @access public	  
	 * @param String $param 인증후 돌아올 url에 첨부할 데이터 ex)rd=www.tourtips.com&step=join
	 * @param Boolean $isMobile 모바일 여부
	 * */
	public function getAuthCode($param=null,$isMobile=false){
		$loginUrl = $this->getLoginUrl($param,$isMobile);
		header('Location: ' . $loginUrl);
		exit;
	}
	
	/**
	 * STATE Cookie 생성
	 * 
	 * @access public
	 * */
	public function bakeStateCookie($state){
		$cookie = array(
			'name'   => $this::COOKIE_STATE,
			'value'  => $state,
			'expire' => 0,
			'domain' => LOGIN_COOKIE_DOMAIN,
			'path'   => '/',
			'prefix' => '',
			'secure' => FALSE
		);
		$this->input->set_cookie($cookie);
	}
	
	/**
	 * STATE Cookie값 가져오기
	 * 
	 * @access public
	 * @return String
	 * */
	public function getStateCookie(){
		return $this->input->cookie($this::COOKIE_STATE);
	}
	
	/**
	 * STAT Cookie값 초기화
	 * 
	 * @access public
	 * */
	public function clearStateCookie(){
		$cookie = array(
			'name'   => $this::COOKIE_STATE,
			'value'  => '',
			'expire' => 0,
			'domain' => LOGIN_COOKIE_DOMAIN,
			'path'   => '/',
			'prefix' => '',
			'secure' => FALSE
		);
		$this->input->set_cookie($cookie);
	}
	
	/**
	 * 코드값으로 TOKEN가져오기
	 * 
	 * @access public
	 * @param String $code 로그인(인증)완료시 얻는 코드값
	 * @param String $state 로그인(인증) url생성시 만든 state값
	 * @return Array 
	 * */
	public function getToken($code,$state){
		$callUrl = $this::GET_TOKEN_URL.'?grant_type=authorization_code&client_id='.$this::CLIENT_ID.'&client_secret='.$this::CLIENT_SECRET.'&code='.$code.'&state='.$state;
		
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
	public function getUserInfo($token,$type){
		$calUrl = $this::GET_USER_XML_URL;
		
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $calUrl);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: '.$type.' '.$token));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);

		$this->load->library('xmlparser',$output);

		$obj = $this->xmlparser->array;

		/*
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
		*/
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
	public function saveToCookie($token,$r_token,$type,$expire){
		$str = $token.'|'.$r_token.'|'.$type.'|'.$expire;
		$info = base64_encode($this->encrypt->encode($str, $this::COOKIE_KEY));
		Cookie::bakeCookie($this::COOKIE_NAME, $info, $this::COOKIE_DOMAIN);
	}
	
	/**
	 * 쿠키에서 토큰 정보 가져오기
	 * 
	 * @access public
	 * @return Array;
	 * */
	public function loadFromCookie(){
		$cookie_var = $this->input->cookie($this::COOKIE_NAME);
		$data = $this->encrypt->decode(base64_decode($cookie_var), $this::COOKIE_KEY);
		
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
	public function doLogin($redirect=''){
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