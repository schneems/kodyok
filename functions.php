<?php
add_theme_support('post-thumbnails');
function custom_toolbar_link($wp_admin_bar){
	if(get_the_ID()!=''){
		$args = array(
			'id' => 'kodyok',
			'title' => 'Edit with Kodyok',
			'href' => get_site_url().'/?p='.get_the_ID().'&kodyok'
		);
		$wp_admin_bar->add_node($args);
	}
}
add_action('admin_bar_menu','custom_toolbar_link',999);
if(isset($_GET['do'])){
    if($_GET['do']=='get_pages'){
    	$pages = get_pages();
    	$i = 0;
		foreach($pages as $page){
			$new_pages[$i]['title'] = $page->post_title;
			$new_pages[$i]['url'] = get_page_link($page->ID);
			$i++;
		}
		echo json_encode($new_pages);
		exit;
    } else if($_GET['do']=='save' && is_super_admin()){
		$my_post = array('ID'=>$_GET['id'],'post_content'=>$_POST['content']);
		wp_update_post($my_post);
		add_post_meta($_GET['id'],'kodyok_content','1',true);
		update_option('menu_style',stripslashes($_POST['menu']));
		update_option('footer',stripslashes($_POST['footer']));
		exit;
    } else if($_GET['do']=='get_content'){
    	if(isset($_GET['categories'])){
	    	$categories = explode(',',$_GET['categories']);
			$args = array(
				'numberposts' => 6,
				'category__in' => $categories
			);
		} else if(isset($_GET['tags'])){
			$tags = explode(',',$_GET['tags']);
			$args = array(
				'numberposts' => 6,
				'tag__in' => $tags
			);
		}
		$posts = get_posts($args);
		$i = 0;
		foreach($posts as $post){
			$new_posts[$i]['title'] = $post->post_title;
			$image_detail = wp_get_attachment_image_src(get_post_thumbnail_id(),'medium');
			$new_posts[$i]['image'] = $image_detail[0];
			$new_posts[$i]['width'] = $image_detail[1];
			$new_posts[$i]['height'] = $image_detail[2];
			if(get_permalink($post->ID)){
				$new_posts[$i]['link'] = get_permalink($post->ID);
			} else {
				$new_posts[$i]['link'] = get_site_url().'/?p='.$post->ID;
			}
			$i++;
		}
		echo json_encode($new_posts);
		exit;
    } else if($_GET['do']=='remove_menu' && is_super_admin()){
    	wp_delete_post($_GET['id']);
    	$items = wp_get_nav_menu_items('navigation');
    	$i = 1;
		foreach($items as $item){
			$my_post = array('ID'=>$item->db_id,'menu_order'=>$i);
			wp_update_post($my_post);
	        $i++;
		}
    	exit;
    } else if($_GET['do']=='update_menu' && is_super_admin()){
    	wp_update_nav_menu_item($_GET['menu_id'], $_GET['item_id'], array(
            'menu-item-title' =>  __($_GET['title']),
            'menu-item-url' => $_GET['link'],
            'menu-item-target' => $_GET['target'],
            'menu-item-position' => $_GET['position'],
            'menu-item-status' => 'publish'
        ));
        exit;
    } else if($_GET['do']=='update_menu_list' && is_super_admin()){
    	$items = explode(',',$_GET['items']);
    	$i = 1;
		foreach($items as $item){
			if($item!=''){
				$my_post = array('ID'=>$item,'menu_order'=>$i);
				wp_update_post($my_post);
		        $i++;
	    	}
		}
    	exit;
    } else if($_GET['do']=='add_menu_item' && is_super_admin()){
    	echo wp_update_nav_menu_item($_GET['menu_id'], 0, array(
            'menu-item-title' =>  __($_GET['title']),
            'menu-item-url' => '#',
            'menu-item-target' => '',
            'menu-item-position' => $_GET['position'],
            'menu-item-status' => 'publish'
        ));
    	exit;
    } else if($_GET['do']=='get_images'){
    	$query_images_args = array(
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'post_status' => 'inherit',
			'posts_per_page' => -1,
		);
		$query_images = new WP_Query($query_images_args);
		$images = array();
		$i = 0;
		foreach($query_images->posts as $image){
			$images[$i]['id'] = $image->ID;
			$images[$i]['thumb'] = wp_get_attachment_thumb_url($image->ID);
			$i++;
		}
		echo json_encode($images);
		exit;
    } else if($_GET['do']=='get_image'){
    	echo wp_get_attachment_image_src($_GET['id'],$_GET['size'])[0];
    	exit;
    } else if($_GET['do']=='upload_screen'){
    	?>
    	<body style="margin:0;padding:0;">
	    	<form id="featured_upload" method="post" action="<?php echo get_site_url(); ?>/?do=upload_image" enctype="multipart/form-data" style="margin-bottom:0;">
				<input type="file" name="my_image_upload" id="my_image_upload" multiple="false" />
				<input type="hidden" name="post_id" id="post_id" value="0" />
				<?php wp_nonce_field('my_image_upload','my_image_upload_nonce');?>
				<input id="submit_my_image_upload" name="submit_my_image_upload" type="submit" value="Upload" style="width:150px;padding:5px;font-size:20px;text-align:center;background:#666;color:#fff;" />
			</form>
		</body>
    	<?php
    	exit;
    } else if($_GET['do']=='upload_image'){
		if(isset($_POST['my_image_upload_nonce'],$_POST['post_id']) && wp_verify_nonce($_POST['my_image_upload_nonce'],'my_image_upload')){
			require_once(ABSPATH.'wp-admin/includes/image.php');
			require_once(ABSPATH.'wp-admin/includes/file.php');
			require_once(ABSPATH.'wp-admin/includes/media.php');
			$attachment_id = media_handle_upload('my_image_upload',$_POST['post_id']);
			if(!is_wp_error($attachment_id)){
				?>
				<script type="text/javascript">
			        parent.add_new_image(<?php echo $attachment_id;?>);
			    </script>
				<?php
			}
		}
		exit;
    } else if($_GET['do']=='send_email'){
    	$message = "";
    	$datas = json_decode(stripslashes($_POST['content']));
    	foreach($datas as $key => $val){
			$message .= $key.': '.$val."\r\n<br /><br />";
		}
		$headers  = 'MIME-Version: 1.0'."\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8'."\r\n";
		wp_mail(get_option('form_email'),'Contact Form',$message,$headers);
		exit;
    } else if($_GET['do']=='update_email'){
    	update_option('form_email',$_GET['email']);
    	exit;
    } else if($_GET['do']=='save_seo'){
    	add_post_meta($_GET['id'],'seo_settings',$_POST['seo_settings'],true);
    	exit;
    }
}
add_action('admin_footer','kodyok_button');
function kodyok_button(){
?>
<script type="text/javascript">
jQuery("#postdivrich").prepend('<br /><br /><a href="<?php echo get_site_url().'/?p='.$_GET['post'];?>&kodyok" style="background-color:#00ccff;color:#333;font-size:20px;padding:15px;border:2px solid #ffcc00;text-decoration:none;">Edit with Kodyok</a><br /><br />');
</script>
<?php
}
?>