<?php
    session_start();
    // エラーがある場合
    if(!empty($_POST)){
      // 開始日、終了日が未入力
      if(($_POST['start'] ==' ') && ($_POST['finsh'] == ' ')){
        $error['days'] ='blank';
      }
      if(!isset($error)){
        // $_SESSION['search_items'] = array($_POST['start'],$_POST['finish'],$_POST['product']);
        // $_SESSION['search_items2'] = array('product'=>$_POST['value']);
        if(!empty($_POST['area_name'])&&(empty($_POST['prefecture']))){
          $_SESSION['search_items'] = $_SESSION['search_items'];
        }else{
        $_SESSION['search_items'] = $_POST;
        }
        // $_SESSION['search_items']['prefecture'] = $_GET['prefecture'];

                header('Location: search_result.php');
        exit();
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
  <link href="assets/css/yuri_main.css" rel="stylesheet">
  <link href="assets/css/yuri_ag_original.css" rel="stylesheet">
  <link rel="stylesheet" href="map.css">
    <!-- カレンダー導入 -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <!-- <script src="https://code.jquery.com/jquery-1.10.2.min.js" type="text/javascript" language="javascript"></script> -->
    <!-- <script src="../common/js/bootstrap.js"></script> -->
    <!-- <link rel="stylesheet" href="../common/css/bootstrap.css"> -->
    <!-- <link href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css" rel="stylesheet"> -->
  <link rel="stylesheet" type="text/css" href="bootstrap-datepicker/css/bootstrap-datepicker.min.css">
  <script type="text/javascript" src="bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
  <script type="text/javascript" src="bootstrap-datepicker/locales/bootstrap-datepicker.ja.min.js"></script>
        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libsjquery-3.1.1.js"></script> -->
    <!-- <script src="assets/js/jquery-migrate-1.4.1.js"></script> -->
    <!-- <script src="assets/js/bootstrap.js"></script> -->

  <script src="js/japanmap/jquery.japan-map.min.js"></script>
</head>
<body>
<!-- ヘッダー -->
<?php include('header.php') ?>
<!-- コンテンツ -->
<!-- タブシステム -->
  <!-- TAB CONTROLLERS -->
<?php if ($_GET['type'] == 'pre'){ ?>
<input id="panel-1-ctrl" class="panel-radios" type="radio" name="tab-radios" checked>
 <?php }else{ ?>
<input id="panel-1-ctrl" class="panel-radios" type="radio" name="tab-radios">
<?php } ?>

  <?php if ($_GET['type'] == 'term'){ ?>
<input id="panel-2-ctrl" class="panel-radios" type="radio" name="tab-radios" checked>
 <?php }else{ ?>
<input id="panel-2-ctrl" class="panel-radios" type="radio" name="tab-radios" >
<?php } ?>

<?php if ($_GET['type'] == 'product'){ ?>
<input id="panel-3-ctrl" class="panel-radios" type="radio" name="tab-radios" checked>
 <?php }else{ ?>
<input id="panel-3-ctrl" class="panel-radios" type="radio" name="tab-radios" >
<?php } ?>

<!-- TABS LIST -->
<div class="row">
    <ul id="tabs-list">
        <!-- MENU TOGGLE -->
      <li id="li-for-panel-1">
        <label class="panel-label" for="panel-1-ctrl">都道府県</label>
      </li><!--INLINE-BLOCK FIX-->
      <li id="li-for-panel-2">
        <label class="panel-label" for="panel-2-ctrl">期間</label>
      </li><!--INLINE-BLOCK FIX-->
      <li id="li-for-panel-3">
        <label class="panel-label" for="panel-3-ctrl">作物</label>
      </li><!--INLINE-BLOCK FIX-->
    </ul>
</div>

<!-- THE PANELS -->
  <article id="panels">
    <div class="container">
    <!-- 日本地図セクション -->
      <section id="panel-1">
        <main>
          <div class="low" id="jmap">
            <div id="map" style="width:100%;"></div>
              <p id="text" style="padding: 10px; width: 800px; color: #ffffff; text-align: center"></p>
          </div>
          <!-- 選んだ都道府県を表示 -->
          <div class="low" style="text-align: center">
          <form method="post" action="">
            <input type="text" id="my_selsct_area" name="area_name">
            <?php if(empty($_SESSION['search_items']['prefecture'])){ ?>
            <input type="hidden" name="prefecture" id="my_selsct_code">
            <?php }else{ ?>
            <input type="hidden" name="prefecture" id="my_selsct_code" value="<?php $_SESSION['search_items']['prefecture'] ?>">
            <?php } ?>
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i>検索</button>
          </form>
          </div>
        </main>
      </section>

<!-- カレンダーセクション -->
    <form method="post" action="">
      <section id="panel-2">
        <main>
          <div class="row calender" id="calender">
            <div class="col-md-6">
              <div class="form-group" id="datepicker-inline">
                <div class="col-sm-9 form-inline">
                <label class="control-label">開始日</label>
                  <div class="in-line" data-date="today"></div>
                    <input type="text" name="start" id="my_hidden_input">
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="form-group" id="datepicker-inline2">
                <div class="col-sm-9 form-inline">
                  <label class="control-label">終了日</label>
                  <div class="in-line2" data-date="tomorrow"></div>
                  <input type="text" name="finish" id="my_hidden_input2">
                </div>
              </div>
            </div>
          </div>
          <div class="row search-button">
          <?php if(isset($error['days']) && ($error['days'] == 'blank')){ ?>
            <p style="color:red:">開始日か終了日のどちらかを入力してください</p>
          <?php } ?>
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i>検索</button>

          </div>
        </main>
      </section>
      </form>

  <!-- 作物セクション -->
    <form method="post" action="">
      <section id="panel-3">
        <main>
          <div class="row produce" id="produce">
            <div class="produce-item">
                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-001.gif"></p>
                    <p><input type="checkbox" name="product[]" value="1">キャベツ</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-002.gif"></p>
                    <p><input type="checkbox" name="product[]" value="2">トマト</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-003.gif"></p>
                    <p><input type="checkbox" name="product[]" value="3">ナス</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-004.gif"></p>
                    <p><input type="checkbox" name="product[]" value="4">きゅうり</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-005.gif"></p>
                    <p><input type="checkbox" name="product[]" value="5">さつまいも</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-006.gif"></p>
                    <p><input type="checkbox" name="product[]" value="6">にんじん</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-007.gif"></p>
                    <p><input type="checkbox" name="product[]" value="7">だいこん</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-019.gif"></p>
                    <p><input type="checkbox" name="product[]" value="8">じゃがいも</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-028.gif"></p>
                    <p><input type="checkbox" name="product[]" value="9">ピーマン</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-035.gif"></p>
                    <p><input type="checkbox" name="product[]" value="10">たまねぎ</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-037.gif"></p>
                    <p><input type="checkbox" name="product[]" value="11">葉野菜</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/vegitable01-045.gif"></p>
                    <p><input type="checkbox" name="product[]" value="12">その他の野菜</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-001.png"></p>
                    <p><input type="checkbox" name="product[]" value="13">りんご</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-003.png"></p>
                    <p><input type="checkbox" name="product[]" value="14">さくらんぼ</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-004.png"></p>
                    <p><input type="checkbox" name="product[]" value="15">みかん</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-006.png"></p>
                    <p><input type="checkbox" name="product[]" value="16">メロン</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-008.png"></p>
                    <p><input type="checkbox" name="product[]" value="17">いちご</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-009.png"></p>
                    <p><input type="checkbox" name="product[]" value="18">なし</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-015.png"></p>
                    <p><input type="checkbox" name="product[]" value="19">すいか</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-020.png"></p>
                    <p><input type="checkbox" name="product[]" value="20">ぶどう</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-037.png"></p>
                    <p><input type="checkbox" name="product[]" value="21">もも</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-038.png"></p>
                    <p><input type="checkbox" name="product[]" value="22">かき</p>
                  </div>
                </div>

                <div class="col-ld-1 col-md-2 col-sm-3 col-xs-6">
                  <div class="produce-box">
                    <p><img src="img/fruitsicon01-011.png"></p>
                    <p><input type="checkbox" name="product[]" value="23">その他の果物</p>
                  </div>
                </div>
            </div>
          </div>
          <div class="row search-button">
            <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-search"></i> 検索</button>
          </div>
        </main>
      </section>
    </form>
    </div>
  </article>

  <!-- フッター -->
<?php include('footer.php') ?>


</body>
<script>
$(function(){

    var areas = [
        {"code": 1 , "name":"北海道地方", "color":"#ca93ea", "hoverColor":"#e0b1fb", "prefectures":[1]},
        {"code": 2 , "name":"東北地方",   "color":"#a7a5ea", "hoverColor":"#d6d4fd", "prefectures":[2,3,4,5,6,7]},
        {"code": 3 , "name":"関東地方",   "color":"#84b0f6", "hoverColor":"#c1d8fd", "prefectures":[8,9,10,11,12,13,14]},
        {"code": 4 , "name":"北陸・甲信越地方",   "color":"#52d49c", "hoverColor":"#93ecc5", "prefectures":[15,16,17,18,19,20]},
        {"code": 4 , "name":"東海地方",   "color":"#77e18e", "hoverColor":"#aff9bf", "prefectures":[21,22,23,24]},
        {"code": 6 , "name":"近畿地方",   "color":"#f2db7b", "hoverColor":"#f6e8ac", "prefectures":[25,26,27,28,29,30]},
        {"code": 7 , "name":"中国地方",   "color":"#f9ca6c", "hoverColor":"#ffe5b0", "prefectures":[31,32,33,34,35]},
        {"code": 8 , "name":"四国地方",   "color":"#fbad8b", "hoverColor":"#ffd7c5", "prefectures":[36,37,38,39]},
        {"code": 9 , "name":"九州地方",   "color":"#f7a6a6", "hoverColor":"#ffcece", "prefectures":[40,41,42,43,44,45,46]},
        {"code":10 , "name":"沖縄地方",   "color":"#ea89c4", "hoverColor":"#fdcae9", "prefectures":[47]}
    ];

    $("#map").japanMap(
        {
            areas  : areas,
            selection : "prefecture",
            borderLineWidth: 0.25,
            drawsBoxLine : false,
            movesIslands : true,
            showsAreaName : true,
            width: 800,
            font : "MS Mincho",
            fontSize : 12,
            fontColor : "areaColor",
            fontShadowColor : "black",
            onSelect:function(data){
                //クリックした時にやりたい処理を書く！！
                //alert(data.name);
                // location.href = "genre_search.php?prefecture_id="+ data.code;

                $('#my_selsct_area').val(
            data.name
            );
                $('#my_selsct_code').val(
            data.code
            );

            },
        }
    );
    $('#datepicker-inline .in-line').datepicker(
        {
            format: "yyyy-mm-dd",
            language: "ja"
        });

    $('.in-line').on('changeDate', function() {
        $('#my_hidden_input').val(
            $('.in-line').datepicker('getFormattedDate')
            );
    });

    $('#datepicker-inline2 .in-line2').datepicker(
        {
            format: "yyyy-mm-dd",
            language: "ja"
        });
    $('.in-line2').on('changeDate', function() {
        $('#my_hidden_input2').val(
            $('.in-line2').datepicker('getFormattedDate')
        );
    });
});

</script>
</html>
