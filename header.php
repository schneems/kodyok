<?php
$menu_name = 'navigation';
$menu_exists = wp_get_nav_menu_object($menu_name);
if(!$menu_exists){
    $menu_id = wp_create_nav_menu($menu_name);
    $pages = get_pages();
    $i = 1;
    foreach($pages as $page){
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' =>  __($page->post_title),
            'menu-item-url' => get_page_link($page->ID),
            'menu-item-target' => '',
            'menu-item-position' => $i,
            'menu-item-status' => 'publish'
        ));
        $i++;
    }
} else {
    $menu_id = $menu_exists->term_id;
}
?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Kodyok</title>
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/bootstrap/css/bootstrap-theme.min.css">
    <script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-1.12.0.min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri();?>/assets/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-ui.min.js"></script>
    <link type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/assets/css/jquery-ui.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/font-awesome/css/font-awesome.min.css">
    <link href="<?php echo get_stylesheet_directory_uri();?>/assets/lightbox2/css/lightbox.min.css" rel="stylesheet">
    <?php
    if(isset($_GET['editor'])){
    ?>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/colpick.js"></script>
    <link href="<?php echo get_stylesheet_directory_uri();?>/assets/css/colpick.css" rel="stylesheet" type="text/css">
    <script type="text/javascript">
        get_stylesheet_directory_uri = '<?php echo get_stylesheet_directory_uri();?>';
        get_site_url = '<?php echo get_site_url();?>';
        $(document).ready(function(){
            $("body").on("click","a",function(e){
                if($(this).attr('href')[0]!='#' && $(this).attr('target')!='_blank'){
                    e.preventDefault();
                    window.open($(this).attr('href')+'?kodyok','_top');
                }
            });
        });
    </script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.button.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.form.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.grid.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.icon.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.image.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.menu.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.section.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.slider.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/core.text.js"></script>
    <style type="text/css">
        .place_holder { width:100%;height:20px;background:#ddd;margin-top:10px;margin-bottom:10px;}
    </style>
    <?php
    } else {
        wp_head();
    }
    ?>
    <script type="text/javascript">
        $(document).ready(function(){
            $("body").on("click",".send_button",function(){
                form_error = 0;
                $(this).parent().parent().children(".form_content").children().each(function(){
                    if($(this).children('input').val()==''){
                        form_error++;
                    }
                });
                if(form_error>0){
                    alert('This fields are required.');
                } else {
                    form_content = {};
                    $(this).parent().parent().children(".form_content").children().each(function(){
                        form_content[$(this).children('input').attr('placeholder')] = $(this).children('input').val();
                    });
                    $.ajax({
                        type: "POST",
                        url: get_site_url+"/?do=send_email",
                        data: {content:JSON.stringify(form_content)},
                        success: function(html){
                            alert('Success');
                        }
                    });
                    return false;
                }
            });
        });
    </script>
</head>
<body style="margin:0;padding:0;">
    <?php
    if(get_option('menu_style')){
        $menu_style = json_decode(get_option('menu_style'));
        $font = $menu_style->font;
        $color = $menu_style->color;
        $bgcolor = $menu_style->bgcolor;
        $logo = $menu_style->logo;
    } else {
       $font = '"Helvetica Neue", Helvetica, Arial, sans-serif';
       $color = 'rgb(119, 119, 119)';
       $bgcolor = 'rgb(238, 238, 238)';
       $logo = get_stylesheet_directory_uri().'/assets/img/logo.png';
    }
    ?>
    <nav class="navbar navbar-default" style="border:0;margin-bottom:0;background-image:none;border-radius:0;box-shadow:none;-webkit-box-shadow:none;background-color:<?php echo $bgcolor;?>;">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#" style="padding:0;padding-left:15px;"><img src="<?php echo $logo;?>" height="50"></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right" data-menu-id="<?php echo $menu_id;?>">
                    <?php
                    $items = wp_get_nav_menu_items($menu_name);
                    foreach($items as $item){
                    ?>
                    <li data-item-id="<?php echo $item->db_id;?>" data-position="<?php echo $item->menu_order;?>"><a href="<?php echo $item->url;?>" <?php if($item->target=='_blank'){ echo 'target="_blank"'; } ?> style='color:<?php echo $color;?>;font-family:<?php echo $font;?>;'><?php echo $item->title;?></a></li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <div class="main_sortable" style="height:100%;">