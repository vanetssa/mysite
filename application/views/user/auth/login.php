<div id="fb-root"></div>
<form class="form-horizontal" role="form" method="post" name="loginForm" id="loginForm" action="/user/auth/getauth">
  <input type="hidden" name="snsType"  id="snsType"  value="">
  <input type="hidden" name="snsID"    id="snsID"    value="">
  <input type="hidden" name="nvurl"    id="nvurl"    value="<?php echo $nvurl; ?>">
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-4">
      <input type="email" class="form-control" name="email" id="email" placeholder="Email">
    </div>
  </div>
  <div class="form-group">
    <label for="passwd" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" name="password" id="password" placeholder="Password">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">      
      <button type="button" class="btn" data-name="actionBtn" data-act="signin">Login</button>
      <button type="button" id="googleOauthBtn"   class="btn btn-danger"  data-name="actionBtn" data-act="loginGoogle">google로 로그인</button>
      <button type="button" id="facebookOauthBtn" class="btn btn-primary" data-name="actionBtn" data-act="loginFacebook">facebook으로 로그인</button>
      <button type="button" id="naverOauthBtn"    class="btn btn-success" data-name="actionBtn" data-act="loginNaver">네이버로 로그인</button>
    </div>
  </div>
</form>