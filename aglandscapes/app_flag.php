<?php

    session_start();
    require('dbconnect.php');
  //ログインチェック
    if (isset($_SESSION['login_member_id'])){

    $sql= 'UPDATE `applies` SET `flag`=1 WHERE `member_id`='.$_GET['member_id'].' AND `article_id`='.$_GET['article_id'];

    //SQLを実行
    $stmt=$dbh->prepare($sql);
    $stmt->execute();

    }

    header('Location: articles.php');
    exit();

?>