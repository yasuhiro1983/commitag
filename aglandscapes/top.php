
<?php
session_start();//SESSION変数を使う時に絶対必要
//デーtベースに接続
    //外部ファイルから処理の読み込み
  require('dbconnect.php');

//ログインボタンが押された時
if (!empty($_POST)){
  //email,passwordどちらも値が入力されてた時

  if ($_POST['email'] !=''&& $_POST['password']!=''){

    
  
    // 今入力された情報の会員登録が存在しているかチェック
    //SELECT文で入力されたemail,passwordを条件にして一致するデータを取得
    $sql = 'SELECT * FROM `members` WHERE `email`= ?
    AND `password`=?';
    $data= array($_POST['email'],sha1($_POST['password']));
    //入力されたデータを指定（最初はemailだけ

    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    //データ取得
    $record=$stmt->fetch(PDO::FETCH_ASSOC);
   // var_dump($reord);
     if ($record == false){
      //フェッチして、データが取得できない時は＄recordにfalseが代入される
      //ログイン失敗
      //存在してなかったら、ログイン失敗のマークを保存
      $error['login'] = 'failed';
    
    }else{
      //ログイン成功
      //ログインしている人のmember_idをSESSIONに保存
      //現在の時刻を表す情報をSESSIONに保存（1時間を使用していない場合に自動でログアウトさせるため）
      $_SESSION['login_member_id']=$record['member_id'];
      //どの画面でも保存されているデータ

      $_SESSION['time']=time();
      //1970年1月1日０時0分０秒から現在までの秒数が保存される

      header('Location:top.php#search');
      exit();
    }

  }else{
   
    //存在していたら、index.phpへ移動、存在していなかったら、ログイン失敗のマークを保存
    $error ['login'] = 'blank';

  }
}
    // articlesより全てのデータを取ってくる

$page = '';

//パラメータが存在したら、ページ番号を取得

if(isset($_GET['page'])){
  $page = $_GET['page'];
}
//パラメータが存在しない場合は、ページ番号を１とする
if($page==''){
  $page=1;
  //max(-1,1)
  //という指定の場合、大きい方の１が結果として返される。
}
//1以下のイレギュラーな数値が入ってきた場合は、ページ番号を１とする（max:中の複数の数値の中で最大の数値を返す関数）
$page =max($page,1);

//データの件数から最大ページ数を計算する


$sql = "SELECT COUNT(*) AS `cnt` FROM `articles` ";

  $stmt=$dbh->prepare($sql);
  $stmt->execute();
  $cnt=$stmt->fetch(PDO::FETCH_ASSOC);
  // var_dump($cnt['cnt']);



$start = 0;

$card_number = 6; //1 ページに何個artcileをだすか指定

$max_page = ceil($cnt['cnt'] / $card_number);

//パラメータのページ番号が最大ページ数を超えていれば、最後のページ数に設定する(min:指定された複数の数値の中で最小の数値を返す関数)

$page = min($page,$max_page);
//min(100,3)と指定されてたら、３が帰ってくる

$start =($page -1) * $card_number;

$sql = sprintf('SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` ORDER BY `articles`.`created` DESC LIMIT  %d,%d',$start,$card_number);

    // articles&prefectures&productsより全てのデータを取ってくる
   
//SQL文実行
$stmt = $dbh->prepare($sql);
$stmt->execute();
$article=array();
// $articles=array();
//データを取得して配列に保存
while ($record = $stmt->fetch(PDO::FETCH_ASSOC)){

      if(isset( $_SESSION['login_member_id']) && ($_SESSION['login_member_id']==$record['member_id'])){

         // favorite状態の取得（ログインユーザーごと）
    $sql='SELECT COUNT(*) as `favorite_count` FROM `favorites` WHERE `article_id`='.$record['article_id'].' AND `member_id`='.$_SESSION['login_member_id'];
 
    // sql文実行
    $stmt_flag=$dbh->prepare($sql);
    $stmt_flag->execute();
    $favorite_cnt=$stmt_flag->fetch(PDO::FETCH_ASSOC);

        //apply状態の取得
    $sql='SELECT COUNT(*) as `apply_count` FROM `applies` WHERE `article_id`='.$record['article_id'].' AND `member_id`='.$_SESSION['login_member_id'];
    // sql文実行
    $stmt_flag=$dbh->prepare($sql);
    $stmt_flag->execute();
    $apply_cnt=$stmt_flag->fetch(PDO::FETCH_ASSOC);
  }else{

    //ログインしてない場合
    $favorite_cnt['favorite_count']=0;
    $apply_cnt['apply_count']=0;
  }

    // 全件配列に入れる
    $article[]=array(
    "article_id"=>$record['article_id'],
    "member_id"=>$record['member_id'],
    "title"=>$record['title'],
    "prefecture_id"=>$record['prefecture_id'],
    "prefecture"=>$record['prefecture'],
    "place"=>$record['place'],
    "access"=>$record['access'],
    "start"=>$record['start'],
    "finish"=>$record['finish'],
    "product_id"=>$record['product_id'],
    "product"=>$record['product'],
    "work"=>$record['work'],
    "treatment1"=>$record['treatment1'],
    "treatment2"=>$record['treatment2'],
    "treatment3"=>$record['treatment3'],
    "treatment4"=>$record['treatment4'],
    "treatment5"=>$record['treatment5'],
    "treatment6"=>$record['treatment6'],
    "landscapes"=>$record['landscapes'],
    "comment"=>$record['comment'],
    "favorite_flag"=>$favorite_cnt['favorite_count'],
    "apply_flag"=>$apply_cnt['apply_count']
    );
  }

  // おすすめ表示
  // sql文(favorites,articlesをテーブル結合)
$sql =sprintf('SELECT * FROM `articles` INNER JOIN `favorites` ON `articles`.`article_id`=`favorites`.`article_id` WHERE `favorites`.`member_id`= 5 ORDER BY `articles`.`created` DESC limit %d,%d',$start,$card_number);

  // sql文の実行
$stmt = $dbh->prepare($sql);
$stmt->execute();

//   データを取得して配列に保存
while ($record1 = $stmt->fetch(PDO::FETCH_ASSOC)){

// prefecturesテーブルから都道府県名を取得して代入(selsect文)
$sql = 'SELECT `prefecture` FROM `prefectures` WHERE `prefecture_id`='.$record1['prefecture_id'].' ';
// SQL文実行
$stmt2 = $dbh->prepare($sql);
$stmt2->execute();
$record2 = $stmt2->fetch(PDO::FETCH_ASSOC);


// productsテーブルから作物名を取得して代入(select文)
$sql = 'SELECT `product` FROM `products` WHERE `product_id`='.$record1['product_id'].' ';
// sql文の実行
$stmt3 = $dbh->prepare($sql);
$stmt3->execute();
$record3 = $stmt3->fetch(PDO::FETCH_ASSOC);



    // 全件配列に入れる
    $admin_article[]=array(
    "article_id"=>$record1['article_id'],
    "member_id"=>$record1['member_id'],
    "title"=>$record1['title'],
    "prefecture_id"=>$record1['prefecture_id'],
    "prefecture"=>$record2['prefecture'],
    "place"=>$record1['place'],
    "access"=>$record1['access'],
    "start"=>$record1['start'],
    "finish"=>$record1['finish'],
    "product_id"=>$record1['product_id'],
    "product"=>$record3['product'],
    "work"=>$record1['work'],
    "treatment1"=>$record1['treatment1'],
    "treatment2"=>$record1['treatment2'],
    "treatment3"=>$record1['treatment3'],
    "treatment4"=>$record1['treatment4'],
    "treatment5"=>$record1['treatment5'],
    "treatment6"=>$record1['treatment6'],
    "landscapes"=>$record1['landscapes'],
    "comment"=>$record1['comment'],
     "favorite_flag"=>$favorite_cnt['favorite_count'],
    "apply_flag"=>$apply_cnt['apply_count']
    // "favorite_flag"=>$favorite_cnt
    );
  }

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
      <link href="assets/css/screen.css" rel="stylesheet" />
      <link rel="stylesheet" type="text/css" href="css/assets/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="css/assets/css/bootstrap.css">

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/top_timeline.css" rel="stylesheet">
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



  <div class="">

   <div class="content-box">
         <!-- Hero Section -->
         <section class="section section-hero">
            <div class="hero-box">
               <div class="container">
                  <div class="hero-text align-center">
                     <h1>AglandScapes</h1>
                      <h2 style="color: #FFFFFF">農業に新たな活力を！　旅行に新たな感動を!</h2>
                       <h2 style="color: #FFFFFF">そして、その出会いは人生の新たな一歩へ!</h2>
                  </div>

                    <!--Login Section-->
            <div class="container" align="center">
                        
                        <form method="POST" action="" class="form-signin mg-btm" name="login">
                           <div class="social-box">
<!--                               <div class="row mg-btm">
                                  <div class="col-md-12">
                                     <a href="#" class="btn btn-primary">
                                       <i class="icon-facebook"></i>  Facebookアカウントでログイン
                                     </a>
                                  </div>
                              </div>
                              <div class="row">
                                 <div class="col-md-12">
                                     <a href="#" class="btn btn-info" >
                                       <i class="icon-twitter"></i>   Twitterアカウントでログイン
                                     </a>
                                 </div>
                               </div> -->
                                  <?php if(isset($error['login']) && ($error['login']=='blank')) {?>
              <!--issetこの変数は存在していた時-->
              <h4 class="error">*メールアドレスとパスワードをご記入ください。</h4>
               <!--$error[login']がfailedの時、class="error"のレイアウトを使用して、「＊ログインに失敗しました。再度正しい情報でログインしてください。」
               と表示しましょう。jion/index.phpを参考に-->

              <?php }?>
               <?php if(isset($error['login']) && ($error['login']=='failed')) {?>
              <!--issetこの変数は存在していた時-->
              <h4 class="error">*ログインに失敗しました。再度正しい情報でログインしてください。</h4>
              <?php }?>
                            <div class="main">   
        
                               <input name="email" type="text" class="form-control" placeholder="メールアドレス">
                               <input name="password" type="password" class="form-control" placeholder="パスワード">
                              <a href="javascript:void(0)" onclick="document.login.submit();return false;" type="submit" class="btn btn-large btn-danger pull-right">ログイン</a>
                           　</div>
                              <div class="main">   
                             <h5 style="color:#FFFFFF">アカウントをお持ちではない場合</h5>
                             <a type="button" href="join/index.php" class="btn btn-danger pull-right">新規登録</a>
                              </div>
                           </div>
                        </form>

                         <section class="">
                         <div class="section section-hero" style="color: #FFFFFF">
                              <h3 class="text-center">農家さんと農業に関心がある、学びたいと思っている方（体験者）をつなぐ役割</h3>
                              <h3 class="text-center">を担うのがAglandscapes。Agri(農業）＋landscapes(風景）で、農業のある風景</h3>
                              <h3 class="text-center">を将来に残したい。そんな思いから出来上がったサービスです。　　　　　　　</h3>
                              
                         </div>

                         <div name="search" id="search">
                           
                           
                         </div> 
                        </div>
                    </div>       
              </div>

            <!-- Statistics Box -->

         
                    
            <div   class="container">
               <div class="statistics-box">
                  <div class="statistics-item">

                     <a name="" href="genre_search.php?type=pre"  class="value">都道府県</a>
                  </div>

                  <div class="statistics-item">
                     <a name="" href="genre_search.php?type=term"  class="value">期間</a>
                  </div>

                 <div class="statistics-item">
                     <a name="" href="genre_search.php?type=product"  class="value">作物</a>
                  </div>
                  
               </div>
            </div>
            
            <br>
            <br>

         <!-- Destinations Section -->
         <section class="section section-destination">
            <!-- Title -->
            <div class="section-title">
               <div class="container">

                  <h2 id="new_article" name="new_article" class="title">新着募集</h2>
               </div>
            </div>


            <!-- card section-->
 <div class="container"> 
 <div class="row">
            
           
             <!-- <div class="row row-margin-bottom"> -->
      <!-- card section-->
    <?php if (isset($article)) { ?>
    <?php foreach ($article as $article_each) { ?>
 <!-- <div class="col-md-6 col-sm-6 col-xs-12">       -->
     <?php $article_id=$article_each['article_id']; ?>
    <?php $title=$article_each['title']; ?>
    <?php $prefecture=$article_each['prefecture']; ?>
    <?php $place=$article_each['place']; ?>
    <?php $access=$article_each['access']; ?>
    <?php $start=$article_each['start']; ?>
    <?php $finish=$article_each['finish']; ?>
    <?php $product=$article_each['product']; ?>
    <?php $work=$article_each['work']; ?>
    <?php $treatment1=$article_each['treatment1']; ?>
    <?php $treatment2=$article_each['treatment2']; ?>
    <?php $treatment3=$article_each['treatment3']; ?>
    <?php $treatment4=$article_each['treatment4']; ?>
    <?php $treatment5=$article_each['treatment5']; ?>
    <?php $treatment6=$article_each['treatment6']; ?>
    <?php $landscape=$article_each['landscapes']; ?>
    <?php $comment=$article_each['comment']; ?>
    <?php if(isset($article_each['favorite_flag'])){$favorite_flag=$article_each['favorite_flag'];} ?> 
    <?php if(isset($article_each['apply_flag'])){$apply_id=$article_each['apply_flag'];} ?>

   
  <div class="col-md-6">

    <?php require('card.php'); ?>
  </div>

  <?php }} ?>
  </div>

            <div class="row row-margin-bottom">
               <div class="align-center">
               <ul class="paging">
           
                <li>
                <?php if ($page >1){ ?>
                <a href="top.php?page=<?php echo $page -1; ?>" class="btn btn-info btn-load-destination"><span class="text">戻る</span>
                <i class="icon-spinner6"></i></a></li>
                <?php } else { ?>
              　　戻る
                <?php } ?>
                </li>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <li>
                <?php if($page < $max_page){?>

                <a href="top.php?page=<?php echo $page +1; ?>#new_article" class="btn btn-info btn-load-destination"><span class="text">新着募集をもっと見る</span>
                <i class="icon-spinner6"></i></a></li>
                <?php }else{ ?>
                 新着情報をもっと見る
                 <?php } ?>
                </li>
         　　　 </ul>
               </div>
            </div>


         <!-- Parallax Box --> 
      <div class="container">
         <div class="parallax-box">
               <div class="text align-center">
                  <h1>おすすめ</h1>
               </div>
         </div>

       <div class="container">
         <div class="row">

          <hr>
    <?php if (isset($admin_article)) { ?>
    <?php foreach ($admin_article as $admin_article_each) { ?>
          <div class="col-md-6 col-sm-6 col-xs-12">

    <?php $article_id=$admin_article_each['article_id']; ?>
    <?php $title=$admin_article_each['title']; ?>
    <?php $prefecture=$admin_article_each['prefecture']; ?>
    <?php $place=$admin_article_each['place']; ?>
    <?php $access=$admin_article_each['access']; ?>
    <?php $start=$admin_article_each['start']; ?>
    <?php $finish=$admin_article_each['finish']; ?>
    <?php $product=$admin_article_each['product']; ?>
    <?php $work=$admin_article_each['work']; ?>
    <?php $treatment1=$admin_article_each['treatment1']; ?>
    <?php $treatment2=$admin_article_each['treatment2']; ?>
    <?php $treatment3=$admin_article_each['treatment3']; ?>
    <?php $treatment4=$admin_article_each['treatment4']; ?>
    <?php $treatment5=$admin_article_each['treatment5']; ?>
    <?php $treatment6=$admin_article_each['treatment6']; ?>
    <?php $landscape=$admin_article_each['landscapes']; ?>
    <?php $comment=$admin_article_each['comment']; ?>
    <!-- <?php // $favorite_flag=$admin_article_each['favorite_flag']['favorite_count']; ?> -->
    <?php if(isset($_SESSION['apply_flag'])){
      $apply_flag=$_SESSION['apply_flag'];}?>

    <?php require('card.php'); ?>
  </div>

  <?php }} ?>
          </div>
       </div>
            <div class="row row-margin-bottom">
               <div class="align-center">
               <ul class="paging">
           
                <li>
                <?php if ($page >1){ ?>
                <a href="top.php?page=<?php echo $page -1; ?>" class="btn btn-info btn-load-destination"><span class="text">戻る</span>
                <i class="icon-spinner6"></i></a></li>
                <?php } else { ?>
              　　戻る
                <?php } ?>
                </li>
                &nbsp;&nbsp;|&nbsp;&nbsp;
                <li>
                <?php if($page < $max_page){?>

                <a href="top.php?page=<?php echo $page +1; ?>#new_article" class="btn btn-info btn-load-destination"><span class="text">おすすめ情報をもっと見る</span>
                <i class="icon-spinner6"></i></a></li>
                <?php }else{ ?>
                 おすすめ情報をもっと見る
                 <?php } ?>
                </li>
         　　　 </ul>
               </div>
            </div>


          </div>
       </div>

            </div>
         </section>
      </div>
   <!-- フッター -->
      <?php include('footer.php') ?>

   <!-- Scripts -->
      <script src="js/jquery.js"></script>
      <script src="js/functions.js"></script>
   </body>
</html>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="../assets/js/jquery-3.1.1.js"></script> -->
    <!-- <script src="../assets/js/jquery-migrate-1.4.1.js"></script> -->
    <!-- <script src="../assets/js/bootstrap.js"></script> -->
  <!-- </body> -->
<!-- </html> -->
