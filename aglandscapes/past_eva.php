<?php
    session_start(); //sesstion変数を使うときに必ず記述

    require('dbconnect.php');

    var_dump($_GET['member_id']);

// evaluationの中身をとってくる
    $sql = 'SELECT * FROM `evaluation` WHERE `member_id`='.$_GET['member_id'];
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $evaluation=array();
    while($record = $stmt->fetch(PDO::FETCH_ASSOC)){
          $evaluation[]=array("member_id"=>$record['member_id'],
                            "article_id"=>$record['article_id'],
                            "work"=>$record['work'],
                            "personality"=>$record['personality'],
                            "farm"=>$record['farm'],
                            "content"=>$record['content'],
                            "star"=>$record['star']);
    }

// member_id=~のstarの平均
    $sql='SELECT AVG(`star`) AS `star_ave` FROM `evaluation` WHERE `member_id`='.$_GET['member_id'];
    $stmt2 = $dbh->prepare($sql);
    $stmt2->execute();
    $star_ave = $stmt2->fetch(PDO::FETCH_ASSOC);





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
    <link href="assets/css/body.css" rel="stylesheet">

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

          <!-- edit form column -->
        <div class="col-md-10 col-sm-6 col-xs-12 personal-info">
        <!--         left column finish        -->
          <div class="text-center">
            <div class="col-sm-8 col-sm-offset-1">
              <!-- <div class="post"> -->
              <!-- 応募した記事 -->
              <div class="post-header font-alt">
                <h1 class="post-title"><i class="glyphicon glyphicon-pencil">過去の評価レビュー</i></h1>
                <h3>-過去の総合平均点数 <?php echo sprintf('%.1f',$star_ave['star_ave']); ?>/5.0点-</h3>
              </div>
              <div class="row">
                <div class="col-sm-12" style="width: 1000px;">
                  <table class="table table-striped table-bordered checkout-table">
                    <tbody>
                      <tr>
                        <th class="hidden-xs" style="width: 126px">星の数</th>
                        <th>仕事ぶり</th>
                        <th>人柄</th>
                        <th>農業への興味</th>
                        <th>コメント</th>
                        <!-- <th>評価</th> -->
                      </tr>

                      <?php if(isset($evaluation)){?>
                      <?php foreach ($evaluation as $eva_each) { ?>
<!--                             // "member_id"=>$evaluation['member_id'];
                            // "article_id"=>$evaluation['article_id'];
                            // "work"=>$evaluation['work'];
                            // "personality"=>$evaluation['personality'];
                            // "farm"=>$evaluation['farm'];
                            // "content"=>$evaluation['content'];
                            // "star"=>$evaluation['star']);
 -->
                        
                      

                      <tr>
                        <td class="hidden-xs">
                          <?php echo $eva_each['star']; ?>
                        </td>
                        <td>
                          <h5 class="product-title font-alt"><?php echo $eva_each['work']; ?></h5>
                        </td>
                        <td>
                          <h5 class="product-title font-alt"><?php echo $eva_each['personality'];?></h5>
                        </td>
                        <td>
                          <h5 class="product-title font-alt"><?php echo $eva_each['farm'];?></h5>
                        </td>
                        <td>
                          <h5 class="product-title font-alt"><?php echo $eva_each['content'];?></h5>
                        </td>

                        <!-- <td class="pr-remove"><input type="button" value="評価"></td> -->
                      </tr>
                       <?php } ?>
                       <?php } ?>

                    </tbody>
                  </table>
                </div>
              </div>


            </div>
          </div>
        </div>
      </div>
    </div>


              <div class="row">
                <div class="text-center">
                <FORM>
                <INPUT type="button" value="戻る" onClick="history.back()">
                </FORM>


                </div>
                <br>
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
