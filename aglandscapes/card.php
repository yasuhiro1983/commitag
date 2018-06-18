<?php

    // treatmentの数を数える
    $count_treatment = 0;

    // 待遇表示用の変数定義
    $all_treatment_text = '';

    if($treatment1 ==1){
      $treatment_text1 = ' 朝食あり';
      $count_treatment += 1;
    }else{
      $treatment_text1 ='';
    }
    $all_treatment_text .= $treatment_text1;

    if($treatment2 ==1){
      $treatment_text2 = ' 昼食あり';
      $count_treatment += 1;
    }else{
      $treatment_text2 ='';
    }
    $all_treatment_text .= $treatment_text2;

    if($treatment3 ==1){
      $treatment_text3 = ' 夕食あり';
      $count_treatment += 1;
    }else{
      $treatment_text3 ='';
    }
    $all_treatment_text .= $treatment_text3;

    if($treatment4 ==1){
      $treatment_text4 = ' 送迎あり';
      $count_treatment += 1;
    }else{
      $treatment_text4 = '';
    }
    $all_treatment_text .= $treatment_text4;

    if($treatment5 ==1){
      $treatment_text5 = ' 道具貸与';
      $count_treatment += 1;
    }else{
      $treatment_text5 ='';
    }
    $all_treatment_text .= $treatment_text5;

    if($treatment6 ==1){
      $treatment_text6 = ' 個室あり';
      $count_treatment += 1;
    }else{
      $treatment_text6 ='';
    }
    $all_treatment_text .= $treatment_text6;

    if($count_treatment > 2){
      $sall_treatment_text = mb_substr($all_treatment_text,0,10).'…';
    }

    // 文字数カウント
    if(mb_strlen($title) > 14){
      $stitle = mb_substr($title,0,13).'…';
    }elseif(mb_strlen($title) <= 7){
      $title .='<br>'.'<br>';
    }else{}

    if(mb_strlen($place) > 8){
      $splace = mb_substr($place,0,8).'…';
    }
    if(mb_strlen($access) > 8){
      $saccess = mb_substr($access,0,8).'…';
    }
    if(mb_strlen($work) > 8){
      $swork = mb_substr($work,0,8).'…';
    }
    if(mb_strlen($comment) > 38){
      $scomment = mb_substr($comment,0,37).'…';
    }elseif(mb_strlen($comment) <= 19){
      $comment .='<br>'.'<br>';
    }else{}


?>

<div class="row row-margin-bottom">
  <div class="lib-panel">
  <div class="row box-shadow">
        <div class="row1 col-md-12 col-sm-12 col-xs-12">
      <!-- 写真部分 -->
          <div class="col-md-6 col-sm-12 col-xs-12" id="card-picture">
            <img src="post_picture/<?php echo $landscape ?>" style="width:320px;height:250px;">
          </div>
        <!-- 詳細部分 -->
          <div class="col-md-6 col-sm-12 col-xs-12" id="detail">
            <div class="lib-row lib-header">
              <?php if(isset($stitle)){ ?>
              <h2><a href="card_detail.php?article_id=<?php echo $article_id ?>"><?php echo $stitle; ?></a></h2>
              <?php }else{ ?>
              <h2><a href="card_detail.php?article_id=<?php echo $article_id ?>"><?php echo $title; ?></a></h2>
              <?php } ?>
              <!-- メロン好き集まれ！メロンの収穫を手伝っ… -->
              <div class="lib-header-seperator"></div>
            </div>
            <div class="lib-row lib-desc">
              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">地域</label>
                <p class="col-sm-8 no-padding"><?php echo $prefecture ?></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">場所</label>
                <!-- 札幌市中央区北４… -->
                  <?php if(isset($splace)){ ?>
                  <p class="col-sm-8 no-padding"><?php echo $splace ?></p>
                  <?php }else{ ?>
                  <p class="col-sm-8 no-padding"><?php echo $place ?></p>
                  <?php } ?>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">アクセス</label>
                <!-- 札幌駅から◯◯線… -->
                <?php if(isset($saccess)){ ?>
                <p class="col-sm-8 no-padding"><?php echo $saccess ?></p>
                <?php }else{ ?>
                <p class="col-sm-8 no-padding"><?php echo $access ?></p>
                <?php } ?>

              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">期間</label>
                <p class="col-sm-8 no-padding"><?php echo $start.'<br>'.$finish ?></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">作物</label>
                <p class="col-sm-8 no-padding"><?php echo $product ?></p>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">作業</label>
                <!-- メロンの収穫収穫… -->
                <?php if(isset($swork)){ ?>
                <p class="col-sm-8 no-padding"><?php echo $swork ?></p>
                <?php }else{ ?>
                <p class="col-sm-8 no-padding"><?php echo $work ?></p>
                <?php } ?>
              </div>

              <div class="form-group">
                <label class="col-sm-4 control-label no-padding">待遇</label>
                <p class="col-sm-8 no-padding">
                  <?php if(isset($sall_treatment_text)){ ?>
                  <?php echo $sall_treatment_text ?>
                  <?php }else{ ?>
                  <?php echo $all_treatment_text ?>
                  <?php } ?>
                </p>
              </div>

            </div>
          </div>
      <!-- コメント部分 -->
        <div class="row2 col-md-12 col-sm-12 col-xs-12" id="comment">
          <?php if(isset($scomment)){ ?>
          <p style="padding-top:20px; font-size:25px;"><?php echo $scomment ?></p>
          <?php }else{ ?>
          <p style="padding-top:20px; font-size:25px;"><?php echo $comment ?></p>
          <?php } ?>
          <!-- 絶景広がる牧場で働くおだやかな家族、のどかな環境のもと、牛たちとともにあなたらしく生きませんか… -->
        </div>
      <!-- 質問・応募・お気に入りボタン -->
        <div class="row3 col-md-12 col-sm-12 col-xs-12 id" id="button">
          <center>
            <a href="chat.php?article_id=<?php echo $article_id ?>"><button type="button" class="btn btn-primary btn-lg btn3d"><span class="glyphicon glyphicon-question-sign"></span> 質問する</button></a>
            <?php if(empty($apply_id)){ ?>
            <a href="subscription.php?article_id=<?php echo $article_id ?>"><button type="button" class="btn btn-warning btn-lg btn3d"><span class="glyphicon glyphicon-ok"></span> 応募する</button></a>
            <?php }else{ ?>
            <button type="button" class="btn btn-warning btn-lg btn3d disabled"><span class="glyphicon glyphicon-ok"></span> 応募する</button>
            <?php } ?>
            <?php if($favorite_flag == 0){ ?>
            <a href="favorite.php?article_id=<?php echo $article_id ; ?>"><button type="button" class="btn btn-danger btn-lg btn3d"><span class="glyphicon glyphicon-heart"></span> お気に入り</button></a>
            <?php }else{ ?>
            <button type="button" class="btn btn-danger btn-lg btn3d disabled"><span class="glyphicon glyphicon-heart"></span> お気に入り</button>
            <?php } ?>
          </center>
        </div>
      </div>
    </div>
  </div>
</div>
