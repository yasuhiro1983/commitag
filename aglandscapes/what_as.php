<?php
    session_start();
    require('dbconnect.php'); 



    if (isset($_SESSION['login_member_id']) && ($_SESSION['time'] + 3600 > time())) {
      // 存在してたらログインしてる
      // 最終アクション時間を更新
      $_SESSION['time'] = time();


      $sql = 'SELECT * FROM `members` WHERE `member_id` ='.$_SESSION['login_member_id'];
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      $name = $record['name'];

    }
?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AGLANDSCAPES</title>

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/risa_main.css" rel="stylesheet">
    <link href="assets/css/risa_ag_original.css" rel="stylesheet">
    <link href="assets/css/body.css" rel="stylesheet">

    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->
  </head>
  <body>
    <!-- ヘッダー -->
    <?php include('header.php') ?>

    <div class="container">
      <div class="col-md-6 col-md-offset-3 content-margin-top">
        <legend><h2>AGLANDSCAPESとは</h2></legend><br>
          <div class="row">
            <div>
              <center>
              <img src="img/f_181.jpg" width="410" height="150">
              </center>
            </div><br><br>
            <div class="well">
              <center><h2><b> 私たちの目的 </b></h2></center>
            </div>

            <div class="what">
              <h3>　農業に関心があり農作業を学びたい・体験したい体験者と
                  農作業を手伝ってもらいたい農家さんとのマッチングサービスです。<br>
                  　体験者は農家さんにお手伝いに行き、農家さんは宿泊等を提供してもらいます。<br>
                  　交通費は自己負担、作業代金は発生しませんのでご了承下さい。<br>
              </h3>
            </div><br><br>
            <div class="well">
              <center><h2><b>AGLANDSCAPESの魅力</b></h2></center>
            </div>
            <div class="what">
              <h3>　農業に関心があり農作業を学びたい・体験したい体験者と
                  農家さんを繋ぐ役割を担うのがAGLANDSCAPES。<br><br>
                  <center>Agli(農業)　＋　Landscapes(風景)</center><br>
                  　農業のある風景を将来に残したい。そんな想いから出来上がったサービスです<br>
              </h3>
            </div>
          </div><br><br><br><br>
          <div>
            <center>
            <a href="top.php" class="btn btn-default">トップページ</a>
            </center>
            <br>
            <br>
            <br>
            <br>
          </div>
      </div>
    </div>
      </div><!-- <div class="col-md-6 col-md-offset-3 content-margin-top"> -->
    </div>

  <!-- フッター -->
  <?php include('footer.php') ?>


      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="assets/js/jquery-3.1.1.js"></script>
      <script src="assets/js/jquery-migrate-1.4.1.js"></script>
      <script src="assets/js/bootstrap.js"></script>
  </body>
</html>

