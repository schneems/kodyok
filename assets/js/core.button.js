$(document).ready(function(){
	$('body').on('click','.button_edit',function(){
		active_element = $(this).parent().parent();
		$(active_element).children('a').attr('contenteditable','true');
		$(active_element).children('a').focus();
		selection = window.getSelection();
        range = document.createRange();
	    range.selectNodeContents($(active_element).children('a')[0]);
        selection.removeAllRanges();
        selection.addRange(range);
	});
	$('body').on('click','.button_link',function(){
		active_element = $(this).parent().parent();
		parent.link_edit($(active_element).children('a').attr('href'));
	});
	$("body").on("input","a",function(){
		button_text = $(this).html();
		if($(this).parent().hasClass('button') && $(this).parent().parent().parent().parent().hasClass('dynamic_content')){
			grid_element = $(this).parent().parent().parent().parent();
			a = 0;
			$(grid_element).children('.row').each(function(){
				b = 0;
				$(grid_element).eq(a).children('div').each(function(){
					$(grid_element).eq(b).find('.button').children('a').not(':focus').html(button_text);
					b++;
				});
				a++;
			});
		}
	});
});
function get_button_settings(){
	opacity_value = $(active_element).children('a').css('background-color');
	opacity_value = opacity_value.split(',');
	if(opacity_value.length==4){
		opacity_value = opacity_value[3].replace(' ','');
		opacity_value = opacity_value.replace(')','');
		$('#button_opacity',window.parent.document).val(parseFloat(opacity_value).toFixed(1)*100);
	} else {
		$('#button_opacity',window.parent.document).val(100);
	}
	$('#button_border',window.parent.document).val($(active_element).children('a').css('border-width'));
}
function set_button_border(change_type){
	border_value = $(active_element).children('a').css('border-width');
	border_value = border_value.split('px');
	if(change_type=='minus'){
		border_value = parseInt(border_value[0])-1;
	} else if(change_type=='plus'){
		border_value = parseInt(border_value[0])+1;
	}
	$(active_element).children('a').css('border-style','solid');
	$(active_element).children('a').css('border-width',border_value+'px');
	apply_to_all('button','border-style','solid');
	apply_to_all('button','border-width',border_value+'px');
	return border_value+'px';
}
function set_button_opacity(change_type){
	opacity_value = $(active_element).children('a').css('background-color');
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
	if(change_type=='minus'){
		if(opacity_value>0){
			opacity_value = opacity_value-10;
		}
	} else if(change_type=='plus'){
		if(opacity_value<100){
			opacity_value = opacity_value+10;
		}
	}
	if(opacity_defined==1){
		new_background_color = old_opacity_value_1.replace('rgb(','rgba(');
		new_background_color = new_background_color.replace(old_opacity_value_2+')',opacity_value/100+')');		
	} else {
		new_background_color = old_opacity_value_1.replace('rgb(','rgba(');
		new_background_color = new_background_color.replace(')',','+opacity_value/100+')');
	}
	$(active_element).children('a').css('background-color',new_background_color);
	apply_to_all('button','background-color',new_background_color);
	return opacity_value;
}
function change_button_background(value){
	$(active_element).children('a').css('background',value);
	apply_to_all('button','background',value);
}
function change_button_font(value){
	$(active_element).children('a').css('color',value);
	apply_to_all('button','color',value);
}
function change_button_border(value){
	$(active_element).children('a').css('border-color',value);
	apply_to_all('button','border-color',value);
}
function set_button_font(value,font_name){
	WebFont.load({
		google: {
			families: [font_name]
		}
	});
	$(active_element).children('a').css('font-family',value);
	apply_to_all('button','font-family',value);
}
function set_button_size(value){
	$(active_element).children('a').css('font-size',value);
	apply_to_all('button','font-size',value);
}