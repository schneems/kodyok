<?php
if(is_super_admin() && isset($_GET['kodyok'])){
	include('editor.php');
	exit;
}
get_header();
if(isset($_GET['p'])){
	$post = get_post($_GET['p']);
} else {
	$post = get_post(get_the_ID());
}
if($post->post_type=='page' || $post->post_type=='post'){
	if(!metadata_exists('post',$post->ID,'kodyok_content')){
		$post->post_content = '<section><div class="container sortable" style="min-height:50px;"><div class="element text"><div class="editable" style="font-size:20px;" spellcheck="false">'.$post->post_content.'</div></div></div></section>';
	}
}
echo $post->post_content;
get_footer();
?>