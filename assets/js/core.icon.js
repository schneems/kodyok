$(document).ready(function(){
	$("body").on("click",".icon_edit",function(){
		active_element = $(this).parent().parent();
		parent.icon_edit();
	});
	$('body').on('click','.icon_link',function(){
		active_element = $(this).parent().parent();
		parent.link_edit($(active_element).children('a').attr('href'));
	});
});
function update_icon(icon){
	$(active_element).children('a').children('i').attr('class',icon);
}
function change_icon_color(value){
	$(active_element).children('a').children('i').css('color',value);
}
function select_active_icon(){
	active_element = $('i[class="fa fa-font-awesome"]').parent().parent();
}
function set_icon_size(value){
	icon_size = $(active_element).children('a').children('i').css('font-size');
	icon_size = icon_size.split('px');
	if(value=='minus'){
		icon_size = parseInt(icon_size[0])-2;
	} else if(value=='plus'){
		icon_size = parseInt(icon_size[0])+2;
	}
	$(active_element).children('a').children('i').css('font-size',icon_size+'px');
	return icon_size+'px';
}