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

    while ($rec = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $member[] = array("member_id"=>$rec['member_id'],
                             "name"=>$rec['name'],
                            "email"=>$rec['email'],
                     "phone_number"=>$rec['phone_number'],
                          "address"=>$rec['address'],
                          "profile"=>$rec['profile']);
    }

    if (!empty($_POST)) {
      // エラー項目の確認
      // タイトルが未入力
          if ($_POST['title'] == '') {
          $error['title'] = 'blank';
          }
      // 場所が未入力
          if ($_POST['place'] == '') {
            $error['place'] = 'blank';
          }
      // アクセスが未入力
          if ($_POST['access'] == '') {
            $error['access'] = 'blank';
          }
      // 最初の日にちが未入力
          if ($_POST['start'] == '') {
            $error['start'] = 'blank';
          }
      // 終了の日にちが未入力
          if ($_POST['finish'] == '') {
            $error['finish'] = 'blank';
          }
      // 作業が未入力
          if ($_POST['work'] == '') {
            $error['work'] = 'blank';
          }
      // 画像が未入力
          if ($_FILES['picture_path']['name'] == '') {
            $error['picture_path'] = 'blank';
          }

      // コメントが未入力
          if ($_POST['comment'] == '') {
            $error['comment'] = 'blank';
          }

      // 画像ファイルの拡張子ファイルチェック
      // jpg,gif,png この三つを許可して他はエラーにする
      // 注意：画像ファイルの拡張子を自分で手入力して変えないこと
      // 画像サイズは2MB以下のものを用意すること！
      $file_name = $_FILES['picture_path']['name'];
      // ファイルが指定された時に実行
      if (!empty($file_name)) {
      // 拡張子を取得
      // $file_nameに『3.png』が代入されている場合、後ろの３文字を取得する
      // substr()文字列の場所を指定して一部分を切り出す関数
      // -3は後ろから３文字を指定する意味 ただの3だと前から３文字の意味
      $ext = substr($file_name, -3);
      // チャレンジ問題 チェックする拡張子にjpegを追加してみてください
      // 3文字のファイルの時と4文字のファイルの時
      $ext2 = substr($file_name, -4);
      if ($ext != 'jpg' && $ext != 'gif' && $ext != 'png' && $ext2 != 'jpeg') {
        // $error['picture_path'] = 'type';だったら『ファイルは、jpg、gif、pngのいずれかを指定してください』というエラーメッセージを表示
      $error['picture_path'] = 'type';
      }
      // $ext = substr($file_name, -4);
      // if ($ext != 'jpeg') {
      //  }
      }

      // エラーがない場合
      if (empty($error)) {
      // 画像をアップロードする
      // アップロード後のファイル名を作成
        $picture_path = date('YmdHis').$_FILES['picture_path']['name'];
      // post送信された際に一度tmp_nameでサーバー上に仮に置かれる
        move_uploaded_file($_FILES['picture_path']['tmp_name'],'post_picture/'.$picture_path);

      // セッションに値を保存
      // $_SESSION どの画面でもアクセス可能なスーパーグローバス変数
      // $_SESSIONに['join']を入れて次のthanks.phpの時に繋げられるようにする
        $_SESSION['join'] = $_POST;

        if (!isset($_POST['treatment1'])) {
          $_SESSION['join']['treatment1'] = 0;
        }
        if (!isset($_POST['treatment2'])) {
          $_SESSION['join']['treatment2'] = 0;
        }
        if (!isset($_POST['treatment3'])) {
          $_SESSION['join']['treatment3'] = 0;
        }
        if (!isset($_POST['treatment4'])) {
          $_SESSION['join']['treatment4'] = 0;
        }
        if (!isset($_POST['treatment5'])) {
          $_SESSION['join']['treatment5'] = 0;
        }
        if (!isset($_POST['treatment6'])) {
          $_SESSION['join']['treatment6'] = 0;
        }


        $_SESSION['join']['picture_path'] = $picture_path;
      // check.phpに移動
        header('Location: add_post_check.php');
        exit(); //ここで処理を終了する
      }

    }



      $sql = 'SELECT * FROM `prefectures` WHERE `prefecture_id`';
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $prefs = array();

      while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $prefs[] = array("prefecture_id"=>$record['prefecture_id'],
                            "prefecture"=>$record['prefecture']);
      }


        $sql = 'SELECT * FROM `products` WHERE `product_id`';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $pros = array();

      while ($record = $stmt->fetch(PDO::FETCH_ASSOC)) {

        $pros[] = array("product_id"=>$record['product_id'],
                           "product"=>$record['product']);
      }




 ?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>AGLANDSCAPES</title>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript" language="javascript"></script>
    <!-- <script src="../common/js/bootstrap.js"></script>
    <link rel="stylesheet" href="../common/css/bootstrap.css"> -->
    <!-- <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" type="text/css" href="bootstrap-datepicker/css/bootstrap-datepicker.min.css">
    <script type="text/javascript" src="bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript" src="bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js"></script>
        <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/jpn_main.css" rel="stylesheet">
    <link href="assets/css/jpn_ag_original.css" rel="stylesheet">
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
        <div class="col-sm-3">
          <form>  <!-- 左サイドバー -->
            <div class="form-group">
              <br>
              <br>
              <center>
                <?php foreach ($member as $rec) {
                    $profile = $rec['profile']; ?>
                <?php if (empty($profile)){ ?>
                <img src="img/misteryman.jpg" style="width:160px;height:160px ">
                          <?php }else{ ?>
                <img src="member_picture/<?php echo $profile; ?>" style="width:160px;height:160px" alt="avatar">
                <?php } } ?>
              </center>
              <div>
                <br>
                <center>
                  <p><?php echo $name; ?>さん</p>
                </center>
              </div>
              <center>
                <a type="button" href="account.php" class="btn btn-primary col-xs-12">アカウント情報の編集</a>
              </center>
              <br>
              <br>
              <center>
                <a type="button" href="top.php" class="btn btn-primary col-xs-12">TOPページに戻る</a>
              </center>
            </div>
          </form>
        </div>
        <br>
        <br>
        <div class="col-sm-9"><!-- 最初のサイドバー -->
          <div class="col-sm-9">
            <center>
              <h1>募集記事作成</h1>
            </center><!-- <h1>募集記事作成</h1> -->
          </div>
          <legend></legend>
          <form method="POST" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
            <!-- タイトル -->
            <div class="form-group">
              <label class="col-sm-2 control-label">タイトル</label>
              <div class="col-sm-7">
                <?php if (isset($_POST['title'])) { ?>
                <input type="text" name="title" class="form-control" MAXLENGTH="20" placeholder="例:タイトル" value="<?php echo htmlspecialchars($_POST['title'],ENT_QUOTES,'UTF-8'); ?>">
                <?php } else { ?>
                <input type="text" name="title" class="form-control" MAXLENGTH="20" placeholder="例:タイトル">
                <?php }  ?>
                <?php if (isset($error['title']) && ($error['title'] == 'blank')) { ?>
                <p class="error">*タイトルを入力してください。</p>
                <?php } ?>

              </div>
            </div>
            <br>
            <!-- 都道府県 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">都道府県</label>
              <div class="col-sm-9">
                <select name="prefecture_id" >
<!--                 // $prefs = array (array("id"=>1,"name"=>'北海道'), array("id"=>2,"name"=>'青森県'), array("id"=>3,"name"=>'岩手県'), array("id"=>4,"name"=>'宮城県'), array("id"=>5,"name"=>'秋田県'), array("id"=>6,"name"=>'山形県'), array("id"=>7,"name"=>'福島県'), array("id"=>8,"name"=>'茨城県'), array("id"=>9,"name"=>'栃木県'), array("id"=>10,"name"=>'群馬県'), array("id"=>11,"name"=>'埼玉県'), array("id"=>12,"name"=>'千葉県'), array("id"=>13,"name"=>'東京都'), array("id"=>14,"name"=>'神奈川県'), array("id"=>15,"name"=>'山梨県'), array("id"=>16,"name"=>'新潟県'), array("id"=>17,"name"=>'富山県'), array("id"=>18,"name"=>'石川県'), array("id"=>19,"name"=>'福井県'), array("id"=>20,"name"=>'長野県'), array("id"=>21,"name"=>'岐阜県'), array("id"=>22,"name"=>'静岡県'), array("id"=>23,"name"=>'愛知県'), array("id"=>24,"name"=>'三重県'), array("id"=>25,"name"=>'滋賀県'), array("id"=>26,"name"=>'京都府'), array("id"=>27,"name"=>'大阪府'), array("id"=>28,"name"=>'兵庫県'), array("id"=>29,"name"=>'奈良県'), array("id"=>30,"name"=>'和歌山県'), array("id"=>31,"name"=>'鳥取県'), array("id"=>32,"name"=>'島根県'), array("id"=>33,"name"=>'岡山県'), array("id"=>34,"name"=>'広島県'), array("id"=>35,"name"=>'山口県'), array("id"=>36,"name"=>'徳島県'), array("id"=>37,"name"=>'香川県'), array("id"=>38,"name"=>'愛媛県'), array("id"=>39,"name"=>'高知県'), array("id"=>40,"name"=>'福岡県'), array("id"=>41,"name"=>'佐賀県'), array("id"=>42,"name"=>'長崎県'), array("id"=>43,"name"=>'熊本県'), array("id"=>44,"name"=>'大分県'), array("id"=>45,"name"=>'宮崎県'), array("id"=>46,"name"=>'鹿児島県'), array("id"=>47,"name"=>'沖縄県')); -->
                  <?php foreach($prefs as $record) {
                    print('<option value="'.$record['prefecture_id'].'">'.$record['prefecture'].'</option>');
                   }?>
                </select>
              </div>
            </div>
            <br>

            <!-- 場所 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">場　所</label>
              <div class="col-sm-7">
              <?php if (isset($_POST['place'])) { ?>
                <input type="text" name="place" class="form-control" MAXLENGTH="20" placeholder="例:場　所" value="<?php echo htmlspecialchars($_POST['place'],ENT_QUOTES,'UTF-8'); ?>">
                <?php } else { ?>
                <input type="text" name="place" class="form-control" MAXLENGTH="20" placeholder="例:場　所">
                <?php } ?>
                <?php if (isset($error['place']) && ($error['place'] == 'blank')) { ?>
                <p class="error">*場所を入力してください。</p>
                <?php } ?>
              </div>
            </div>
            <br>

            <!-- アクセス -->
            <div class="form-group">
              <label class="col-sm-2 control-label">アクセス</label>
              <div class="col-sm-7">
              <?php if (isset($_POST['access'])) { ?>
                <input type="text" name="access" class="form-control" MAXLENGTH="20" placeholder="例:アクセス" value="<?php echo htmlspecialchars($_POST['access'],ENT_QUOTES,'UTF-8'); ?>">
                <?php } else { ?>
                <input type="text" name="access" class="form-control" MAXLENGTH="20" placeholder="例:アクセス">
                <?php } ?>
                <?php if (isset($error['access']) && ($error['access'] == 'blank')) { ?>
                <p class="error">*アクセス方法を入力してください。</p>
                <?php } ?>
              </div>
            </div>
            <br>

            <!-- 期間 -->
            <div class="form-group" id="datepicker-daterange">
              <label class="col-sm-2 control-label">期　間</label>
              <div class="col-sm-7 form-inline">
                <div class="input-daterange input-group" id="datepicker">
                  <input type="text" class="input-sm form-control" name="start">
                  <span class="input-group-addon">から</span>
                  <input type="text" class="input-sm form-control" name="finish">
                </div>
              </div>
            </div>
            <br>

            <!-- 作物 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">作　物</label>
              <div class="col-sm-9">
                <select name="product_id">
<!--
                // $prefs = array (array("id"=>1,"name"=>'キャベツ'), array("id"=>2,"name"=>'トマト'), array("id"=>3,"name"=>'ナス'), array("id"=>4,"name"=>'キュウリ'), array("id"=>5,"name"=>'サツマイモ'), array("id"=>6,"name"=>'ニンジン'), array("id"=>7,"name"=>'ダイコン'), array("id"=>8,"name"=>'ジャガイモ'), array("id"=>9,"name"=>'ピーマン'), array("id"=>10,"name"=>'タマネギ'), array("id"=>11,"name"=>'葉野菜'), array("id"=>12,"name"=>'その他の野菜'), array("id"=>13,"name"=>'リンゴ'), array("id"=>14,"name"=>'サクランボ'), array("id"=>15,"name"=>'ミカン'), array("id"=>16,"name"=>'イチゴ'), array("id"=>17,"name"=>'梨'), array("id"=>18,"name"=>'スイカ'), array("id"=>19,"name"=>'ブドウ'), array("id"=>20,"name"=>'桃'), array("id"=>21,"name"=>'柿'), array("id"=>22,"name"=>'その他の果物')); -->
                 <?php foreach($pros as $record){
                    print('<option value="'.$record['product_id'].'">'.$record['product'].'</option>');
                  } ?>
                </select>
              </div>
            </div>
            <br>
            <!-- 作業 -->
            <div class="form-group">
              <label class="col-sm-2 control-label">作　業</label>
              <div class="col-sm-7">
                <?php if (isset($_POST['work'])) { ?>

                <input type="text" name="work" class="form-control" MAXLENGTH="20" placeholder="例:作　業" value="<?php echo htmlspecialchars($_POST['work'],ENT_QUOTES,'UTF-8'); ?>">
                <?php }else{ ?>
                <input type="text" name="work" class="form-control" MAXLENGTH="20" placeholder="例:作　業">
                <?php } ?>
                <?php if (isset($error['work']) && ($error['work'] == 'blank')) { ?>
                <p class="error">*場所を入力してください。</p>
                <?php } ?>
              </div>
            </div>
            <br>
            <!-- 待遇 -->
            <div class="form-group">
              <label class="col-sm-2 control-label"><span>待　遇</span></label>
                <div class="col-sm-10">
                  <input type="checkbox" name="treatment1" id="" value="1">
                  <label for="agree">朝食あり</label>
                  <input type="checkbox" name="treatment2" id="" value="1">
                  <label for="agree">昼食あり</label>
                  <input type="checkbox" name="treatment3" id="" value="1">
                  <label for="agree">夕食あり</label>
                </div>
            </div>
            <br>
            <label class="col-sm-2 control-label"><span></span></label>
            <div class="col-sm-10">
              <input type="checkbox" name="treatment4" id="" value="1">
              <label for="agree">送迎あり</label>
              <input type="checkbox" name="treatment5" id="" value="1">
              <label for="agree">道具貸与</label>
              <input type="checkbox" name="treatment6" id="" value="1">
              <label for="agree">個室あり</label>
            </div>
            <br>
            <br>
            <br>
            <div class="form-group">
            <label class="col-sm-2 control-label">画像の添付</label>
            <div class="col-sm-6">
              画像ファイル
              <input type="file" name="picture_path">
              <?php if (isset($error['picture_path']) && ($error['picture_path'] == 'blank')) { ?>
              <p class="error">画像を登録してください。</p>
              <?php } ?>
              <?php if (isset($error['picture_path']) && ($error['picture_path'] == 'type')) { ?>
              <p class="error">jpg、gif、pngのいずれかを指定してください。</p>
              <?php } ?>
            </div>
            </div>
            <br>

            <!-- コメント -->
            <div class="form-group">
            <label class="col-sm-2 control-label" style="left">
              <span>コメント</span>
            </label>
              <div class="col-sm-7 col-sm-offset-0">
                <?php if (isset($_POST['comment'])) { ?>
                <textarea class="form-control" name="comment" MAXLENGTH="100" placeholder="例： いちごの収穫を行います。" value="<?php echo htmlspecialchars($_POST['comment'],ENT_QUOTES,'UTF-8'); ?>"></textarea>
                <?php }else{ ?>
                <textarea class="form-control" name="comment" MAXLENGTH="100" placeholder="例： いちごの収穫を行います。"></textarea>
                <?php } ?>
                <?php if (isset($error['comment']) && ($error['comment'] == 'blank')) { ?>
                <p class="error">*コメントを入力してください。</p>
                <?php } ?>
              </div>
            </div>
            <br>
            <br>
            <br>
            <div class="col-sm-9">
              <center>
                <input type="submit" class="btn btn-default" value="確認画面へ">
              </center>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
          </form>
        </div>
      </div>
    </div>



    <!-- フッター -->
    <?php include('footer.php') ?>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="assets/js/jquery-3.1.1.js"></script> -->
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script>
      $(function(){
      $('#datepicker-daterange .input-daterange').datepicker({
        language: 'ja',
        format: "yyyy-mm-dd"
      });
      });
    </script>
  </body>
</html>