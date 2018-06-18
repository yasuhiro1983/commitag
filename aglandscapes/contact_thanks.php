<?php
    // セッション変数使う時は必ず冒頭にsession_startをかく
    session_start();
    // var_dump($_SESSION['join']);
    require('dbconnect.php');

// 会員登録ボタンが押された時
    if (!empty($_POST)) {
      // まずDB接続
    // $dsn = 'mysql:dbname=seed_sns;host=localhost';
    // $user = 'root';
    // $password='';
    // $dbh = new PDO($dsn, $user, $password);
    // $dbh->query('SET NAMES utf8');

      // 外部ファイルから処理を読み込む
      // requireを使うと書いたものを読み込むことができる

      // require('dbconnect.php');


// データーベースに会員登録するためのINSERT文を作成
    // sqlの? はサニタイズ
    // インジェクションを防ぐため
    $sql = 'INSERT INTO `contacts` SET `name`=?,
                                      `email`=?,
                                    `content`=?,
                                      `index`=?,
                                    `created`=NOW()';

// SQL文実行
// sha1()でパスワードを暗号化する
// sha1という暗号化の種類 暗号を読み取られないように色々な種類がある
    $data = array($_POST['name'], $_POST['email'], $_POST['content'], $_POST['index']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);




// $_SESSION情報を削除
// 情報を削除しないと前に入力した人のものが残ってしまうのであまり良くない
    // unset($_SESSION['join']);
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

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/risa_main.css" rel="stylesheet">
    <link href="assets/css/risa_ag_original.css" rel="stylesheet">

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
              <legend>お問い合わせ内容確認</legend>
                <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
                  <!-- お問い合わせ項目選択 -->
                  <div class="form-group">
                    <label class="col-sm-4 control-label">お問い合わせ項目</label>
                      <p class="col-sm-8 margin"><?php echo $_POST['index']; ?></p>
                  </div>

                    <!-- 名前 -->
                    <div class="form-group">
                      <label class="col-sm-4 control-label">お 名 前</label>
                        <p class="col-sm-8 margin"><?php echo $_POST['name']; ?></p>
                    </div>

                        <!-- メールアドレス -->
                      <div class="form-group">
                        <label class="col-sm-4 control-label">メールアドレス</label>
                          <p class="col-sm-8 margin"><?php echo $_POST['email']; ?></p>
                      </div>
                        <div class="form-group">
                          <label class="col-sm-4 control-label">コメント</label>
                              <p class="col-sm-8 margin"><?php echo $_POST['content']; ?></p>
                        </div>

                </form>
            </div>
          </div>
        </div>


          <!-- 空白 -->
        <Table border="0" width="100%" height="50" cellspacing="0" bgcolor="#ffffff">
          <Tr>
          <Td align="center" valign="top"></Td>
          </Tr>
        </Table>
            <div class="container">
              <div class="row">
                <div class="text-center">
                  <a href="top.php" class="btn btn-default">トップページへ</a>
                </div>
              </div>
            </div>
              <Table border="0" width="350" height="100" cellspacing="0" bgcolor="#ffffff">
                <Tr>
                <Td align="center" valign="top"></Td>
                </Tr>
              </Table>
              <!-- フッター -->
               <?php include('footer.php') ?>


                    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
                    <script src="assets/js/jquery-3.1.1.js"></script>
                    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
                    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>

