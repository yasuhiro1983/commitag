<?php
    session_start(); //sesstion変数を使うときに必ず記述


    // var_dump($_SESSION['join']);

    // 会員登録ボタンが押されたとき
    if(!empty($_POST)){
    //   // DB接続
      require('../dbconnect.php');

    // ＤＢに会員登録するためのINSSERT文を作成
    // SQLインジェクション（SQLのサニタイズ）を防ぐために"？"を使っている
    $sql = 'INSERT INTO `members` SET `name`=?,`email`=?,`password`=?,`address`=?,`phone_number`=?,`birthday`=?,`created`=NOW()';

    // SQL文実行
    $data=array($_SESSION['join']['name'],$_SESSION['join']['email'],sha1($_SESSION['join']['password']),$_SESSION['join']['address'],$_SESSION['join']['phone_number'],$_SESSION['join']['birthday']);
    $stmt=$dbh->prepare($sql);
    $stmt->execute($data);

    //＄＿SESSIONの情報の削除
    unset($_SESSION['join']);
    // 例）$errorの情報を削除したい場合
    // unset($error);

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
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/kaz_form.css" rel="stylesheet">
    <link href="../assets/css/timeline.css" rel="stylesheet">
    <link href="../assets/css/anly_main.css" rel="stylesheet">
    <link href="../assets/css/anly_ag_original.css" rel="stylesheet">
    <link href="../assets/css/body.css" rel="stylesheet">


    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->
  </head>
  <body>
<!-- ヘッダー -->
    <?php include('../header.php') ?>


  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 content-margin-top">
        <div class="well">
          <center><h2><b>ようこそ、AGLANDSCAPESへ </b></h2></center>
        </div>
        <div>
          <center><img src="../img/people.jpg" width="550" height="400"></center><br>
        </div>
        <div>
          <center>
            <a href="../top.php" class="btn btn-default">ログイン</a></center><br><br><br>
        </div>
      </div>
    </div>
  </div>



      <!-- フッター -->
      <?php include('../footer.php') ?>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../assets/js/jquery-3.1.1.js"></script>
    <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
  </body>
</html>

