function get_slider_settings(){
	$('#slider_content',window.parent.document).html('');
	a = 0;
	$(active_element).children('.carousel').children('.carousel-inner').children().each(function(){
		$("#slider_content",window.parent.document).append('<div id="content_slider_'+a+'" style="border:1px solid #CCC;padding-left:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span><img src="'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(a).children('img').attr('src')+'" height="42"><span class="fa fa-trash remove_slider" style="font-size:18px;margin-left:10px;"></span></div>');
		a++;
	});
	$("#slider_content",window.parent.document).sortable({
		update:function(event,ui){
			order = $("#slider_content",window.parent.document).sortable('toArray').toString().split(',');
    		slider_content = '';
    		slider_content_content = '';
    		for(a=0;a<order.length;a++){
    			slider_id = order[a].split('content_slider_');
    			if(a==0){
    				slider_content += '<div class="item active">'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(slider_id[1]).html()+'</div>';
    			} else {
    				slider_content += '<div class="item">'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(slider_id[1]).html()+'</div>';
    			}
    			slider_content_content += '<div id="content_slider_'+a+'" style="border:1px solid #CCC;padding-left:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span><img src="'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(slider_id[1]).children('img').attr('src')+'" height="42"><span class="fa fa-trash remove_slider" style="font-size:18px;margin-left:10px;"></span></div>';
    		}
    		$(active_element).children('.carousel').children('.carousel-inner').html(slider_content);
    		$('#slider_content',window.parent.document).html(slider_content_content);
		},tolerance:'pointer',cursor:'move',axis:'y'
	});
}
function add_slider(selected_image){
	next_item_id = $(active_element).children('.carousel').children('.carousel-inner').children('.item').length;
	slider_tag_id = $(active_element).children('.carousel').attr('id');
	if(next_item_id==0){
		$(active_element).children('.carousel').children('.carousel-inner').append('<div class="item active"><img src="'+selected_image+'" style="margin-left:auto;margin-right:auto;"><div class="carousel-caption">'+$('#slider_caption').val()+'</div></div>');
		$(active_element).children('.carousel').children('.carousel-indicators').append('<li data-target="#'+slider_tag_id+'" data-slide-to="0" class="active"></li>');
	} else {
		$(active_element).children('.carousel').children('.carousel-inner').append('<div class="item"><img src="'+selected_image+'" style="margin-left:auto;margin-right:auto;"><div class="carousel-caption">'+$('#slider_caption').val()+'</div></div>');
		$(active_element).children('.carousel').children('.carousel-indicators').append('<li data-target="#'+slider_tag_id+'" data-slide-to="'+next_item_id+'"></li>');
	}
	$("#slider_content",window.parent.document).append('<div id="content_slider_'+next_item_id+'" style="border:1px solid #CCC;padding-left:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span><img src="'+selected_image+'" height="42"><span class="fa fa-trash remove_slider" style="font-size:18px;margin-left:10px;"></span></div>');
	$('#slider_caption',window.parent.document).val('');
	$("#slider_content",window.parent.document).sortable({
		update:function(event,ui){
			order = $("#slider_content",window.parent.document).sortable('toArray').toString().split(',');
    		slider_content = '';
    		slider_content_content = '';
    		for(a=0;a<order.length;a++){
    			slider_id = order[a].split('content_slider_');
    			if(a==0){
    				slider_content += '<div class="item active">'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(slider_id[1]).html()+'</div>';
    			} else {
    				slider_content += '<div class="item">'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(slider_id[1]).html()+'</div>';
    			}
    			slider_content_content += '<div id="content_slider_'+a+'" style="border:1px solid #CCC;padding-left:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span><img src="'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(slider_id[1]).children('img').attr('src')+'" height="42"><span class="fa fa-trash remove_slider" style="font-size:18px;margin-left:10px;"></span></div>';
    		}
    		$(active_element).children('.carousel').children('.carousel-inner').html(slider_content);
    		$('#slider_content',window.parent.document).html(slider_content_content);
		},tolerance:'pointer',cursor:'move',axis:'y'
	});
}
function remove_slider(value){
	$('#slider_content',window.parent.document).html('');
	$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(value).remove();
	slider_tag_id = $(active_element).children('.carousel').attr('id');
	a = 0;
	slider_content = '';
	slider_button_content = '';
	$(active_element).children('.carousel').children('.carousel-inner').children().each(function(){
		$("#slider_content",window.parent.document).append('<div id="content_slider_'+a+'" style="border:1px solid #CCC;padding-left:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span><img src="'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(a).children('img').attr('src')+'" height="42"><span class="fa fa-trash remove_slider" style="font-size:18px;margin-left:10px;"></span></div>');
		if(a==0){
			slider_content += '<div class="item active">'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(a).html()+'</div>';
			slider_button_content += '<li data-target="#'+slider_tag_id+'" data-slide-to="0" class="active"></li>';
		} else {
			slider_content += '<div class="item">'+$(active_element).children('.carousel').children('.carousel-inner').children('.item').eq(a).html()+'</div>';
			slider_button_content += '<li data-target="#'+slider_tag_id+'" data-slide-to="'+a+'"></li>';
		}
		a++;
	});
	$(active_element).children('.carousel').children('.carousel-inner').html(slider_content);
	$(active_element).children('.carousel').children('.carousel-indicators').html(slider_button_content);
}