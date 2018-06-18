<?php
session_start();//SESSION変数を使う時に絶対必要

   
  
   //前の画面から送られてきたIDが何か判別できる
   //前の画面から送られてきたIDを使ってSQLを作成（1件）

   //SQL文実行
   require('dbconnect.php');
    $sql = 'SELECT * FROM `members`  WHERE `member_id`=?';

    
var_dump($_SESSION['login_member_id']);
   //データ取得
    $data=array($_SESSION['login_member_id']);
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

//データを取得して配列に保存
while ($record = $stmt->fetch(PDO::FETCH_ASSOC)){
  //$recordにfalseが代入された時、処理が終了します（データの一番最後まで取得してしまい、次に取得するデータが存在しない時）
  //$tweets[] 配列の一番最後に新しいデータを追加する
$account[]=array(
  "name"=>$record['name'],
  "email"=>$record['email'],
  "password"=>$record['password'],
  "address"=>$record['address'],
  "phone_number"=>$record['phone_number'],
  "birthday"=>$record['birthday'],
  "profile"=>$record['profile'],
  "gender"=>$record['gender'],
  "self-introduction"=>$record['self-introduction']);
  
}


   //取得したデータを表示に使用（ここはHTMLタグの方に記述）

//更新ボタンが押された時、編集したつぶやきをUPDATEする
if(isset($_POST)){
  if(!empty($_FILES)){

$file_name = $_FILES['profile']['name'];

//ファイルが指定された時に実行
  if (!empty($file_name)){
    //拡張子を取得
    //$file_nameに[3.png]が代入されている場合、後ろ三文字を取得する
    //substr()文字列を場所を指定して一部分切り出す関数
    //$error['picture_path']がtypeだったら「ファイルはjpg,gif,pngのいずれかを指定してください」というエラーメッセージを表示してください。」
    //チャレンジ問題！チェックする拡張子にjpegを追加してみてください。
    $ext = substr($file_name,-4);
    if ($ext !='.jpg' && $ext !='.gif' && $ext !='.png' && $ext !='.jpeg')
  {
    $error['profile'] = 'type';
  }

if (empty($error)){

  //画像をアップロードする
  //アップロード後のファイル名を作成
  $profile = date('YmdHis').$_FILES['profile']['name'];
  //$_FILES['picture_path']['tmp_name']->
  //サーバー上に仮にファイルが置かれている場所と名前
  move_uploaded_file ($_FILES ['profile']['tmp_name'],'member_picture/'.$profile);

}

  
}else{

  $profile = $account[0]['profile'];
}


//セッションに値を保存
//$_SESSION どの画面でもアクセス可能なスーパーグローバル変数
  //UPDATE文作成
    $sql= 'UPDATE `members` SET `name`=?,
                              `email`=?,
                              `address`=?,
                              `phone_number`=?,
                              `birthday`=?,
                              `profile`=?,
                              `gender`=?,
                              `self-introduction`=?,
                             `modified`=now()  WHERE `member_id`=?';
                              

   $data= array($_POST['name'],$_POST['email'],$_POST['address'],$_POST['phone_number'],$_POST['birthday'],$profile,$_POST['gender'],$_POST['self-introduction'],
         $_SESSION['login_member_id']);
   //SQL文実行

    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

 header("Location: account.php");

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
    <link href="assets/css/en_main.css" rel="stylesheet">
    <link href="assets/css/en_ag_original.css" rel="stylesheet">
    <link href="assets/css/body.css" rel="stylesheet">
    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->
  </head>

<body>


    <!-- ヘッダー -->
    <?php include('header.php') ?>



  <div class="container" style="padding-top: 60px;">
  <div class="row">
    <!-- left column -->
    <div class="col-md-2 col-sm-6 col-xs-12"> 
      <div class="text-center">
        <?php if (empty($account[0]['profile'])){ ?>
        <img src="img/misteryman.jpg" style="width:160px;height:160px " alt="avatar">

        <?php }else{ ?>
        <img src="member_picture/<?php echo $account[0]['profile']; ?>" style="width:160px;height:160px " alt="avatar">

        <?php } ?>


        <?php if(isset($error['profile']) && ($error['profile']=='type')){ ?>
              <!--issetこの変数は存在していた時-->
              <p class="error">*ファイルはjpg,gif,pngのいずれかを指定してください」</p>
 　　　　　　　<?php }?>
    
        <div> 
      　 <a type="submit" href="account.php" class=" btn btn-primary col-xs-12">アカウント情報の編集</a>
        </div>
        <br><br>
        <div>
         <a type="submit" href="add_post.php" class="btn btn-primary col-xs-12 ">募集記事作成</a>
         </div>
         <br><br>
         <div>
         <a type="submit" href="articles.php" class="btn btn-primary col-xs-12 ">募集記事一覧</a>
         </div>
          <br><br>

         <div>
        <a type="submit" href="top.php" class=" btn btn-primary col-xs-12">トップページへ戻る</a>
        </div>
      </div>
    </div>
    <!-- edit form column -->
    <!-- <div class="col-md-9 col-sm-8 col-xs-12 personal-info"> -->
    
     <div class="col-md-9 col-md-offset-1 ">
    
     <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
      <div class="text-center">
        <?php if (empty($account[0]['profile'])){ ?>
        <img src="img/misteryman.jpg" style="width:160px;height:160px " alt="avatar">

        <?php }else{ ?>
        <img src="member_picture/<?php echo $account[0]['profile']; ?>" style="width:160px;height:160px " alt="avatar">

        <?php } ?>


        <?php if(isset($error['profile']) && ($error['profile']=='type')) {?>
              <!--issetこの変数は存在していた時-->
              <p class="error">*ファイルはjpg,gif,pngのいずれかを指定してください」</p>
 　　　　　　　<?php }?>
        
        <input name="profile" type="file" class="text-center center-block well well-sm">
      </div>
  
    <div class="row">
      <div class="col-md-9">
      <h3>現在登録されているアカウント情報</h3>
      <!-- <form class="form-horizontal" role="form"> -->
        <div class="form-group">
          <label class="col-lg-3 control-label">氏名</label>
          <div class="col-lg-8">
            <input name="name" class="form-control" value="<?php echo $account[0]['name'];?>" placeholder="例：Aglandscapes" type="text">
          
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">メールアドレス</label>
          <div class="col-lg-8">
            <input name="email" class="form-control" value="<?php echo $account[0]['email'];?>" placeholder="例：aglandscapes@gmail.com" type="email">
             <?php echo $record['email'];?> 
          </div>
        </div>

        <div class="form-group">
          <label class="col-lg-3 control-label">パスワード</label>
          <div class="col-lg-8">
      　 　<a type="submit" href="password.php" class="btn btn-primary col-xs-6">パスワードを変更する</a> 
          </div>
        </div>
      
        <div class="form-group">
          <label class="col-lg-3 control-label">電話番号</label>
          <div class="col-lg-8">
            <input name="phone_number" class="form-control"  value="<?php echo $account[0]['phone_number'];?>" placeholder="例：080-3086-5168" type="text">
            <h6 style="color: red">*応募時は必須です。</h6>
          
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">性別</label>
          <div class="col-lg-8">
            <div class="ui-select">
              <select name="gender" id="user_time_zone" class="form-control">
                
                  <?php if( $account[0]['gender'] == '男性'){ ?>
                    <option value="男性" selected>男性</option> 
                    <option value="女性">女性</option>  
                 <?php }else{ ?>
                    <option value="男性" >男性</option> 
                    <option value="女性" selected>女性</option>  
                 <?php } ?> 
              </select>
            </div>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">生年月日</label>
          <div class="col-lg-8">
            <input name="birthday" class="form-control"  value="<?php echo $account[0]['birthday'];?>" placeholder="例：2007/08/01" type="date">
　　　　　　　  
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">住所</label>
          <div class="col-lg-8">
            <input name="address" class="form-control"  value="<?php echo $account[0]['address'];?>" placeholder="例：東京都江東区" type="text">
            <h6 style="color: red">*応募時は必須です。</h6>
             
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-3 control-label">自己紹介</label>
          <div class="col-lg-8" >
           <textarea name="self-introduction" cols=28 rows="5" style="border:1px solid #CCC;padding-left:0px;padding-top:0px;padding-right:0px;padding-bottom:30px;border-radius:10px;width:100%"  ><?php echo $account[0]['self-introduction'];?>
           </textarea>
            
          </div>
        </div>
    
          <label class="text-center center-block ">
            <input type="checkbox" name="agree_privacy" required="required">
            <span class="cr"><i class="cr-icon glyphicon "></i></span>
             <label for="agree">入力内容を確認しました。</label>
          </label>

        <div class="form-group">
          <label class="col-md-3 control-label"></label>
          <div class="col-md-8">
              <!--issetこの変数は存在していた時-->
             <!-- <h4 class="error">*入力した情報を確認しましたら、チェックを入れて保存してください。</h4> -->
            <input class="text-center center-block btn btn-primary" value="保存" type="submit">
            <br>
            <br>
           <br>
           <br>

          </div>
        </div>
       </div>
      </div><!-- md-6 end -->


        </form>

      </div>
    </div>
  </div>
</div>


      <!-- フッター -->
      <?php include('footer.php') ?>


       <!-- Scripts -->
      <script src="js/jquery.js"></script>
      <script src="js/functions.js"></script>
   </body>
</html>




    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../assets/js/jquery-3.1.1.js"></script>
    <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
  </body>
</html>
