<?php
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
if(is_super_admin()){
if(isset($_GET['do'])){
    if($_GET['do']=='get_pages'){
    	$pages = get_pages();
    	$i = 0;
		foreach($pages as $page){
			$new_pages[$i]['title'] = $page->post_title;
			$new_pages[$i]['url'] = str_replace(get_site_url().'/','',get_page_link($page->ID));
			$i++;
		}
		echo json_encode($new_pages);
		exit;
    } else if($_GET['do']=='save'){
		$my_post = array('ID'=>$_GET['id'],'post_content'=>$_POST['content']);
		wp_update_post($my_post);
		add_post_meta($_GET['id'],'kodyok_content','1',true);
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
			$images = get_attached_media('',$post->ID);
			foreach($images as $image){
				$new_posts[$i]['image'] = $image->guid;
			}
			$new_posts[$i]['guid'] = $post->guid;
			$i++;
		}
		echo json_encode($new_posts);
		exit;
    } else if($_GET['do']=='remove_menu'){
    	wp_delete_post($_GET['id']);
    	$items = wp_get_nav_menu_items('navigation');
    	$i = 1;
		foreach($items as $item){
			$my_post = array('ID'=>$item->db_id,'menu_order'=>$i);
			wp_update_post($my_post);
	        $i++;
		}
    	exit;
    } else if($_GET['do']=='update_menu'){
    	wp_update_nav_menu_item($_GET['menu_id'], $_GET['item_id'], array(
            'menu-item-title' =>  __($_GET['title']),
            'menu-item-url' => $_GET['link'],
            'menu-item-target' => $_GET['target'],
            'menu-item-position' => $_GET['position'],
            'menu-item-status' => 'publish'
        ));
        exit;
    } else if($_GET['do']=='update_menu_list'){
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
    } else if($_GET['do']=='add_menu_item'){
    	echo wp_update_nav_menu_item($_GET['menu_id'], 0, array(
            'menu-item-title' =>  __($_GET['title']),
            'menu-item-url' => '#',
            'menu-item-target' => '',
            'menu-item-position' => $_GET['position'],
            'menu-item-status' => 'publish'
        ));
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
}
?>