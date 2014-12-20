<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_m extends MY_Model {

	const USER_STATUS_NORMAL = 'AA';
	const USER_STATUS_BLOCK  = 'BA';
	const USER_STATUS_DELETE = 'CA';

	const SNS_TYPE_FACEBOOK = 'FB';
	const SNS_TYPE_NAVER    = 'NV';
	const SNS_TYPE_GOOGLE   = 'GG';

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
	 * @param int $userID 사용자ID
	 * @param string $name 사용자명
	 * @param string $passwd 비밀번호
	 * @return void
	 */
	public function modUser($userID,$name='',$passwd=''){		

		$set  = array();
		$bind = array();

		$set[] = '`ModifyDate`=now()';
		if(!empty($name)){ $set[] = '`Name`=?'; $bind[] = trim($name); }
		if(!empty($passwd)){ $set[] = '`PassWord`=?'; $bind[] = md5(trim($passwd)); }
		
		$bind[] = $userID;

		$sql = '
			UPDATE `USER`.`UserData` SET 
				'.implode(",",$set).'
			WHERE ID = ?
		';

		try{
  			$this->dbm->query($sql,$bind);
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}
	}

	/**
	 * 비밀번호 수정
	 * 
	 * @access public
	 * @param int $userID 사용자ID
	 * @param string $passwd 새 비밀번호
	 * @return void
	 */
	public function changePass($userID,$passwd){
		$this->modUser($userID,'',$passwd);
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
			$rstData[] = $this->setUserData($row);
		}

		return $rstData;
	}

	/**
	 * 회원정보 데이터 만들기
	 * 
	 * @access private
	 * @param array $userInfo 회원 정보
	 * @return array
	 */
	private function setUserData($userInfo){
		$data = array();
		$data['userID']      = $userInfo->ID;
		$data['name']        = $userInfo->Name;
		$data['email']       = $userInfo->Email;
		$data['status']      = $userInfo->Status;
		$data['type']        = $userInfo->Type;
		$data['createDate']  = !empty($userInfo->CreateDate)?substr($userInfo->CreateDate,0,16):'';
		$data['modifyDate']  = !empty($userInfo->ModifyDate)?substr($userInfo->ModifyDate,0,16):'';
		$data['statusValue'] = $this->getCodeValue('USER_STATUS',$data['status']);

		return $data;
	}

	/**
	 * 로그인 정보 가져오기
	 * 
	 * @access public
	 * @param string $email 이메일주소
	 * @param string $passwd 비밀번호
	 * @return array
	 */
	public function getLoginInfo($email,$passwd){
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
	 * @param array $userInfo 사용자정보
	 * @return boolean
	 */
	public function login($userInfo){		
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

	/**
	 * SNS 계정 가져오기
	 * 
	 * @access public
	 * @param int $userID 사용자ID
	 * @param string $type SNS타입
	 * @param string $snsID SNSID
	 * @return array
	 */
	public function getSNSAccount($userID,$type,$snsID=''){
		if($snsID){
			$sql = 'SELECT ID,UserID,SNSID,Email,Status FROM `USER`.`UserSNS` WHERE `SNSID` = ? AND `Type` = ?';
			$data = array($snsID,$type);
		}else{
			$sql = 'SELECT ID,UserID,SNSID,Email,Status FROM `USER`.`UserSNS` WHERE `UserID` = ? AND `Type` = ?';
			$data = array($userID,$type);
		}

		try{
			$res = $this->dbs->query($sql,$data);
			if ($res->num_rows() > 0){
   				$row = $res->row();
   				return array('id'=>$row->ID,'userID'=>$row->UserID,'snsID'=>$row->SNSID,'email'=>$row->Email,'status'=>$row->Status);
			}else{
				return array();
			}
		}catch(Exception $e){
			throw new Exception($e->getMessage().'|'.__LINE__, 100);
		}
	}

	/**
	 * SNS계정정보로 사용자 정보 가져오기
	 * 
	 * @access public
	 * @param string $snsType SNS타입
	 * @param string $snsID SNSID
	 * @param string $snsEmail SNS Email
	 * @return array
	 */
	public function getUserBySNS($snsType,$snsID='',$snsEmail=''){
		$sql = 'SELECT `UserData`.* FROM `USER`.`UserData`, `USER`.`UserSNS` WHERE `UserData`.`ID` = `UserSNS`.`UserID` AND `UserSNS`.`Type` = ?';
		
		$data = array();
		$data[] = $snsType;

		if($snsID){
			$sql .= ' AND `UserSNS`.`SNSID` = ?';
			$data[] = trim($snsID);
		}

		if($snsEmail){
			$sql .= ' AND `UserSNS`.`Email` = ?';
			$data[] = trim($snsEmail);	
		}

		try{
			$res = $this->dbs->query($sql,$data);
			if ($res->num_rows() > 0){
   				$row = $res->row();
   				return $this->setUserData($row);   				
			}else{
				return array();
			}
		}catch(Exception $e){
			throw new Exception($e->getMessage().'|'.__LINE__, 100);
		}
	}

	/**
	 * SNS 계정 연결하기
	 * 
	 * @access public
	 * @param int $userID 사용자ID
	 * @param string $snsID 사용자의 SNS ID
	 * @param string $email 사용자의 Email
	 * @param string $type SNS타입
	 * @return array
	 */
	public function setSNSAccount($userID,$snsID,$email,$type){
		$snsAcc = $this->getSNSAccount($userID,$type);
		if(!empty($snsAcc)){
			if($snsAcc['status'] == 'AA'){
				return array('rst'=>'exist','data'=>$snsAcc);
			}else{
				$this->recoverySNSAccount($userID,$type);
				return array('rst'=>'recovery','data'=>$snsAcc);
			}
		}else{
			$sql = 'INSERT INTO `USER`.`UserSNS` VALUES ("",?,?,?,?,"AA",now(),now())';

			$data = array();
			$data[] = $userID;
			$data[] = $snsID;
			$data[] = trim($email);
			$data[] = $type;

			try{
				$res = $this->dbm->query($sql,$data);
				return array('rst'=>'new','data'=>array());
			}catch(Exception $e){
				throw new Exception($e->getMessage().'|'.__LINE__, 100);
			}
		}
	}

	/**
	 * SNS 계정 끊기
	 * 
	 * @access public
	 * @param int $userID 사용자ID
	 * @param string $type SNS타입
	 * @return void
	 */
	public function disconnectSNSAccount($userID,$type){
		$sql = 'UPDATE `USER`.`UserSNS` SET `Status` = "CA",`ModifyDate` = now() WHERE UserID = ?, Type = ?';

		$data = array();
		$data[] = $userID;
		$data[] = $type;

		try{
			$res = $this->dbm->query($sql,$data);
		}catch(Exception $e){
			throw new Exception($e->getMessage().'|'.__LINE__, 100);
		}
	}

	/**
	 * SNS 계정 복원
	 * 
	 * @access public
	 * @param int $userID 사용자ID
	 * @param string $type SNS타입
	 * @return void
	 */
	public function recoverySNSAccount($userID,$type){
		$sql = 'UPDATE `USER`.`UserSNS` SET `Status` = "AA",`ModifyDate` = now() WHERE UserID = ?, Type = ?';

		$data = array();
		$data[] = $userID;
		$data[] = $type;

		try{
			$res = $this->dbm->query($sql,$data);
		}catch(Exception $e){
			throw new Exception($e->getMessage().'|'.__LINE__, 100);
		}
	}

	/**
	 * 페이스북 계정 연결
	 * 
	 * @access public
	 * @param int $userID 사용자ID
	 * @param string $facebookID 페이스북ID
	 * @param string $email 이메일주소
	 * @param string $type SNS 타입
	 * @return array
	 */
	public function setFacebookAccount($userID,$facebookID,$email){
		return $this->setSNSAccount($userID,$facebookID,$email,$this::SNS_TYPE_FACEBOOK);
	}

	/**
	 * 네이버 계정 연결
	 * 
	 * @access public
	 * @param int $userID 사용자ID
	 * @param string $naverID 네이버ID
	 * @param string $email 이메일주소
	 * @param string $type SNS 타입
	 * @return array
	 */
	public function setNaverAccount($userID,$naverID,$email){
		return $this->setSNSAccount($userID,$naverID,$email,$this::SNS_TYPE_NAVER);
	}

	/**
	 * 구글 계정 연결
	 * 
	 * @access public
	 * @param int $userID 사용자ID
	 * @param string $googleID 구글ID
	 * @param string $email 이메일주소
	 * @param string $type SNS 타입
	 * @return array
	 */
	public function setGoogleAccount($userID,$googleID,$email){
		return $this->setSNSAccount($userID,$googleID,$email,$this::SNS_TYPE_GOOGLE);
	}
}
