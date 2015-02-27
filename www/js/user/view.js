var userView = {};

function doGoogleProcess(obj){	
	if(obj['email']){
		$('#snsType').val(_SNS_TYPE_GOOGLE);
		$('#snsID').val(obj['id']);
		$('#email').val(obj['email']);
		$('#saveForm').attr('action','/user/sns/connect');
		userView.dataSubmit('구글 계정과 연결 되었습니다.');
	}
}

function doFacebookProcess(obj){
	if(obj.id && obj.email){
		$('#snsType').val(_SNS_TYPE_FACEBOOK);
		$('#snsID').val(obj.id);
		$('#email').val(obj.email);
		$('#saveForm').attr('action','/user/sns/connect');
		userView.dataSubmit('페이스북 계정과 연결 되었습니다.');
	}
}

userView = {
	init:function(){
		$('#siteBody').on('click','button[data-name=actionBtn],a[data-name=actionBtn],div[data-name=actionBtn]',function(){
			userView.action($(this));
		});
	}
	,connectFacebook:function(){
		__facebook.getOauth();
	}
	,connectGoogle:function(){
		__google.getOauth();
	}
	,connectNaver:function(){
		commonFunction.movePage($('#nvurl').val());
	}
	,disconnectSNS:function(snsType){
		$('#snsType').val(snsType);
		$('#saveForm').attr('action','/user/sns/disconnect');

		userView.dataSubmit('연결 해제 되었습니다.');
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
		
		$('#saveForm').attr('action','/user/info/modify');

		userView.dataSubmit();
	}
	,dataSubmit:function(msg,url){
		var _msg = (msg)?msg:'수정완료';
		var _url = (url)?url:'/user/info/view';
		ajaxManager.setCallback(function(res){
			if(res.status == 200){
				commonFunction.movePage(_url,_msg);
			}else{
				messageFunction.showDanger(res.msg);
			}
		});
		ajaxManager.callAjaxSubmit('saveForm');
	}
	,action:function(actBtn){
		var act = actBtn.data('act');
		switch(act){
			case 'connectSNS':
				var snsType = actBtn.data('type');
				if(snsType == _SNS_TYPE_FACEBOOK){ userView.connectFacebook(); }
				else if(snsType == _SNS_TYPE_GOOGLE){ userView.connectGoogle(); }
				else if(snsType == _SNS_TYPE_NAVER){ userView.connectNaver(); }
				break;
			case 'disconnectSNS':
				var snsType = actBtn.data('type');
				userView.disconnectSNS(snsType);
				break;
			case 'modify':
				userView.modify();
				break;
		}
	}
};

window.onload = function(){
	userView.init();
}

