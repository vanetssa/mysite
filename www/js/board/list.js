var boardList = {};

boardList = {
	init:function(){
		$('#siteBody').on('click','button[data-name=actionBtn],a[data-name=actionBtn]',function(){
			console.log(this.parent);
			//boardList.action($(this));
		});
	}
	,moveToWrite:function(){

	}
	,viewDetail:function(){

	}
	,search:function(){
		
	}
	,action:function(actBtn){
		var act = actBtn.attr('data-act');
		switch(act){
			case 'search':
				boardList.dataSubmit();
				break;
			case 'viewDetail':
				boardList.selectDropdown(actBtn);
				break;
			case 'moveToWrite':

		}
	}
};

$(document).ready(function(){
	boardList.init();
});