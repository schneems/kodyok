	</div>
	<footer style="background-color:rgb(238,238,238);">
	    <div class="container sortable" style="min-height:50px;">
	    	<?php
	    	if(get_option('footer')){
	        	echo get_option('footer');
		    } else {
		    ?>
	    	<div class="element grid" style="margin-top:50px;padding-top:5px;padding-bottom:5px;">
                <div class="row">
                	<div class="col-xs-4 sortable" style="min-height:50px;"></div>
                    <div class="col-xs-1 sortable" style="min-height:50px;">
                        <div class="element icon" style="text-align:center;"><a href="#"><i class="fa fa-twitter" aria-hidden="true" style="font-size:30px;color:#333;"></i></a></div>
                    </div>
                    <div class="col-xs-1 sortable" style="min-height:50px;">
                        <div class="element icon" style="text-align:center;"><a href="#"><i class="fa fa-facebook" aria-hidden="true" style="font-size:30px;color:#333;"></i></a></div>
                    </div>
                    <div class="col-xs-1 sortable" style="min-height:50px;">
                        <div class="element icon" style="text-align:center;"><a href="#"><i class="fa fa-instagram" aria-hidden="true" style="font-size:30px;color:#333;"></i></a></div>
                    </div>
                    <div class="col-xs-1 sortable" style="min-height:50px;">
                        <div class="element icon" style="text-align:center;"><a href="#"><i class="fa fa-linkedin" aria-hidden="true" style="font-size:30px;color:#333;"></i></a></div>
                    </div>
                    <div class="col-xs-4 sortable" style="min-height:50px;"></div>
                </div>
            </div>
	        <div class="element text" style="margin-bottom:50px;">
	            <div class="editable" style="text-align:center;font-size:20px;outline:none;" spellcheck="false">
	                <div><font style="font-size:16px;">Copyright © Your Website 2017</font></div>
	            </div>
	        </div>
		    <?php
		    }
		    ?>
	    </div>
	</footer>
	<?php
	if(!isset($_GET['editor'])){
		wp_footer();
	}
	?>
	<script src="<?php echo get_stylesheet_directory_uri();?>/assets/lightbox2/js/lightbox.min.js"></script>
</body>
</html>