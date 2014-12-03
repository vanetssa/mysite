var boardWrite = {};

boardWrite = {
	init:function(){

		$('#siteBody').on('click','button[data-name=actionBtn],input[data-name=actionBtn]',function(){
			boardWrite.action($(this));
		});

		$('#siteBody').on('keyup','input[data-name=actionBtn]',function(e){
			if($(this).data('act') == 'categoryAddInput'){
				if(e.keyCode == 13){
					boardWrite.categoryAdd();
				}
			}
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

		ajaxManager.setCallback(function(res){
			if(res.status == 200){
				location.replace('/admin/board');
			}
		});
		ajaxManager.callAjaxSubmit('saveForm');
	}
	,retunToList:function(){
		location.href = '/admin/board?type='+_searchType;
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
			case 'retunToList':
				boardWrite.retunToList();
				break;
		}
	}
};

$(document).ready(function(){
	boardWrite.init();
});