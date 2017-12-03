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
?>
<header style="background-image:linear-gradient(rgba(0,0,0,0.5) 0%,rgba(0,0,0,0.5) 100%),url(<?php echo wp_get_attachment_image_src(get_post_thumbnail_id(),'original')[0];?>);background-attachment:scroll;background-size:cover;-webkit-background-size:cover;background-position:50% 50%;background-repeat:no-repeat no-repeat;">
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2" style="padding-top:100px;padding-bottom:100px;">
				<h4 style="color:#FFF;"><?php echo get_the_date('F j, Y').' - '.get_the_author_meta('display_name',$post->post_author);?></h4>
				<h1 style="color:#FFF;"><?php echo $post->post_title;?></h1>
			</div>
		</div>
	</div>
</header>
<article>
	<div class="container" style="padding-top:20px;">
		<div class="row">
			<?php
			if(!metadata_exists('post',$post->ID,'kodyok_content')){
			?>
			<div id="post_content_area" class="col-md-8 col-md-offset-2 sortable" style="min-height:50px;">
				<div class="element text">
					<div class="editable" style="font-size:20px;" spellcheck="false">
						<?php echo $post->post_content;?>
					</div>
				</div>
			</div>
			<?php
			} else {
			?>
			<div id="post_content_area" class="col-md-8 col-md-offset-2 sortable" style="min-height:50px;">
				<?php echo $post->post_content;?>
			</div>
			<?php
			}
			?>
		</div>
		<?php
		$args = array(
			'post_id' => $post->ID,
			'status' => 'approve'
		);
		$comments = get_comments($args);
		foreach($comments as $comment){
		?>
		<div class="row" style="margin-top:40px;">
			<div class="col-md-8 col-md-offset-2" style="padding-top:40px;border-top:1px solid #EEE;">
				<a href="<?php echo $comment->comment_author_url;?>" target="_blank"><b><?php echo $comment->comment_author;?></b></a><br />
				<?php echo get_comment_date('F j, Y - H:i');?><br /><br />
				<?php echo $comment->comment_content;?>
			</div>
		</div>
		<?php
		}
		?>
		<div class="row" style="margin-top:40px;margin-bottom:100px;">
			<div class="col-md-8 col-md-offset-2" style="padding-top:40px;border-top:1px solid #EEE;">
				<?php
				$args2 = array(
					'comment_field' => '<textarea id="comment" name="comment" class="form-control" cols="45" rows="8" aria-required="true" style="margin-bottom:20px;resize:none;"></textarea>',
					'class_submit' => 'btn btn-default'
				);
				comment_form($args2);
				?>
			</div>
		</div>
	</div>
</article>
<?php
get_footer();
?>