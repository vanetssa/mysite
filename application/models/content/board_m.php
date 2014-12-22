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

	const DATA_STATUS_NORMAL = 'AA';
	const DATA_STATUS_TEMP   = 'AB';
	const DATA_STATUS_DELETE = 'CA';
	const DATA_STATUS_BLIND  = 'BA';

	const FILE_STATUS_NORMAL = 'AA';
	const FILE_STATUS_DELETE = 'CA';

	const BOARD_DATA_FOLDER = '/webdata/file/board';
	const BOARD_DATA_FILE   = 'boardData.data';

	const BOARD_TEMPLATE_NORMAL  = 'normal';
	const BOARD_TEMPLATE_BLOG    = 'blog';
	const BOARD_TEMPLATE_COMMENT = 'comment';
	const BOARD_TEMPLATE_HTML    = 'html';

	var $BOARD_TYPE      = array();
	var $BOARD_STATUS    = array();
	var $BOARD_TEMPLATE  = array();
	var $CATEGORY_STATUS = array();
	var $DATA_STATUS     = array();

	var $CODE_DATA       = array();

	var $FILE_SIZE_LIST  = array(array(1,'1MB'),array(3,'3MB'),array(5,'5MB'),array(10,'10MB'),array(20,'20MB'));
	var $FILE_COUNT_LIST = array(array(1,'1개'),array(2,'2개'),array(3,'3개'),array(4,'4개'),array(5,'5개'));

	var $IMG_POOL_PATH = '/webdata/img/board';

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

		$this->DATA_STATUS[] = array($this::DATA_STATUS_NORMAL,'정상');
		$this->DATA_STATUS[] = array($this::DATA_STATUS_TEMP,'임시저장');
		$this->DATA_STATUS[] = array($this::DATA_STATUS_DELETE,'삭제됨');
		$this->DATA_STATUS[] = array($this::DATA_STATUS_BLIND,'블라인드');

		$this->BOARD_TEMPLATE[] = array($this::BOARD_TYPE_NORMAL,$this::BOARD_TEMPLATE_NORMAL);
		$this->BOARD_TEMPLATE[] = array($this::BOARD_TYPE_BLOG,$this::BOARD_TEMPLATE_BLOG);
		$this->BOARD_TEMPLATE[] = array($this::BOARD_TYPE_COMMENT,$this::BOARD_TEMPLATE_COMMENT);
		$this->BOARD_TEMPLATE[] = array($this::BOARD_TYPE_HTML,$this::BOARD_TEMPLATE_HTML);

		$this->CODE_DATA['BOARD_TYPE']      = $this->BOARD_TYPE;
		$this->CODE_DATA['BOARD_STATUS']    = $this->BOARD_STATUS;		
		$this->CODE_DATA['CATEGORY_STATUS'] = $this->CATEGORY_STATUS;
		$this->CODE_DATA['BOARD_TEMPLATE']  = $this->BOARD_TEMPLATE;

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
			$rstData[] = $this->setBoardData($row,$categoryData);
		}

		return $rstData;
	}

	/**
	 * 게시판 데이터 정리
	 * 
	 * @param array $boardData 게시판 정보
	 * @param array $categoryData 카테고리 정보
	 * @return array
	 */
	private function setBoardData($boardData,$categoryData=array()){
		$data = array();

		$data['id']           = !empty($boardData->ID)?$boardData->ID:'';
		$data['type']         = !empty($boardData->Type)?$boardData->Type:'';
		$data['name']         = !empty($boardData->Name)?$boardData->Name:'';
		$data['desc']         = !empty($boardData->Desc)?$boardData->Desc:'';
		$data['useComment']   = !empty($boardData->UseComment)?$boardData->UseComment:'';
		$data['useRecommend'] = !empty($boardData->UseRecommend)?$boardData->UseRecommend:'';
		$data['useFile']      = !empty($boardData->UseFile)?$boardData->UseFile:'';
		$data['fileSize']     = !empty($boardData->FileSize)?$boardData->FileSize:'';
		$data['fileCount']    = !empty($boardData->FileCount)?$boardData->FileCount:'';
		$data['status']       = !empty($boardData->Status)?$boardData->Status:'';
		$data['createDate']   = !empty($boardData->CreateDate)?substr($boardData->CreateDate,0,16):'';
		$data['modifyDate']   = !empty($boardData->ModifyDate)?substr($boardData->ModifyDate,0,16):'';
		$data['typeValue']    = $this->getCodeValue('BOARD_TYPE',$data['type']);
		$data['statusValue']  = $this->getCodeValue('BOARD_STATUS',$data['status']);		
		$data['category']     = !empty($categoryData[$data['id']])?$categoryData[$data['id']]:array();

		return $data;
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

		$sql .= ' ORDER BY `Order`,`ID`';

		$rstData = array();
		$res = $this->dbs->query($sql,$bindData);

		foreach($res->result() as $row){
			$rstData[] = $this->setCategoryData($row);
		}

		return $rstData;
	}

	/**
	 * 카테고리 데이터 정리
	 * 
	 * @param array $categoryData 카테고리 정보
	 * @return array
	 */
	private function setCategoryData($categoryData){
		$data = array();
		$data['id']          = !empty($categoryData->ID)?$categoryData->ID:'';
		$data['boardID']     = !empty($categoryData->BoardID)?$categoryData->BoardID:'';
		$data['name']        = !empty($categoryData->Name)?$categoryData->Name:'';
		$data['order']       = !empty($categoryData->Order)?$categoryData->Order:'';
		$data['status']      = !empty($categoryData->Status)?$categoryData->Status:'';
		$data['createDate']  = !empty($categoryData->CreateDate)?substr($categoryData->CreateDate,0,16):'';
		$data['modifyDate']  = !empty($categoryData->ModifyDate)?substr($categoryData->ModifyDate,0,16):'';
		$data['statusValue'] = $this->getCodeValue('CATEGORY_STATUS',$data['status']);

		return $data;
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
	 * 게시판 정보 파일로 저장
	 * 
	 * @access public
	 * @return void
	 */
	public function saveToFile(){
		$folder = $this::BOARD_DATA_FOLDER;

		setFolder($folder);

		$filePath = $folder.'/'.$this::BOARD_DATA_FILE;

		$boardData = $this->getBoard();
		$boardData = json_encode($boardData);
		
		try{
			writeFile($filePath,$boardData);
		}catch(Exception $e){
			throw new Exception($e->getMessage().'|'.__LINE__, 99);
		}
	}

	/**
	 * 파일로 저장된 게시판 정보 가져오기
	 * 
	 * @access public
	 * @return array
	 */
	public function getBoardFromFile(){
		$boardData = readFileInfo($this::BOARD_DATA_FOLDER.'/'.$this::BOARD_DATA_FILE);
		return $boardData;
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
	 * @return int
	 */
	public function saveContent($boardID,$categoryID,$userID,$title,$content,$imgFile){
		$title   = trim($title);
		$content = trim($content);

		$data = array();
		$data[] = $boardID;
		$data[] = $categoryID;
		$data[] = $userID;
		$data[] = $title;
		$data[] = $content;

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

  		$this->saveContentImage($dataID,$content,$imgFile);

  		return $dataID;
	}

	/**
	 * 게시판 내용 수정
	 * 
	 * @access public
	 * @param int $dataID 게시글 ID
	 * @param int $boardID 게시판 ID
	 * @param int $categoryID 말머리(분류) ID
	 * @param string $title 제목
	 * @param string $content 내용
	 * @param array $imgFile 이미지 데이터(array('name|size|tempName|extention','name|size|tempName|extention',...))
	 * @return true
	 */
	public function modifyContent($dataID,$boardID,$categoryID,$title,$content,$imgFile){
		$title   = trim($title);
		$content = trim($content);

		$data = array();
		$data[] = $boardID;
		$data[] = $categoryID;		
		$data[] = $title;
		$data[] = $content;
		$data[] = $dataID;

		$sql = "
			UPDATE `BOARD`.`Data` SET 
				`BoardID` = ?,`CategoryID` = ?,`Title` = ?,`Content` = ?,`ModifyDate` = now()
			WHERE `ID` = ?
		";

  		try{
  			$this->dbm->query($sql,$data);
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}

  		$data = array();
  		$data[] = $this::FILE_STATUS_DELETE;
  		$data[] = $dataID;

  		$sql = "
  			UPDATE `BOARD`.`File` SET
  				`Status` = ?, `ModifyDate` = now()
  			WHERE
  				`DataID` = ?
  		";

  		try{
  			$this->dbm->query($sql,$data);
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}

  		$this->saveContentImage($dataID,$content,$imgFile);

  		return true;
	}

	/**
	 * 게시글 삭제
	 * 
	 * @access public
	 * @param int $dataID 게시글 ID
	 * @return void
	 */
	public function deleteContent($dataID){
		$sql = 'UPDATE `BOARD`.`Data` SET `Status` = "CA" WHERE ID = ?';

		try{
  			$this->dbm->query($sql,array($dataID));
  		}catch(Exception $e){
  			throw new Exception($e->getMessage().'|'.__LINE__, 100);
  		}
	}

	/**
	 * 컨텐츠 내의 이미지 저장
	 * 
	 * @param int $dataID 게시글 ID
	 * @param string $content 게시글 내용
	 * @param array $imgFile 이미지파일 리스트
	 * @return void
	 */
	private function saveContentImage($dataID,$content,$imgFile){
		if(!empty($dataID) && !empty($content) && !empty($imgFile) && is_array($imgFile)){
			foreach($imgFile as $img){
				$data = explode('|',$img);

				$fileName = trim($data[0]);
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

				$data[] = $now;
				$data[] = $now;
				
				$sql = "
					INSERT INTO `BOARD`.`File` (
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

  				$newPath = $this->IMG_POOL_PATH.'/'.$year;
  				setFolder($newPath,$month);

  				$newFileName = $dataID.'_'.$fileID.'.'.$fileExt;

  				$newPath .= '/'.$month.'/'.$newFileName;

  				fileSave(getenv('DOCUMENT_ROOT').'/img/board_tmp/'.$fileTemp,$newPath,false);

  				$newContent = str_replace('/img/board_tmp/'.$fileTemp,'/image/draw/board/'.$year.$month.'/'.$newFileName,$content);
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

	/**
	 * 게시판글 가져오기
	 * 
	 * @param int $boardID 게시판ID
	 * @param int $categoryID 게시판 머릿말ID
	 * @param int $dataID 게시글ID
	 * @param array $search array(array('type'=>'','value'=>''),array('type'=>'','value'=>''))
	 * @return array
	 */
	public function getContent($boardID='',$categoryID='',$dataID='',$search=array()){
		$select   = array();
		$from     = '';
		$where    = array();
		$bindData = array();

		$select[] = '`Data`.*';
		$select[] = '`Board`.`Type` as `BoardType`';
		$select[] = '`Board`.`Name` as `BoardName`';
		$select[] = '`Board`.`Status` as `BoardStatus`';
		$select[] = '`Category`.`Name` as `CategoryName`';
		$select[] = '`Category`.`Status` as `CategoryStatus`';

		$from = '`BOARD`.`Data` LEFT JOIN `BOARD`.`Category` ON `Category`.`ID` = `Data`.`CategoryID`,`BOARD`.`Board`';

		$where[] = '`Data`.`BoardID` = `Board`.`ID`';
		$where[] = '`Data`.`Status` = "AA"';
		$where[] = '`Board`.`Status` = "AA"';

		if(!empty($boardID)){
			$where[]    = '`Data`.`BoardID` = ?';
			$bindData[] = $boardID;
		}

		if(!empty($categoryID)){
			$where[]    = '`Data`.`CategoryID` = ?';
			$bindData[] = $categoryID;
		}

		if(!empty($dataID)){
			$where[]    = '`Data`.`ID` = ?';
			$bindData[] = $dataID;
		}

		$sql = 'SELECT '.implode(',',$select).' FROM '.$from;

		if(!empty($where)){
			$sql .= ' WHERE '.implode(' AND ',$where);
		}

		$sql .= ' ORDER BY `ModifyDate` DESC';

		$rstData = array();
		$res = $this->dbs->query($sql,$bindData);

		foreach($res->result() as $row){			
			$messageData = $this->setData($row);

			$boardInfo = new stdClass();			
			$boardInfo->Type   = $row->BoardType;
			$boardInfo->Status = $row->BoardStatus;
			
			$getBoardInfo = $this->setBoardData($boardInfo);
			
			$messageData['boardName']        = $row->BoardName;
			$messageData['boardType']        = $row->BoardType;
			$messageData['boardStatus']      = $row->BoardStatus;
			$messageData['boardTypeValue']   = $getBoardInfo['typeValue'];
			$messageData['boardStatusValue'] = $getBoardInfo['statusValue'];

			$commentInfo = new stdClass();
			$commentInfo->Status = $row->CategoryStatus;
			
			$getCategoryInfo = $this->setCategoryData($commentInfo);

			$messageData['categoryName']        = $row->CategoryName;
			$messageData['categoryStatus']      = $row->CategoryStatus;
			$messageData['categoryStatusValue'] = $getCategoryInfo['statusValue'];

			$commentData   = array();
			$recommendData = array();
			$fileData      = array();

			$messageData['commentData']   = $commentData;
			$messageData['recommendData'] = $recommendData;
			$messageData['fileData']      = $fileData;

			$rstData[] = $messageData;
		}

		return $rstData;
	}

	/**
	 * 게시글 데이터 정리
	 * 
	 * @param array $messageData 게시글 데이터
	 * @return array
	 */
	private function setData($messageData){
		$data = array();
		$data['id']             = !empty($messageData->ID)?$messageData->ID:'';
		$data['boardID']        = !empty($messageData->BoardID)?$messageData->BoardID:'';
		$data['categoryID']     = !empty($messageData->CategoryID)?$messageData->CategoryID:'';
		$data['userID']         = !empty($messageData->UserID)?$messageData->UserID:'';
		$data['title']          = !empty($messageData->Title)?$messageData->Title:'';
		$data['content']        = !empty($messageData->Content)?$messageData->Content:'';
		$data['viewCount']      = !empty($messageData->ViewCount)?$messageData->ViewCount:0;
		$data['commentCount']   = !empty($messageData->CommentCount)?$messageData->CommentCount:0;
		$data['recommendCount'] = !empty($messageData->RecommendCount)?$messageData->RecommendCount:0;
		$data['status']         = !empty($messageData->Status)?$messageData->Status:'';
		$data['createDate']     = !empty($messageData->CreateDate)?substr($messageData->CreateDate,0,16):'';
		$data['modifyDate']     = !empty($messageData->ModifyDate)?substr($messageData->ModifyDate,0,16):'';
		$data['statusValue']    = $this->getCodeValue('DATA_STATUS',$data['status']);

		return $data;
	}

	/**
	 * 게시글 상세 가져오기
	 * 
	 * @param int $dataID 게시글ID
	 * @return array
	 */
	public function getContentDetail($dataID){
		$message = $this->getContent('','',$dataID);
		return !empty($message[0])?$message[0]:array();
	}
}