<?php
    session_start(); //sesstion変数を使うときに必ず記述

    require('dbconnect.php');





    $sql = 'INSERT INTO `applies` SET `member_id`=?,
                                     `article_id`=?,
                                        `message`=?,
                                        `created`=NOW()';

    // SQL文実行
    $data=array($_SESSION['login_member_id'], $_SESSION['article_id'], $_POST['message']);
    $stmt=$dbh->prepare($sql);
    $stmt->execute($data);

    //＄＿SESSIONの情報の削除
    // unset($_SESSION['join']);
    // 例）$errorの情報を削除したい場合
    // unset($error);


    $sql = 'UPDATE `members` SET `phone_number`=?, `address`=? WHERE `member_id`=?';
    $data=array($_POST['phone_number'], $_POST['address'], $_SESSION['login_member_id']);
    $stmt=$dbh->prepare($sql);
    $stmt->execute($data);









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
      <div class="row">
        <div class="col-md-6 col-md-offset-3 content-margin-top">
          <div class="well">
            <center><h2><b> 応募完了致しました！ </b></h2>
            </center>
          </div>
          <div>
            <center>
            <img src="img/yasai.jpeg" width="550" height="400">
            </center>
          </div>
          <br>
          <br>
          <br>
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
  </div>

  <!-- フッター -->
  <?php include('footer.php') ?>


      <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="assets/js/jquery-3.1.1.js"></script>
      <script src="assets/js/jquery-migrate-1.4.1.js"></script>
      <script src="assets/js/bootstrap.js"></script>
  </body>
</html>

