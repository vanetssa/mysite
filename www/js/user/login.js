var userLogin = {};
var googleSignupOpt = _googleApiConfig;
googleSignupOpt.callback = 'signinCallback';

function signinCallback(authResult){	
	if(authResult['access_token']){
		doGoogleLogin(authResult);
	}else if(authResult['error'] == "immediate_failed"){
		/*
        gapi.auth.authorize({
            client_id: _googleApiConfig.clientid,
            scope: _googleApiConfig.scope,
            immediate: true
        }, function (authRes) {
            if (authRes['status']['signed_in']) {
                doGoogleLogin(authResult);
            }
        });
		*/
    }
}

function doGoogleLogin(authResult){
	gapi.auth.setToken(authResult);
	gapi.client.load('oauth2', 'v2', function(){
    	var request = gapi.client.oauth2.userinfo.get();
    	request.execute(getEmailCallback);
  	});
}

function getEmailCallback(obj){
	if(obj['id']){
		doSnsLogin(obj['id'],'GG');
	}else{
		messageFunction.showDanger('가입된 SNS 계정이 아닙니다.');
	}
}

function doSnsLogin(snsID,snsType){
	$('#snsType').val(snsType);
	$('#snsID').val(snsID);

	ajaxManager.callAjaxSubmit('loginForm');
}

userLogin = {
	init:function(){
		$('#siteBody').on('click','button[data-name=actionBtn],a[data-name=actionBtn],div[data-name=actionBtn]',function(){
			userLogin.action($(this));
		});

		facebook.init(_facebookApiConfig.appid,{});

		$('#siteBody').on('keydown','#password',function(e){
			if(e.keyCode == 13){
				$('button[data-act=signin]').trigger('click');
			}
		});

		ajaxManager.setCallback(function(res){
			if(res.status == 200){
				location.href='/';
			}else{
				messageFunction.showDanger(res.msg);
			}
		});
	}
	,loginGoogle:function(){
		gapi.signin.render('googleSignupBtn',googleSignupOpt);
	}
	,loginFacebook:function(){
		facebook.loginProcess(_facebookApiConfig.scope,function(res){			
			var facebookUserID = '';
			try{
				facebookUserID = res.authResponse.userID;
			}catch(err){}
			
			if(facebookUserID){
				doSnsLogin(facebookUserID,'FB');
			}else{
				messageFunction.showDanger('가입된 SNS 계정이 아닙니다.');
			}
		});
	}
	,loginNaver:function(){
		alert('준비중!');
	}
	,signin:function(){
		var email  = $('#email').val().trim();
		var password = $('#password').val().trim();	

		if(!email){
			alert('이메일 제대로 입력 합시다~');
			$('#email').focus();
			return false;
		}

		if(!password){
			alert('비번을 입력해야 로그인을 하든 말든 하지...');
			$('#email').focus();
			return false;
		}

		ajaxManager.callAjaxSubmit('loginForm');
	}
	,action:function(actBtn){
		var act = actBtn.data('act');		
		switch(act){
			case 'signin':
				userLogin.signin();
				break;
			case 'loginGoogle':
				userLogin.loginGoogle();
				break;
			case 'loginFacebook':
				userLogin.loginFacebook();
				break;
			case 'loginNaver':
				userLogin.loginNaver();
				break;
		}
	}
};

window.onload = function(){
	userLogin.init();
}
