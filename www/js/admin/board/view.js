var boardView = {};

boardView = {
	init:function(){
		$('#siteBody').on('click','button[data-name=actionBtn]',function(){
			boardView.action($(this));
		});
	}
	,dataSubmit:function(){
		$('#viewForm').submit();
	}
	,retunToList:function(){
		$('#viewForm').attr('action','/admin/board');
		$('#viewForm').submit();
	}
	,action:function(actBtn){
		var act = actBtn.attr('data-act');		
		switch(act){
			case 'dataSubmit':
				boardView.dataSubmit();
				break;
			case 'retunToList':
				boardView.retunToList();
				break;
		}
	}
};

$(document).ready(function(){
	boardView.init();
});