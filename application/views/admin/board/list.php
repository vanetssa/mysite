<div class="page-header">
  	<div class="col-sm-offset-10">
		<button type="button" class="btn btn-success" onclick="location.href='/admin/board/write'">등록</button>
		<button type="button" class="btn btn-danger"  onclick="location.href='/admin/board/tofile'">파일로저장</button>
	</div>
</div>
<div class="col-sm-12">
	<table class="table table-striped">
		<tr>
			<th>타입</th>
			<th>게시판명</th>
			<th>댓글</th>
			<th>추천</th>
			<th>파일</th>
			<th>등록일</th>
			<th>수정일</th>
		</tr>
		<?php foreach($listData as $row){ ?>
		<tr>
			<td><?php echo $row['typeValue']; ?></td>
			<td><a href="/admin/board/view/<?php echo $row['id']; ?>"><?php echo $row['name']; ?></a></td>
			<td><?php echo $row['useComment']; ?></td>
			<td><?php echo $row['useRecommend']; ?></td>
			<td><?php echo $row['useFile']; ?></td>
			<td><?php echo $row['createDate']; ?></td>
			<td><?php echo $row['modifyDate']; ?></td>
		</tr>
		<?php } ?>
	</table>
</div>