var ajaxManager = {};
ajaxManager = {
	opt:{
		dataType:'json',
		type:'post',
		timeout:5000,
		async:true,
		cache:true
	}
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
}

var regularExpression = {};
regularExpression = {
	expr:{
		email:/^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i
		,passwd:/^[a-zA-Z0-9!@#$%^&*]{6,20}$/
		,name:/^[가-힣ㄱ-ㅎㅏ-ㅣa-zA-Z0-9!@#$%^&*]{3,10}$/
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
}