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
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-4">
      <button type="button" class="btn btn-primary" data-name="actionBtn" data-act="modify">수정</button>
    </div>
  </div>
</form>
