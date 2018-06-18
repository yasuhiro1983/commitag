
 <nav class="navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header page-scroll">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/aglandscapes/top.php"><span class="strong-title header">AGLANDSCAPES</span></a>
      </div>
      <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
          <li><a id="home" href="/aglandscapes/what_as.php">AGLANDSCAPESとは</a></li>
          <li><a id="" href="/aglandscapes/to_farmers.php">農家の方へ</a></li>
          <li><a id="" href="/aglandscapes/to_experiences.php">体験者の方へ</a></li>
          <li><a id="" href="/aglandscapes/question.php">よくある質問</a></li>
          <li><a id="" href="/aglandscapes/contact.php">お問い合わせ</a></li>
          <?php if (isset($_SESSION['login_member_id'])) { ?>
          <li><a id="" href="/aglandscapes/mypage.php">マイページ</a></li>
          <li><a id="" href="/aglandscapes/logout.php">ログアウト</a></li>
          <?php } ?>
        </ul>
      </div>
    </div>
          <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav navbar-right">
      </ul>
    </div>
  </nav>