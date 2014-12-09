<form class="form-horizontal" role="form" method="post" action="/user/auth/getauth">
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-4">
      <input type="email" class="form-control" name="email" id="email" placeholder="Email">
    </div>
  </div>
  <div class="form-group">
    <label for="passwd" class="col-sm-2 control-label">Password</label>
    <div class="col-sm-4">
      <input type="password" class="form-control" name="passwd" id="passwd" placeholder="Password">
    </div>
  </div>
  <?php /* ?>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox"> Remember me
        </label>
      </div>
    </div>
  </div>
  <?php */ ?>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-success">Login</button>
    </div>
  </div>
</form>