<?php
get_header();
?>
<section style="background-color:rgb(245,247,250);">
    <div class="container sortable" style="min-height:50px;">
    	<div class="element text" style="margin-top:100px;margin-bottom:20px;">
            <div class="editable" style="text-align:center;font-size:20px;outline:none;" spellcheck="false">
                <h2 style="color:#666;">BLOG</h2>
            </div>
        </div>
        <div class="element grid dynamic_content" style="margin-bottom:100px;">
    		<div class="row" style="display:flex;flex-wrap:wrap;"></div>
    		<div class="row">
    			<div class="col-md-12" style="min-height:50px;">
    				<div class="element button" style="text-align:center;margin-top:50px;">
			            <a class="btn load_more" style="padding:10px 20px;background-color:rgb(235,208,9);color:rgb(255,255,255);text-decoration:none;font-size:18px;">LOAD MORE</a>
			        </div>
    			</div>
    		</div>
    	</div>
    </div>
</section>
<?php
get_footer();
?>