<form class="form-horizontal" role="form" action="/admin/board/modify/<?php echo $boardData['id']; ?>" id="viewForm" name="viweForm" metho="GET">  
  <input type="hidden" name="type"    value="<?php echo $searchType; ?>">
  <div class="form-group">
    <label class="col-sm-3 control-label">게시판 타입</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['typeValue']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">게시판명</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['name']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">설명</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['desc']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">댓글 사용</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['useComment']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">추천 사용</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['useRecommend']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">첨부파일 사용</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['useFile']; ?></p>
    </div>
  </div>
  <?php if($boardData['useFile'] == 'Y'){ ?>
  <div class="form-group">
    <label class="col-sm-3 control-label">첨부파일 사이즈</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['fileSize']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">첨부파일 갯수</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['fileCount']; ?></p>
    </div>
  </div>
  <?php } ?>
  <div class="form-group">
    <label class="col-sm-3 control-label">생성일</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['createDate']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">수정일</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['modifyDate']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3 control-label">상태</label>
    <div class="col-sm-9">
      <p class="form-control-static"><?php echo $boardData['statusValue']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-5 col-sm-10">
      <button type="button" class="btn btn-success" data-name="actionBtn" data-act="dataSubmit" >수정</button>
      <button type="button" class="btn"             data-name="actionBtn" data-act="retunToList">목록</button>
    </div>
  </div>
</form>