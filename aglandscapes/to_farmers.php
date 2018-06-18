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
        <legend><h2>農家の方へ</h2></legend>
          <div class="row">
            <div class="well">
              <center><h2><b> 登録方法と使用方法 </b></h2></center>
            </div><br>
            <div>
              <h4>① ”新規会員登録” 画面にて会員情報を登録をします。</h4><br>
              <h4>② ”アカウント情報の設定” 画面にて個人情報の登録をします。</h4><br>
              <h4>③ ”募集記事作成” 画面にてお仕事内容の募集記事を登録をします。</h4><br>
              <h4>④ 体験者の方から質問が来る場合もあります。その際は返事をお願い致します。</h4><br>
              <h5>　体験者の方は募集記事を見て応募されます。<br>
                  体験者の方に対する報酬・交通費等、農家の方から体験者に支払う費用は一切ございません。<br>
                  もし、可能でしたら農家の方から体験者の方に宿泊先・食事・送迎等の提供をお願い致します。<br>
                  　農業を知らない若者たちの中にも興味を持っている人たちが沢山います。そんな若者達にも機会をと思っています。<br>
                  「この日の収穫に人手が欲しい」などでも構いません。ぜひご登録を！！<br>
                  　体験者の方には身元確認の為に氏名・住所・電話番号を必ず登録してもらいます。事前の説明や送迎の際に体験者と連絡できるようにしております。<br>
                  　アカウント情報・募集記事に関しては編集可能となっております。変更がある際には編集画面にてお願い致します。



              </h5>
            </div><br><br>
            <div>
              <center>
              <img src="img/images.jpeg">
              </center>
            </div><br><br>
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

