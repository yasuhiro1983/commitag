<?php

    session_start();//SESSION変数を使う時に絶対必要

    require('dbconnect.php');


    if(isset($_SESSION['login_member_id']) && ($_SESSION['time'] + 3600 >time())){
        // ログインしている
      // 最終アクション時間を更新
    $_SESSION['time'] = time();


//画像とname表示用
    $sql='SELECT * FROM `members` WHERE `member_id`='.$_SESSION['login_member_id'];
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    while($record2=$stmt->fetch(PDO::FETCH_ASSOC)){
      $account[]=array("name"=>$record2['name'],"profile"=>$record2['profile']);}

        // フォームからデータが送信されたとき
        if(!empty($_POST)){

          // エラー項目の確認

          // パスワードが未入力
              if($_POST['password']==''){
              $error['password']='blank';
            }else{
              // パスワード文字長チェック
              // ここのチェックした結果を使ってHTMLに「パスワードは４文字以上を入力してください」というメッセージを表示してください
              if(strlen($_POST['password']) < 4){
                $error['password'] = 'length';
              }
            }


              // パスワード確認が未入力
              if($_POST['password_re']==''){
              $error['password_re']='blank';
              }

              //パスワードとパスワード確認が一致していない
              if($_POST['password_re']!==$_POST['password']){
                $error['password_re']='diffarent';
              }

            // エラーがない場合
        if(empty($error)){

       $sql = 'UPDATE `members` SET `password`=? WHERE `member_id`='.$_SESSION['login_member_id'];
    }

    //     // SQL文実行
        $data=array(sha1($_POST['password']));
        $stmt=$dbh->prepare($sql);
        $stmt->execute($data);

              
          header('Location: account.php');
          exit();}
    
  }else{
          header('Location: top.php');
          exit();
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
    <link href="assets/css/kaz_main.css" rel="stylesheet">
    <link href="assets/css/kaz_ag.original.css" rel="stylesheet">
    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->

  </head>


  <body>
  <!-- ヘッダー -->
    <?php require('header.php'); ?>


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
              <div><?php echo $account[0]['name']; ?>さん、ようこそ
              </div>
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
                <button type="submit" class="btn btn-primary col-xs-12" onClick="location.href='articles.php'">募集記事一覧</button>
                <br>
                <br>
              </div>
              <div>
                <button type="submit" class=" btn btn-primary col-xs-12" onClick="location.href='top.php'">トップページへ戻る</button>
              </div>
          </div>
        </div>

  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 content-margin-top">
        <legend>パスワード変更</legend>
        <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">

          <!-- パスワード -->
          <div class="form-group">
            <label class="col-sm-4 control-label">新しいパスワード</label>
            <div class="col-sm-8">
            <?php if(isset($_POST['password'])){ ?>
              <input type="password" name="password" class="form-control" placeholder="例： 4文字以上入力" value="<?php echo htmlspecialchars($_POST['password'],ENT_QUOTES, 'UTF-8'); ?>"><br>
              <?php }else{ ?>
              <input type="password" name="password" class="form-control" placeholder="例： 4文字以上入力"><br>
              <?php } ?>
              <?php if (isset($error['password']) &&($error['password']=='blank')) { ?>
                <h6 style="color: red">*パスワードを入力してください。</h6>
                <?php } ?>
              <?php if (isset($error['password']) &&($error['password']=='length')) { ?>
                <h6 style="color: red">*パスワードは４文字以上を入力してください。</h6>
                <?php } ?>
            </div>
          </div>
          <!-- パスワード確認用 -->
          <div class="form-group">
            <label class="col-sm-4 control-label">パスワード確認</label>
            <div class="col-sm-8">
            <?php if(isset($_POST['password_re'])){ ?>
              <input type="password" name="password_re" class="form-control" placeholder="例： 4文字以上入力" value="<?php echo htmlspecialchars($_POST['password_re'],ENT_QUOTES, 'UTF-8'); ?>"><br>
              <?php }else{ ?>
              <input type="password" name="password_re" class="form-control" placeholder="例： 4文字以上入力"><br>
              <?php } ?>
              <?php if (isset($error['password_re']) &&($error['password_re']=='blank')) { ?>
                <h6 style="color: red">*パスワード確認を入力してください。</h6>
                <?php } ?>
              <?php if (isset($error['password_re']) && ($error['password_re']=='diffarent')) { ?>
                <h6 style="color: red">*パスワードと異なります。</h6>
                <?php } ?>

            </div>
          </div>




              


            </div>
          </div>


            <div class="form-group">
              <label class="col-sm-4 control-label"></label>&nbsp;
              <center><input type="checkbox" name="agree_privacy" id="agree" value="" required="required">
              <label for="agree">新しいパスワードに変更する。</label></center>


                </select>


          </div>


          <center><input type="submit" class="btn btn-default" value="変更する"></center><br><br><br><br><br>
        </form>

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