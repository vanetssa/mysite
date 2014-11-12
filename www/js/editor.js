/**
 * editorload : 특정 엘리먼트에 에디터 적용하는 스크립트.
 * 
 * @param _skin ( 스킨 파일명, 확장자 빼고... )
 **/
var oEditors = [];
$.fn.editorload = function (_skin)
{
    var _this = $(this);
    
    nhn.husky.EZCreator.createInIFrame(
    {
    	oAppRef        : oEditors,
    	elPlaceHolder  : _this.attr("name"),
    	sSkinURI       : "/js/module/smart_editor/skin_simple.html",
    	htParams       : {
    	    bUseToolbar : true,
    		fOnBeforeUnload: function (){}
    	},
		fOnAppLoad     : function ()
		{
		    var sDefaultFont = '나눔고딕';
            var nFontSize = 10;
            oEditors.getById[_this.attr("name")].setDefaultFont(sDefaultFont, nFontSize);
		}
    });
}


/**
 * editorUpdated : 적용.
 * 
 **/
$.fn.editorUpdated = function ()
{
    try {
        oEditors.getById[$(this).attr("name")].exec("UPDATE_CONTENTS_FIELD", []);
    }
    catch (e){
        return false;
    }
}


/**
 * 에디터에 태그 추가.
 * 
 * @param _tag 태그내용.
 **/
$.fn.editorAppendTag = function (_tag)
{
    try {
        oEditors.getById[$(this).attr("name")].exec("PASTE_HTML", [_tag]);
    }
    catch (e){
        return false;
    }
}


/**
 * 에디터 태그 내용 변경.
 * 
 * @param _var 변경 대상.
 * @param _after 변경 내용.
 **/
$.fn.editorReplaceTag = function (_var, _after)
{
    try {
        oEditors.getById[$(this).attr("name")].exec("SET_CONTENTS", [oEditors.getById[$(this).attr("name")].getIR().replace(_var, _after)]);
    }
    catch (e){
        return false;
    }
}

$.fn.flashUpload = function ()
{
    var args = arguments;
    
    var defOpt = {
        filesize            : '10 MB', 
        limit               : '99999', 
        ext                 : '*.*', 
        imgsrc              : '//img.tourtips.com/images/community/attach_file_btn.gif', 
        width               : 71, 
        height              : 48        
    };
    
    // library load.
    if (typeof SWFUpload != 'function'){
        //loadFlashLibrary();
    }
    
    // 서버 URL 확인.
    if (typeof(args[0]) != 'string'){
        alert('첨부 처리할 주소가 올바르지 않습니다.');
        return false;
    }
    
    // 파일 사이즈 확인.
    if (typeof(args[1]) == 'object'){
        if (typeof(args[1].filesize) == 'undefined') args[1].filesize = defOpt.filesize;
        if (typeof(args[1].limit) == 'undefined') args[1].limit = defOpt.limit;
        if (typeof(args[1].ext) == 'undefined') args[1].ext = defOpt.ext;
        if (typeof(args[1].imgsrc) == 'undefined') args[1].imgsrc = defOpt.imgsrc;
        if (typeof(args[1].width) == 'undefined') args[1].width = defOpt.width;
        if (typeof(args[1].height) == 'undefined') args[1].height = defOpt.height;
    }
    else {
        args[1] = defOpt;
    }
    
    
    var handler = {
        // default variables.
        'object':null, 
        'selector':null, 
        'progressbar':null, 
        'callback':null, 
        'size':null, 
        'limit':null, 
        'box':false, 
        'count':0,
        
        
        // 기본 변수값 호출.
        get: function (objName)
        {
            return eval('this.'+objName);
        }, 
        
        // 기본 변수값 갱신.
        set: function (objName, objValue)
        {
            eval('this.'+objName+'=objValue;');
        }, 
        
        init : function ()
        {
            // 파일박스 사용여부.
            if (handler.box == true){
                if ($('.editor_file_list').length <= 0){
                    $('#'+handler.selector).parent().append('<div class="editor_file_list" style="display:none"><ul id="filebox" class="attach_file_list"></ul></div>');
                }
            }
        }, 
        
        cancelQueue : function ()
        {
            
        }, 
        
        fileDialogStart : function ()
        {
            /* I don't need to do anything here */
        }, 
        
        fileQueued : function (file)
        {
            if (handler.limit <= handler.count){
                alert('첨부할 수 없습니다. 파일은 최대 '+handler.limit+'개의 파일을 첨부할 수 있습니다.');
                handler.cancelQueue();
                return false;
            }
            else {
                handler.count += 1;
            }
            
            try {
                if (handler.box == true){
                    $("#filebox").parent().css("display", "");
                    var progress = new FileProgress(file, this.customSettings.progressTarget);
                    progress.setStatus("첨부 대기중...");
                    progress.toggleCancel(true, this);
                }
            }
            catch (ex){
                this.debug(ex);
            }
        }, 
        
        fileQueueError : function (file, errorCode, message)
        {
            try {
                if (errorCode === SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED) {
                    alert("첨부할 수 없습니다..\n" + (message === 0 ? "첨부 요청중인 파일이 너무 많습니다." : "파일은 최대 " + handler.limit + "개의 파일을 첨부 할 수 있습니다.."));
                    return;
                }
                
                if (handler.box == true){
                    var progress = new FileProgress(file, this.customSettings.progressTarget);
                    progress.setError();
                    progress.toggleCancel(false);
                }
                
                switch (errorCode)
                {
                    case SWFUpload.QUEUE_ERROR.FILE_EXCEEDS_SIZE_LIMIT:
                        if (handler.box == true) progress.setStatus("파일이 너무 큽니다.");
                        //this.debug("Error Code: File too big, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                        alert('업로드할 수 있는 파일 용량을 초과하였습니다. ( 업로드 제한 : '+handler.size+' )');
                    break;
                    
                    case SWFUpload.QUEUE_ERROR.ZERO_BYTE_FILE:
                        if (handler.box == true) progress.setStatus("Cannot upload Zero Byte files.");
                        //this.debug("Error Code: Zero byte file, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                        alert('빈 파일은 업로드 하실 수 없습니다.');
                    break;
                    
                    case SWFUpload.QUEUE_ERROR.INVALID_FILETYPE:
                        if (handler.box == true) progress.setStatus("Invalid File Type.");
                        //this.debug("Error Code: Invalid File Type, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                        alert('업로드 할 수 있는 파일 형식이 아닙니다.');
                    break;
                    
                    case SWFUpload.QUEUE_ERROR.QUEUE_LIMIT_EXCEEDED:
                        //alert("You have selected too many files.  " +  (message > 1 ? "You may only add " +  message + " more files" : "You cannot add any more files."));
                        alert("첨부할 수 없습니다..\n" + (message === 0 ? "첨부 요청중인 파일이 너무 많습니다." : "파일은 최대 " + handler.limit + "개의 파일을 첨부 할 수 있습니다.."));
                    break;
                    
                    default:
                        if (file !== null){
                            if (handler.box == true) progress.setStatus("Unhandled Error");
                        }
                        this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                    break;
                }
            }
            catch (ex){
                this.debug(ex);
            }
        }, 
        
        fileDialogComplete : function (numFilesSelected, numFilesQueued)
        {
            try {
                if (this.getStats().files_queued > 0) {
                    if (typeof(this.customSettings.cancelButtonId) != 'undefined'){
                        document.getElementById(this.customSettings.cancelButtonId).disabled = false;
                    }
                }
                
                /* I want auto start and I can do that here */
                this.startUpload();
            }
            catch (ex){
                this.debug(ex);
            }
        }, 
        
        uploadStart : function (file)
        {
            try {
                /* I don't want to do any file validation or anything,  I'll just update the UI and return true to indicate that the upload should start */
                if (handler.box == true){
                    var progress = new FileProgress(file, this.customSettings.progressTarget);
                    progress.setStatus("첨부 중...");
                    progress.toggleCancel(true, this);
                }
            }
            catch (ex) {
                this.debug(ex);
            }
            
            return true;
        }, 
        
        uploadProgress : function (file, bytesLoaded, bytesTotal)
        {
            try {
                var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
                
                if (handler.box == true){
                    var progress = new FileProgress(file, this.customSettings.progressTarget);
                    progress.setProgress(percent);
                    progress.setStatus("첨부 중...");
                }
            }
            catch (ex){
                this.debug(ex);
            }
        }, 
        
        uploadSuccess : function (file, serverData)
        {
            try {
                var _this = this;
                
                if (handler.box == true){
                    var progress = new FileProgress(file, this.customSettings.progressTarget);
                    progress.setComplete();
                    progress.setStatus("첨부 완료.");
                    
                    
                    $('#'+file.id).off().on('click', function()
                    {
                        if (confirm('파일을 삭제하시겠습니까?')){
                            $(this).remove();
                            
                            var stat = _this.getStats();
                            stat.successful_uploads = stat.successful_uploads - 1;
                            _this.setStats(stat);
                            handler.count -= 1;
                            
                            if (handler.count <= 0 && $("#filebox").find('li').length <= 0) $("#filebox").parent().hide();
                        }
                        return false;
                    });
                    
                    // 첨부파일 저장.
                    var srvdata = eval("["+serverData+"]");
                    $('#'+file.id).find('input[name="infile[]"]').val(file.name+'|'+file.size+'|'+srvdata[0].tmpfile+'|'+srvdata[0].type);
                }
                
                handler.callback.call($('#'+handler.selector), {'file':file, 'returndata':serverData, callback:function()
                {
                    var stat = _this.getStats();
                    stat.successful_uploads = stat.successful_uploads - 1;
                    _this.setStats(stat);
                    handler.count -= 1;
                }});
            }
            catch (ex){
                this.debug(ex);
            }
        }, 
        
        uploadComplete : function (file)
        {
            try {
                /*  I want the next upload to continue automatically so I'll call startUpload here */
                if (this.getStats().files_queued === 0) {
                    if (typeof(this.customSettings.cancelButtonId) != 'undefined'){
                        document.getElementById(this.customSettings.cancelButtonId).disabled = true;
                    }
                }
                else {  
                    this.startUpload();
                }
            }
            catch (ex){
                this.debug(ex);
            }
        }, 
        
        uploadError : function (file, errorCode, message)
        {
            try {
                if (handler.box == true){
                    var progress = new FileProgress(file, this.customSettings.progressTarget);
                    progress.setError();
                    progress.toggleCancel(false);
                }
                
                switch (errorCode)
                {
                    case SWFUpload.UPLOAD_ERROR.HTTP_ERROR:
                        if (handler.box == true) progress.setStatus("Upload Error: " + message);
                        this.debug("Error Code: HTTP Error, File name: " + file.name + ", Message: " + message);
                    break;
                    
                    case SWFUpload.UPLOAD_ERROR.MISSING_UPLOAD_URL:
                        if (handler.box == true) progress.setStatus("Configuration Error");
                        this.debug("Error Code: No backend file, File name: " + file.name + ", Message: " + message);
                    break;
                    
                    case SWFUpload.UPLOAD_ERROR.UPLOAD_FAILED:
                        if (handler.box == true) progress.setStatus("Upload Failed.");
                        this.debug("Error Code: Upload Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                    break;
                    
                    case SWFUpload.UPLOAD_ERROR.IO_ERROR:
                        if (handler.box == true) progress.setStatus("Server (IO) Error");
                        this.debug("Error Code: IO Error, File name: " + file.name + ", Message: " + message);
                    break;
                    
                    case SWFUpload.UPLOAD_ERROR.SECURITY_ERROR:
                        if (handler.box == true) progress.setStatus("Security Error");
                        this.debug("Error Code: Security Error, File name: " + file.name + ", Message: " + message);
                    break;
                    
                    case SWFUpload.UPLOAD_ERROR.UPLOAD_LIMIT_EXCEEDED:
                        if (handler.box == true) progress.setStatus("Upload limit exceeded.");
                        this.debug("Error Code: Upload Limit Exceeded, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                    break;
                    
                    case SWFUpload.UPLOAD_ERROR.SPECIFIED_FILE_ID_NOT_FOUND:
                        if (handler.box == true) progress.setStatus("File not found.");
                        this.debug("Error Code: The file was not found, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                    break;
                    
                    case SWFUpload.UPLOAD_ERROR.FILE_VALIDATION_FAILED:
                        if (handler.box == true) progress.setStatus("Failed Validation.  Upload skipped.");
                        this.debug("Error Code: File Validation Failed, File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                    break;
                    
                    case SWFUpload.UPLOAD_ERROR.FILE_CANCELLED:
                        if (this.getStats().files_queued === 0) {
                            if (typeof(this.customSettings.cancelButtonId) != 'undefined'){
                                document.getElementById(this.customSettings.cancelButtonId).disabled = true;
                            }
                        }
                        if (handler.box == true) progress.setStatus("첨부 취소됨");
                        if (handler.box == true) progress.setCancelled();
                        handler.count -= 1;
                    break;
                    
                    case SWFUpload.UPLOAD_ERROR.UPLOAD_STOPPED:
                        if (handler.box == true) progress.setStatus("첨부 중지됨");
                    break;
                    
                    default:
                        if (handler.box == true) progress.setStatus("Unhandled Error: " + error_code);
                        this.debug("Error Code: " + errorCode + ", File name: " + file.name + ", File size: " + file.size + ", Message: " + message);
                    break;
                }
            }
            catch (ex){
                this.debug(ex);
            }
        }
    };
    
    
    // swfuploader options setting.
    var settings = {
        flash_url : "/js/uploader/swfupload.swf", 
        upload_url: args[0], 
        file_size_limit : args[1].filesize, 
        file_types : args[1].ext, 
        
//        file_size_limit : '1000MB', 
//        file_types : '*.*', 
        
        file_types_description : "Select Files", 
        file_upload_limit : args[1].limit, 
        file_queue_limit : args[1].limit, 
        
        debug : false, 
        
        // Button settings
        button_image_url : args[1].imgsrc, 
        button_width : args[1].width, 
        button_height : args[1].height, 
        button_cursor : SWFUpload.CURSOR.HAND, 
        button_placeholder_id: $(this).attr('id'), 
        //button_text:$(this).html('').wrapAll('<div return-id="'+$(this).attr('id')+'" style="margin-bottom:2px;></div>').parent().html(), 
        
        moving_average_history_size: 40, 
        
        file_dialog_start_handler : handler.fileDialogStart,
        file_queued_handler : handler.fileQueued,
        file_queue_error_handler : handler.fileQueueError,
        file_dialog_complete_handler : handler.fileDialogComplete,
        upload_start_handler : handler.uploadStart,
        upload_progress_handler : handler.uploadProgress,
        upload_error_handler : handler.uploadError,
        upload_success_handler : handler.uploadSuccess,
        upload_complete_handler : handler.uploadComplete
    };
    
    if (typeof(args[1].filebox) != 'undefined' && args[1].filebox == true){     
        settings.custom_settings = {progressTarget : "filebox"};
    }
    
    // event variables settings.
    handler.set('selector', $(this).attr('id'));
    handler.set('callback', (typeof(args[2]) == 'function' ? args[2] : (typeof(args[1] == 'function' ? args[1] : function(){}))));
    handler.set('size', args[1].filesize);
    handler.set('limit', args[1].limit);
    handler.set('box', (typeof(args[1].filebox) != 'undefined' && args[1].filebox == true ? true : false));
    
    handler.init();
    
    handler.obj = new SWFUpload(settings);
}