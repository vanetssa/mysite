<div class="page-header">
	<h1><?php echo $board['name']; ?></h1>
</div>
<form class="form-horizontal" role="form" id="saveForm" name="saveForm" action="/board/community/save/<?php echo $board['id']; ?>" method="post">
	<div id="imgbox"></div>
	<div class="form-group">
		<div class="col-sm-10">
			<?php echo $categorySel; ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10">
			<input type="text" class="form-control" id="title" name="title" placeholder="제목">
		</div>
	</div>
	<div class="form-group">
		<div style="position:relative">
			<div style="position:absolute; right:10px; width:66px; height:26px;">
				<div id="img_upload"></div>
			</div>
		</div>		
		<textarea name="content" id="content" rows="10" cols="100" style="width:100%; height:412px; display:none;"></textarea>
	</div>
	<div class="form-group">
		<button type="button" class="btn btn-success" data-name="actionBtn" data-act="dataSubmit">저장</button>
	</div>
</form>