var boardWrite = {};

boardWrite = {
	init:function(){
		if ($("#img_upload").length > 0){
			var imageUploadOption = {};
			imageUploadOption['imgsrc']   = '/img/editor/writer_file_upbtn_w2.gif';
			imageUploadOption['width']    = 66;
			imageUploadOption['height']   = 29;
			imageUploadOption['filesize'] = '2MB';
			imageUploadOption['limit']    = 99999;
			imageUploadOption['ext']      = '*.png;*.jpg;*.gif;';

		    $("#img_upload").flashUpload('/image/upload/temp', imageUploadOption, function(data){
		        var fileData = eval('['+data.returndata+']');
		        if(typeof(fileData[0]) != 'undefined' && fileData[0].status == 200){
		        	fileData = fileData[0].data;
			        if (typeof(fileData.url) != 'undefined'){
			            $('#imgbox').append('<input type="hidden" name="imgfile[]" value="' + (fileData.name+'|'+fileData.size+'|'+fileData.temp+'|'+fileData.ext) + '" />');
			            $('#content').editorAppendTag('<img src="'+fileData.url+'" class="img-rounded"/>');
			        }
		        }
		    });
		}

		$('#content').editorload();

		$('#siteBody').on('click','button[data-name=actionBtn],li[data-name=actionBtn]',function(){
			boardWrite.action($(this));
		});
	}
	,selectDropdown:function(actBtn){
		var name  = actBtn.data('grp');
		var value = actBtn.data('val');
		var text  = $('a',actBtn).text();

		$('#'+name+'_id').val(value);
		$('#'+name+'_dropdownMenu').html(text+'<span class="caret"></span>');
	}
	,dataSubmit:function(){
		if(commonFunction.isLogin()){
			$('#content').editorUpdated();

			if(!$('#title').val()){
				alert('제목을 입력 해 주세요');
				return false;
			}

			if(!$('#content').val()){
				alert('내용을 입력 해 주세요');
				return false;
			}

			ajaxManager.setCallback(function(res){
				if(res.status == 200){
					var searchParam = $('#searchParam').val();
					if(res.data.dataID){
						location.replace('/board/'+_boardGroup+'/view/'+res.data.dataID+'/?'+searchParam);
					}else{
						location.replace('/board/'+_boardGroup+'/list/?'+searchParam);
					}
				}
			});
			ajaxManager.callAjaxSubmit('saveForm');
		}else{
			commonFunction.moveToLogin();
		}		
	}
	,action:function(actBtn){
		var act = actBtn.attr('data-act');
		switch(act){
			case 'dataSubmit':
				boardWrite.dataSubmit();
				break;
			case 'selectDropdown':
				boardWrite.selectDropdown(actBtn);
				break;
		}
	}
};

$(document).ready(function(){
	boardWrite.init();
});