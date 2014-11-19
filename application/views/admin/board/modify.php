<form action="/admin/board/save/<?php echo $boardData['id']; ?>" id="saveForm" name="saveForm" role="form" class="form-horizontal" method="post">  
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
      <input type="text" class="form-control" id="name" name="name" placeholder="게시판명" value="<?php echo $boardData['name']; ?>">
    </div>
  </div>
  <div class="form-group">
    <label for="desc" class="col-sm-3 control-label">설명</label>
    <div class="col-sm-9">
      <textarea name="desc" id="desc" rows="10" cols="55"><?php echo $boardData['desc']; ?></textarea>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="useComment" id="useComment" value="Y" <?php echo ($boardData['useComment']=='Y')?'checked':''; ?>> 댓글 사용
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="useRecommend" id="useRecommend" value="Y" <?php echo ($boardData['useRecommend']=='Y')?'checked':''; ?>> 추천 사용
        </label>
      </div>
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-3 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="useFile" id="useFile" value="Y" data-name="actionBtn" data-act="addFileGroupDisplay" <?php echo ($boardData['useFile']=='Y')?'checked':''; ?>> 첨부파일 사용
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
    <label for="fileCount" class="col-sm-3 control-label">게시판 상태</label>
    <div class="col-xs-2">
      <select class="form-control" name="status" id="status">
        <?php echo $boardStatusOpt; ?>
      </select>
    </div>
  </div>
  <div class="form-group">
    <label for="category" class="col-sm-3 control-label">카테고리</label>
    <div class="col-lg-4">
      <div class="input-group">
        <input type="text" class="form-control" id="category" name="category" placeholder="카테고리">
        <span class="input-group-btn">
          <button class="btn btn-primary" type="button" data-name="actionBtn" data-act="categoryAdd">추가</button>
        </span>
      </div>
      <?php
        foreach($boardData['category'] as $category){
      ?>
      <div class="input-group" style="margin-top:5px">
        <input type="text" class="form-control" id="category_<?php echo $category['id']; ?>" name="category_<?php echo $category['id']; ?>" value="<?php echo $category['name']; ?>">
        <span class="input-group-btn">
          <input type="hidden" class="form-control" id="order_<?php echo $category['id']; ?>" name="order_<?php echo $category['id']; ?>" value="<?php echo $category['order']; ?>">
          <button class="btn btn-danger" type="button" data-name="actionBtn" data-act="categoryModify" data-val="<?php echo $category['id']; ?>">수정</button>
        </span>
      </div>
      <?php
        }
      ?>      
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-5 col-sm-10">
      <button type="button" class="btn btn-success" data-name="actionBtn" data-act="dataSubmit">저장</button>
    </div>
  </div>
</form>