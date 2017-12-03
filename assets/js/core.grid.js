$(document).ready(function(){
	$('body').on('click','.column_settings',function(){
		active_element = $(this).parent().parent();
		$('#iframe',window.parent.document).css('width',($('#all_content',window.parent.document).width()-380)+'px');
		$('#settings_menu',window.parent.document).show();
		$('#settings_menu > div',window.parent.document).hide();
		$('#column_settings',window.parent.document).show();
		if($(active_element).hasClass('dynamic_content')){
			$('.set_column[rel=5]',window.parent.document).hide();
			$('.set_column[rel=6]',window.parent.document).hide();
			$('.set_column[rel=7]',window.parent.document).hide();
			$('.set_column[rel=8]',window.parent.document).hide();
			$('.set_column[rel=9]',window.parent.document).hide();
		}
	});
	$("body").on("click",".add_row",function(){
		active_element = $(this).parent().parent();
		$(active_element).children('.row:last').clone().insertAfter($(active_element).children('.row:last'));
		$(active_element).children('.row:last').children('.sortable').css('box-shadow','inset 0 0 0 1px #ffcc00');
		$(active_element).children('.row:last').children('.sortable').html('');
		parent.load_add_item_drag();
	});
	$("body").on("click",".remove_row",function(){
		active_element = $(this).parent().parent();
		if($(active_element).children('.row').length>1){
			$(active_element).children('.row:last').remove();
		}
	});
});
function change_column(columns){
	row = $(active_element).children('.row').length;
	column = $(active_element).children('.row:first').children('div').length;
	layout_type = columns.split(',');
	for(a=0;a<row;a++){
		for(b=0;b<column;b++){
			if($(active_element).hasClass('dynamic_content')){
				$(active_element).children('.row').eq(a).children('div').eq(b).attr('class','col-md-'+layout_type[b]);
			} else {
				$(active_element).children('.row').eq(a).children('div').eq(b).attr('class','col-md-'+layout_type[b]+' sortable');
			}
		}
	}
	parent.load_add_item_drag();
}
function get_grid_settings(){
	opacity_value = $(active_element).children(".row:first").css('background-color').split(',');
	if(opacity_value.length==4){
		opacity_value = opacity_value[3].replace(' ','');
		opacity_value = opacity_value.replace(')','');
		$('#grid_opacity',window.parent.document).val(parseFloat(opacity_value).toFixed(1)*100);
	} else {
		$('#grid_opacity',window.parent.document).val(100);
	}
}
function change_grid_background(value){
	$(active_element).children('.row').css('background-color',value);
}
function set_grid_opacity(value){
	opacity_value = $(active_element).children('.row:first').css('background-color');
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
		$(active_element).children('.row').css('background-color',new_background_color);
	} else {
		new_background_color = old_opacity_value_1.replace('rgb(','rgba(');
		new_background_color = new_background_color.replace(')',','+opacity_value/100+')');
		$(active_element).children('.row').css('background-color',new_background_color);
	}
	return opacity_value;
}
function set_column(value){
	columns = [];
	columns[1] = '12,';
	columns[2] = '6,6,';
	columns[3] = '4,4,4,';
	columns[4] = '3,3,3,3,';
	columns[5] = '3,9,';
	columns[6] = '9,3,';
	columns[7] = '3,6,3,';
	columns[8] = '2,3,5,2,';
	columns[9] = '2,2,2,4,2,';
	columns[10] = '2,2,2,2,2,2,';
	grid_old_content = [];
	$(active_element).children().each(function(){
		if($(this).attr('class')=='row'){
			$(this).children().each(function(){
				grid_old_content.push($(this).html());
			});
		}
	});
	row = $(active_element).children('.row').length;
	column = $(active_element).children('.row:first').children('div').length;
	grid_content = '';
	a = 0;
	while(a<(row*column)){
		grid_content += '<div class="row">';
		layout_type = columns[value].split(',');
		for(b=0;b<(layout_type.length-1);b++){
			if(!grid_old_content[a]){
				grid_old_content[a] = '';
			}
			if($(active_element).hasClass('dynamic_content')){
				grid_content += '<div class="col-md-'+layout_type[b]+'" style="min-height:50px;">'+grid_old_content[a]+'</div>';
			} else {
				grid_content += '<div class="col-md-'+layout_type[b]+' sortable" style="min-height:50px;">'+grid_old_content[a]+'</div>';
			}
			a++;
		}
		grid_content += '</div>';
	}
	$(active_element).children('.row').remove();
	$(active_element).append(grid_content);
	parent.load_add_item_drag();
	if(value==1){
		return '';
	} else {
		if($(active_element).hasClass('dynamic_content')){
			return '';
		} else {
			return 'Customize<br /><iframe src="'+get_stylesheet_directory_uri+'/assets/column_settings.php?id='+value+'" width="240" height="40" style="border:none;margin:4px;"></iframe>';
		}
	}
}
function remove_grid_background(){
	$(active_element).children('.row').css('background-color','transparent');
}