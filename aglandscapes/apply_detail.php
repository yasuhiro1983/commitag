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


    $sql='SELECT * FROM `members` INNER JOIN `applies` ON `members`.`member_id`=`applies`.`member_id` WHERE `applies`.`article_id`='.$_GET['article_id'].' AND `applies`.`member_id`='.$_GET['member_id'];

    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $applicant=array();
    while($record = $stmt->fetch(PDO::FETCH_ASSOC)){

      $applicant[]=array("member_id"=>$record['member_id'],
                           "profile"=>$record['profile'],
                              "name"=>$record['name'],
                             "email"=>$record['email'],
                           "address"=>$record['address'],
                      "phone_number"=>$record['phone_number'],
                          "birthday"=>$record['birthday'],
                 "self-introduction"=>$record['self-introduction'],
                        "article_id"=>$record['article_id'],
                           "message"=>$record['message']);

          }

// var_dump($applicant[0]['name']);

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AGLANDSCAPES</title>

      <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Raleway:400,700" rel="stylesheet" />
      <link href="img/favicon.png" type="image/x-icon" rel="shortcut icon" />
      <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/risa_main.css" rel="stylesheet">
    <link href="assets/css/risa_ag_original.css" rel="stylesheet">
    <link href="assets/css/anly_main.css" rel="stylesheet">
    <link href="assets/css/anly_ag_original.css" rel="stylesheet">
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
        <legend>応募者詳細</legend>
      </div>
    </div>

    <div class="content-box">
      <div class="row">
        <div class="col-md-10 col-sm-6 col-xs-12">
          <div class="col-md-9 col-md-offset-3">
            <!-- card start -->
            <div class="lib-panel">
              <div class="row box-shadow">
                <div class="row1 col-md-12 col-sm-12 col-xs-12">
                  <?php if (isset($_GET['member_id'])&&isset($_GET['article_id'])) { ?>
                  <?php foreach($applicant as $applicant_each){ ?>
                  <!-- 写真部分 -->
                  <div class="col-md-6 col-sm-12 col-xs-12" id="card-picture">
                    <?php if (empty($applicant_each['profile'])){ ?>
                    <img src="img/misteryman.jpg" style="width:160px;height:160px" alt="avatar">
                    <?php }else{ ?>
                    <img src="member_picture/<?php echo $applicant_each['profile']; ?>" style="width:100%;height:100%;">
                    <?php } ?>
                  </div>
                  <!-- 詳細部分 -->
                  <div class="col-md-6 col-sm-12 col-xs-12" id="detail">
                    <div class="lib-row lib-header">
                      <h3><?php echo $applicant_each['name']; ?></h3>
                      <div class="lib-header-seperator"></div>
                    </div>
                    <div class="lib-row lib-desc">
                      <div class="form-group">
                        <label class="col-sm-4 control-label no-padding">メールアドレス</label>
                        <p class="col-sm-8 no-padding"><h5><?php echo $applicant_each['email']; ?></h5></p>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-4 control-label no-padding">住所</label>
                        <p class="col-sm-8 no-padding"><h5><?php echo $applicant_each['address']; ?></h5></p>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-4 control-label no-padding">電話番号</label>
                          <p class="col-sm-8 no-padding"><h5><?php echo $applicant_each['phone_number']; ?></h5></p>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-4 control-label no-padding">生年月日</label>
                        <p class="col-sm-8 no-padding"><h5><?php echo $applicant_each['birthday']; ?></h5></p>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-4 control-label no-padding">自己紹介</label>
                        <p class="col-sm-8 no-padding"><h5><?php echo $applicant_each['self-introduction']; ?></h5></p>
                      </div>

                      <div class="form-group">
                        <label class="col-sm-4 control-label no-padding">意気込み</label>
                        <p class="col-sm-8 no-padding"><h5><?php echo $applicant_each['message']; ?></h5></p><br>
                    <a href="past_eva.php?member_id=<?php echo $applicant_each['member_id']; ?>"> >>過去の評価を見る</a>
                      </div>
                    </div>

                  </div>
                </div>
                <p class="col-md-1"></p>
                <button type="submit" class="btn btn-default col-md-5 col-xs-12" onClick="location.href='articles.php'">戻る</button>
                <button type="submit" class="btn btn-primary col-md-5 col-xs-12" onClick="location.href='app_flag.php?article_id=<?php echo $_GET['article_id'];?>&member_id=<?php echo $applicant_each['member_id']; ?>'">採用する</button>
                <p class="col-md-1"></p>
                <?php }} ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<!-- フッター -->
<?php include('footer.php'); ?>

</body>
</html>