<div id="fb-root"></div>
<form class="form-horizontal" role="form" action="/user/info/modify" method="post" id="saveForm" name="saveForm">  
  <input type="hidden" name="userID" id="userID" value="<?php echo $userInfo['userID']; ?>">
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-4">
      <p class="form-control-static"><?php echo $userInfo['email']; ?></p>
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label">비밀번호</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="password" name="password" placeholder="비밀번호">
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label">새비밀번호</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="passwordNew" name="passwordNew" placeholder="비밀번호">
    </div>
  </div>
  <div class="form-group">
    <label for="passwordConfirm" class="col-sm-2 control-label">새비밀번호확인</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="passwordConfirmNew" name="passwordConfirmNew" placeholder="비밀번호확인">
    </div>
  </div>
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">이름</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="name" name="name" value="<?php echo $userInfo['name']; ?>" placeholder="이름">
    </div>
  </div>  
  <?php
    foreach($userInfo['snsInfo'] as $code=>$data){
  ?>
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label"><?php echo $data['name']; ?></label>
    <div class="col-sm-4">
      <?php if(!empty($data['info'])){ ?>
      <p class="form-control-static">
        <?php echo $data['info']['email']; ?>
        <button type="button" class="btn btn-danger" data-name="actionBtn" data-act="disconnectSNS">연결끊기</button>
      </p>
      <?php }else{ ?>
      <p class="form-control-static">        
        <button type="button" class="btn btn-success" data-name="actionBtn" data-act="connectSNS">연결하기</button>
      </p>
      <?php } ?>      
    </div>
  </div>
  <?php
    }
  ?>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-4">
      <button type="button" class="btn btn-primary" data-name="actionBtn" data-act="modify">수정</button>
    </div>
  </div>
</form>
