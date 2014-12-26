<div id="fb-root"></div>
<form class="form-horizontal" role="form" action="/user/auth/save" method="post" id="saveForm" name="saveForm">
  <input type="hidden" name="snsType"  id="snsType"  value="">
  <input type="hidden" name="snsID"    id="snsID"    value="">
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-4">
      <input type="email" class="form-control input-" id="email" name="email" placeholder="Email">
    </div>
  </div>
  <div class="form-group">
    <label for="password" class="col-sm-2 control-label">비밀번호</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="password" name="password" placeholder="비밀번호">
    </div>
  </div>
  <div class="form-group">
    <label for="passwordConfirm" class="col-sm-2 control-label">비밀번호확인</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" id="passwordConfirm" name="passwordConfirm" placeholder="비밀번호확인">
    </div>
  </div>
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">이름</label>
    <div class="col-sm-4">
      <input type="text" class="form-control" id="name" name="name" placeholder="이름">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-8">
      <button type="button" class="btn" data-name="actionBtn" data-act="signup">Sign up</button>
      <button type="button" id="googleSignupBtn"   class="btn btn-danger"  data-name="actionBtn" data-act="getGoogle">google로 가입하기</button>
      <button type="button" id="facebookSignupBtn" class="btn btn-primary" data-name="actionBtn" data-act="getFacebook">facebook으로 가입하기</button>
      <button type="button" id="naverSignupBtn"    class="btn btn-success" data-name="actionBtn" data-act="getNaver" data-val="<?php echo $nvurl; ?>">네이버로 가입하기</button>

      <?php /* ?>
      <div id="googleSignupBtn" class="googleSignupBtn" data-name="actionBtn" data-act="getGoogle">
        <span class="icon"></span>
        <span class="buttonText">Google</span>
      </div>
      <?php */ ?>
    </div>
  </div>
</form>
