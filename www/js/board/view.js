var boardView = {};

boardView = {
	init:function(){
		$('#siteBody').on('click','button[data-name=actionBtn]',function(){
			boardView.action($(this));
		});
	}
	,moveToModify:function(src){
		$('#modifyForm').submit();
	}
	,moveToList:function(src){
		$('#modifyForm').attr('action','/board/'+_boardGroup+'/lists/'+_boardID);
		$('#modifyForm').submit();
	}	
	,action:function(actBtn){
		var act = actBtn.data('act');
		switch(act){
			case 'moveToModify':
				boardView.moveToModify();
				break;
			case 'moveToList':
				boardView.moveToList();
				break;			
		}
	}
};

$(document).ready(function(){
	boardView.init();
});