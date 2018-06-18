<?php
    session_start();
    require('dbconnect.php');

// 記事データ取得
    $sql='SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `articles`.`article_id`='.$_GET['article_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);

// 変数定義(待遇表示用)
    if($record['treatment1'] == 1){
      $treatment_text1 = '朝食あり';
    }else{
      $treatment_text1 = '';
    }
    if($record['treatment2'] == 1){
      $treatment_text2 = '昼食あり';
    }else{
      $treatment_text2 = '';
    }
    if($record['treatment3'] == 1){
      $treatment_text3 = '夕食あり';
    }else{
      $treatment_text3 = '';
    }
    if($record['treatment4'] == 1){
      $treatment_text4 = '送迎あり';
    }else{
      $treatment_text4 = '';
    }
    if($record['treatment5'] == 1){
      $treatment_text5 = '道具貸与';
    }else{
      $treatment_text5 = '';
    }
    if($record['treatment6'] == 1){
      $treatment_text6 = '個室あり';
    }else{
      $treatment_text6 = '';
    }

// 会員のとき
    if(isset($_SESSION['login_member_id']) && isset($_SESSION['time'])&&($_SESSION['time'] + 3600 > time() )){
        // ログインしている
      // 最終アクション時間を更新
      $_SESSION['time'] = time();
// お気に入りされているかどうか確認
      $sql='SELECT COUNT(*) as `favorite_count` FROM `favorites` WHERE `article_id`='.$record['article_id'].' AND `member_id`='.$_SESSION['login_member_id'];
    // sql文実行
      $stmt_flag = $dbh->prepare($sql);
      $stmt_flag->execute();
      $favorite_cnt = $stmt_flag->fetch(PDO::FETCH_ASSOC);
// 申し込みされているかどうか確認
      $sql='SELECT COUNT(*) as `apply_count` FROM `applies` WHERE `article_id`='.$record['article_id'].' AND `member_id`='.$_SESSION['login_member_id'];
    // sql文実行
      $stmt_flag=$dbh->prepare($sql);
      $stmt_flag->execute();
      $apply_cnt=$stmt_flag->fetch(PDO::FETCH_ASSOC);

      // favoriteされているとき
      if($favorite_cnt['favorite_count'] == 0){
            $favorite_flag = 0;
          }else{
            $favorite_flag = 1;
          }
// 会員以外のとき
    }else{
      $favorite_flag = 0;
    }


?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>カード詳細</title>
  <link href="assets/css/bootstrap.css" rel="stylesheet">
  <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
  <link href="assets/css/form.css" rel="stylesheet">
  <link href="assets/css/timeline.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/card_main.css">
  <link rel="stylesheet" href="assets/css/card_ag_original.css">
  <link rel="stylesheet" href="assets/css/body.css">
</head>
<body>
<!-- ヘッダー -->
<?php include('header.php') ?>
<div class="container" style="margin-top: 100px">
  <div class="row">
  <div class="col-md-3 col-sm-3"></div>
  <div class="col-md-6 col-sm-6 col-xs-12">
      <!-- 写真部分 -->
          <div class="col-md-6 col-sm-12 col-xs-12" id="card-picture">
            <img src="post_picture/<?php echo $record['landscapes'] ?>" style="width:100%;height:100%;">
          </div>
        <!-- 詳細部分 -->
          <div class="col-md-6 col-sm-12 col-xs-12" id="detail">
            <div class="lib-row lib-header">
              <h2><?php echo $record['title']; ?></h2>
              <div class="lib-header-seperator"></div>
            </div>
            <div class="lib-row lib-desc">
              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">地域</label>
                <p class="col-sm-8 no-padding"><?php echo $record['prefecture'] ?></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">場所</label>
                  <p class="col-sm-8 no-padding"><?php echo $record['place'] ?></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">アクセス</label>
                <p class="col-sm-8 no-padding"><?php echo $record['access'] ?></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">期間</label>
                <p class="col-sm-8 no-padding"><?php echo $record['start'].'<br>'.$record['finish'] ?></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">作物</label>
                <p class="col-sm-8 no-padding"><?php echo $record['product'] ?></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">作業</label>
                <p class="col-sm-8 no-padding"><?php echo $record['work'] ?></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">待遇</label>
                <p class="col-sm-8 no-padding">
                  <?php if(isset($treatment_text1)){ ?>
                  <?php echo $treatment_text1 ?>
                  <?php } ?>
                  <?php if(isset($treatment_text2)){ ?>
                  <?php echo $treatment_text2 ?>
                  <?php } ?>
                  <?php if(isset($treatment_text3)){ ?>
                  <?php echo $treatment_text3 ?>
                  <?php } ?>
                  <?php if(isset($treatment_text4)){ ?>
                  <?php echo $treatment_text4 ?>
                  <?php } ?>
                  <?php if(isset($treatment_text5)){ ?>
                  <?php echo $treatment_text5 ?>
                  <?php } ?>
                  <?php if(isset($treatment_text6)){ ?>
                  <?php echo $treatment_text6 ?>
                  <?php } ?>
                </p>
              </div>

            </div>
          </div>
      <!-- コメント部分 -->
        <div class="row2 col-md-12 col-sm-12 col-xs-12" id="comment">
          <p style="padding-top:20px; font-size:25px;"><?php echo $record['comment'] ?></p>
        </div>
      <!-- 質問・応募・お気に入りボタン -->
        <div class="row3 col-md-12 col-sm-12 col-xs-12 id" id="button">
          <center>
            <a href="chat.php?article_id=<?php echo $record['article_id'] ?>"><button type="button" class="btn btn-primary btn-lg btn3d"><span class="glyphicon glyphicon-question-sign"></span> 質問する</button></a>
            <?php if(empty($apply_id)){ ?>
            <a href="subscription.php?article_id=<?php echo $record['article_id'] ?>"><button type="button" class="btn btn-warning btn-lg btn3d"><span class="glyphicon glyphicon-ok"></span> 応募する</button></a>
            <?php }else{ ?>
            <button type="button" class="btn btn-warning btn-lg btn3d disabled"><span class="glyphicon glyphicon-ok"></span> 応募する</button>
            <?php } ?>
            <?php if($favorite_flag == 0){ ?>
            <a href="favorite.php?article_id=<?php echo $record['article_id'] ?>"><button type="button" class="btn btn-danger btn-lg btn3d"><span class="glyphicon glyphicon-heart"></span> お気に入り</button></a>
            <?php }else{ ?>
            <button type="button" class="btn btn-danger btn-lg btn3d disabled"><span class="glyphicon glyphicon-heart"></span> お気に入り</button>
            <?php } ?>
          </center>
        </div>

  </div>
  <div class="col-md-3 col-sm-3"></div>
  </div>
</div>
<!-- フッター -->
<?php include('footer.php') ?>

</body>
</html>