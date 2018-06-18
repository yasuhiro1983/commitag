  <?php

  $article = array();
  $record = array();
      //   //ログインチェック
    if(isset($_SESSION['login_member_id']) && ($_SESSION['time'] + 3600 >time())){
        // ログインしている
      // 最終アクション時間を更新
    $_SESSION['time'] = time();

    // articles&products&prefecturesより全てのデータを取ってくる
    $sql='SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `articles`.`article_id`='.$article_id;
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $article=array();
    while($record=$stmt->fetch(PDO::FETCH_ASSOC)){

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
  }
    $number=0;

?>
<div id="modal">

<div id="open<?php echo $article_id;?>" class="open">
<a href="#" class="close_overlay">×</a>
<div class="modal_window">

    <?php foreach ($article as $article_each) { ?>
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
    <?php $favorite_flag=$article_each['favorite_flag']; ?>
    <?php $apply_id=$article_each['apply_flag']; ?>


<?php require('card.php');?>
  <?php } ?>

<a href="#">【×】CLOSE</a>

</div><!--/.modal_window-->
</div><!--/#open01-->
