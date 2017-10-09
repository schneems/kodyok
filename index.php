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
if($post->post_type=='page'){
	if(!metadata_exists('post',$post->ID,'kodyok_content')){
		echo '<section><div class="container sortable" style="min-height:50px;"><div class="element text"><div class="editable" style="font-size:20px;" spellcheck="false">'.$post->post_content.'</div></div></div></section>';
	} else {
		echo $post->post_content;
	}
} else if($post->post_type=='post'){
	echo '<section style="background-image:linear-gradient(rgba(0,0,0,0.5) 0%,rgba(0,0,0,0.5) 100%),url('.wp_get_attachment_image_src(get_post_thumbnail_id(),'original')[0].');background-attachment:scroll;background-size:cover;-webkit-background-size:cover;background-position:50% 50%;background-repeat:no-repeat no-repeat;"><div class="container"><div class="row"><div class="col-md-8 col-md-offset-2" style="padding-top:100px;padding-bottom:100px;"><h4 style="text-transform:uppercase;color:#FFF;">'.date('F j, Y',strtotime($post->post_date)).' by '.get_the_author_meta('display_name',$post->post_author).'</h4><h1 style="color:#FFF;">'.$post->post_title.'</h1></div></div></div></section>';
	echo '<section>';
	echo '<div class="container" style="padding-top:20px;">';
	echo '<div class="row">';
	if(!metadata_exists('post',$post->ID,'kodyok_content')){
		echo '<div id="post_content_area" class="col-md-8 col-md-offset-2 sortable" style="min-height:50px;"><div class="element text"><div class="editable" style="font-size:20px;" spellcheck="false">'.$post->post_content.'</div></div></div>';
	} else {
		echo '<div id="post_content_area" class="col-md-8 col-md-offset-2 sortable" style="min-height:50px;">'.$post->post_content.'</div>';
	}
	echo '</div>';
	$args = array(
		'post_id' => $post->ID,
		'status' => 'approve'
	);
	$comments = get_comments($args);
	foreach($comments as $comment){
		echo '<div class="row" style="margin-top:40px;"><div class="col-md-8 col-md-offset-2" style="padding-top:40px;border-top:1px solid #EEE;">';
		echo '<a href="'.$comment->comment_author_url.'" target="_blank"><b>'.$comment->comment_author.'</b></a><br />';
		echo '<span style="text-transform:uppercase;">'.date('F j, Y \A\T H:i',strtotime($comment->comment_date_gmt)).'</span><br /><br />';
		echo $comment->comment_content;
		echo '</div></div>';
	}
	echo '<div class="row" style="margin-top:40px;margin-bottom:100px;"><div class="col-md-8 col-md-offset-2" style="padding-top:40px;border-top:1px solid #EEE;">';
	$args2 = array(
		'comment_field' => '<textarea id="comment" name="comment" class="form-control" cols="45" rows="8" aria-required="true" style="margin-bottom:20px;"></textarea>',
		'class_submit' => 'btn'
	);
	comment_form($args2);
	echo '</div></div>';
	echo '</div>';
	echo '</section>';
}
get_footer();
?>