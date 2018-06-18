<?php
    session_start();//SESSION変数を使うとき必ず指定 $errorという変数を用意して入力チェック、引っかかった項目の情報を保存する
// $errorはhtmlの表示の部分で入力を促す表示を作るときに使用
// 例）もしnick_nameに何も入っていなかったら $error['nick_name']='blank';という情報を保存

// フォームからデータが送信されたとき
if(!empty($_POST)){
  // エラー項目の確認
  // ニックネームが未入力
    if($_POST['name']==''){
      $error['name']='blank';
    }
    // 管理者アカウント
   if($_POST['name'] == '管理者'){
     $error['name'] = 'admin';
   }
  // メールアドレスが未入力
      if($_POST['email']==''){
      $error['email']='blank';
    }

       // 管理者アカウント
   if($_POST['email']=='aglandscapes.admin@gmail.com'){
     $error['email']='admin';
   }
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

      $_SESSION['join']=$_POST;

      $_SESSION['join']['birthday']=$_SESSION['join']['year']."/".$_SESSION['join']['month']."/".$_SESSION['join']['day'];



    // エラーがない場合
    if(empty($error)){

    // check.phpへ移動する
    header('Location: check.php');
    exit();//ここでphpの処理が終わる
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
    <link href="../assets/css/bootstrap.css" rel="stylesheet">
    <link href="../assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="../assets/css/form.css" rel="stylesheet">
    <link href="../assets/css/timeline.css" rel="stylesheet">
    <link href="../assets/css/kaz_main.css" rel="stylesheet">
    <link href="../assets/css/kaz_ag.original.css" rel="stylesheet">
    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->

  </head>


  <body>
  <!-- ヘッダー -->
    <?php require('../header.php'); ?>



  <div class="container">
    <div class="row">
      <div class="col-md-6 col-md-offset-3 content-margin-top">
        <legend>会員登録</legend>
        <form method="post" action="" class="form-horizontal" role="form" enctype="multipart/form-data">
<!--         <p><center><u><a href="#">Facebook</a></u> あるいは <u><a href="#">Twitter</a></u> でログインする。</center></p>
 -->        <center>or</center><br>


          <!-- ネーム -->
          <div class="form-group">
            <label class="col-sm-4 control-label">氏名</label>
            <div class="col-sm-8">

            <!--ユーザーがネームを入力して確認へボタンを確認した後だったら -->
            <?php if (isset($_POST['name']))  { ?>
              <input type="text" name="name" class="form-control" placeholder="例： Seed kun" value="<?php echo htmlspecialchars($_POST['name'],ENT_QUOTES, 'UTF-8'); ?>"><br>

            
            <?php }else{ ?>
              <input type="text" name="name" class="form-control" placeholder="例： Seed kun"><br>
            <?php } ?>
            <?php if (isset($error['name']) &&($error['name']=='blank')) { ?>
                <h6 style="color: red">*名前を入力してください。</h6>
            <?php } ?>
             <?php if (isset($error['name']) &&($error['name']=='admin')) { ?>
               <h6 style=“color: red”>*その名前は使用できません。。</h6>
           <?php } ?>

            </div>
          </div>


          <!-- メールアドレス -->
          <div class="form-group">
            <label class="col-sm-4 control-label">メールアドレス</label>
            <div class="col-sm-8">
            <!--ユーザーがemailを入力して確認へボタンを確認した後だったら -->
            <?php if (isset($_POST['email'])) { ?>
              <input type="email" name="email" class="form-control" placeholder="例： seed@nex.com" value="<?php echo htmlspecialchars($_POST['email'],ENT_QUOTES, 'UTF-8'); ?>"><br>
            <?php }else{ ?>
              <input type="email" name="email" class="form-control" placeholder="例： seed@nex.com"><br>
            <?php } ?>
            <?php if (isset($error['email']) &&($error['email']=='blank')) { ?>
                <h6 style="color: red">*メールアドレスを入力してください。</h6>
            <?php } ?>
              <?php if (isset($error['email']) &&($error['email']=='admin')) { ?>
               <h6 style=“color: red”>*そのメールアドレスは使用できません。</h6>
           <?php } ?>

            </div>
          </div>
          <!-- パスワード -->
          <div class="form-group">
            <label class="col-sm-4 control-label">パスワード</label>
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

          <!-- 住所 -->
          <div class="form-group">
            <label class="col-sm-4 control-label">住所（任意）<br>*体験応募時は必須</label>
            <div class="col-sm-8">
            <?php if(isset($_POST['address'])){ ?>
              <input type="text" name="address" class="form-control" placeholder="例： 東京都八王子市〇〇ー〇" value="<?php echo htmlspecialchars($_POST['address'],ENT_QUOTES, 'UTF-8'); ?>">
              <?php }else{ ?>
              <input type="text" name="address" class="form-control" placeholder="例： 東京都八王子市〇〇ー〇">
              <?php } ?>
            </div>
          </div>

          <!-- 電話番号 -->
          <div class="form-group">
            <label class="col-sm-4 control-label">電話番号（任意）<br>*体験応募時は必須</label>
            <div class="col-sm-8">
            <?php if(isset($_POST['phone_number'])){ ?>
              <input type="number" name="phone_number" class="form-control" placeholder="例： 090〇〇〇〇〇〇〇〇" value="<?php echo htmlspecialchars($_POST['phone_number'],ENT_QUOTES, 'UTF-8'); ?>">
              <?php }else{ ?>
              <input type="number" name="phone_number" class="form-control" placeholder="例： 090〇〇〇〇〇〇〇〇">
              <?php } ?>
              


            </div>
          </div>


            <div class="form-group">
              <label class="col-sm-4 control-label">生年月日（任意）</label>&nbsp;

              <form name="yyyymmdd" method="post" action="#">
                <select name="year">
                <option value="">--</option>
                                <?php $i=2000;
                for($i=2000;$i>=1920;$i--){ ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php } ?>

                </select>
                年 
                <SELECT name="month">
                <option value="">--</option>
                <?php $i=1;
                for($i=1;$i<=12;$i++){ ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php } ?>
                </SELECT>
                月 
                <SELECT name="day">
                <option value="">--</option>
                <?php $i=1;
                for($i=1;$i<=31;$i++){ ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                <?php } ?>
                </select>
                日 
                </center></p><br>
              <center><h6>会員登録するためには18歳以上でなくてはなりません。</h6></center>

          </div>
      <div class="col-md-8 col-md-offset-2">
      <div style="border:thin solid gray;width:350;height:200;overflow:auto;
              scrollbar-3dlight-color:gray;
              scrollbar-arrow-color:gray;
              scrollbar-darkshadow-color:gray;
              scrollbar-face-color:gray;
              scrollbar-highlight-color:gray;
              scrollbar-shadow-color:gray;
              scrollbar-track-color:gray;
              overflow-y: scroll;
              height: 150px;">
              <Table border="0" width="300" height="300" cellspacing="0" bgcolor="#ffffff">
              <Tr><Td align="left" valign="top">
              
              <center>サイト利用規約</center><br>
　この度は、AGLANDSCAPESへお越し頂きましてまことにありがとうございます。<br>
このウェブサイト、（以下「当サイト」）20170612（以下「弊社」）が運営しております。お客様が当サイトをご利用されるにあたっては、以下の利用規約をお読み頂き、同意された場合にのみご利用頂けます。なお、本規約につきましては予告なく変更することがありますので、あらかじめ御了承下さい。<br>
<br>
第１条【サービス】<br>
１．当サイトの利用に際してはウェブにアクセスする必要がありますが、利用者は自らの費用と責任に必要な機器・ソフトウェア・通信手段等を用意し適切に接続・操作することとします。<br>
<br>
２．当サイトでは、本サービスを利用する（以下「利用者」）、農業従事者（以下「農家の方」）が情報を提供し、農業体験を希望する者（以下「体験者」）との情報のやり取りを行う場を提供するサービスを言います。但し、本サービスの遂行は、農家の方と体験者の雇用を斡旋するものではありません。当サイトでは農業体験情報等に関する情報提供をおこなっていますが、将来、様々なサービスを追加または変更・削除することがあります。<br>
<br>
３．当サイトが提供及び付随するサービスに対する保証行為を一切しておりません。また、弊社は、当サイトの提供するサービスの不確実性・サービス停止等に起因する利用者への損害について、一切責任を負わないものとします。詳細については、「免責事項について」をご覧下さい。
<br>
<br>
<br>
第２条【個人情報の取り扱い】<br>
当サイトとの利用に際して利用者から取得した氏名、メールアドレス、住所、電話番号等の個人情報は、別途定める「プライバシーポリシー」に則り取り扱われます。<br>
<br>
<br>
第３条【著作権等知的財産権】<br>
当サイト内のプログラム、その他の知的財産権は弊社に帰属します。利用者は、当該情報を私用目的で利用される場合にかぎり使用できます。弊社に無断で、それを越えて、使用（複製、送信、譲渡、二次利用等を含む）することは禁じます。<br>
<br>
<br>
第４条【禁止事項】<br>
１．弊社は、利用者が以下の行為を行うことを禁じます。<br>
１）弊社または第三者に損害を与える行為、または損害を与える恐れのある行為<br>
２）弊社または第三者の財産、名誉、プライバシー等を侵害する行為、または侵害する恐れのある行為<br>
３）公序良俗に反する行為、またはその恐れのある行為<br>
４）他人のメールアドレスを登録するなど、虚偽の申告、届出を行う行為<br>
５）コンピュータウィルス等有害なプログラムを使用または提供する行為<br>
６）迷惑メールやメールマガジン等を一方的に送付する行為<br>
７）その他、法令に違反する行為、またはその恐れがある行為<br>
８）その他弊社が不適切と判断する行為<br>
<br>
２．上記に違反した場合、弊社は利用者に対し損害賠償請求をすることができることに利用者は同意します。<br>
<br>
<br>
第５条【免責事項】<br>
１．弊社は、当サイトに掲載されている全ての情報を慎重に作成し、また管理しますが、その正確性および完全性などに関して、いかなる保証もするものではありません。<br>
<br>
2．弊社は、予告なしに、本サイトの運営を停止または中止し、また本サイトに掲載されている情報の全部または一部を変更する場合があります。<br>
<br>
３.利用者は、本サービスを、農業体験に参加する目的以外の利用はできません。<br>
<br>
４．利用者が当サイトを利用したこと、または何らかの原因によりこれをご利用できなかったことにより生じる一切の損害および第三者によるデータの書き込み、不正なアクセス、発言、メールの送信等に関して生じる一切の損害について、弊社は、何ら責任を負うものではありません。<br>
<br>
５． 体験者は、体験者自らの責任をもって、本サービスを利用し、その利用にかかわる全ての責任(本人のケガ及び病気発生時の医療費、体験者または農家の方へケガを負わせてしまった際の賠償、農業体験時における器具等の破損時の賠償、生産物及び生産樹木への損傷時の賠償、金銭を含む体験者の持ち物の紛失等)を負っていただきます。<br>
<br>
６． 弊社は、本サービスの利用により生じた体験者及び農家の方との間のトラブルに関して一切関与する責任を負いません。当該トラブルによって、弊社に損害が発生した場合には、利用者は、これを補償いただきます。<br>
<br>
７． 本サービスの利用により利用者又は弊社に対して第三者から何らかの訴え、異議申立、請求等がなされた場合、利用者は自己の責任と負担において、当該第三者との紛争を処理し、弊社は免責していただきます。<br>
<br>
８． 弊社は、利用者につき農業体験情報等の一切につき、その真実性、正確性、違法性又は有益性について責任を負いません。<br>
<br>
９． 弊社は、体験者に対しその採用活動について何ら保証はしません。<br>
<br>
１０．  弊社は、天変地異、サーバーダウン、ウィルス、不可抗力、その他の事由によって、データ消去及び変更並びに本サービスの停止等が生じ、利用者に損害が生じた場合であっても、責任を負いません。<br>
<br>
<br>
第６条【契約解除】<br>
１．弊社は、利用者が本規約に反する行為をした場合、即時にサービスを停止することができます。<br>
<br>
２．前項の事由が発生したとき、弊社は利用者に損害賠償をすることができます。<br>
<br>
３．弊社は、本サービスにおいてユーザーから提供を受けた個人情報のうち、１年を超えるものについては抹消することができます。<br>
<br>
第６条（損害賠償）<br>
<br>
第７条【損害賠償】<br>
本規約に違反した場合、弊社に発生した損害を賠償していただきます。<br>
<br>
第８条【管轄裁判所】<br>
万が一裁判所での争いとなったときは、◯◯◯地方裁判所を第一審の専属管轄裁判所とします。<br>
<br>
第９条【特例】<br>
１．本規約に基づき、特別の規定が別途定められている場合があります。<br>
<br>
２．弊社の各サービスの説明のページに当規約と相反する規定があった場合は、各サービスの説明ページに記載してある規定を適用します。<br>
<br>
<br>
（附則）<br>
本規約は、 2017年 09月 01日より施行致します。<br>
 
 
<!-- 　　年　　月　　日制定
　　年　　月　　日改訂
　　年　　月　　日改訂 -->
              </Td></Tr>
              
              </Table></div><br>
          <div>
              <center><input type="checkbox" name="agree_privacy" id="agree" value="" required="required">
              <label for="agree">規約に同意する。</label></center>
          </div><br>
            </div>

          <center><input type="submit" class="btn btn-default" value="確認画面へ"></center><br><br><br><br><br>
        </form>
      </div>
    </div>
  </div>
  </div>


<!-- フッター -->
    <?php include('../footer.php') ?>





    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="../assets/js/jquery-3.1.1.js"></script>
    <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
  </body>
</html>