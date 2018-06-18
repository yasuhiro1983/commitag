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

    }else{

      // ログインしていない
      header('Location: top.php');
      exit();
    }

// article_idを$_SESSIONに代入
    $_SESSION['article_id'] = $_GET['article_id'];


// 募集記事を書いた人の名前取得
    $sql = 'SELECT * FROM `articles` INNER JOIN `members` ON `articles`.`member_id`=`members`.`member_id` WHERE `article_id` ='.$_SESSION['article_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $farm = $record['name'];



    $sql = sprintf('SELECT * FROM `applies` INNER JOIN `articles` ON `applies`.`article_id`=`articles`.`article_id` WHERE `applies`.`member_id`=%d',$_SESSION['login_member_id']);
    $stmt=$dbh->prepare($sql);
    $stmt->execute();

        // データ取得
    while($record=$stmt->fetch(PDO::FETCH_ASSOC)){
      // $recordにfalseが代入されたとき処理が終了します
    // （データの一番最後まで取得してしまい、次に取得するデータが存在しないとき）
      // $tweets[]..配列の最後に新しいデータを追加する
      $apply[]=array(
        "apply_id"=>$record['apply_id'],
        "member_id"=>$record['member_id'],
        "article_id"=>$record['article_id'],
        "title"=>$record['title'],
        "start"=>$record['start'],
        "finish"=>$record['finish']
        );

      }



// 募集記事表示に必要な情報取得
    $sql = 'SELECT * FROM `articles` WHERE `article_id`='.$_SESSION['article_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $card = array();

    while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {

      $sql = 'SELECT COUNT(*) as `favorite_count` FROM `favorites` WHERE `article_id`='.$record['article_id'].' AND `member_id`='.$_SESSION['login_member_id'];
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


// カード内の都道府県をidから取ってきて名前で表示
    $sql = 'SELECT * FROM `prefectures` INNER JOIN `articles` ON `prefectures`.`prefecture_id`=`articles`.`prefecture_id` WHERE `article_id` ='.$_SESSION['article_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $prefecture = $record['prefecture'];

// カード内の作物をidから取ってきて名前で表示
    $sql = 'SELECT * FROM `products` INNER JOIN `articles` ON `products`.`product_id`=`articles`.`product_id` WHERE `article_id` ='.$_SESSION['article_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $product = $record['product'];



// お気に入り押しているやつは出さない、出すの取得
    if($favorite_cnt['favorite_count']==0){
      $favorite_flag=0; //favoriteされていない
    }else{
      $favorite_flag=1; //favoriteされている
    }

// var_dump($_POST);

      if (!empty($_POST)) {

        if ($_POST['content'] != '') {
        $sql = 'INSERT INTO `questions` SET `member_id`=?,
                                              `content`=?,
                                           `article_id`=?,
                                            `answer_id`=?,
                                              `created`=NOW()';
        $data = array($_SESSION['login_member_id'], $_POST['content'], $_SESSION['article_id'], -1);
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);

          // 画面再表示（再送信防止）

      }
        // header('Location: chat.php');
            }


// 質問内容取得
    $sql = 'SELECT * FROM `questions` WHERE `article_id`='.$_SESSION['article_id'].' AND `member_id`='.$_SESSION['login_member_id']. ' ORDER BY `created` DESC';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $chat = array();
        while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $chat[] = array("member_id"=>$rec['member_id'],
                            "content"=>$rec['content'],
                          "answer_id"=>$rec['answer_id'],
                            "created"=>$rec['created']);

    }




    if(isset($apply[0]['apply_id'])){
      $apply_id=$apply[0]['apply_id'];
    }
    if(isset($_SESSION['apply_flag'])) {
        $apply_flag=$_SESSION['apply_flag'];
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
    $member_id = $record['member_id'];

  }



?>

<!DOCTYPE html>
<html lang="ja" dir="ltr">
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

  </head>

  <body>
    <?php include('header.php') ?>



        <!-- 空白 -->
        <Table border="0" width="100%" height="60" cellspacing="0" bgcolor="#ffffff">
          <Tr>
          <Td align="center" valign="top"></Td>
          </Tr>
        </Table>
        <!-- メイン画面 -->
          <section class="module">
            <div class="container"><br><br>
              <div class="row">

                <!-- 左側の部分 -->
                <div class="col-xs-12 col-sm-4 col-md-5">
                  <div>
                     <?php require('card.php'); ?>
                  </div>
                </div>

                  <!-- 右側の部分 -->
                <div class="col-xs-12 col-sm-8 col-md-7">
                  <div>
                    <form method="post" action="" class="form-horizontal" role="form">
                      <div class="panel-footer">
                        <div class="input-group">
                          <textarea id="btn-input" name="content" type="text" class="form-control input-sm"
                                     placeholder="質問したい内容をこちらに入力してください。"></textarea>
                          <br>
                          <br>
                        </div>
                        <span class="input-group-btn">
                          <input type="submit" class="btn btn-warning btn-sm pull-right" id="btn-chat" value="送信">
                        </span>
                        <div class="panel-heading">
                          <span class="glyphicon glyphicon-comment"></span> 質問
                        </div>
                      </div>
                      <div class="panel-body">
                      <?php if (isset($chat)) { ?>
                        <?php foreach ($chat as $rec) {
                              $member = $rec['member_id'];
                             $content = nl2br($rec['content']);
                              $answer = $rec['answer_id'];
                                $time = $rec['created']; ?>
                        <ul class="chat">
                          <?php if ($answer != -1) { ?>
                          <li class="right clearfix" align="left">
                            <span class="chat-img pull-left"></span>
                            <div class="chat-body clearfix farmer">
                              <div class="header">
                                <strong class="primary-font"><?php echo $farm; ?>さん</strong>
                                <small class="pull-center text-muted">
                                <span class="glyphicon glyphicon-time"></span><?php echo $time; ?></small>
                              </div>
                            <p><?php echo $content; ?></p>
                            </div>
                          </li>
                        </ul>
                        <ul class="chat">
                        <?php } ?>
                          <?php if ($answer == -1) {?>
                          <li class="left clearfix" align="right">
                            <span class="chat-img pull-right"></span>
                            <div class="chat-body clearfix user">
                              <div class="header">
                                <strong class="pull-right primary-font"><?php echo $name; ?></strong>
                                <small class="pull-center text-muted">
                                <span class="glyphicon glyphicon-time"></span><?php echo $time; ?></small>
                              </div>
                            <p><?php echo $content; ?></p>
                            </div>
                          </li>
                          <?php } ?>
                        </ul>
                      <?php } ?>
                    </div>
                    <?php } ?>
                  </form>
                </div>
              </div>
                        <!-- 空白 -->
                <Table border="0" width="100%" height="60" cellspacing="0" bgcolor="#ffffff">
                  <Tr>
                  <Td align="center" valign="top"></Td>
                  </Tr>
                </Table>
                  <div class="container">
                    <div class="row">
                      <div class="text-center">
                        <center><a href="top.php" class="btn btn-default">トップページへ</a></center>
                        <br><br><br><br>
                      </div>
                    </div>
                  </div>
                        <!-- 空白 -->
                    <Table border="0" width="100%" height="50" cellspacing="0" bgcolor="#ffffff">
                      <Tr>
                        <Td align="center" valign="top"></Td>
                      </Tr>
                    </Table>
          </div>
        </div>
      </section>

      <?php include('footer.php') ?>

            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="assets/js/jquery-3.1.1.js"></script>
            <script src="assets/js/jquery-migrate-1.4.1.js"></script>
            <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
