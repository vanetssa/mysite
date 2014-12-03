<div class="page-header">
  <h1><?php echo $board['name']; ?></h1>  
  <form class="form-horizontal" role="form" id="searchForm" name="searchForm" action="/board/community/view" method="get">
  <?php foreach($this->_searchParam as $key=>$val){ ?>
  <input type="hidden" name="<?php echo $key; ?>" value="<?php echo $val; ?>">
  <?php } ?>
  </form>
  <div class="col-sm-offset-9">
    <p>
        <button type="button" class="btn btn-primary" data-name="actionBtn" data-act="moveToWrite" data-src="/board/community/write/<?php echo $board['id']; ?>">글쓰기</button>
    </p>
  </div>
</div>
<div class="row">
  <div class="col-md-10">
    <table class="table table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>제목</th>
          <th>등록자</th>
          <th>조회수</th>
          <th>작성일</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          foreach($listData as $row){ 
        ?>
        <tr>
          <td><?php echo $row['id']; ?></td>
          <td>
            <a href='javascript:void(0);' data-name="actionBtn" data-act="viewDetail" data-val="<?php echo $row['id']; ?>">
              <?php echo !(empty($row['categoryName']))?'['.$row['categoryName'].'] ':'';?><?php echo $row['title']; ?>
            </a>
          </td>
          <td>사용자</td>
          <td><?php echo $row['viewCount']; ?></td>
          <td><?php echo substr($row['createDate'],0,-6); ?></td>
        </tr>
        <?php
          }
        ?>        
      </tbody>
    </table>
  </div>
</div>