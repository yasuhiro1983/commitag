<?php
session_start();

      if (!empty($_POST)) {
        // エラー項目の確認
        // ニックネームが未入力
            if ($_POST['name'] == '') {
            $error['name'] = 'blank';
            }
        // メールが未入力
            if ($_POST['email'] == '') {
              $error['email'] = 'blank';
            }
            if ($_POST['content'] == ''){
              $error['content'] = 'blank';
            }

      if (empty($error)) {
        $_SESSION['join'] = $_POST;

        header('Location: contact_thanks.php');
        exit();
      }

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
    <?php include('header.php') ?>


    <form method="POST" action="contact_thanks.php" class="form-horizontal" role="form" enctype="multipart/form-data">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-md-offset-3 content-margin-top">
            <legend>お問い合わせ</legend>
              <!-- お問い合わせ項目選択 -->
              <div class="form-group">
                <label class="col-sm-4 control-label">お問い合わせ項目</label>
                <div class="col-sm-8">
                  <select name="index" class="form-control">
                    <option value="">お問い合わせ内容をお選びください</option>
                    <option value="サービスについて">サービスについて</option>
                    <option value="使い方について">使い方について</option>
                    <option value="ご要望">ご要望</option>
                    <option value="その他">その他</option>
                   </select>
                </div>
              </div>
              <br>

              <!-- 名前 -->
              <div class="form-group">
                <label class="col-sm-4 control-label">氏 名</label>
                <div class="col-sm-8">
                <!-- 名前エラー文 -->
                  <?php if (isset($_POST['name'])) { ?>
                  <input type="text" name="name" class="form-control" placeholder="例： 田中 太郎" value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'); ?>">
                  <?php } else { ?>
                  <input type="text" name="name" class="form-control" placeholder="例： 田中 太郎">
                  <?php } ?>
                  <?php if (isset($error['name']) && ($error['name'] == 'blank')) { ?>
                  <p class="error">*氏名を入力してください。</p>
                  <?php } ?>
                </div>
              </div>
              <br>

              <!-- メールアドレス -->
              <div class="form-group">
                <label class="col-sm-4 control-label">メールアドレス</label>
                <div class="col-sm-8">
                <!-- メールアドレスエラー文 -->
                  <?php if (isset($_POST['email'])) { ?>
                  <input type="email" name="email" class="form-control" placeholder="例： seed@nex.com" value="<?php echo htmlspecialchars($_POST['email'],ENT_QUOTES,'UTF-8'); ?>">
                  <?php } else { ?>
                  <input type="email" name="email" class="form-control" placeholder="例： seed@nex.com">
                  <?php } ?>
                  <?php if (isset($error['email']) && ($error['email'] == 'blank')) { ?>
                    <p class="error">*メールアドレスを入力してください。</p>
                  <?php } ?>
                </div>
              </div>
              <br>

              <div class="form-group">
                <label class="col-sm-4 control-label">コメント</label>
                <div class="col-sm-8">
                  <!-- お問い合わせ内容エラー文 -->
                  <?php if (isset($_POST['content'])) { ?>
                  <textarea class="form-control" name="content" placeholder="例： 〇〇の場合はどうしたら良いですか？" value="<?php echo htmlspecialchars($_POST['content'],ENT_QUOTES,'UTF-8'); ?>" style="border:thin solid gray;width:337;
                  height:200;overflow:auto;
                  scrollbar-3dlight-color:gray;
                  scrollbar-arrow-color:gray;
                  scrollbar-darkshadow-color:gray;
                  scrollbar-face-color:gray;
                  scrollbar-highlight-color:gray;
                  scrollbar-shadow-color:gray;
                  scrollbar-track-color:gray;
                  overflow-y: scroll;
                  height: 150px;"></textarea>
                  <?php } else { ?>
                  <textarea class="form-control" name="content" placeholder="例： 〇〇の場合はどうしたら良いですか？" style="border:thin solid gray;width:337;
                  height:200;overflow:auto;
                  scrollbar-3dlight-color:gray;
                  scrollbar-arrow-color:gray;
                  scrollbar-darkshadow-color:gray;
                  scrollbar-face-color:gray;
                  scrollbar-highlight-color:gray;
                  scrollbar-shadow-color:gray;
                  scrollbar-track-color:gray;
                  overflow-y: scroll;
                  height: 150px;"></textarea>
                  <?php } ?>
                  <?php if (isset($error['content']) && ($error['content'] == 'blank')) { ?>
                  <p class="error">*お問い合わせ内容をご記入ください。</p>
                  <?php } ?>
                </div>
              </div>
              <br>
              <!-- 空白 -->
              <Table border="0" width="308" height="50" cellspacing="0" bgcolor="#ffffff">
                <Tr><Td align="center" valign="top"></Td></Tr>
              </Table>
              <br>
              <div>
               <center>
                <input type="checkbox" name="agree_privacy" id="agree" required="required">
                <label for="agree">以上の内容で送信してもよろしいですか？</label>
               </center>
              </div>
              <br>
              <center>
                <input type="submit" class="btn btn-default" value="送信">
              </center>
              <br>
              <br>
              <br>
              <br>
              <br>
          </div>
        </div>
      </div>
    </form>



      <?php include('footer.php') ?>

                  <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
                  <script src="assets/js/jquery-3.1.1.js"></script>
                  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
                  <script src="assets/js/bootstrap.js"></script>

  </body>
</html>
