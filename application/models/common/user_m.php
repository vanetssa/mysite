<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends MY_Model {

	const USER_STATUS_NORMAL = 'AA';
	const USER_STATUS_BLOCK  = 'BA';
	const USER_STATUS_DELETE = 'CA';

	var $USER_STATUS = array();
	var $CODE_DATA   = array();

	function __construct() {
		parent::__construct();

		$this->USER_STATUS[] = array($this::USER_STATUS_NORMAL,'정상');
		$this->USER_STATUS[] = array($this::USER_STATUS_BLOCK,'차단');
		$this->USER_STATUS[] = array($this::USER_STATUS_DELETE,'탈퇴');

		$this->CODE_DATA['USER_STATUS'] = $this->USER_STATUS;		

		$this->load->library('encrypt');
		$this->load->helper('file');
	}

	/**
	 * 코드값 가져오기
	 * 
	 * @access public
	 * @param string $type 코드 종류
	 * @param string $code 코드값
	 * @return string
	 */
	public function getCodeValue($type,$code){
		$codeValue = '';
		$codeData = !empty($this->CODE_DATA[$type])?$this->CODE_DATA[$type]:array();
		foreach($codeData as $data){
			if($data[0] == $code){
				$codeValue = $data[1];
				break;
			}
		}
		return $codeValue;
	}

	/**
	 * 회원가입
	 * 
	 * @access public
	 * @param string $email 이메일 주소
	 * @param string $name 사용자명
	 * @param string $passwd 비밀번호
	 * @param string $type 사용자 타입
	 * @return integer
	 */
	public function saveUser($email,$name,$passwd,$type="AA"){
		$sql = '
			INSERT INTO `USER`.`UserData` (
				`Email`,`Name`,`PassWord`,`Type`,`Status`,`CreateDate`,`ModifyDate`
			) VALUES (
				?,?,?,?,"AA",now(),now()
			)
		';

		$data = array();
		$data[] = trim($email);
		$data[] = trim($name);
		$data[] = md5(trim($passwd));
		$data[] = trim($type);

		try{
  			$this->dbm->query($sql,$data);
  			$dataID = $this->dbm->insert_id();
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}

  		return $dataID;
	}

	/**
	 * 회원정보 수정
	 * 
	 * @access public
	 * @param string $name 사용자명
	 * @param string $passwd 비밀번호
	 * @return void
	 */
	public function modUser($userID,$name,$passwd){
		$sql = '
			UPDATE `USER`.`UserData` SET 
			`Name`=?,`PassWord`=?,`ModifyDate`=now()
			WHERE ID = ?
		';

		$data = array();
		$data[] = trim($name);
		$data[] = md5(trim($passwd));
		$data[] = $userID;

		try{
  			$this->dbm->query($sql,$data);
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}
	}

	/**
	 * 회원정보 가져오기
	 * 
	 * @access public
	 * @param int $userID 사용자 ID
	 * @param string $email 사용자 email
	 * @param string $passwd 비밀번호
	 * @return array
	 */
	public function getUser($userID,$email,$passwd){
		$sql = 'SELECT * FROM `USER`.`UserData`';

		$where = array();
		$bindData = array();

		$where[] = '`Status` = "AA"';
		if(!empty($userID)){ $where[] = '`ID` = ?'; $bindData[] = $userID; }
		if(!empty($email)){ $where[] = '`Email` = ?'; $bindData[] = trim($email); }
		if(!empty($passwd)){ $where[] = '`PassWord` = ?'; $bindData[] = md5(trim($passwd)); }

		if(!empty($where)){
			$sql .= ' WHERE '.implode(' AND ',$where);
		}

		try{
			$res = $this->dbs->query($sql,$bindData);
		}catch(Exception $e){
			throw new Exception($e->getMessage().'|'.__LINE__, 100);
		}

		$rstData = array();
		foreach($res->result() as $row){
			$data = array();
			$data['userID']      = $row->ID;
			$data['name']        = $row->Name;
			$data['email']       = $row->Email;
			$data['status']      = $row->Status;
			$data['type']        = $row->Type;
			$data['createDate']  = !empty($row->CreateDate)?substr($row->CreateDate,0,16):'';
			$data['modifyDate']  = !empty($row->ModifyDate)?substr($row->ModifyDate,0,16):'';
			$data['statusValue'] = $this->getCodeValue('USER_STATUS',$data['status']);

			$rstData[] = $data;
		}

		return $rstData;
	}

	/**
	 * 로그인 정보 가져오기
	 * 
	 * @access private
	 * @param string $email 이메일주소
	 * @param string $passwd 비밀번호
	 * @return array
	 */
	private function getLoginInfo($email,$passwd){
		$userInfo = $this->getUser('',$email,$passwd);
		if(empty($userInfo[0])){
			return array();
		}else{
			return $userInfo[0];
		}
	}

	/**
	 * 로그인
	 * 
	 * @access public
	 * @param string $email 이메일주소
	 * @param string $passwd 비밀번호
	 * @return boolean
	 */
	public function login($email,$passwd){
		if(empty($email) || empty($passwd)){ return false; }
		$userInfo = $this->getLoginInfo($email,$passwd);
		if(empty($userInfo)){
			return false;
		}else{
			$cookieValue = array();
			$cookieValue['userID'] = $userInfo['userID'];
			$cookieValue['name']   = $userInfo['name'];
			$cookieValue['email']  = $userInfo['email'];
			$cookieValue['status'] = $userInfo['status'];
			$cookieValue['type']   = $userInfo['type'];

			$encStr = base64_encode($this->encrypt->encode(json_encode($cookieValue),LOGIN_COOKIE_KEY));

			$cookie = array(
				'name'   => LOGIN_COOKIE_NAME,
				'value'  => $encStr,
				'expire' => LOGIN_COOKIE_EXPIRE,
				'domain' => LOGIN_COOKIE_DOMAIN,
				'path'   => '/',
				'prefix' => '',
				'secure' => FALSE
			);

			$this->input->set_cookie($cookie);

			return true;
		}
	}

	/**
	 * 로그아웃
	 * 
	 * @access public
	 * @return boolean
	 */
	public function logout(){
		$cookie = array(
			'name'   => LOGIN_COOKIE_NAME,
			'value'  => '',
			'expire' => 0,
			'domain' => LOGIN_COOKIE_DOMAIN,
			'path'   => '/',
			'prefix' => '',
			'secure' => FALSE
		);

		$this->input->set_cookie($cookie);

		return true;
	}

	/**
	 * 로그인 정보 가져오기
	 * 
	 * @access public
	 * @return array
	 */
	public function getCookieInfo(){
		$cookieValue = $this->input->cookie(LOGIN_COOKIE_NAME);
		$descStr = $this->encrypt->decode(base64_decode($cookieValue),LOGIN_COOKIE_KEY);
		$result = json_decode($descStr);
		return $result;
	}
}