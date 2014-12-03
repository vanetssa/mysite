<div class="page-header">
	<h1><?php echo $board['name']; ?></h1>
</div>
<form class="form-horizontal" role="form" id="modifyForm" name="modifyForm" action="/board/community/modify/<?php echo $message['id']; ?>" method="get">
	<?php foreach($this->_searchParam as $key=>$val){ ?>
  	<input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>">
  	<?php } ?>
	<div class="form-group">
		<div class="col-sm-10">
			<?php echo $message['categoryName']; ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10">
			<?php echo $message['title']; ?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-10">
			<?php echo $message['content']; ?>
		</div>
	</div>
	<div class="form-group">
		<button type="button" class="btn btn-success" data-name="actionBtn" data-act="moveToModify">수정</button>
		<button type="button" class="btn"             data-name="actionBtn" data-act="moveToList">목록</button>
	</div>
</form>