var boardList = {};

boardList = {
	init:function(){
		$('#siteBody').on('click','button[data-name=actionBtn],a[data-name=actionBtn]',function(){
			boardList.action($(this));
		});
	}
	,moveToWrite:function(src){
		location.href = src;
	}
	,viewDetail:function(dataID){
		$('#searchForm').attr('action',$('#searchForm').attr('action')+'/'+dataID);
		$('#searchForm').submit();
	}
	,search:function(){
		
	}
	,action:function(actBtn){
		var act = actBtn.data('act');
		switch(act){
			case 'search':
				boardList.dataSubmit();
				break;
			case 'viewDetail':
				boardList.viewDetail(actBtn.data('val'));
				break;
			case 'moveToWrite':
				boardList.moveToWrite(actBtn.data('src'));
				break;
		}
	}
};

$(document).ready(function(){
	boardList.init();
});