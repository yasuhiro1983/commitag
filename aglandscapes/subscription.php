<?php

    session_start();

    require('dbconnect.php');



    if (isset($_SESSION['login_member_id']) && ($_SESSION['time'] + 3600 > time())) {
      // 存在してたらログインしてる
      // 最終アクション時間を更新
      $_SESSION['time'] = time();


      $sql = 'SELECT * FROM `members` WHERE `member_id` ='.$_SESSION['login_member_id'];
      // ログインする際にはPOST送信で送信されているのでarray($POST())になるが
      // すでにログインしている人をSESSIONで情報を保存している
      // どこの画面からでも使えるSESSIONで使える
      // ログインしている情報をいろんなページで閲覧できるようにSESSIONで保存した方が使いやすい
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $record = $stmt->fetch(PDO::FETCH_ASSOC);


    }else{

      // ログインしていない
      header('Location: top.php');
      exit();
    }



    $sql = 'SELECT * FROM `members` WHERE `member_id`='.$_SESSION['login_member_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $member = array();

    while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $member[] = array("member_id"=>$rec['member_id'],
                             "name"=>$rec['name'],
                            "email"=>$rec['email'],
                     "phone_number"=>$rec['phone_number'],
                          "address"=>$rec['address'],
                          "profile"=>$rec['profile']);
    }


    $_SESSION['article_id'] = $_GET['article_id'];

    $sql = 'SELECT * FROM `articles` WHERE `article_id`='.$_SESSION['article_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $card = array();

    while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {



    $sql = 'SELECT COUNT(*) as `favorite_count` FROM `favorites` WHERE `article_id`='.$record['article_id'].' AND `member_id`='.$_SESSION['login_member_id'];

    // sql文実行
    $stmt_flag = $dbh->prepare($sql);
    $stmt_flag->execute();
    $favorite_cnt = $stmt_flag->fetch(PDO::FETCH_ASSOC);


        $card[] = array("article_id"=>$record['article_id'],
                         "member_id"=>$record['member_id'],
                             "title"=>$record['title'],
                     "prefecture_id"=>$record['prefecture_id'],
                             "place"=>$record['place'],
                            "access"=>$record['access'],
                             "start"=>$record['start'],
                            "finish"=>$record['finish'],
                        "product_id"=>$record['product_id'],
                              "work"=>$record['work'],
                        "treatment1"=>$record['treatment1'],
                        "treatment2"=>$record['treatment2'],
                        "treatment3"=>$record['treatment3'],
                        "treatment4"=>$record['treatment4'],
                        "treatment5"=>$record['treatment5'],
                        "treatment6"=>$record['treatment6'],
                        "landscapes"=>$record['landscapes'],
                           "comment"=>$record['comment'],
                     "favorite_flag"=>$favorite_cnt
                            );

    }



    $sql = 'SELECT * FROM `prefectures` INNER JOIN `articles` ON `prefectures`.`prefecture_id`=`articles`.`prefecture_id` WHERE `article_id` ='.$_SESSION['article_id'];

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    $prefecture = $record['prefecture'];


    $sql = 'SELECT * FROM `products` INNER JOIN `articles` ON `products`.`product_id`=`articles`.`product_id` WHERE `article_id` ='.$_SESSION['article_id'];

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

    $product = $record['product'];





    if($favorite_cnt['favorite_count']==0){
      $favorite_flag=0; //favoriteされていない
    }else{
      $favorite_flag=1; //favoriteされている
    }









      foreach ($card as $record) {


    $title = $record['title'];
    $prefecture_id = $record['prefecture_id'];
    $place = $record['place'];
    $access = $record['access'];
    $start = $record['start'];
    $finish = $record['finish'];
    $product_id = $record['product_id'];
    $work = $record['work'];
    $treatment1 = $record['treatment1'];
    $treatment2 = $record['treatment2'];
    $treatment3 = $record['treatment3'];
    $treatment4 = $record['treatment4'];
    $treatment5 = $record['treatment5'];
    $treatment6 = $record['treatment6'];
    $comment = $record['comment'];
    $landscape = $record['landscapes'];
    $article_id = $record['article_id'];
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
    <link href="assets/css/kaz_ag_original.css" rel="stylesheet">
    <link href="assets/css/kaz_ag_original.css" rel="stylesheet">
    <link href="assets/css/body.css" rel="stylesheet">

    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->
  </head>

  <body>
    <!-- ヘッダー -->
    <?php include('header.php') ?>

     <!-- メイン部分 -->
    <div class="container">
      <div class="col-md-6 col-md-offset-3 content-margin-top">
        <legend>応募確認</legend>
          <form method="post" action="sub_thanks.php" class="form-horizontal" role="form" enctype="multipart/form-data">
          <!-- 登録内容を表示 -->
            <div class="row">
              <?php foreach($member as $rec) { ?>
              <!-- 名前 -->
              <div class="form-group">
                <label class="col-sm-4 control-label">氏  名</label>
                  <div class="col-sm-8">
                    <input type="text" name="address" class="form-control" placeholder="田中　花子　様" value="<?php echo $rec['name']; ?>">
                  </div><!-- 名前 -->
              </div>
              <br>
              <!-- 電話番号（任意） -->
              <div class="form-group">
                <label class="col-sm-4 control-label">電話番号</label>
                  <div class="col-sm-8">
                    <?php if (!empty($rec['phone_number'])) { ?>
                      <input type="text" name="phone_number" class="form-control" placeholder="電話番号" value="<?php echo $rec['phone_number']; ?>">
                    <?php } elseif (empty($rec['phone_number'])) { ?>
                    <input type="text" name="phone_number" class="form-control" placeholder="電話番号">
                    <h6 class="error">*電話番号を入力してください。</h6>
                    <?php } ?>
                  </div>
              </div>
                  <!-- メールアドレス -->
              <div class="form-group">
                <label class="col-sm-4 control-label">メールアドレス</label>
                  <div class="col-sm-8">
                    <input type="email" name="email" class="form-control" placeholder="メールアドレス" value="<?php echo $rec['email']; ?>">
                  </div>
              </div>
              <br>
              <!-- 住所（任意） -->
              <div class="form-group">
                <label class="col-sm-4 control-label">住  所</label>
                  <div class="col-sm-8">
                    <?php if (!empty($rec['address'])) { ?>
                    <input type="text" name="address" class="form-control" placeholder="〇〇県〇〇市〇〇町" value="<?php echo $rec['address']; ?>">
                    <?php } elseif (empty($rec['address'])) { ?>
                    <input type="text" name="address" class="form-control" placeholder="〇〇県〇〇市〇〇町">
                      <h6 class="error">*住所を入力してください</h6>
                      <?php } ?>
                  </div>
              </div>
            <?php } ?>

              <!-- コメント -->
              <div class="form-group">
                <label class="col-sm-4 control-label">コメント</label>
                  <div class="col-sm-8">
                    <textarea class="form-control" name="message" style="border:thin solid gray;width:337;height:200;overflow:auto;
                      scrollbar-3dlight-color:gray;
                      scrollbar-arrow-color:gray;
                      scrollbar-darkshadow-color:gray;
                      scrollbar-face-color:gray;
                      scrollbar-highlight-color:gray;
                      scrollbar-shadow-color:gray;
                      scrollbar-track-color:gray;
                      overflow-y: scroll;
                      height: 150px;" placeholder="例： 私はトマト作りを勉強したいです。" value="<?php echo htmlspecialchars($_POST['message'],ENT_QUOTES,'UTF-8'); ?>"></textarea>
                  </div>
              </div>
              <br>
              <!-- 応募先 （カード部分）-->
              <div class="form-group">
                <label class="col-sm-4 control-label">応 募 先</label>
                <div class="col-sm-8">
                <center>
                  <?php require('card.php'); ?>
                </center>
                </div><!-- <div class="lib-row lib-desc"> -->
              </div><!-- <div class="row box-shadow"> -->
            </div><!--<div class="lib-panel"> -->
            <br>
            <br>
            <div class="form-group">
              <div class="row">
                <center>
                  <a type="button" href="account.php" class="btn btn-default">アカウント情報の編集</a><br><br>
                  <input type="checkbox" name="agree_privacy" id="agree" value="" required="required">
                  <label for="agree">こちらの内容で応募しても宜しいですか？</label><br><br>
                  <a type="button" href="search_result.php" class="btn btn-default">検索画面に戻る</a>
                  <input type="submit" class="btn btn-default" value="応募する"><br><br>
                </center>
                <br>
                <br>
                <br>
                <br>
              </div>
            </div>
          </form>
      </div><!-- <div class="col-md-6 col-md-offset-3 content-margin-top"> -->
    </div><!-- <div class="container"> -->


    <!-- フッター -->
    <?php include('footer.php'); ?>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
     <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
