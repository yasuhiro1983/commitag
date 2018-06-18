<?php


    session_start();



    $title = $_SESSION['join']['title'];
    $prefecture_id = $_SESSION['join']['prefecture_id'];
    $place = $_SESSION['join']['place'];
    $access = $_SESSION['join']['access'];
    $start = $_SESSION['join']['start'];
    $finish = $_SESSION['join']['finish'];
    $product_id = $_SESSION['join']['product_id'];
    $work = $_SESSION['join']['work'];
    $treatment1 = $_SESSION['join']['treatment1'];
    $treatment2 = $_SESSION['join']['treatment2'];
    $treatment3 = $_SESSION['join']['treatment3'];
    $treatment4 = $_SESSION['join']['treatment4'];
    $treatment5 = $_SESSION['join']['treatment5'];
    $treatment6 = $_SESSION['join']['treatment6'];
    $comment = $_SESSION['join']['comment'];
    $landscape = $_SESSION['join']['picture_path'];
    $favorite_flag = 1;
    $apply_id = 1;

    require('dbconnect.php');


    $sql = 'SELECT * FROM `prefectures` WHERE `prefecture_id`='.$prefecture_id;

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    $prefecture = $record['prefecture'];



    $sql = 'SELECT * FROM `products` WHERE `product_id`='.$product_id;
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    $product = $record['product'];








// 確認ボタンが押された時
    if (!empty($_POST)) {




// データーベースに会員登録するためのINSERT文を作成
    // sqlの? はサニタイズ
    // インジェクションを防ぐため
    $sql = 'INSERT INTO `articles` SET `title`=?,
                                   `member_id`=?,
                               `prefecture_id`=?,
                                       `place`=?,
                                      `access`=?,
                                       `start`=?,
                                      `finish`=?,
                                  `product_id`=?,
                                        `work`=?,
                                  `treatment1`=?,
                                  `treatment2`=?,
                                  `treatment3`=?,
                                  `treatment4`=?,
                                  `treatment5`=?,
                                  `treatment6`=?,
                                  `landscapes`=?,
                                     `comment`=?,
                                     `created`=NOW()';



    $data = array($_SESSION['join']['title'], $_SESSION['login_member_id'], $_SESSION['join']['prefecture_id'], $_SESSION['join']['place'], $_SESSION['join']['access'], $_SESSION['join']['start'], $_SESSION['join']['finish'], $_SESSION['join']['product_id'], $_SESSION['join']['work'], $_SESSION['join']['treatment1'], $_SESSION['join']['treatment2'], $_SESSION['join']['treatment3'], $_SESSION['join']['treatment4'], $_SESSION['join']['treatment5'], $_SESSION['join']['treatment6'], $_SESSION['join']['picture_path'], $_SESSION['join']['comment']);

    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);


    header('Location: top.php');
// $_SESSION情報を削除
// 情報を削除しないと前に入力した人のものが残ってしまうのであまり良くない
    unset($_SESSION['join']);
// $errorの情報を削除したい時もunset($error;)で存在を削除することができる


    }



 ?>



<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AGLANDSCAPES</title>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript" language="javascript"></script>
    <script src="js/bootstrap.js"></script>
    <!-- <link rel="stylesheet" href="../common/css/bootstrap.css"> -->
    <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="bootstrap-datepicker/css/bootstrap-datepicker.min.css">
    <script type="text/javascript" src="bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js"></script>
    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/card_main.css" rel="stylesheet">
    <link href="assets/css/card_ag_original.css" rel="stylesheet">
    <link href="assets/css/body.css" rel="stylesheet">

    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->

  </head>
  <body>


    <!-- ヘッダー -->
    <?php include('header.php') ?>


  <!-- rowでくくる -->
  <div class="container">
    <div class="row">
      <br>
      <br>
      <center>
        <h1>募集記事確認ページ</h1>
      </center><!-- <h1>募集記事作成</h1> -->
      <hr>
      <div>
        <center>
          <h3><p>あなたの記事は下記の形で投稿されます。</p></h3>
        </center>
      </div><br>
      <div class="col-md-3">
      </div>
      <form method="POST" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
        <div class="col-md-6" data-category="view">
          <?php require('card.php'); ?>
            <br>
            <br>
            <div style="color: red;">
              <center>
                <h4><p>※よろしければ[募集記事を投稿する]をクリックして下さい。</p></h4>
                <p>※記事投稿後トップページに飛びます。</p>
              </center>
              <br>
              <br>
            </div>
            <center>
              <input type="submit" class="btn btn-default" value="募集記事を投稿する">
              <input type="hidden" name="insert" value="1">
            </center>
          </div>
      </form>
    </div>
  </div>

  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>
  <br>

    <!-- フッター -->
    <?php include('footer.php') ?>




    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script>
      $(function(){
          $('#datepicker-daterange .input-daterange').datepicker({
              language: 'ja',
              format: "yyyy-mm-dd"
          });
      });
    </script>

 </body>
</html>




