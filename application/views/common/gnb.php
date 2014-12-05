<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation" id="siteHead">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Van</a>
    </div>
    <div id="navbar" class="navbar-collapse collapse">
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#about">About</a></li>
        <li><a href="#contact">Contact</a></li>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">게시판 <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <?php foreach($this->_community as $communityMenu){ ?>
            <li><a href="<?php echo $communityMenu[1]; ?>"><?php echo $communityMenu[0]; ?></a></li>
            <?php } ?>
            <?php /* ?>
            <li class="divider"></li>
            <li class="dropdown-header">Nav header</li>
            <li><a href="#">Separated link</a></li>
            <li><a href="#">One more separated link</a></li>
            <?php */ ?>
          </ul>
        </li>
      </ul>
      <form class="navbar-form navbar-right" role="form" action="/user/auth/login">
        <div class="form-group">
          <input type="text" placeholder="Email" class="form-control" name="email">
        </div>
        <div class="form-group">
          <input type="password" placeholder="Password" class="form-control" name="pass">
        </div>
        <button type="submit" class="btn btn-success">Sign in</button>
        <button type="button" class="btn btn-primary" onclick="location.href='/user/auth/join'">Join</button>
      </form>
    </div><!--/.nav-collapse -->
  </div>
</nav>