var userLogin = {};
var googleSignupOpt = _googleApiConfig;
googleSignupOpt.callback = 'signinCallback';

function signinCallback(authResult){
	gapi.auth.setToken(authResult);
	if(authResult['access_token']){
		gapi.client.load('oauth2', 'v2', function() {
        	var request = gapi.client.oauth2.userinfo.get();
        	request.execute(getEmailCallback);
      	});
	}else{

	}
}

function getEmailCallback(obj){	
	if(obj['email']){
		$('#snsType').val('GG');
		$('#snsID').val(obj['id']);
		//$('#email').val(obj['email']);

		ajaxManager.callAjaxSubmit('loginForm');
	}
}

userLogin = {
	init:function(){
		$('#siteBody').on('click','button[data-name=actionBtn],a[data-name=actionBtn],div[data-name=actionBtn]',function(){
			userLogin.action($(this));
		});

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
	,signin:function(){
		var email  = $('#email').val().trim();
		var passwd = $('#password').val().trim();	

		if(!email){
			alert('이메일 제대로 입력 합시다~');
			$('#email').focus();
			return false;
		}

		if(!passwd){
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
		}
	}
};

window.onload = function(){
	userLogin.init();
}
