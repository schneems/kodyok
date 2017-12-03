<!DOCTYPE html>
<html>
<head>
  <script src="js/jquery.min.js"></script>
  <script type="text/javascript" src="js/jquery-ui.min.js"></script>
  <link type="text/css" href="css/jquery-ui.min.css" rel="stylesheet" />
  <meta charset="utf-8">
  <script>
  $(function(){
    $(".draggable").draggable({
      drag:function(event,ui){
        if($(this).next().next().length){
          div_left = $(this).next().next().offset().left;
          if(ui.position.left>div_left-20){
            ui.position.left = div_left-20;
          }
          if(ui.position.left<=20){
            ui.position.left = 20;
          }
          $(this).next().css('width',(div_left-ui.position.left)+'px');
          $(this).prev().css('width',ui.position.left+'px');
        } else {
          if(ui.position.left<=20){
            ui.position.left = 20;
          }
          $(this).next().css('width',(ui.position.left)+'px');
          $(this).prev().css('width',ui.position.left+'px');
        }
        if($(this).prev().prev().length){
          div_left = $(this).prev().prev().offset().left;
          if(ui.position.left<div_left+20){
            ui.position.left = div_left+20;
          }
          if(ui.position.left>=220){
            ui.position.left = 220;
          }
          $(this).prev().css('width',(ui.position.left-div_left)+'px');
          if($(this).next().next().length){
            $(this).next().css('width',($(this).next().next().offset().left-ui.position.left)+'px');
          } else {
            $(this).next().css('width',(240-ui.position.left)+'px');
          }
        } else {
          if(ui.position.left>=220){
            ui.position.left = 220;
          }
          $(this).prev().css('width',(ui.position.left)+'px');
          if($(this).next().next().length){
            $(this).next().css('width',($(this).next().next().offset().left-ui.position.left)+'px');
          } else {
            $(this).next().css('width',(240-ui.position.left)+'px');
          }
        }
        $(this).prev().html($(this).prev().width()/20);
        $(this).next().html($(this).next().width()/20);
        columns = '';
        $('body > div').children().each(function(a,b){
          if($(this).html()!=''){
            columns += $(this).html()+',';
          }
        });
        parent.$('#design_area')[0].contentWindow.change_column(columns);
      },
      grid:[20],
      axis:"x",
      containment:"parent"
    });
  });
  </script>
  <style type="text/css">
    body { -webkit-user-select:none; -moz-user-select:none; -ms-user-select:none; user-select:none;}
    .draggable { cursor:col-resize;}
  </style>
</head>
<body style="margin:0;padding:0;">
  <?php
  if($_GET['id']==2){
  ?>
  <div style="width:240px;height:40px;background-color:#EEE;">
    <div style="width:120px;float:left;text-align:center;padding-top:10px;">6</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:120px;position:absolute;"></div>
    <div style="width:120px;float:left;text-align:center;padding-top:10px;">6</div>
  </div>
  <?php
  }
  ?>
  <?php
  if($_GET['id']==3){
  ?>
  <div style="width:240px;height:40px;background-color:#EEE;">
    <div style="width:80px;float:left;text-align:center;padding-top:10px;">4</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:80px;position:absolute;"></div>
    <div style="width:80px;float:left;text-align:center;padding-top:10px;">4</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:160px;position:absolute;"></div>
    <div style="width:80px;float:left;text-align:center;padding-top:10px;">4</div>
  </div>
  <?php
  }
  ?>
  <?php
  if($_GET['id']==4){
  ?>
  <div style="width:240px;height:40px;background-color:#EEE;">
    <div style="width:60px;float:left;text-align:center;padding-top:10px;">3</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:60px;position:absolute;"></div>
    <div style="width:60px;float:left;text-align:center;padding-top:10px;">3</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:120px;position:absolute;"></div>
    <div style="width:60px;float:left;text-align:center;padding-top:10px;">3</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:180px;position:absolute;"></div>
    <div style="width:60px;float:left;text-align:center;padding-top:10px;">3</div>
  </div>
  <?php
  }
  ?>
  <?php
  if($_GET['id']==5){
  ?>
  <div style="width:240px;height:40px;background-color:#EEE;">
    <div style="width:60px;float:left;text-align:center;padding-top:10px;">3</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:60px;position:absolute;"></div>
    <div style="width:180px;float:left;text-align:center;padding-top:10px;">9</div>
  </div>
  <?php
  }
  ?>
  <?php
  if($_GET['id']==6){
  ?>
  <div style="width:240px;height:40px;background-color:#EEE;">
    <div style="width:180px;float:left;text-align:center;padding-top:10px;">9</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:180px;position:absolute;"></div>
    <div style="width:60px;float:left;text-align:center;padding-top:10px;">3</div>
  </div>
  <?php
  }
  ?>
  <?php
  if($_GET['id']==7){
  ?>
  <div style="width:240px;height:40px;background-color:#EEE;">
    <div style="width:60px;float:left;text-align:center;padding-top:10px;">3</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:60px;position:absolute;"></div>
    <div style="width:120px;float:left;text-align:center;padding-top:10px;">6</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:180px;position:absolute;"></div>
    <div style="width:60px;float:left;text-align:center;padding-top:10px;">3</div>
  </div>
  <?php
  }
  ?>
  <?php
  if($_GET['id']==8){
  ?>
  <div style="width:240px;height:40px;background-color:#EEE;">
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:40px;position:absolute;"></div>
    <div style="width:60px;float:left;text-align:center;padding-top:10px;">3</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:100px;position:absolute;"></div>
    <div style="width:100px;float:left;text-align:center;padding-top:10px;">5</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:200px;position:absolute;"></div>
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
  </div>
  <?php
  }
  ?>
  <?php
  if($_GET['id']==9){
  ?>
  <div style="width:240px;height:40px;background-color:#EEE;">
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:40px;position:absolute;"></div>
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:80px;position:absolute;"></div>
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:120px;position:absolute;"></div>
    <div style="width:80px;float:left;text-align:center;padding-top:10px;">4</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:200px;position:absolute;"></div>
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
  </div>
  <?php
  }
  ?>
  <?php
  if($_GET['id']==10){
  ?>
  <div style="width:240px;height:40px;background-color:#EEE;">
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:40px;position:absolute;"></div>
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:80px;position:absolute;"></div>
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:120px;position:absolute;"></div>
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:160px;position:absolute;"></div>
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
    <div class="draggable" style="width:5px;height:40px;background-color:#6699cc;float:left;left:200px;position:absolute;"></div>
    <div style="width:40px;float:left;text-align:center;padding-top:10px;">2</div>
  </div>
  <?php
  }
  ?>
</body>
</html>