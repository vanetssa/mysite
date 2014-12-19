<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/client:plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>

<form class="form-horizontal" role="form" action="/user/auth/save" method="post" id="saveForm" name="saveForm">
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
    <div class="col-sm-offset-2 col-sm-4">
      <button type="button" class="btn btn-primary" data-name="actionBtn" data-act="signup">Sign up</button>



      <span id="signinButton">
        <span
          class="g-signin"
          data-callback="signinCallback"
          data-clientid="204554475871-qjfnejv0cu0tc3dkcn5i1tnco1p8th2j.apps.googleusercontent.com"
          data-cookiepolicy="single_host_origin"
          data-requestvisibleactions="http://schemas.google.com/AddActivity"
          data-scope="https://www.googleapis.com/auth/userinfo.email scope."
          data-scope="https://www.googleapis.com/auth/plus.login">
        </span>
      </span>


    </div>
  </div>
</form>

<script type="text/javascript">
  function signinCallback(authResult) {
    //console.log(authResult);
    gapi.auth.setToken(authResult);
    getEmail();
    /*
    if (authResult['access_token']) {
      // 승인 성공
      // 사용자가 승인되었으므로 로그인 버튼 숨김. 예:
      document.getElementById('signinButton').setAttribute('style', 'display: none');
    } else if (authResult['error']) {
      // 오류가 발생했습니다.
      // 가능한 오류 코드:
      //   "access_denied" - 사용자가 앱에 대한 액세스 거부
      //   "immediate_failed" - 사용자가 자동으로 로그인할 수 없음
      // console.log('오류 발생: ' + authResult['error']);
    }
    */
  }





  function getEmail(){
    // userinfo 메소드를 사용할 수 있도록 oauth2 라이브러리를 로드합니다.
    gapi.client.load('oauth2', 'v2', function() {
          var request = gapi.client.oauth2.userinfo.get();
          request.execute(getEmailCallback);
        });
  }

  function getEmailCallback(obj){
    //var el = document.getElementById('email');
    var email = '';

    if (obj['email']) {
      email = 'Email: ' + obj['email'];
    }

    //console.log(obj['email']);
    //console.log(obj);   // 전체 개체를 검사하려면 주석을 해제합니다.

    //el.innerHTML = email;
    //toggleElement('email');

    gapi.client.load('plus','v1', function(){
 var request = gapi.client.plus.people.list({
   'userId': 'me',
   'collection': 'visible'
 });
 request.execute(function(resp) {
   //console.log('Num people visible:' + resp.totalItems);
   console.log(resp);
 });
});


  }
</script>