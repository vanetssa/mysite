function objectCopy(obj,add){
	var newObj = {};
	for(key in obj){
		newObj[key] = obj[key];
	}

	if(typeof add == 'object'){
		for(key in add){
			newObj[key] = add[key];
		}
	}
	return newObj;
}

var ajaxManager = {};
ajaxManager = {
	opt:{}
	,setUrl:function(url){
		if(url){ ajaxManager.opt.url = url;	}
	}
	,setParam:function(param){
		if(param){ ajaxManager.opt.data = param; }
	}
	,setCallback:function(callback){
		if(typeof callback == 'function'){ ajaxManager.opt.success = function(response){ callback(response); } }
	}
	,setExt:function(extOption){
		if(extOption){
			try{
				ajaxManager = $.extend(ajaxManager,extOption);
			}catch(err){
				//에러처리
			}
		}
	}
	,setOpt:function(url,param,callback,extOption){
		ajaxManager.setUrl(url);
		ajaxManager.setParam(param);
		ajaxManager.setCallback(callback);
		ajaxManager.setExt(extOption);
	}
	,callAjax:function(){
		$.ajax(ajaxManager.opt);
	}
	,callAjaxSubmit:function(formID){
		$('#'+formID).ajaxSubmit(ajaxManager.opt);
	}
};

ajaxManager.opt = objectCopy(_ajaxCommonOpt);

var regularExpression = {};
regularExpression = {
	expr:{
		email:/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i
		,passwd:/^[a-zA-Z0-9!@#$%^&*]{6,20}$/
		,name:/^[ 가-힣ㄱ-ㅎㅏ-ㅣa-zA-Z0-9!@#$%^&*]{3,10}$/
	}
	,checkValid:function(value,type){
		var expr = regularExpression.expr[type];
		if(typeof expr == 'undefined'){
			return false;
		}
		var pattern = new RegExp(expr);
        var patternCheck = pattern.test( $.trim(value) );
		return ( patternCheck ) ? true : false;	
	}
};

function getCookie( cookieName ){
	var search = cookieName + "=";
	var cookie = document.cookie;

	// 현재 쿠키가 존재할 경우
	if( cookie.length > 0 ){
		// 해당 쿠키명이 존재하는지 검색한 후 존재하면 위치를 리턴.
		startIndex = cookie.indexOf( cookieName );

		// 만약 존재한다면
		if( startIndex != -1 ){
			// 값을 얻어내기 위해 시작 인덱스 조절
			startIndex += cookieName.length;

			// 값을 얻어내기 위해 종료 인덱스 추출
			endIndex = cookie.indexOf( ";", startIndex );

			// 만약 종료 인덱스를 못찾게 되면 쿠키 전체길이로 설정
			if( endIndex == -1) endIndex = cookie.length;

			// 쿠키값을 추출하여 리턴
			return unescape( cookie.substring( startIndex + 1, endIndex ) );
		}else{
			// 쿠키 내에 해당 쿠키가 존재하지 않을 경우
			return false;
		}
	}else{
		// 쿠키 자체가 없을 경우
		return false;
	}
}

var commonFunction = {};
commonFunction = {
	isLogin:function(){
		var cookie = getCookie('van');
		if (!cookie) return false;
		if (cookie==null) return false;
		if (cookie=="") return false;
		return true;
	}
	,moveToLogin:function(){
		location.href = '/user/auth/login';
	}
};

var messageFunction = {};
messageFunction = {
	show:function(target,msg){
		$('div.alert').hide();
		$('#'+target).html(msg).show();
		setTimeout( "$('div.alert').hide();",3000 );
	}
	,showSuccess:function(msg){
		messageFunction.show('alertSuccessBar',msg);
	}
	,showInfo:function(msg){
		messageFunction.show('alertInfoBar',msg);
	}
	,showWarning:function(msg){
		messageFunction.show('alertWarningBar',msg);
	}
	,showDanger:function(msg){
		messageFunction.show('alertDangerBar',msg);
	}
}