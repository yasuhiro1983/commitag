<?php

      session_start();

      require('dbconnect.php');


    if (isset($_SESSION['login_member_id']) && ($_SESSION['time'] + 3600 > time())) {
      // 存在してたらログインしてる
      // 最終アクション時間を更新
      $_SESSION['time'] = time();


      $sql = 'SELECT * FROM `members` WHERE `member_id` ='.$_SESSION['login_member_id'];
      $stmt   = $dbh->prepare($sql);
      $stmt->execute();
      $record = $stmt->fetch(PDO::FETCH_ASSOC);
      $name = $record['name'];

    }else{

      // ログインしていない
      header('Location: top.php');
      exit();
    }

    $sql = 'SELECT * FROM `members` WHERE `member_id`='.$_SESSION['login_member_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $member = array();
    while ($record = $stmt->fetch(PDO::FETCH_ASSOC)){
        $member[] = array("name"=>$record['name'],
                       "profile"=>$record['profile']);
      }


      // 記事を書いた人のIDからカード情報取り出し
    $sql = 'SELECT * FROM `articles` WHERE `member_id`='.$_SESSION['login_member_id'];
    $stmt2 = $dbh->prepare($sql);
    $stmt2->execute();
    $card = array();

    while ($record2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {


          // 応募者一覧
          $sql='SELECT * FROM `members` INNER JOIN `applies` ON `members`.`member_id`=`applies`.`member_id` WHERE `applies`.`article_id`='.$record2['article_id'];

          $stmt4 = $dbh->prepare($sql);
          $stmt4->execute();
          $applicant=array();
          while($record4 = $stmt4->fetch(PDO::FETCH_ASSOC)){

            $applicant[] = array("member_id"=>$record4['member_id'],
                                    "name"=>$record4['name'],
                                    "flag"=>$record4['flag']);


                           //                "email"=>$record4['email'],
                           //          "address"=>$record4['address'],
                           //       "phone_number"=>$record4['phone_number'],
                           //            "birthday"=>$record4['birthday'],
                           // "self-introduction"=>$record4['self-introduction'],
                           //         "article_id"=>$record4['article_id']);

          }
          // $card = array();

          // 採用者
          // $sql = 'SELECT * FROM `members` INNER JOIN `applies` ON `members`.`member_id`=`applies`.`member_id` WHERE `flag`= 1'.' AND `article_id`='.$record2['article_id'];
          // $stmt5 = $dbh->prepare($sql);
          // $stmt5->execute();

          // $record5 = $stmt5->fetch(PDO::FETCH_ASSOC);
          // // $id = $record5['member_id'];
          // $name1 = $record5['name'];



          $card[] = array("article_id"=>$record2['article_id'],
                           "member_id"=>$record2['member_id'],
                               "title"=>$record2['title'],
                       "prefecture_id"=>$record2['prefecture_id'],
                               "place"=>$record2['place'],
                              "access"=>$record2['access'],
                               "start"=>$record2['start'],
                              "finish"=>$record2['finish'],
                          "product_id"=>$record2['product_id'],
                                "work"=>$record2['work'],
                          "treatment1"=>$record2['treatment1'],
                          "treatment2"=>$record2['treatment2'],
                          "treatment3"=>$record2['treatment3'],
                          "treatment4"=>$record2['treatment4'],
                          "treatment5"=>$record2['treatment5'],
                          "treatment6"=>$record2['treatment6'],
                          "landscapes"=>$record2['landscapes'],
                             "comment"=>$record2['comment'],
                           "applicant"=>$applicant);
                                // "flag"=>$name1);

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
    <link href="assets/css/anly_main.css" rel="stylesheet">
    <link href="assets/css/anly_ag_original.css" rel="stylesheet">
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

<!--          header finish             -->

  <div class="container" style="padding-top: 60px;">
    <div class="row">
<!--         left column         -->
      <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="text-center">
          <?php foreach ($member as $record) {
                $name = $record['name'];
             $profile = $record['profile'];
          } ?>
          <?php if (empty($profile)){ ?>
          <img src="img/misteryman.jpg" style="width:160px;height:160px ">
          <?php }else{ ?>
          <img src="member_picture/<?php echo $profile; ?>" class="avatar img-thumbnail">
          <?php } ?>
          <div>
            <?php echo $name; ?>さん
          </div>
          <div>
            <button type="submit" class="btn btn-primary col-xs-12" onClick="location.href='account.php'">アカウント情報の編集</button>
          </div>
          <br><br>
          <div>
            <button type="submit" class="btn btn-primary col-xs-12" onClick="location.href='add_post.php'">募集記事作成画面</button>
          </div>
          <br><br>
          <div>
            <button type="submit" class="btn btn-primary col-xs-12" onClick="location.href='top.php'">トップページへ戻る</button>
          </div>
        </div>
      </div>
      <!-- edit form column -->
      <div class="col-md-10 col-sm-6 col-xs-12 personal-info">

        <div class="text-center">
          <div class="col-sm-8 col-sm-offset-1">
            <!-- 募集記事 -->
            <div class="post-header font-alt">
              <h1 class="post-title">募集記事一覧</h1>
            </div>
            <br>
            <br>
            <div class="row">
              <div class="col-sm-12" style="width: 790px;">
               <table class="table table-striped table-bordered checkout-table">
                  <tbody>
                    <tr>
                      <th class="hidden-xs" style="width: 126px">タイトル</th>
                      <th>期間</th>
                      <th>質問</th>
                      <th>応募者</th>
                      <th>採用者　/　評価</th>
                    </tr>
                    <?php foreach ($card as $card_each) {
                      $article = $card_each['article_id'];
                      $title = $card_each['title'];
                      $start = $card_each['start'];
                      $finish = $card_each['finish']; ?>

                    <?php if(count($card) >0){ ?>
                    <tr>
                      <!-- 記事タイトル -->
                      <td class="hidden-xs"><h5><?php echo $title; ?></h5>
                      </td>
                      <!-- 期間表示 -->
                      <td>
                        <h5 class="product-title font-alt"><?php echo $start.'~'.$finish; ?></h5>
                      </td>
                      <!-- 質問回答チャット -->
                      <td>
                        <button type="submit" class="btn btn-default" onClick="location.href='answer.php?article_id=<?php echo $article;?>'">質問ルームへ</button>
                      </td>
                      <!-- 応募者一覧 -->
                      <td>

                      <?php foreach($card_each['applicant'] as $applicant_each){ ?>
                        <a href="apply_detail.php?member_id=<?php echo $applicant_each['member_id']; ?>&article_id=<?php echo $article; ?>">
                        <?php echo $applicant_each['name']; ?>
                        </a><br>
                        <?php } ?>
                      </td>
                      <!-- 採用者 -->
                      <td style="width: 200px">
                      <p>
                      <?php foreach ($card_each['applicant'] as $applicant_each) { ?>
                      <?php if ($applicant_each['flag'] == 1) { ?>
                      <?php echo $applicant_each['name'].' / '; ?>
                      <?php } ?>
                      <?php if ($applicant_each['flag'] == 1){ ?>
                        <button type="submit" class="btn btn-default" onClick="location.href='evaluation.php?article_id=<?php echo $article; ?>&member_id=<?php echo $applicant_each['member_id']; ?>'">評価する</button><br>
                      <?php } } ?>
                      </p>
                      </td>
                    </tr>
                    <?php }} ?>
                    <?php if(count($card) == 0){ ?>
                      <h5>まだ投稿した記事がありません</h5>
                     <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>


    <!-- フッター -->
    <?php include('footer.php') ?>


       <!-- Scripts -->
      <script src="js/jquery.js"></script>
      <script src="js/functions.js"></script>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
