function change_menu_background(value){
	$('nav').css('background-color',value);
}
function change_menu_font(value){
	$('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('color',value);
}
function set_menu_font(value){
	$('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('font-family',value);
}
function set_menu_opacity(value){
	opacity_value = $('nav').css('background-color');
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
		$('nav').css('background-color',new_background_color);
	} else {
		new_background_color = old_opacity_value_1.replace('rgb(','rgba(');
		new_background_color = new_background_color.replace(')',','+opacity_value/100+')');
		$('nav').css('background-color',new_background_color);
	}
	return opacity_value;
}
function remove_menu(value){
	$('#sortable',window.parent.document).children('div').eq(value).remove();
	$.get(get_site_url+"/?do=remove_menu&id="+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).attr('data-item-id'));
	$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).remove();
	a = 0;
	$('#sortable',window.parent.document).html('');
	$('nav').children('.container').children('.collapse').children('.nav').children().each(function(){
		$('#sortable',window.parent.document).append('<div id="content_menu_'+a+'" style="border:1px solid #677888;padding:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+$(this).text()+'<span class="fa fa-link menu_link" data-href="'+$(this).children('a').attr('href')+'" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-pencil menu_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_menu" style="font-size:18px;margin-left:10px;"></span></div>');
		a++;
	});
}
function edit_menu(value){
	$('#button_name',window.parent.document).val($('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).text());
	return $('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).children('a').attr('href');
}
function add_menu(value){
	button_name = $('#button_name',window.parent.document).val();
	$('#button_name',window.parent.document).attr('placeholder','New button');
	$('#button_name',window.parent.document).attr('style','');
	if(button_name==''){
		$('#button_name',window.parent.document).attr('placeholder','This field is required.');
		$('#button_name',window.parent.document).attr('style','border:1px solid #a94442;');
	} else {
		if($('.add_menu',window.parent.document).html()=='Add'){
			next_item_id = $('#sortable',window.parent.document).children().length;
			$.get(get_site_url+"/?do=add_menu_item&title="+button_name+"&position="+(next_item_id+1)+"&menu_id="+$('nav').children('.container').children('.collapse').children('.nav').attr('data-menu-id'),function(data){
				$('nav').children('.container').children('.collapse').children('.nav').append('<li data-item-id="'+parseInt(data)+'" data-position="'+(next_item_id+1)+'"><a href="#">'+button_name+'</a></li>');
			});
			$('#sortable',window.parent.document).append('<div id="content_menu_'+next_item_id+'" style="border:1px solid #677888;padding:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+button_name+'<span class="fa fa-link menu_link" data-href="#" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-pencil menu_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_menu" style="font-size:18px;margin-left:10px;"></span></div>');
		} else if($('.add_menu',window.parent.document).html()=='Save'){
			$.get(get_site_url+"/?do=update_menu&menu_id="+$('nav').children('.container').children('.collapse').children('.nav').attr('data-menu-id')+"&item_id="+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).attr('data-item-id')+"&link="+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).children('a').attr('href')+"&target="+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).children('a').attr('target')+"&title="+button_name+"&position="+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).attr('data-position'));
			$('#sortable',window.parent.document).children('div').eq(value).html('<span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+button_name+'<span class="fa fa-link menu_link" data-href="'+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).children('a').attr('href')+'" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-pencil menu_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_menu" style="font-size:18px;margin-left:10px;"></span>');
			$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(value).children('a').text(button_name);
			$('.add_menu',window.parent.document).html('Add');
		}
		$('#button_name',window.parent.document).val('');
		$('#button_name',window.parent.document).attr('placeholder','New button');
		$('#button_name',window.parent.document).attr('style','');
		menu_sortable();
	}
}
function update_menu_link(item_id,link){
	$('#content_menu_'+item_id+' > .menu_link',window.parent.document).attr('data-href',link);
	$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(item_id).children('a').attr('href',link);
	target = '';
	if(link.search('http://')>=0 || link.search('https://')>=0){
		$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(item_id).children('a').attr('target','_blank');
		target = '_blank';
	}
	$.get(get_site_url+"/?do=update_menu&menu_id="+$('nav').children('.container').children('.collapse').children('.nav').attr('data-menu-id')+"&item_id="+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(item_id).attr('data-item-id')+"&link="+link+"&target="+target+"&title="+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(item_id).text()+"&position="+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(item_id).attr('data-position'));
}
function remove_menu_link(item_id){
	$('#content_menu_'+item_id+' > .menu_link',window.parent.document).attr('data-href','#');
	$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(item_id).children('a').attr('href','#');
	$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(item_id).children('a').removeAttr('target');
}
function get_menu_settings(){
	$('#iframe',window.parent.document).css('width',$('#all_content',window.parent.document).width()-380);
	$('#settings_menu',window.parent.document).show();
	$('#settings_menu > div',window.parent.document).hide();
	$('#menu_settings',window.parent.document).show();
	opacity_value = $('nav').css('background-color');
	opacity_value = opacity_value.split(',');
	if(opacity_value.length==4){
		opacity_value = opacity_value[3].replace(' ','');
		opacity_value = opacity_value.replace(')','');
		$('#menu_opacity',window.parent.document).val(parseFloat(opacity_value).toFixed(1)*100);
	} else {
		$('#menu_opacity',window.parent.document).val(100);
	}
	if($('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('color')){
		font_color = $('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('color');
		font_color = font_color.split('rgb(');
		font_color = font_color[1].split(')');
		font_color = font_color[0].split(', ');
		font_color = rgbToHex(parseInt(font_color[0]),parseInt(font_color[1]),parseInt(font_color[2]));
		$(".picker[data-type='menu_font']",window.parent.document).val(font_color);
		$(".picker[data-type='menu_font']",window.parent.document).css('border-color','#'+font_color);
	}
	if($('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('font-family')){
		$("#menu_change_font option[value='"+$('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('font-family')+"']",window.parent.document).attr('selected','selected');
	}
	a = 0;
	$('#sortable',window.parent.document).html('');
	$('nav').children('.container').children('.collapse').children('.nav').children().each(function(){
		$('#sortable',window.parent.document).append('<div id="content_menu_'+a+'" style="border:1px solid #677888;padding:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+$(this).text()+'<span class="fa fa-link menu_link" data-href="'+$(this).children('a').attr('href')+'" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-pencil menu_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_menu" style="font-size:18px;margin-left:10px;"></span></div>');
		a++;
	});
	menu_sortable();
}
function menu_sortable(){
	$("#sortable",window.parent.document).sortable({
		start:function(event,ui){
			current_font = $('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('font-family');
			current_color = $('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('color');
		},
		update:function(event,ui){
			order = $("#sortable",window.parent.document).sortable('toArray').toString().split(',');
    		item_list = '';
    		menu_content = '';
    		menu_content_content = '';
    		for(a=0;a<order.length;a++){
    			menu_id = order[a].split('content_menu_');
    			item_list += $('nav').children('.container').children('.collapse').children('.nav').children('li').eq(menu_id[1]).attr('data-item-id')+',';
    			target = '';
    			if($('nav').children('.container').children('.collapse').children('.nav').children('li').eq(menu_id[1]).children('a').attr('target')){
    				target = ' target="'+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(menu_id[1]).children('a').attr('target')+'"';
    			}
    			menu_content += '<li data-item-id="'+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(menu_id[1]).attr('data-item-id')+'" data-position="'+(a+1)+'"><a href="'+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(menu_id[1]).children('a').attr('href')+'"'+target+'>'+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(menu_id[1]).text()+'</a></li>';
    			menu_content_content += '<div id="content_menu_'+a+'" style="border:1px solid #677888;padding:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(menu_id[1]).text()+'<span class="fa fa-link menu_link" data-href="'+$('nav').children('.container').children('.collapse').children('.nav').children('li').eq(menu_id[1]).children('a').attr('href')+'" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-pencil menu_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_menu" style="font-size:18px;margin-left:10px;"></span></div>';
    		}
    		$.get(get_site_url+"/?do=update_menu_list&items="+item_list);
    		$('nav').children('.container').children('.collapse').children('.nav').html(menu_content);
    		$('#sortable',window.parent.document).html(menu_content_content);
    		$('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('color',current_color);
			$('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('font-family',current_font);
		},tolerance:'pointer',cursor:'move',axis:'y'
	});
}