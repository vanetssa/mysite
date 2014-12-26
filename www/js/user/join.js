var userJoin = {};
var googleSignupOpt = objectCopy(_googleApiConfig);
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
		setSnsAccount(obj['id'],obj['email'],obj['name'],'GG');
	}
}

function setSnsAccount(snsID,snsEmail,snsName,snsType){
	$('#snsType').val(snsType).attr('readonly',true);
	$('#snsID').val(snsID).attr('readonly',true);
	$('#email').val(snsEmail).attr('readonly',true);
	$('#name').val(snsName);

	var ajaxOption = objectCopy(_ajaxCommonOpt);
	ajaxOption.url = '/user/auth/checksns';
	ajaxOption.data = {'snsType':snsType,'snsID':snsID};
	ajaxOption.success = function(res){		
		if(res.status == 200){
			$('#password').attr('readonly',true).closest('div.form-group').hide();
			$('#passwordConfirm').attr('readonly',true).closest('div.form-group').hide();

			$('#saveForm').attr('action','/user/auth/setsns');
		}else{
			if(res.status == 201){
				location.href='/';
			}else{
				messageFunction.showDanger(res.msg);
			}
		}
	}
	$.ajax(ajaxOption);
}

userJoin = {
	init:function(){
		$('#siteBody').on('click','button[data-name=actionBtn],a[data-name=actionBtn]',function(){
			userJoin.action($(this));
		});

		facebook.init(_facebookApiConfig.appid,{});
	}
	,getGoogle:function(){
		gapi.signin.render('googleSignupBtn',googleSignupOpt);
	}
	,getFacebook:function(){
		facebook.oauthProcess(_facebookApiConfig.scope,function(){
			facebook.getLoginUserInfo(function(response){
				setSnsAccount(response.id,response.email,response.name,'FB');
			});
		});
	}
	,getNaver:function(src){
		//location.href = src;
		alert('준비중!!');
	}
	,signup:function(){
		var email  = $('#email').val().trim();
		var passwd = $('#password').val().trim();
		var passwdConfirm = $('#passwordConfirm').val().trim();
		var name = $('#name').val().trim();
		var snsType = $('#snsType').val();

		if(!regularExpression.checkValid(email,'email')){
			alert('이메일 제대로 입력 합시다~');
			$('#email').focus();
			return false;
		}

		if(!snsType){
			if(!regularExpression.checkValid(passwd,'passwd')){
				alert('비번은 영문 특수기호 포함 6~20자!');
				$('#password').focus();
				return false;
			}

			if(passwd != passwdConfirm){
				alert('비번 동일하게 입력 하자구요~');
				$('#passwordConfirm').focus();
				return false;	
			}
		}

		if(!regularExpression.checkValid(name,'name')){
			alert('이름은 영문 특수기호 포함 3~10자!한글도 됨!');
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
			case 'signup':
				userJoin.signup();
				break;
			case 'getGoogle':
				userJoin.getGoogle();
				break;
			case 'getFacebook':
				userJoin.getFacebook();
				break;
			case 'getNaver':
				userJoin.getNaver(actBtn.data('val'));
				break;
		}
	}
};

window.onload = function(){
	userJoin.init();
}