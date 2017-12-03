	</div>
	<?php
	if(get_option('footer')){
    	echo get_option('footer');
    } else {
    	include("sections/footer.html");
    }
	if(!isset($_GET['editor'])){
		wp_footer();
	}
	?>
	<script src="<?php echo get_stylesheet_directory_uri();?>/assets/lightbox2/js/lightbox.min.js"></script>
</body>
</html>