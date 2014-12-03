<div class="page-header">
  <h1><?php echo $board['name']; ?></h1>  
  <div class="col-sm-offset-11">
    <p>
        <button type="button" class="btn btn-primary" onclick="location.href='/board/community/write/<?php echo $board['id']; ?>'">글쓰기</button>
    </p>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>제목</th>
          <th>등록자</th>
          <th>조회수</th>
          <th>등록일</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          foreach($listData as $row){ 
        ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td><a href='/board/community/view/<?php echo $row['id']; ?>?<?php echo $searchParam; ?>'><?php echo $row['title']; ?></a></td>
          <td>사용자</td>
          <td><?php echo $row['viewCount']; ?></td>
          <td><?php echo $row['createDate']; ?></td>
        </tr>
        <?php
          }
        ?>        
      </tbody>
    </table>
  </div>
</div>