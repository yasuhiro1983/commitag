<?php
    session_start();


    // var_dump($_SESSION['search_items']);
    //[search_items]・・・・・・ 0=[prefecture] 1=[start] 2=[finish] 3=[product]

    require('dbconnect.php');

    // 都道府県で検索したときのタイトル
    if(isset($_SESSION['search_items']['prefecture'])){
    $sql = 'SELECT * FROM `prefectures` WHERE `prefecture_id`='.$_SESSION['search_items']['prefecture'];

    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $title_pref=array();
    $record=$stmt->fetch(PDO::FETCH_ASSOC);
    $title_pref[]=array("pref"=>$record['prefecture']);
    }


        // 作物で検索したときのタイトル
    if(isset($_SESSION['search_items']['product'])){

    $product_several=implode(',',$_SESSION['search_items']['product']);

    $sql = 'SELECT * FROM `products` WHERE `product_id`IN ('.$product_several.')';

    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    while($record=$stmt->fetch(PDO::FETCH_ASSOC)){
    $title_prod[]=array("prod"=>$record['product']);
    }
}




        //   //ログインチェック
    if(isset($_SESSION['login_member_id']) && isset($_SESSION['time'])&&($_SESSION['time'] + 3600 > time() )){
        // ログインしている
      // 最終アクション時間を更新
    $_SESSION['time'] = time();

    

    // 都道府県検索
    if(isset($_SESSION['search_items']['prefecture'])){

//ページング機能
    $page='';
    if(isset($_GET['page'])){
      $page = $_GET['page'];
    }
    if ($page=='') {
      $page=1;
    }

      // 1以下のイレギュラーな数値が入ってきた場合はページ番号を１とする(max:中の複数の数値の中で最大の数値を返す関数)
      $page=max($page,1);
      // max(-1,1)という指定の場合、大きい方の１が結果として返される

    // データの件数から最大ページ数を計算する
    $max_page=0;

// このSQL文を実行して、取得したデータ数をvar_dumpで表示しましょう。
    $sql='SELECT COUNT(*) AS `cnt` FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `prefectures`.`prefecture_id`='.$_SESSION['search_items']['prefecture'];

    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    // データ数取得
    $cnt=$stmt->fetch(PDO::FETCH_ASSOC);
    $start=0;
    // 1ページ目：０
    // 2ページ目：１０
    // 3ページ目：２０

    $article_number=10;  //1ページに何個つぶやきを出すか指定
    // 少数点を切り上げた計算結果を代入
    $max_page=ceil($cnt['cnt']/$article_number);

    // パラメータのページ番号が最大ページ数を超えていれば、最後のページ数に設定する(min:指定された複数の最小の数値を返す関数)
    $page = min($page, $max_page);
    // min(100,5) と指定されていたら、５が返ってくる

    $start=($page-1)*$article_number;
    if($start<0){$start=1;}

    // articles&prefectures&productsより全てのデータを取ってくる
    $sql = sprintf('SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `prefectures`.`prefecture_id`='.$_SESSION['search_items']['prefecture']. ' ORDER BY `articles`.`created` DESC LIMIT %d,%d',$start,$article_number);

    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    if ($stmt === false) {
    $article = false;
    } else {


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



    if($favorite_cnt['favorite_count']==0){
      $favorite_flag=0; //favoriteされていない
    }else{
      $favorite_flag=1; //favoriteされている
    }

  }
}
}

    // 期間検索
    if((isset($_SESSION['search_items']['start']) && !empty($_SESSION['search_items']['start'])) && (isset($_SESSION['search_items']['finish'])&& !empty($_SESSION['search_items']['finish']))){

//ページング機能
    $page='';
    if(isset($_GET['page'])){
      $page = $_GET['page'];
    }
    if ($page=='') {
      $page=1;
    }

      // 1以下のイレギュラーな数値が入ってきた場合はページ番号を１とする(max:中の複数の数値の中で最大の数値を返す関数)
      $page=max($page,1);
      // max(-1,1)という指定の場合、大きい方の１が結果として返される

    // データの件数から最大ページ数を計算する
    $max_page=0;

// このSQL文を実行して、取得したデータ数をvar_dumpで表示しましょう。
    $sql='SELECT COUNT(*) AS `cnt` FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `articles`.`start`>=\''.$_SESSION['search_items']['start'].'\' AND `articles`.`finish`<=\''.$_SESSION['search_items']['finish'].'\'';
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    // データ数取得
    $cnt=$stmt->fetch(PDO::FETCH_ASSOC);
    $start=0;
    // 1ページ目：０
    // 2ページ目：１０
    // 3ページ目：２０

    $article_number=10;  //1ページに何個つぶやきを出すか指定
    // 少数点を切り上げた計算結果を代入
    $max_page=ceil($cnt['cnt']/$article_number);

    // パラメータのページ番号が最大ページ数を超えていれば、最後のページ数に設定する(min:指定された複数の最小の数値を返す関数)
    $page = min($page, $max_page);
    // min(100,5) と指定されていたら、５が返ってくる

    $start=($page-1)*$article_number;
    if($start<0){$start=1;}

    // articles&products&prefectureより全てのデータを取ってくる
    $sql=sprintf('SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `articles`.`start`>=\''.$_SESSION['search_items']['start'].'\' AND `articles`.`finish`<=\''.$_SESSION['search_items']['finish'].'\''. ' ORDER BY `articles`.`created` DESC LIMIT %d,%d',$start,$article_number);

         // SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `articles`.`start`>='2017-09-01' AND `articles`.`finish`<='2017-09-10'

    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    if ($stmt === false) {
    $article = false;
    } else {
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

        if($favorite_cnt['favorite_count']==0){
          $favorite_flag=0; //favoriteされていない
        }else{
          $favorite_flag=1; //favoriteされている
        }
    }}//($stmt === false)のelse閉じ
}//期間検索の閉じ




    // 作物検索
    if(isset($_SESSION['search_items']['product'][0])){
    // articles&products&prefecturesより全てのデータを取ってくる

    $page='';
    // パラメータが存在したら、ページ番号を取得
    if(isset($_GET['page'])){
      $page = $_GET['page'];
    }

    // パラメータが存在しない場合は、ページ番号を1とする
    if ($page=='') {
      $page=1;
    }

      // 1以下のイレギュラーな数値が入ってきた場合はページ番号を１とする(max:中の複数の数値の中で最大の数値を返す関数)
      $page=max($page,1);
      // max(-1,1)という指定の場合、大きい方の１が結果として返される

    // データの件数から最大ページ数を計算する
    $max_page=0;

    $product_several=implode(',',$_SESSION['search_items']['product']);

// このSQL文を実行して、取得したデータ数をvar_dumpで表示しましょう。
    $sql='SELECT COUNT(*) AS `cnt` FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `products`.`product_id`IN ('.$product_several.')';
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    // データ数取得
    $cnt=$stmt->fetch(PDO::FETCH_ASSOC);


    $start=0;
    // 1ページ目：０
    // 2ページ目：１０
    // 3ページ目：２０

    $article_number=10;  //1ページに何個つぶやきを出すか指定
    // 少数点を切り上げた計算結果を代入
    $max_page=ceil($cnt['cnt']/$article_number);

    // パラメータのページ番号が最大ページ数を超えていれば、最後のページ数に設定する(min:指定された複数の最小の数値を返す関数)
    $page = min($page, $max_page);
    // min(100,5) と指定されていたら、５が返ってくる

    $start=($page-1)*$article_number;
    if($start<0){$start=1;}


    $sql=sprintf('SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `products`.`product_id` IN ('.$product_several.') ORDER BY `articles`.`created` DESC LIMIT %d,%d',$start,$article_number);
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    if ($stmt === false) {
    $article = false;
    } else {
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
        if($favorite_cnt['favorite_count']==0){
          $favorite_flag=0; //favoriteされていない
        }else{
          $favorite_flag=1; //favoriteされている
        }
    }}// if ($stmt === false)elseの閉じ
    }//作物検索閉じ
}






//ログインしていないとき
    else{    // 都道府県検索
    if(isset($_SESSION['search_items']['prefecture'])){

//ページング機能
    $page='';
    if(isset($_GET['page'])){
      $page = $_GET['page'];
    }
    if ($page=='') {
      $page=1;
    }

      // 1以下のイレギュラーな数値が入ってきた場合はページ番号を１とする(max:中の複数の数値の中で最大の数値を返す関数)
      $page=max($page,1);
      // max(-1,1)という指定の場合、大きい方の１が結果として返される

    // データの件数から最大ページ数を計算する
    $max_page=0;

// このSQL文を実行して、取得したデータ数をvar_dumpで表示しましょう。
    $sql='SELECT COUNT(*) AS `cnt` FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `prefectures`.`prefecture_id`='.$_SESSION['search_items']['prefecture'];
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    // データ数取得
    $cnt=$stmt->fetch(PDO::FETCH_ASSOC);
    $start=0;
    // 1ページ目：０
    // 2ページ目：１０
    // 3ページ目：２０

    $article_number=10;  //1ページに何個つぶやきを出すか指定
    // 少数点を切り上げた計算結果を代入
    $max_page=ceil($cnt['cnt']/$article_number);

    // パラメータのページ番号が最大ページ数を超えていれば、最後のページ数に設定する(min:指定された複数の最小の数値を返す関数)
    $page = min($page, $max_page);
    // min(100,5) と指定されていたら、５が返ってくる

    $start=($page-1)*$article_number;
    if($start<0){$start=1;}


    // articles&prefectures&productsより全てのデータを取ってくる
    $sql = sprintf('SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `prefectures`.`prefecture_id`='.$_SESSION['search_items']['prefecture']. ' ORDER BY `articles`.`created` DESC LIMIT %d,%d',$start,$article_number);

    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $article=array();
    while($record=$stmt->fetch(PDO::FETCH_ASSOC)){

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
    "favorite_flag"=>0
    );





  }
}


    // ログインしていない期間検索
    if((isset($_SESSION['search_items']['start']) && !empty($_SESSION['search_items']['start'])) && (isset($_SESSION['search_items']['finish'])&& !empty($_SESSION['search_items']['finish']))){

//ページング機能
    $page='';
    if(isset($_GET['page'])){
      $page = $_GET['page'];
    }
    if ($page=='') {
      $page=1;
    }

      // 1以下のイレギュラーな数値が入ってきた場合はページ番号を１とする(max:中の複数の数値の中で最大の数値を返す関数)
      $page=max($page,1);
      // max(-1,1)という指定の場合、大きい方の１が結果として返される

    // データの件数から最大ページ数を計算する
    $max_page=0;

// このSQL文を実行して、取得したデータ数をvar_dumpで表示しましょう。
    $sql='SELECT COUNT(*) AS `cnt` FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `articles`.`start`>=\''.$_SESSION['search_items']['start'].'\' AND `articles`.`finish`<=\''.$_SESSION['search_items']['finish'].'\'';
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    // データ数取得
    $cnt=$stmt->fetch(PDO::FETCH_ASSOC);
    $start=0;
    // 1ページ目：０
    // 2ページ目：１０
    // 3ページ目：２０

    $article_number=10;  //1ページに何個つぶやきを出すか指定
    // 少数点を切り上げた計算結果を代入
    $max_page=ceil($cnt['cnt']/$article_number);

    // パラメータのページ番号が最大ページ数を超えていれば、最後のページ数に設定する(min:指定された複数の最小の数値を返す関数)
    $page = min($page, $max_page);
    // min(100,5) と指定されていたら、５が返ってくる

    $start=($page-1)*$article_number;
    if($start<0){$start=1;}


    // articles&products&prefectureより全てのデータを取ってくる
    $sql=sprintf('SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `articles`.`start`>=\''.$_SESSION['search_items']['start'].'\' AND `articles`.`finish`<=\''.$_SESSION['search_items']['finish'].'\''. ' ORDER BY `articles`.`created` DESC LIMIT %d,%d',$start,$article_number);
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $article=array();
    while($record=$stmt->fetch(PDO::FETCH_ASSOC)){


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
    "favorite_flag"=>0

    );

    }}
    



    // ログインしていない作物検索
    if(isset($_SESSION['search_items']['product'][0])){
    // articles&products&prefecturesより全てのデータを取ってくる

    $page='';
    // パラメータが存在したら、ページ番号を取得
    if(isset($_GET['page'])){
      $page = $_GET['page'];
    }

    // パラメータが存在しない場合は、ページ番号を1とする
    if ($page=='') {
      $page=1;
    }

      // 1以下のイレギュラーな数値が入ってきた場合はページ番号を１とする(max:中の複数の数値の中で最大の数値を返す関数)
      $page=max($page,1);
      // max(-1,1)という指定の場合、大きい方の１が結果として返される

    // データの件数から最大ページ数を計算する
    $max_page=0;

    $product_several=implode(',',$_SESSION['search_items']['product']);

// このSQL文を実行して、取得したデータ数をvar_dumpで表示しましょう。
    $sql='SELECT COUNT(*) AS `cnt` FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `products`.`product_id`IN ('.$product_several.')';
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    // データ数取得
    $cnt=$stmt->fetch(PDO::FETCH_ASSOC);


    $start=0;
    // 1ページ目：０
    // 2ページ目：１０
    // 3ページ目：２０

    $article_number=10;  //1ページに何個つぶやきを出すか指定
    // 少数点を切り上げた計算結果を代入
    $max_page=ceil($cnt['cnt']/$article_number);

    // パラメータのページ番号が最大ページ数を超えていれば、最後のページ数に設定する(min:指定された複数の最小の数値を返す関数)
    $page = min($page, $max_page);
    // min(100,5) と指定されていたら、５が返ってくる

    $start=($page-1)*$article_number;
    if($start<0){$start=1;}



    $sql=sprintf('SELECT * FROM `products` INNER JOIN (`articles` INNER JOIN `prefectures` ON `articles`.`prefecture_id`=`prefectures`.`prefecture_id`) ON `products`.`product_id`=`articles`.`product_id` WHERE `products`.`product_id` IN ('.$product_several.') ORDER BY `articles`.`created` DESC LIMIT %d,%d',$start,$article_number);
    $stmt=$dbh->prepare($sql);
    $stmt->execute();
    $article=array();
    while($record=$stmt->fetch(PDO::FETCH_ASSOC)){

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
    "favorite_flag"=>0
    
    );
    }
}}
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
      <link href="css/screen.css" rel="stylesheet" />
      <link rel="stylesheet" type="text/css" href="css/assets/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="css/assets/css/bootstrap.css">

    <!-- Bootstrap -->
    <link href="assets/css/bootstrap.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="assets/css/form.css" rel="stylesheet">
    <link href="assets/css/timeline.css" rel="stylesheet">
    <link href="assets/css/risa_main.css" rel="stylesheet">
    <link href="assets/css/risa_ag_original.css" rel="stylesheet">
    <link href="assets/css/anly_main.css" rel="stylesheet">
    <link href="assets/css/anly_ag_original.css" rel="stylesheet">

    <!--
      designフォルダ内では2つパスの位置を戻ってからcssにアクセスしていることに注意！
     -->
  </head>
<body>


<!-- ヘッダー -->
      <?php include('header.php') ?>


  <div class="container">

   <div class="content-box">

         

         <!-- Destinations Section -->
         <section class="section section-destination">
            <!-- Title -->
            <div class="section-title">
              <div class="container">
                <?php if (isset($_SESSION['search_items']['prefecture'])) { ?>
                    <h2 class="title" style="padding-top: 80px">地域>><?php echo $title_pref[0]['pref']; ?></h2>

                <?php ;} ?>
                <?php if((isset($_SESSION['search_items']['start']) && !empty($_SESSION['search_items']['start'])) && (isset($_SESSION['search_items']['finish'])&& !empty($_SESSION['search_items']['finish']))) { ?>
                    <h2 class="title" style="padding-top: 80px">日付>><?php echo $_SESSION['search_items']['start']."~".$_SESSION['search_items']['finish']; ?></h2>
                <?php ;} ?>
                <?php if(isset($_SESSION['search_items']['product'])) { ?>
                <h2 class="title" style="padding-top: 80px">作物>><?php for($i=0; $i<=22; $i++){
                if(isset($_SESSION['search_items']['product'][$i])) { 
                $prod='「'.$title_prod[$i]['prod'].'」'.'  ';
                echo $prod;
                }
                mb_substr($prod, 0, -1,"utf-8");
                } ?></h2>
                <?php ;} ?>
                <?php if(!isset($_SESSION['search_items'])){ ?>
                  <h2 class="title" style="padding-top: 80px">検索結果はありませんでした。</h2>
                <?php ;} ?>
                </div><!-- container -->

      <div class="container">
        <div class="row">
          <hr>
            <!-- <div class="row row-margin-bottom"> -->
      <!-- card section-->
    <?php if (isset($article)) { ?>
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
    <?php if(isset($article_each['favorite_flag'])){$favorite_flag=$article_each['favorite_flag'];} ?> 

    <?php if(isset($article_each['apply_flag'])){$apply_id=$article_each['apply_flag'];} ?>

  <div class="col-md-6">

    <?php require('card.php'); ?>
</div><!-- col-md-6 -->
  <?php }}else{
    echo '<h2>'."検索結果は見つかりませんでした。".'<h2>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>';
    } ?>
    <?php if(isset($article)){if($article==false){
        echo '検索結果はありませんでした。'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>'.'<br>';
    }} ?>
  </div><!-- row -->






               <div class="align-center">
                   <?php if(isset($page)){if ($page>1){ ?> 
                  <a href="search_result.php?page=<?php echo $page -1; ?>" class="btn btn-info btn-load-boats">
                    <span class="text">前
                    </span>
                    <i class="icon-spinner6">
                    </i>
                  </a>
                  <?php }elseif ($article==false) {
                      echo '';
                   ?>
                  <?php }else{ ?>
                  前
                  <?php } ?>

                <?php if($page<$max_page){ ?>

                  <a href="search_result.php?page=<?php echo $page +1; ?>" class="btn btn-info btn-load-boats">
                    <span class="text">後
                    </span>
                    <i class="icon-spinner6">
                    </i>
                  </a>
                <?php }elseif ($article==false) {
                      echo '';
                   ?>
                <?php }else{ ?>
                次
                <?php }} ?>
               </div>
            </div><!-- section-title -->
         </section>
      </div>
    </div>


       <!-- Scripts -->
      <script src="js/jquery.js"></script>
      <script src="js/functions.js"></script>
   </body>
</html>

<!-- フッター -->
<?php include('footer.php'); ?>



    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
  </body>
</html>
