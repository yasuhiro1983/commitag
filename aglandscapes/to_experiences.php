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
        <legend><h2>体験者の方へ</h2></legend>
          <div class="row">
            <div class="well">
              <center><h2><b> 登録方法と使用方法 </b></h2></center>
            </div><br>
            <div>
              <h4>① ”新規会員登録” 画面にて会員情報を登録します。</h4><br>
              <h4>② ”アカウント情報の設定” 画面にて個人情報の登録をします。</h4><br>
              <h4>③ ジャンル別検索から農家さん情報を検索します。</h4><br>

              <h4>④ 募集記事のカードから”お気に入り”ボタンを押すとお気に入りに登録されます。お気に入り情報は”マイページ”にて確認することができます。</h4><br>
              <h4>⑤ 募集記事のカードから”質問する”ボタンを押すと農家さんに質問することができます。</h4><br>
              <h4>⑥ 募集記事のカードから”応募する”ボタンを押すと農家さんに応募されます。</h4><br>
              <h5>体験者の方に対する報酬・交通費等、農家の方から体験者の方に支払われる費用は一切ございません。<br>
                  農家の方からのご厚意により体験者の方に宿泊先・食事・送迎等の提供がある場合もあります。<br>
                  　体験者の方には身元確認の為に氏名・住所・電話番号を必ず登録してもらいます。事前の説明や送迎の際に農家の方と連絡できるようにしております。<br>
                  　アカウント情報に関しては編集可能となっております。変更がある際にはアカウント情報の編集画面にてお願い致します。<br>
                  　農家の方は一生懸命農業をされています。これを機にいろいろな事を体験して頂ければと思います。<br>
                  　皆様に良いご縁がありますように。


              </h5>
            </div><br><br>
            <div>
              <center>
              <img src="img/images1.jpeg">
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

