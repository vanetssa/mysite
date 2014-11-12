<form action="/admin/board/save" id="saveForm" name="saveForm" role="form" class="form-horizontal" method="post">
  <div class="form-group">
    <label for="type" class="col-sm-3 control-label">게시판 타입</label>
    <div class="col-xs-2">
      <select class="form-control" name="type" id="type">
        <option value="">-선택-</option>
        <?php echo $boardTypeOpt; ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="name" class="col-sm-3 control-label">게시판명</label>
    <div class="col-sm-9">
      <input type="text" class="form-control" id="name" name="name" placeholder="게시판명">
    </div>
  </div>
  <div class="form-group">
    <label for="desc" class="col-sm-3 control-label">설명</label>
    <div class="col-sm-9">
      <textarea name="desc" id="desc" rows="10" cols="55"></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="useComment" id="useComment" value="Y"> 댓글 사용
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="useRecommend" id="useRecommend" value="Y"> 추천 사용
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="useFile" id="useFile" value="Y" data-name="actionBtn" data-act="addFileGroupDisplay"> 첨부파일 사용
        </label>
      </div>
    </div>
  </div>  
  <div class="form-group" name="addFileGroup">
    <label for="fileSize" class="col-sm-3 control-label">첨부파일 사이즈</label>
    <div class="col-xs-2">
      <select class="form-control" name="fileSize" id="fileSize">
        <option value="">-선택-</option>
        <?php echo $fileSizeOpt; ?>
      </select>
    </div>
  </div>
  <div class="form-group" name="addFileGroup">
    <label for="fileCount" class="col-sm-3 control-label">첨부파일 갯수</label>
    <div class="col-xs-2">
      <select class="form-control" name="fileCount" id="fileCount">
        <option value="">-선택-</option>
        <?php echo $fileCountOpt; ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-5 col-sm-10">
      <button type="button" class="btn btn-success" data-name="actionBtn" data-act="dataSubmit">저장</button>
    </div>
  </div>
</form>