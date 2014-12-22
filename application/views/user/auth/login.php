<form class="form-horizontal" role="form" method="post" name="loginForm" id="loginForm" action="/user/auth/getauth">
  <input type="hidden" name="snsType"  id="snsType"  value="">
  <input type="hidden" name="snsID"    id="snsID"    value="">
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
      <button type="button" class="btn btn-primary" data-name="actionBtn" data-act="signin">Login</button>
      <div id="googleSignupBtn" class="googleSignupBtn" data-name="actionBtn" data-act="loginGoogle">
        <span class="icon"></span>
        <span class="buttonText">Google</span>
      </div>
    </div>
  </div>
</form>