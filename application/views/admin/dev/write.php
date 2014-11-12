<form class="form-horizontal" role="form" id="saveForm" name="saveForm" action="/admin/dev/save" method="post">
	<div id="imgbox"></div>
	<div class="form-group">
    	<div class="col-sm-10">
      		<input type="text" class="form-control" id="title" name="title" placeholder="제목">
    	</div>
  	</div>
  	<div class="form-group">
      <div style="position:absolute; right:0; width:66px; height:26px;"><div id="img_upload"></div></div>
		  <textarea name="contents" id="contents" rows="10" cols="100" style="width:100%; height:412px; display:none;"></textarea>
  	</div>
  	<div class="form-group">
  		<button type="button" class="btn btn-success" data-name="actionBtn" data-act="dataSubmit">저장</button>
  </div>
</form>