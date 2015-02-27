var userLogin = {};

function doGoogleProcess(obj){
	if(obj['id']){
		doSnsLogin(obj['id'],_SNS_TYPE_GOOGLE);
	}else{
		messageFunction.showDanger('구글 로그인을 확인하세요.');
	}
}

function doFacebookProcess(obj){
	if(obj.id){
		doSnsLogin(obj.id,_SNS_TYPE_FACEBOOK);
	}else{
		messageFunction.showDanger('페이스북 로그인을 확인하세요.');
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

		__facebook.init(_facebookApiConfig.appid,{});

		$('#siteBody').on('keydown','#password',function(e){
			if(e.keyCode == 13){
				$('button[data-act=signin]').trigger('click');
			}
		});

		ajaxManager.setCallback(function(res){
			if(res.status == 200){
				commonFunction.movePage('/');
			}else{
				messageFunction.showDanger(res.msg);
			}
		});
	}	
	,loginFacebook:function(){		
		__facebook.getOauth();
	}
	,loginGoogle:function(){
		__google.getOauth();
	}
	,loginNaver:function(){		
		commonFunction.movePage($('#nvurl').val());
	}
	,signin:function(){
		var email  = $('#email').val().trim();
		var password = $('#password').val().trim();

		$('#snsType').val('');
		$('#snsID').val('');

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
