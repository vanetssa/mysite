var devWrite = {};

devWrite = {
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
		        fileData = fileData.data;
		        if (typeof(fileData[0].url) != 'undefined'){
		        	fileData = fileData[0];
		            $('#imgbox').append('<input type="hidden" name="imgfile[]" value="' + (fileData.name+'|'+fileData.size+'|'+fileData.temp+'|'+fileData.ext) + '" />');
		            $('#contents').editorAppendTag('<img src="'+fileData.url+'" />');
		        }
		    });
		}

		$('#contents').editorload();

		$('button[data-name=actionBtn]').click(function(){
			devWrite.action($(this));
		});
	}
	,dataSubmit:function(){
		$('#contents').editorUpdated();

		if(!$('#title').val()){
			alert('제목을 입력 해 주세요');
			return false;
		}

		if(!$('#contents').val()){
			alert('내용을 입력 해 주세요');
			return false;
		}

		$('#saveForm').ajaxSubmit({
			success:function(res){
				if(res.status == 200){
					location.replace('/admin/dev');
				}
			}
		});
	}
	,action:function(actBtn){
		var act = actBtn.attr('data-act');
		switch(act){
			case 'dataSubmit':
				devWrite.dataSubmit();
				break;
		}
	}
};

$(document).ready(function(){
	devWrite.init();
});