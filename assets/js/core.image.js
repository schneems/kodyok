$(document).ready(function(){
	$("body").on("click",".image_edit",function(){
		if($(this).parent().parent().hasClass('image')){
			active_element = $(this).parent().parent();
		}
		parent.image_edit();
	});
	$('body').on('click','.image_link',function(){
		active_element = $(this).parent().parent();
		parent.link_edit($(active_element).children('a').attr('href'));
	});
});
function get_image_settings(){
	$('#image_border',window.parent.document).val($(active_element).children('a').children('img').css('border-width'));
}
function select_active_element(){
	active_element = $('img[src="'+get_stylesheet_directory_uri+'/assets/img/blank.gif"]').parent().parent();
}
function update_image(selected_image,id,type){
	if($(active_element).prop("tagName")=='SECTION'){
		rgb_value = $(active_element).css('background-color').split(',');
		if(rgb_value.length==4){
			r = rgb_value[0].replace('rgba(','');
			g = rgb_value[1];
			b = rgb_value[2];
			opacity_value = rgb_value[3].replace(' ','');
			opacity_value = opacity_value.replace(')','');
			if(r==0 && g==0 && b==0 && opacity_value==0){
				r = 255;
				g = 255;
				b = 255;
			}
			opacity_value = parseFloat(opacity_value).toFixed(1)*100;
		} else {
			r = rgb_value[0].replace('rgb(','');
			g = rgb_value[1];
			b = rgb_value[2].replace(')','');
			opacity_value = 0;
		}
		$(active_element).attr('style','background-image:linear-gradient(rgba('+r+','+g+','+b+','+opacity_value/100+') 0%,rgba('+r+','+g+','+b+','+opacity_value/100+') 100%),url('+selected_image+');background-attachment:scroll;background-size:cover;background-position:50% 50%;background-repeat:no-repeat;border:1px dashed #00ccff;');
	} else if($(active_element).hasClass('navbar')){
		$(active_element).children('.container').children('.navbar-header').children('.navbar-brand').html('<img src="'+selected_image+'" height="50" />');
	} else if($(active_element).hasClass('image')){
		if($(active_element).children('a').attr('data-lightbox')){
			$.get(get_site_url+"/?do=get_image&size=medium&id="+id,function(data){
				$(active_element).children('a').children('img').attr('src',data);
			});
			$(active_element).children('a').attr('href',selected_image);
		} else {
			$(active_element).children('a').children('img').attr('src',selected_image);
		}
		img = new Image();
		img.onload = function(){
			$(active_element).css('max-width',this.width+'px');
			$(active_element).css('max-height',this.height+'px');
			image_resizable($(active_element));
		}
		img.src = selected_image;
	} else if($(active_element).hasClass('slider')){
		add_slider(selected_image);
	}
	if(type=='text'){
		img = new Image();
		img.onload = function(){
			document.execCommand("insertHTML", false, '<div class="element image image_in_text last_image" style="max-width:'+this.width+'px;max-height:'+this.height+'px;margin:auto;"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil image_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link image_link" style="font-size:26px;margin:5px;"></span></div><a href="#"><img src="'+selected_image+'" class="img-responsive" /></a></div>');
			image_resizable('.last_image');
		}
		img.src = selected_image;
	}
}
function remove_last_image(){
	$('img[src="'+get_stylesheet_directory_uri+'/assets/img/blank.gif"]').parent().parent().remove();
}
function change_image_border(value){
	$(active_element).children('a').children('img').css('border-color',value);
}
function set_image_border(value){
	border_value = $(active_element).children('a').children('img').css('border-width');
	border_value = border_value.split('px');
	if(value=='minus'){
		border_value = parseInt(border_value[0])-1;
	} else if(value=='plus'){
		border_value = parseInt(border_value[0])+1;
	}
	$(active_element).children('a').children('img').css('border-style','solid');
	$(active_element).children('a').children('img').css('border-width',border_value+'px');
	return border_value+'px';
}
function set_shape(value){
	if(value=='circle'){
		$(active_element).find('a > img').addClass('img-circle');
	} else if(value=='square'){
		$(active_element).find('a > img').removeClass('img-circle');
	}
}
function image_resizable(resize_element){
	if(resize_element=='.load_resize'){
		$(resize_element).find('.ui-resizable-handle').remove();
	}
	$(resize_element).resizable({
		aspectRatio: true,
		handles: 'e,s,se'
	});
	$(resize_element).removeClass('load_resize');
	$(resize_element).removeClass('last_image');
}