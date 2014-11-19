var boardWrite = {};

boardWrite = {
	init:function(){

		$('button[data-name=actionBtn],input[data-name=actionBtn]').click(function(){
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
	,categoryAdd:function(){		
		$.ajax({
			url:'/admin/category/add/'+_boardID

		});
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
		/*
		$('#saveForm').ajaxSubmit({
			dataType:'json',
			success:function(res){
				if(res.status == 200){
					location.replace('/admin/board');
				}
			}
		});
		*/
		ajaxManager.setCallback(function(res){
			if(res.status == 200){
				location.replace('/admin/board');
			}
		});
		ajaxManager.callAjaxSubmit('saveForm');
	}
	,categoryAdd:function(){
		var categoryName = $('#category').val();		
		if(categoryName){
			ajaxManager.setOpt('/admin/category/add',{boardID:_boardID,categoryName:categoryName},function(res){
				if(res.status == 200){
					location.replace(location.href);
				}
			});
			ajaxManager.callAjax();
		}
	}
	,categoryModify:function(categoryID){
		var categoryName  = $('#category_'+categoryID).val();
		var categoryOrder = $('#order_'+categoryID).val();

		if(categoryName){
			ajaxManager.setOpt('/admin/category/modify',{categoryID:categoryID,categoryName:categoryName,categoryOrder:categoryOrder},function(res){
				if(res.status == 200){
					location.replace(location.href);
				}
			});
			ajaxManager.callAjax();
		}
	}
	,action:function(actBtn){
		var act = actBtn.data('act');		
		switch(act){
			case 'addFileGroupDisplay':
				boardWrite.addFileGroupDisplay(actBtn.is(':checked'));
				break;
			case 'categoryAdd':
				boardWrite.categoryAdd();
				break;
			case 'categoryModify':
				boardWrite.categoryModify(actBtn.data('val'));
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