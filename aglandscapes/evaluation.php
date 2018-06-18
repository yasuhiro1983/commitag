<?php
    session_start();
    require('dbconnect.php');



    if (isset($_SESSION['login_member_id']) && ($_SESSION['time'] + 3600 > time())) {
      $_SESSION['time'] = time();

      $sql = 'SELECT * FROM `members` WHERE `member_id` ='.$_SESSION['login_member_id'];
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
    $sql = 'SELECT * FROM `articles` WHERE `article_id`='.$_GET['article_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $record = $stmt->fetch(PDO::FETCH_ASSOC);
    $article = $record['article_id'];

    $_SESSION['article_id'] = $_GET['article_id'];
    $_SESSION['member_id'] = $_GET['member_id'];

    $sql = 'SELECT * FROM `applies` WHERE `member_id` ='.$_SESSION['member_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rec = $stmt->fetch(PDO::FETCH_ASSOC);






      if (!empty($_POST)) {
        if ($_POST['content'] == ''){
          $error['content'] = 'blank';
        }
        if (empty($error)) {
        $_SESSION['join'] = $_POST;

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


    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>



    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/risa_main.css" rel="stylesheet">
    <link href="assets/css/risa_ag_original.css" rel="stylesheet">
    <link href="assets/css/body.css" rel="stylesheet">

    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->

  </head>
  <body>
    <?php include('header.php') ?>

  <div class="container" style="padding-top: 60px;">
    <div class="row">
<!--         left column         -->
      <div class="col-md-1">
      </div>
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
          <br>
          <br>
          <div>
            <button type="submit" class="btn btn-primary col-xs-12" onClick="location.href='add_post.php'">募集記事作成画面</button>
          </div>
          <br>
          <br>
          <div>
            <button type="submit" class="btn btn-primary col-xs-12" onClick="location.href='top.php'">トップページへ戻る</button>
          </div>
        </div>
      </div>
      <!-- 右側 -->
      <div class="col-md-9 col-sm-6 col-xs-12 personal-info">
          <form method="POST" action="eva_thanks.php" class="form-horizontal" role="form" enctype="multipart/form-data">
            <div class="text-center">
             <br>
             <h2>評価を共有しましょう！！</h2>
             <br>
             <br>
             <!-- お問い合わせ項目選択 -->
              <div class="form-group">
                <label class="col-sm-3 control-label">仕事ぶり</label>
                <div class="col-sm-7">
                  <select name="work" class="form-control">
                    <option value="">お問い合わせ内容をお選びください</option>
                    <option value="素晴らしかった">素晴らしかった</option>
                    <option value="指示通り動いてくれた">指示通り動いてくれた</option>
                    <option value="まあまあ">まあまあ</option>
                    <option value="ひどかった">ひどかった</option>
                   </select>
                </div>
              </div>
              <br>

              <div class="form-group">
                <label class="col-sm-3 control-label">人柄</label>
                <div class="col-sm-7">
                  <select name="personality" class="form-control">
                    <option value="">お問い合わせ内容をお選びください</option>
                    <option value="社交的">社交的</option>
                    <option value="チャレンジ精神がある">チャレンジ精神がある</option>
                    <option value="素直・無邪気">素直・無邪気</option>
                    <option value="思いやりがある">思いやりがある</option>
                    <option value="恥ずかしがり屋">恥ずかしがり屋</option>
                    <option value="几帳面">几帳面</option>
                    <option value="のんびりした性格">のんびりした性格</option>
                   </select>
                </div>
              </div>
              <br>

              <div class="form-group">
                <label class="col-sm-3 control-label">農業への興味</label>
                <div class="col-sm-7">
                  <select name="farm" class="form-control">
                    <option value="">お問い合わせ内容をお選びください</option>
                    <option value="勉強熱心">勉強熱心</option>
                    <option value="興味を持っていた">興味を持っていた</option>
                    <option value="あまり興味は持っていなかった">あまり興味は持っていなかった</option>
                    <option value="これから期待">これから期待</option>
                   </select>
                </div>
              </div>
              <br>

              <div class="form-group">
                <label class="col-sm-3 control-label">コメント</label>
                <div class="col-sm-7">
                  <!-- お問い合わせ内容エラー文 -->
                    <?php if (isset($_POST['content'])) { ?>
                  <textarea class="form-control" name="content" placeholder="例： 〇〇の場合はどうしたら良いですか？" value="<?php echo htmlspecialchars($_POST['content'],ENT_QUOTES,'UTF-8'); ?>" style="border:thin solid gray;width:337;
                  height:200;overflow:auto;
                  scrollbar-3dlight-color:gray;
                  scrollbar-arrow-color:gray;
                  scrollbar-darkshadow-color:gray;
                  scrollbar-face-color:gray;
                  scrollbar-highlight-color:gray;
                  scrollbar-shadow-color:gray;
                  scrollbar-track-color:gray;
                  overflow-y: scroll;
                  height: 150px;"></textarea>
                    <?php } else { ?>
                  <textarea class="form-control" name="content" placeholder="例： 〇〇の場合はどうしたら良いですか？" style="border:thin solid gray;width:337;
                  height:200;overflow:auto;
                  scrollbar-3dlight-color:gray;
                  scrollbar-arrow-color:gray;
                  scrollbar-darkshadow-color:gray;
                  scrollbar-face-color:gray;
                  scrollbar-highlight-color:gray;
                  scrollbar-shadow-color:gray;
                  scrollbar-track-color:gray;
                  overflow-y: scroll;
                  height: 150px;"></textarea>
                    <?php } ?>
                    <?php if (isset($error['content']) && ($error['content'] == 'blank')) { ?>
                  <p class="error">*コメントをご記入ください。</p>
                    <?php } ?>
                </div>
              </div>
              <br>
              <br>

              <!-- js値を取ってくる方法 -->
              <!-- input hiddenタグでvalueを使い、jsにも$('#starcount').val(value)を付け加える -->
              <div class="form-group">
                <label class="col-sm-3 control-label"><h4>総合評価</h4></label>
                <div class="col-sm-7">
                  <div class="row lead">
                      <div id="stars" class="starrr"></div>
                      星 <span id="count">0</span> つ
                      <input type="hidden" name="starcount" id="starcount" value="0">
                  </div>
                </div>
              </div>
            <br>

              <!-- 空白 -->
              <Table border="0" width="100%" height="50" cellspacing="0" bgcolor="#ffffff">
                <Tr><Td align="center" valign="top"></Td></Tr>
              </Table>
              <br>
              <div>
               <center>
                <input type="checkbox" name="agree_privacy" id="agree" required="required">
                <label for="agree">以上の内容で送信してもよろしいですか？</label>
               </center>
              </div>
              <br>
              <center>
                <input type="submit" class="btn btn-default" value="送信">
              </center>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
            </form>
            </div>
          </div>
        </div>
      </div>



      <?php include('footer.php') ?>


  </body>
</html>
