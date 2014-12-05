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
	 * @return void
	 */
	public function getUser($userID,$email,$passwd){
		$sql = 'SELECT * FROM `User`.`UserData`';

		$where = array();
		$bindData = array();

		$where[] = '`Status` = "AA"';
		if(!empty($userID)){ $where[] = '`ID` = ?'; $bindData[] = $userID; }
		if(!empty($email)){ $where[] = '`Email` = ?'; $bindData[] = $email; }
		if(!empty($passwd)){ $where[] = '`PassWord` = ?'; $bindData[] = md5(trim($passwd)); }

		if(!empty($where)){
			$sql .= ' WHERE '.implode(' AND ',$where);
		}

		$rstData = array();
		$res = $this->dbs->query($sql,$bindData);

		foreach($res->result() as $row){
			$data = array();
			$data['userID']      = $row->ID;
			$data['name']        = $row->Name;
			$data['email']       = $row->Email;
			$data['status']      = $row->Status;
			$data['createDate']  = !empty($row->CreateDate)?substr($row->CreateDate,0,16):'';
			$data['modifyDate']  = !empty($row->ModifyDate)?substr($row->ModifyDate,0,16):'';
			$data['statusValue'] = $this->getCodeValue('USER_STATUS',$data['status']);

			$rstData[] = $data;
		}

		return $rstData;
	}
}