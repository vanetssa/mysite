var userView = {};
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
		$('#snsType').val('GG').attr('readonly',true);
		$('#snsID').val(obj['id']).attr('readonly',true);
		$('#email').val(obj['email']).attr('readonly',true);
		$('#name').val(obj['name']);
		
		$('#password').attr('readonly',true).closest('div.form-group').hide();
		$('#passwordConfirm').attr('readonly',true).closest('div.form-group').hide();

		$('#saveForm').attr('action','/user/auth/setsns');
	}
}

userView = {
	init:function(){
		$('#siteBody').on('click','button[data-name=actionBtn],a[data-name=actionBtn],div[data-name=actionBtn]',function(){
			userView.action($(this));
		});
	}
	,getGoogle:function(){
		gapi.signin.render('googleSignupBtn',googleSignupOpt);
	}
	,modify:function(){
		var passwd = $('#password').val().trim();
		var passwordNew = $('#passwordNew').val().trim();
		var passwordConfirmNew = $('#passwordConfirmNew').val().trim();
		var name = $('#name').val().trim();
		/*
		if(!passwd){
			messageFunction.showWarning('기존 비번은 입력을 해야쥐...');
			$('#password').focus();
			return false;
		}
		*/
		if(!regularExpression.checkValid(passwordNew,'passwd')){
			messageFunction.showWarning('비번은 영문 특수기호 포함 6~20자!');
			$('#passwordNew').focus();
			return false;
		}

		if(passwordNew != passwordConfirmNew){			
			messageFunction.showWarning('비번 동일하게 입력 하자구요~');
			$('#passwordConfirmNew').focus();
			return false;
		}

		if(!regularExpression.checkValid(name,'name')){
			messageFunction.showWarning('이름은 영문 특수기호 포함 3~10자!한글도 됨!');
			$('#name').focus();
			return false;
		}

		ajaxManager.setCallback(function(res){
			if(res.status == 200){
				alert('저장완료');
				location.href='/';
			}else{
				messageFunction.showDanger(res.msg);
			}
		});
		ajaxManager.callAjaxSubmit('saveForm');
	}
	,action:function(actBtn){
		var act = actBtn.data('act');
		switch(act){
			case 'modify':
				userView.modify();
				break;
			case 'getGoogle':
				userView.getGoogle();
				break;
		}
	}
};

window.onload = function(){
	userView.init();
}

