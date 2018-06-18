<?php
    session_start();
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
    <link href="../assets/css/form.css" rel="stylesheet">
    <link href="../assets/css/timeline.css" rel="stylesheet">
    <link href="../assets/css/kaz_main.css" rel="stylesheet">
    <link href="../assets/css/kaz_ag.original.css" rel="stylesheet">
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
        <form method="post" action="thanks.php" class="form-horizontal" role="form">
          <input type="hidden" name="action" value="submit">
          <div class="well"><center><h4>ご登録内容をご確認ください。</h4></center></div>
            <table class="table table-striped table-condensed">
              <tbody>
                <!-- 登録内容を表示 -->
                <tr>
                  <td><div class="text-center">氏名</div></td>
                  <td><div class="text-center"><?php echo $_SESSION['join']['name']; ?></div></td>
                </tr>
                <tr>
                  <td><div class="text-center">メールアドレス</div></td>
                  <td><div class="text-center"><?php echo $_SESSION['join']['email']; ?></div></td>
                </tr>
                <tr>
                  <td><div class="text-center">パスワード</div></td>
                  <td><div class="text-center">●●●●●●●●</div></td>
                </tr>
                <tr>
                  <td><div class="text-center">住所</div></td>
                  <td><div class="text-center"><?php echo $_SESSION['join']['address']; ?></div></td>
                </tr>
                <tr>
                  <td><div class="text-center">電話番号</div></td>
                  <td><div class="text-center"><?php echo $_SESSION['join']['phone_number']; ?></div></td>
                </tr>
                <tr>
                  <td><div class="text-center">生年月日</div></td>
                  <td><div class="text-center"><?php echo $_SESSION['join']['birthday']; ?></div></td>
                </tr>
              </tbody>
            </table>
        <center>
        <a href="index.php" class="btn btn-default">編集する</a>
        <input type="submit" class="btn btn-default" value="会員登録"></a></center><br>
          </div>
        </form>
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
