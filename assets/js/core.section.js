$(document).ready(function(){
	if($('#post_content_area').length!=1){
		$('section').css('border','1px dashed #00ccff');
		$('section > .sortable').css('border-left','1px dashed #ffcc00');
		$('section > .sortable').css('border-right','1px dashed #ffcc00');
		$('section').each(function(){
			$(this).prepend('<div style="position:absolute;z-index:97;background:rgba(255,204,0,0.8);cursor:default;"><span class="fa fa-arrow-up section_up" style="font-size:26px;margin:5px;"></span><span class="fa fa-arrow-down section_down" style="font-size:26px;margin:5px;"></span><span class="fa fa-clone section_duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash section_delete" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog section_settings" style="font-size:26px;margin:5px;"></span></div>');
		});
		$("body").on("click",".section_up",function(){
			$(this).parent().parent().insertBefore($(this).parent().parent().prev());
		});
		$("body").on("click",".section_down",function(){
			$(this).parent().parent().insertAfter($(this).parent().parent().next());
		});
		$("body").on("click",".section_duplicate",function(){
			$(this).parent().parent().clone().insertAfter($(this).parent().parent());
			$(this).parent().parent().next().find('.slider').each(function(){
				id = 1;
				$('.slider').each(function(){
					slider_tag_id = $(this).children('.carousel').attr('id').split('_');
					if(slider_tag_id[1]>=id){
						id = parseInt(slider_tag_id[1])+1;
					}
				});
				$(this).children('.carousel').attr('id','slider_'+id);
				$(this).children('.carousel').children('.carousel-indicators').children('li').attr('data-target','#slider_'+id);
				$(this).children('.carousel').children('a').attr('href','#slider_'+id);
			});
			parent.load_add_item_drag();
		});
		$("body").on("click",".section_delete",function(){
			if(confirm('This section will be deleted with all content. Are you sure?')){
				$(this).parent().parent().remove();
			}
		});
		$('body').on('click','.section_settings',function(){
			active_element = $(this).parent().parent();
			$('#iframe',window.parent.document).css('width',$('#all_content',window.parent.document).width()-380);
			$('#settings_menu',window.parent.document).show();
			$('#settings_menu > div',window.parent.document).hide();
			$('#section_settings',window.parent.document).show();
			get_section_settings();
		});
	}
});
function get_section_settings(){
	if($(active_element).css('background-image').split('0%')[0].split('linear-gradient(')[1]){
		opacity_value = $(active_element).css('background-image').split('0%')[0].split('linear-gradient(')[1].split(',');
	} else {
		opacity_value = $(active_element).css('background-color').split(',');
	}
	if(opacity_value.length==4){
		opacity_value = opacity_value[3].replace(' ','');
		opacity_value = opacity_value.replace(')','');
		$('#section_opacity',window.parent.document).val(parseFloat(opacity_value).toFixed(1)*100);
	} else {
		$('#section_opacity',window.parent.document).val(100);
	}
	$('#section_id',window.parent.document).val($(active_element).attr('id'));
}
function change_section_background(value){
	if($(active_element).css('background-image').split('0%')[0].split('linear-gradient(')[1]){
		opacity_value = $(active_element).css('background-image').split('0%')[0].split('linear-gradient(')[1];
		old_opacity_value_1 = opacity_value;
		opacity_value = opacity_value.split(',');
		if(opacity_value.length==4){
			r = opacity_value[0].replace('rgba(','');
			g = opacity_value[1];
			b = opacity_value[2];
			opacity_value = opacity_value[3].replace(' ','');
			opacity_value = opacity_value.replace(')','');
			opacity_value = parseFloat(opacity_value).toFixed(1)*100;
		} else {
			r = opacity_value[0].replace('rgb(','');
			g = opacity_value[1];
			b = opacity_value[2].replace(')','');
			opacity_value = 100;
		}
		new_background_color = $(active_element).css('background-image').replace(old_opacity_value_1,value+' ');
		new_background_color = new_background_color.replace(old_opacity_value_1,value+' ');
		$(active_element).css('background-image',new_background_color);
	} else {
		opacity_value = $(active_element).css('background-color').split(',');
		if(opacity_value.length==4){
			r = opacity_value[0].replace('rgba(','');
			g = opacity_value[1];
			b = opacity_value[2];
			opacity_value = opacity_value[3].replace(' ','');
			opacity_value = opacity_value.replace(')','');
			if(r==0 && g==0 && b==0 && opacity_value==0){
				opacity_value = 100;
			} else {
				opacity_value = parseFloat(opacity_value).toFixed(1)*100;
			}
		} else {
			opacity_value = 100;
		}
		$(active_element).css('background-color',value);
	}
}
function set_section_opacity(value){
	if($(active_element).css('background-image').split('0%')[0].split('linear-gradient(')[1]){
		opacity_value = $(active_element).css('background-image').split('0%')[0].split('linear-gradient(')[1];
		old_opacity_value_1 = opacity_value;
		opacity_value = opacity_value.split(',');
		if(opacity_value.length==4){
			r = opacity_value[0].replace('rgba(','');
			g = opacity_value[1];
			b = opacity_value[2];
			opacity_value = opacity_value[3].replace(' ','');
			opacity_value = opacity_value.replace(')','');
			opacity_value = parseFloat(opacity_value).toFixed(1)*100;
		} else {
			r = opacity_value[0].replace('rgb(','');
			g = opacity_value[1];
			b = opacity_value[2].replace(')','');
			opacity_value = 100;
		}
		if(value=='minus'){
			if(opacity_value>0){
				opacity_value = opacity_value-10;
			}
		} else if(value=='plus'){
			if(opacity_value<100){
				opacity_value = opacity_value+10;
			}
		}
		new_background_color = $(active_element).css('background-image').replace(old_opacity_value_1,'rgba('+r+','+g+','+b+','+opacity_value/100+') ');
		new_background_color = new_background_color.replace(old_opacity_value_1,'rgba('+r+','+g+','+b+','+opacity_value/100+') ');
		$(active_element).css('background-image',new_background_color);
		$('#section_opacity',window.parent.document).val(opacity_value);
	} else {
		opacity_value = $(active_element).css('background-color');
		old_opacity_value_1 = opacity_value;
		opacity_value = opacity_value.split(',');
		if(opacity_value.length==4){
			opacity_value = opacity_value[3].replace(' ','');
			old_opacity_value_2 = opacity_value.replace(')','');
			opacity_value = opacity_value.replace(')','');
			opacity_value = parseFloat(opacity_value).toFixed(1)*100;
			opacity_defined = 1;
		} else {
			opacity_value = 100;
			opacity_defined = 0;
		}
		if(value=='minus'){
			if(opacity_value>0){
				opacity_value = opacity_value-10;
			}
		} else if(value=='plus'){
			if(opacity_value<100){
				opacity_value = opacity_value+10;
			}
		}
		if(opacity_defined==1){
			new_background_color = old_opacity_value_1.replace('rgb(','rgba(');
			new_background_color = new_background_color.replace(old_opacity_value_2+')',opacity_value/100+')');
			$(active_element).css('background-color',new_background_color);
		} else {
			new_background_color = old_opacity_value_1.replace('rgb(','rgba(');
			new_background_color = new_background_color.replace(')',','+opacity_value/100+')');
			$(active_element).css('background-color',new_background_color);
		}
		$('#section_opacity',window.parent.document).val(opacity_value);
	}
}
function remove_background(){
	$(active_element).css('background-color','transparent');
	$(active_element).css('background-attachment','scroll');
	$(active_element).css('background-image','none');
	$(active_element).css('background-position','center center');
	$(active_element).css('background-repeat','no-repeat');
	$(active_element).css('-webkit-background-size','cover');
	$(active_element).css('-moz-background-size','cover');
	$(active_element).css('background-size','cover');
	$(active_element).css('-o-background-size','cover');
}
function add_id(value){
	error = 0;
	$('section').each(function(){
		if($(this).attr('id')==value){
			error = 1;
		}
	});
	if(error==0){
		$(active_element).attr('id',value);
	} else {
		$('#section_id',window.parent.document).parent().addClass('has-error');
		$('#section_id',window.parent.document).val('');
		$('#section_id',window.parent.document).attr('placeholder','Type different ID');
	}
}