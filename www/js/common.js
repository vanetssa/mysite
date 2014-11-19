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