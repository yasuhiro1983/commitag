<?php
    session_start();
    require('dbconnect.php');

    //   //ログインチェック
    if(isset($_SESSION['login_member_id']) && ($_SESSION['time'] + 3600 >time())){
        // ログインしている
      // 最終アクション時間を更新
    $_SESSION['time'] = time();

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
        "finish"=>$record['finish'],
        "flag"=>$record['flag']
        );

      }
        // top.php(login)を参考にログインしている人のデータを取得しましょう。取得出来たら「ようこそ○○さん」の部分をログインしている人のnameが表示されるようにしましょう
    $sql = sprintf('SELECT * FROM `favorites` INNER JOIN `articles` ON `favorites`.`article_id`=`articles`.`article_id` WHERE `favorites`.`member_id`=%d',$_SESSION['login_member_id']);
    $stmt=$dbh->prepare($sql);
    $stmt->execute();

    // データ取得
    while($record1=$stmt->fetch(PDO::FETCH_ASSOC)){
      // $recordにfalseが代入されたとき処理が終了します
    // 　　（データの一番最後まで取得してしまい、次に取得するデータが存在しないとき）
      // $tweets[]..配列の最後に新しいデータを追加する
      $favorite[]=array(
        "favorite_id"=>$record1['favorite_id'],
        "member_id"=>$record1['member_id'],
        "article_id"=>$record1['article_id'],
        "title"=>$record1['title'],
        "start"=>$record1['start'],
        "finish"=>$record1['finish']
        );
    }

    $sql='SELECT * FROM `members` WHERE `member_id`='.$_SESSION['login_member_id'];
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    while($record2=$stmt->fetch(PDO::FETCH_ASSOC))
      $account[]=array("profile"=>$record2['profile']);
   } else {

      // ログインしていない
      header('Location: top.php');
      exit();
    }

    if(isset($apply[0]['apply_id'])){
      $apply_id=$apply[0]['apply_id'];
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
              <?php if (empty($account[0]['profile'])){ ?>
              <img src="img/misteryman.jpg" style="width:160px;height:160px" alt="avatar">
              <?php }else{ ?>
              <img src="member_picture/<?php echo $account[0]['profile']; ?>" style="width:160px; height:160px" alt="avatar">
              <?php } ?>
              <?php if (isset($record2['name'])){ ?>
              <div><?php echo $record2['name']; ?>さん、ようこそ
              </div>
              <?php } ?>
              <br>
              <br>
              <div>
                <button type="submit" class=" btn btn-primary col-xs-12" onClick="location.href='account.php'">アカウント情報の編集</button>
                <br>
                <br>
              </div>
              <div>
                <button type="submit" class="btn btn-primary col-xs-12" onClick="location.href='add_post.php'">募集記事作成画面</button>
                <br>
                <br>
              </div>
              <div>
                <button type="submit" class="btn btn-primary col-xs-12" onClick="location.href='articles.php'">募集記事管理画面</button>
                <br>
                <br>
              </div>
              <div>
                <button type="submit" class=" btn btn-primary col-xs-12" onClick="location.href='top.php'">トップページへ戻る</button>
              </div>
          </div>
        </div>
          <!-- edit form column -->
        <div class="col-md-10 col-sm-6 col-xs-12 personal-info">
        <!--         left column finish        -->
          <div class="text-center">
            <div class="col-sm-8 col-sm-offset-1">
              <!-- <div class="post"> -->
              <!-- 応募した記事 -->
              <div class="post-header font-alt">
                <h1 class="post-title"><i class="glyphicon glyphicon-ok"></i>応募した記事</h1>
              </div>
              <div class="row">
                <div class="col-sm-12" style="width: 790px;">
                  <table class="table table-striped table-bordered checkout-table">
                    <tbody>
                      <tr>
                        <th class="hidden-xs" style="width: 126px">タイトル</th>
                        <th>期間</th>
                        <th>受け入れ状況</th>
                        <th>応募の取り消し</th>
                        <!-- <th>評価</th> -->
                      </tr>
                      <?php if(isset($apply)){foreach($apply as $apply_each){ ?>
                      <tr>
                        <td class="hidden-xs">
                          <a href="#open<?php echo $apply_each['article_id']; ?>"><?php echo $apply_each["title"]; ?></a>
                        </td>
                        <?php
                          $article_id=$apply_each["article_id"];
                          include('popup.php'); ?>
                        <td>
                          <h5 class="product-title font-alt"><?php echo $apply_each["start"].'~'.$apply_each["finish"]; ?></h5>
                        </td>
                        <td>
                          <h5 class="product-title font-alt"><?php if($apply_each['flag']==1){echo "受け入れ完了";}?></h5>
                        </td>
                        <td class="hidden-xs"><input type=button value="削除" onClick="location.href='unapply.php?article_id=<?php echo $apply_each["article_id"]?>'">
                          </td>
                        <!-- <td class="pr-remove"><input type="button" value="評価"></td> -->
                        <?php }}else{echo "まだお気に入り記事がありません";} ?>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

                <!-- お気に入りリスト -->
              <div class="post-header font-alt">
                <h1 class="post-title">
                  <i class="glyphicon glyphicon-heart">
                  </i>お気に入りリスト
                </h1>
                <div class="post-entry" style="width: 740px">
                </div>
                <div class="col-sm-12" style="width: 790px">
                  <table class="table table-striped table-bordered checkout-table">
                    <tbody>
                      <tr>
                        <th class="hidden-xs" style="width: 126px">タイトル</th>
                        <th>期間</th>
                        <th class="hidden-xs">削除</th>
                      </tr>
                      <tr>
                      
                      <?php if(isset($favorite)){?>
                      <?php foreach($favorite as $favorite_each){ ?>

                          <td class="hidden-xs">
                            <a href="#open<?php echo $favorite_each['article_id']; ?>"><?php echo $favorite_each['title']?></a>
                          </td>
                          <?php
                            $article_id=$favorite_each['article_id'];
                            include('popup.php'); ?>
                          <td>
                            <h5 class="product-title font-alt"><?php echo $favorite_each['start']."~".$favorite_each['finish'];?></h5>
                          </td>
                          <td class="hidden-xs"><input type=button value="削除" onClick="location.href='unfavorite.php?article_id=<?php echo $favorite_each["article_id"]?>'">
                          </td>
                        </div>
                      </tr>
                      <?php } }else{echo "まだお気に入り記事がありません";} ?>
                    </tbody>
                  </table>
                  <br><br><br><br><br><br><br><br><br>
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
