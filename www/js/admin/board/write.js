var boardWrite = {};

boardWrite = {
	init:function(){

		$('button[data-name=actionBtn],:checkbox[data-name=actionBtn]').click(function(){
			boardWrite.action($(this));
		});
		
		boardWrite.addFileGroupDisplay($('#useFile').is(':checked'));
	}
	,addFileGroupDisplay:function(checked){
		if(checked){
			$('div[name=addFileGroup]').show();
		}else{
			$('div[name=addFileGroup]').hide();
		}
	}
	,dataSubmit:function(){
		if(!$('#type').val()){
			alert('타입을 선택 해 주세요');
			return false;
		}

		if(!$('#name').val()){
			alert('게시판명을 입력 해 주세요');
			return false;
		}

		if(!$('#desc').val()){
			alert('설명을 입력 해 주세요');
			return false;
		}

		if($('#useFile').is(':checked')){
			if(!$('#fileSize').val()){
				alert('첨부파일의 사이즈를 선택 해 주세요');
				return false;
			}

			if(!$('#fileCount').val()){
				alert('첨부파일의 갯수를 선택 해 주세요');
				return false;
			}
		}

		$('#saveForm').ajaxSubmit({
			dataType:'json',
			success:function(res){
				if(res.status == 200){
					location.replace('/admin/board');
				}
			}
		});
	}
	,action:function(actBtn){
		var act = actBtn.attr('data-act');		
		switch(act){
			case 'addFileGroupDisplay':
				boardWrite.addFileGroupDisplay(actBtn.is(':checked'));
				break;
			case 'dataSubmit':
				boardWrite.dataSubmit();
				break;
		}
	}
};

$(document).ready(function(){
	boardWrite.init();
});