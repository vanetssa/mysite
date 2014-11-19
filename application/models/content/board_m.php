<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Board_m extends MY_Model {
	
	const BOARD_TYPE_NORMAL  = 'NM';
	const BOARD_TYPE_BLOG    = 'BL';
	const BOARD_TYPE_COMMENT = 'CM';
	const BOARD_TYPE_HTML    = 'HT';

	const BOARD_STATUS_NORMAL = 'AA';
	const BOARD_STATUS_DELETE = 'CA';

	const CATEGORY_STATUS_NORMAL = 'AA';
	const CATEGORY_STATUS_DELETE = 'CA';

	var $BOARD_TYPE      = array();
	var $BOARD_STATUS    = array();
	var $CATEGORY_STATUS = array();
	var $CODE_DATA       = array();

	var $FILE_SIZE_LIST  = array(array(1,'1MB'),array(3,'3MB'),array(5,'5MB'),array(10,'10MB'),array(20,'20MB'));
	var $FILE_COUNT_LIST = array(array(1,'1개'),array(2,'2개'),array(3,'3개'),array(4,'4개'),array(5,'5개'));

	var $IMG_POOL_PATH = '/website/img/board/';

	function __construct() {
		parent::__construct();

		$this->BOARD_TYPE[] = array($this::BOARD_TYPE_NORMAL,'일반형');
		$this->BOARD_TYPE[] = array($this::BOARD_TYPE_BLOG,'블로그형');
		$this->BOARD_TYPE[] = array($this::BOARD_TYPE_COMMENT,'댓글형');
		$this->BOARD_TYPE[] = array($this::BOARD_TYPE_HTML,'HTML');

		$this->BOARD_STATUS[] = array($this::BOARD_STATUS_NORMAL,'정상');
		$this->BOARD_STATUS[] = array($this::BOARD_STATUS_DELETE,'비노출');

		$this->CATEGORY_STATUS[] = array($this::CATEGORY_STATUS_NORMAL,'정상');
		$this->CATEGORY_STATUS[] = array($this::CATEGORY_STATUS_DELETE,'비노출');

		$this->CODE_DATA['BOARD_TYPE']      = $this->BOARD_TYPE;
		$this->CODE_DATA['BOARD_STATUS']    = $this->BOARD_STATUS;
		$this->CODE_DATA['CATEGORY_STATUS'] = $this->CATEGORY_STATUS;
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
	 * 게시판 등록
	 * 
	 * @access public
	 * @param string $type 게시판 타입코드
	 * @param string $name 게시판 명
	 * @param string $desc 게시판 설명
	 * @param string $useComment 댓글 사용유무(Y|N)
	 * @param string $useRecommend 추천 사용유무(Y|N)
	 * @param string $useFile 첨부파일 사용유무(Y|N)
	 * @param int $fileSize 첨부파일 1개당 허용 용량(단위 MB)
	 * @param int $fileCount 첨부파일 허용 개수
	 * @return integer
	 */
	public function saveBoard($type,$name,$desc,$useComment,$useRecommend,$useFile,$fileSize,$fileCount){
		$sql = '
			INSERT INTO `BOARD`.`Board` (
				`Type`,`Name`,`Desc`,`UseComment`,`UseRecommend`,`UseFile`,`FileSize`,`FileCount`,`Status`,`CreateDate`,`ModifyDate`
			) VALUES (
				?,?,?,?,?,?,?,?,"AA",now(),now()
			)
		';

		if($useFile == 'N'){
			$fileSize  = 0;
			$fileCount = 0;
		}

		$data = array();
		$data[] = trim($type);
		$data[] = trim($name);
		$data[] = trim($desc);
		$data[] = trim($useComment);
		$data[] = trim($useRecommend);
		$data[] = trim($useFile);
		$data[] = $fileSize;
		$data[] = $fileCount;

		try{
  			$this->dbm->query($sql,$data);
  			$dataID = $this->dbm->insert_id();
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}

  		return $dataID;
	}

	/**
	 * 게시판 수정
	 * 
	 * @access public
	 * @param int $boardID 게시판 ID
	 * @param string $type 게시판 타입코드
	 * @param string $name 게시판 명
	 * @param string $desc 게시판 설명
	 * @param string $useComment 댓글 사용유무(Y|N)
	 * @param string $useRecommend 추천 사용유무(Y|N)
	 * @param string $useFile 첨부파일 사용유무(Y|N)
	 * @param int $fileSize 첨부파일 1개당 허용 용량(단위 MB)
	 * @param int $fileCount 첨부파일 허용 개수
	 * @param string $status 게시판 상태
	 * @return void
	 */
	public function modifyBoard($boardID,$type,$name,$desc,$useComment,$useRecommend,$useFile,$fileSize,$fileCount,$status){
		$sql = '
			UPDATE `BOARD`.`Board` SET 
			`Type`=?,`Name`=?,`Desc`=?,`UseComment`=?,`UseRecommend`=?,`UseFile`=?,`FileSize`=?,`FileCount`=?,`Status`=?,`ModifyDate`=now()
			WHERE ID = ?			
		';

		$data = array();
		$data[] = trim($type);
		$data[] = trim($name);
		$data[] = trim($desc);
		$data[] = trim($useComment);
		$data[] = trim($useRecommend);
		$data[] = trim($useFile);
		$data[] = $fileSize;
		$data[] = $fileCount;
		$data[] = $status;
		$data[] = $boardID;

		try{
  			$this->dbm->query($sql,$data);
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}
	}

	/**
	 * 게시판 데이터 가져오기
	 * 
	 * @access public
	 * @param int $boardID 게시판 ID
	 * @param string $type 게시판 타입코드
	 * @return array
	 */
	public function getBoard($boardID=null,$type=''){
		$sql = 'SELECT * FROM `BOARD`.`Board`';

		$where = array();
		$bindData = array();

		$where[] = '`Board`.`Status` = "AA"';
		if(!empty($type)){ $where[] = '`Type` = ?'; $bindData[] = $type; }
		if(!empty($boardID)){ $where[] = '`ID` = ?'; $bindData[] = $boardID; }

		if(!empty($where)){
			$sql .= ' WHERE '.implode(' AND ',$where);
		}

		$categoryData = $this->getCategoryByBoard($boardID,$type);

		$rstData = array();
		$res = $this->dbs->query($sql,$bindData);

		foreach($res->result() as $row){
			$data = array();
			$data['id']           = $row->ID;
			$data['type']         = $row->Type;
			$data['name']         = $row->Name;
			$data['desc']         = $row->Desc;
			$data['useComment']   = $row->UseComment;
			$data['useRecommend'] = $row->UseRecommend;
			$data['useFile']      = $row->UseFile;
			$data['fileSize']     = $row->FileSize;
			$data['fileCount']    = $row->FileCount;
			$data['status']       = $row->Status;
			$data['createDate']   = !empty($row->CreateDate)?substr($row->CreateDate,0,16):'';
			$data['modifyDate']   = !empty($row->ModifyDate)?substr($row->ModifyDate,0,16):'';
			$data['typeValue']    = $this->getCodeValue('BOARD_TYPE',$data['type']);
			$data['statusValue']  = $this->getCodeValue('BOARD_STATUS',$data['status']);
			$data['category']     = !empty($categoryData[$data['id']])?$categoryData[$data['id']]:array();

			$rstData[] = $data;
		}

		return $rstData;
	}

	/**
	 * 게시판 리스트 가져오기
	 * 
	 * @access public
	 * @param string $type 게시판 타입 코드
	 * @return array
	 */
	public function getBoardList($type=''){
		return $this->getBoard(null,$type);
	}

	/**
	 * 게시판 상세 가져오기
	 * 
	 * @access public
	 * @param int $boardID 게시판 ID
	 * @return array
	 */
	public function getBoardDetail($boardID){
		$data = $this->getBoard($boardID);
		$data = !empty($data[0])?$data[0]:array();
		return $data;
	}

	/**
	 * 카테고리 추가
	 * 
	 * @access public
	 * @param int $boardID 게시판 ID
	 * @param string $categoryName 카테고리명
	 * @return int
	 */
	public function addCategory($boardID,$categoryName){
		$sql = 'SELECT MAX(`Order`) as `maxOrder` FROM `BOARD`.`Category` WHERE BoardID = ?';
		$res = $this->dbs->query($sql,array($boardID));
		$row = $res->row();
		if($row){
			$order = $row->maxOrder + 1;
		}else{
			$order = 1;
		}

  		$sql = '
  			INSERT INTO `BOARD`.`Category` (
  				`BoardID`,`Name`,`Order`,`Status`,`CreateDate`,`ModifyDate`
  			) VALUES (
  				?,?,?,"AA",now(),now()
  			)
		';

		$data = array();
		$data[] = $boardID;
		$data[] = trim($categoryName);
		$data[] = $order;

		try{
  			$this->dbm->query($sql,$data);
  			$categoryID = $this->dbm->insert_id();
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}

  		return $categoryID;
	}

	/**
	 * 카테고리 수정
	 * 
	 * @access public
	 * @param int $categoryID 카테고리ID
	 * @param string $categoryName 카테고리명
	 * @param int $categoryOrder 카테고리 노출순서
	 * @return void
	 */
	public function modifyCategory($categoryID,$categoryName,$categoryOrder){		
		if($categoryName){
			$sql = '
				UPDATE `BOARD`.`Category` SET 
				`Name`=?,`Order`=?,`ModifyDate`=now()
				WHERE ID = ?
			';

			$data = array();
			$data[] = trim($categoryName);
			$data[] = $categoryOrder;
			$data[] = $categoryID;
		}else{
			$sql = '
				UPDATE `BOARD`.`Category` SET 
				`Order`=?,`Status`="CA",`ModifyDate`=now()
				WHERE ID = ?
			';

			$data = array();
			$data[] = $categoryOrder;
			$data[] = $categoryID;
		}

		try{
  			$this->dbm->query($sql,$data);
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}
	}

	/**
	 * 카테고리 가져오기
	 * 
	 * @access public
	 * @param int $categoryID 카테고리ID
	 * @param int $boardID 게시판ID
	 * @param string $type 게시판타입코드
	 * @return array
	 */
	public function getCategory($categoryID=null,$boardID=null,$type=''){
		$sql = 'SELECT `Category`.* FROM `BOARD`.`Category`,`BOARD`.`Board`';

		$where = array();
		$bindData = array();

		$where[] = '`Board`.`ID` = `Category`.`BoardID`';
		$where[] = '`Board`.`Status` = "AA"';
		$where[] = '`Category`.`Status` = "AA"';
		if(!empty($categoryID)){ $where[] = '`Category`.`ID` = ?'; $bindData[] = $categoryID; }
		if(!empty($boardID)){ $where[] = '`Category`.`BoardID` = ?'; $bindData[] = $boardID; }
		if(!empty($type)){ $where[] = '`Board`.`Type` = ?'; $bindData[] = $type; }

		if(!empty($where)){
			$sql .= ' WHERE '.implode(' AND ',$where);
		}

		$rstData = array();
		$res = $this->dbs->query($sql,$bindData);

		foreach($res->result() as $row){
			$data = array();
			$data['id']          = $row->ID;
			$data['boardID']     = $row->BoardID;
			$data['name']        = $row->Name;
			$data['order']       = $row->Order;
			$data['status']      = $row->Status;
			$data['createDate']  = !empty($row->CreateDate)?substr($row->CreateDate,0,16):'';
			$data['modifyDate']  = !empty($row->ModifyDate)?substr($row->ModifyDate,0,16):'';
			$data['statusValue'] = $this->getCodeValue('CATEGORY_STATUS',$data['status']);

			$rstData[] = $data;
		}

		return $rstData;
	}

	/**
	 * 게시판에 연결된 카테고리 가져오기
	 * 
	 * @access public
	 * @param int $boardID 게시판ID
	 * @param string $type 게시판타입코드
	 * @return array
	 */
	public function getCategoryByBoard($boardID,$type){
		$data = array();
		$cate = $this->getCategory(null,$boardID,$type);
		foreach($cate as $c){
			$data[$c['boardID']][] = $c;
		}

		return $data;
	}

	/**
	 * 게시판 내용 저장
	 * 
	 * @access public
	 * @param int $boardID 게시판 ID
	 * @param int $categoryID 말머리(분류) ID
	 * @param int $userID 사용자 ID
	 * @param string $title 제목
	 * @param string $content 내용
	 * @param array $imgFile 이미지 데이터(array('name|size|tempName|extention','name|size|tempName|extention',...))
	 * @return void
	 */
	public function saveContent($boardID,$categoryID,$userID,$title,$content,$imgFile){
		$data = array();
		$data[] = $boardID;
		$data[] = $categoryID;
		$data[] = $userID;
		$data[] = trim($title);
		$data[] = trim($content);

		$newContent = trim($content);

  		$sql = "
  			INSERT INTO `BOARD`.`Data` (
  				`BoardID`,`CategoryID`,`UserID`,`Title`,`Content`,`ViewCount`,`CommentCount`,`RecommendCount`,`Status`,`CreateDate`,`ModifyDate`
  			) VALUES (
				?,?,?,?,?,0,0,0,'AA',now(),now()
			)
  		";

  		try{
  			$this->dbm->query($sql,$data);
  			$dataID = $this->dbm->insert_id();	
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}

  		$imgSave = false;
		$imgData = array();
		if(!empty($imgFile) && is_array($imgFile)){
			foreach($imgFile as $img){
				$data = explode('|',$img);

				$fileName = $data[0];
				$fileSize = $data[1];
				$fileTemp = $data[2];
				$fileExt  = $data[3];

				$data = array();
				$data[] = $dataID;
				$data[] = $fileName;
				$data[] = $fileExt;
				$data[] = $fileSize;

				$now = date('Y-m-d H:i:s');
				$year  = substr($now,0,4);
				$month = substr($now,5,2);

				$sql = "
					INSERT INTO `BOARD`.File` (
						`DataID`,`Type`,`Name`,`Path`,`Extention`,`Size`,`Status`,`CreateDate`,`ModifyDate`
					) VALUES (
						?,'BA',?,'',?,?,'AA',?,?
					)
				";

				try{
					$this->dbm->query($sql,$data);
  					$fileID = $this->dbm->insert_id();
				}catch(Exception $e){
					throw new Exception($e->getMessage().'|'.__LINE__, 100);
				}				

  				$newPath = '/website/img/board/'.$year;
  				setFolder($newPath,$month);

  				$newFileName = $dataID.'_'.$fileID.'.'.$fileExt;

  				$newPath .= '/'.$month.'/'.$newFileName;

  				fileSave('/img/board_tmp/'.$fileTemp,$newPath,false);

  				$newContent = ste_replace('/img/board_tmp/'.$fileTemp,'/image/draw/board/'.$year.$month.'/'.$newFileName,$newContent);
			}

			$sql = "UPDATE `BOARD`.`Data` SET Content = ? WHERE ID = ?";
			$data = array();
			$data[] = $newContent;
			$data[] = $dataID;

			try{
				$this->dbm->query($sql,$data);	
			}catch(Exception $e){
				throw new Exception($e->getMessage().'|'.__LINE__, 100);
			}
		}
	}

	public function getContent($boardID,$categoryID,$search){

	}
}