<?php
if(isset($_GET['p'])){
	$id = $_GET['p'];
} else {
	$id = get_the_ID();
}
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Kodyok</title>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/bootstrap/css/bootstrap-theme.min.css">
	<script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-1.12.0.min.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri();?>/assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-ui.min.js"></script>
	<link type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/assets/css/jquery-ui.min.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/colpick.js"></script>
    <link href="<?php echo get_stylesheet_directory_uri();?>/assets/css/colpick.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/font-awesome/css/font-awesome.min.css">
	<script type="text/javascript">
	$(document).ready(function(){
		$('#iframe').css('width',$('#all_content').width()-80);
		$('#settings_menu').css('left',$('#all_content').width()-300);
		$(window).on('resize',function(){
			if($('#settings_menu').css('display')=='none'){
				$('#iframe').css('width',$('#all_content').width()-80);
			} else {
				$('#iframe').css('width',$('#all_content').width()-380);
			}
			$('#settings_menu').css('left',$('#all_content').width()-300);
		});
		$("#save").click(function(){
			$('#design_area')[0].contentWindow.run_before_save();
			menu_style = {};
			menu_style["font"] = $("#design_area").contents().find('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('font-family');
			menu_style["color"] = $("#design_area").contents().find('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('color');
			menu_style["bgcolor"] = $("#design_area").contents().find('nav').css('background-color');
			menu_style["logo"] = $("#design_area").contents().find('nav').children('.container').children('.navbar-header').children('.navbar-brand').children('img').attr('src');
			$.ajax({
				type: "POST",
				url: "<?php echo get_site_url();?>/?do=save&id=<?php echo $id;?>",
				data: {content:$("#design_area").contents().find('.main_sortable').html(),menu:JSON.stringify(menu_style)},
				success: function(html){
					alert('Success');
					$('#design_area')[0].contentWindow.run_after_save();
				}
			});
			return false;
		});
		$('.open_window').click(function(){
			if($('#'+$(this).children('a').attr('rel')+'_menu').css('display')=='none'){
				$('.open_window').addClass('left_menu_icon');
				$(this).removeClass('left_menu_icon');
				$('.open_window').css('background-color','');
				$(this).css('background-color','#ffcc00');
				$('.open_window > a').css('color','#677888');
				$(this).children('a').css('color','#000');
				$('#add_menu').css('display','none');
				$('#'+$(this).children('a').attr('rel')+'_menu').css('display','');
			} else {
				$('.open_window').addClass('left_menu_icon');
				$('.open_window').css('background-color','');
				$('.open_window > a').css('color','#677888');
				$('#'+$(this).children('a').attr('rel')+'_menu').css('display','none');
			}
		});
		$('#add_list').change(function(){
			if($(this).val()=='ready_made'){
				$('#basic_elements').hide();
				$('#ready_made').show();
			} else if($(this).val()=='basic_elements'){
				$('#ready_made').hide();
				$('#basic_elements').show();
			}
		});
		$.getJSON('<?php echo get_stylesheet_directory_uri();?>/sections/list.json',function(data){
			$.each(data.sections,function(i){
				$('#ready_made').append('<div class="add_section" data-section-name="'+data.sections[i]['file']+'" style="text-align:center;cursor:pointer;font-size:10px;margin-bottom:10px;box-shadow:inset 0 0 0 1px #EEE;"><img src="<?php echo get_stylesheet_directory_uri();?>/sections/'+data.sections[i]['file']+'.gif" style="width:100%;" /><br />'+data.sections[i]['name']+'</div>');
			});
		});
	    $("body").on("click",".select",function(){
			$('#select_image').removeAttr('disabled');
			$('#site_images > div').css('border','');
			$(this).css('border','5px solid #00ccff');
			selected_image_id = $(this).attr('data-id');
			$.get("<?php echo get_site_url();?>/?do=get_image&size=intermediate&id="+$(this).attr('data-id'),function(data){
				selected_image = data;
			});
		});
		$("#select_image").click(function(){
			$('#design_area')[0].contentWindow.update_image(selected_image,selected_image_id);
			$('#imageModal').modal('hide');
		});
		$("body").on("input","#icon_search",function(){
			if($('#icon_search').val()!=''){
				$("#icons").css('display','none');
				$("#icon_search_results").css('display','');
				$('#icon_search_results').html('');
				$("#icons > section > div > div > a:contains('"+$('#icon_search').val()+"')").parent().each(function(){
					$('#icon_search_results').append($(this).clone());
				});
			} else {
				$("#icons").css('display','');
				$("#icon_search_results").css('display','none');
			}
		});
		$('.fa-hover').children('a').attr('href','#');
		$("body").on("click",".fa-hover",function(){
			$('#design_area')[0].contentWindow.update_icon($(this).children('a').children('i').attr('class'));
			$('#iconModal').modal('hide');
			$("#icons").css('display','');
			$("#icon_search_results").css('display','none');
			$("#icon_search").val('');
		});
		$('.picker').colpick({
			layout:'hex',
			submit:0,
			colorScheme:'dark',
			onChange:function(hsb,hex,rgb,el,bySetColor) {
				if($(el).attr('data-type')=='button_border'){
					$('#design_area')[0].contentWindow.change_button_border('#'+hex);
				} else if($(el).attr('data-type')=='button_font'){
					$('#design_area')[0].contentWindow.change_button_font('#'+hex);
				} else if($(el).attr('data-type')=='button_background'){
					$('#design_area')[0].contentWindow.change_button_background('rgba('+rgb['r']+','+rgb['g']+','+rgb['b']+','+$('#button_opacity').val()/100+')');
				} else if($(el).attr('data-type')=='grid_background'){
					$('#design_area')[0].contentWindow.change_grid_background('rgba('+rgb['r']+','+rgb['g']+','+rgb['b']+','+$('#grid_opacity').val()/100+')');
				} else if($(el).attr('data-type')=='icon'){
					$('#design_area')[0].contentWindow.change_icon_color('#'+hex);
				} else if($(el).attr('data-type')=='image_border'){
					$('#design_area')[0].contentWindow.change_image_border('#'+hex);
				} else if($(el).attr('data-type')=='menu_background'){
					$('#design_area')[0].contentWindow.change_menu_background('rgba('+rgb['r']+','+rgb['g']+','+rgb['b']+','+$('#menu_opacity').val()/100+')');
				} else if($(el).attr('data-type')=='menu_font'){
					$('#design_area')[0].contentWindow.change_menu_font('#'+hex);
				} else if($(el).attr('data-type')=='section_background'){
					$('#design_area')[0].contentWindow.change_section_background('rgba('+rgb['r']+','+rgb['g']+','+rgb['b']+','+$('#section_opacity').val()/100+')');
				}
				$(el).css('border-color','#'+hex);
				// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
				if(!bySetColor) $(el).val(hex);
			}
		}).keyup(function(){
			$(this).colpickSetColor(this.value);
		});
		$("body").on("click",".set_button_opacity",function(){
			opacity_value = $('#design_area')[0].contentWindow.set_button_opacity($(this).attr('rel'));
			$('#button_opacity').val(opacity_value);
		});
		$("body").on("click",".set_button_border",function(){
			border_value = $('#design_area')[0].contentWindow.set_button_border($(this).attr('rel'));
			$('#button_border').val(border_value);
		});
		$("#button_change_font").change(function(){
			if($("#button_change_font").val()!=0){
				$('#design_area')[0].contentWindow.set_button_font($("#button_change_font").val());
			}
		});
		$("#button_change_size").change(function(){
			if($("#button_change_size").val()!=0){
				$('#design_area')[0].contentWindow.set_button_size($("#button_change_size").val());
			}
		});
		$("body").on("click",".set_grid_opacity",function(){
			opacity_value = $('#design_area')[0].contentWindow.set_grid_opacity($(this).attr('rel'));
			$('#grid_opacity').val(opacity_value);
		});
		$("body").on("click",".set_column",function(){
			$('.set_column').css('border','1px solid #666');
			$(this).css('border','1px solid #EEE');
			set_column = $('#design_area')[0].contentWindow.set_column($(this).attr('rel'));
			$('#customize_layout').html(set_column);
		});
		$("body").on("click",".remove_grid_background",function(){
			$('#design_area')[0].contentWindow.remove_grid_background();
		});
		$('#imageModal').on('hidden.bs.modal',function(){
			$('#design_area')[0].contentWindow.remove_last_image();
		});
		$("body").on("click",".set_image_border",function(){
			border_value = $('#design_area')[0].contentWindow.set_image_border($(this).attr('rel'));
			$('#image_border').val(border_value);
		});
		$("body").on("click",".set_shape",function(){
			$('#design_area')[0].contentWindow.set_shape($(this).attr('rel'));
		});
		$("body").on("click",".set_align",function(){
			$('#design_area')[0].contentWindow.set_align($(this).attr('rel'));
		});
		$("body").on("click",".set_margin",function(){
			margin_side = $(this).attr('rel').split('_');
			margin_value = $('#design_area')[0].contentWindow.set_margin($(this).attr('rel'));
			$('#'+margin_side[0]+'_margin').val(margin_value);
		});
		$("#menu_change_font").change(function(){
			if($("#menu_change_font").val()!=0){
				$('#design_area')[0].contentWindow.set_menu_font($("#menu_change_font").val());
			}
		});
		$("body").on("click",".set_menu_opacity",function(){
			opacity_value = $('#design_area')[0].contentWindow.set_menu_opacity($(this).attr('rel'));
			$('#menu_opacity').val(opacity_value);
		});
		$("body").on("click",".image_edit",function(){
			image_edit();
		});
		menu_id = '';
		$("body").on("click",".remove_menu",function(){
			menu_id = $(this).parent().attr('id').split('content_menu_');
			menu_id = menu_id[1];
			$('#design_area')[0].contentWindow.remove_menu(menu_id);
		});
		$("body").on("click",".menu_edit_item",function(){
			menu_id = $(this).parent().attr('id').split('content_menu_');
			menu_id = menu_id[1];
			item_link = $('#design_area')[0].contentWindow.edit_menu(menu_id);
			$('.add_menu').html('Save');
		});
		$("body").on("click",".add_menu",function(){
			$('#design_area')[0].contentWindow.add_menu(menu_id);
		});
		$("body").on("click",".set_section_opacity",function(){
			$('#design_area')[0].contentWindow.set_section_opacity($(this).attr('rel'));
		});
		$("body").on("click",".remove_background",function(){
			$('#design_area')[0].contentWindow.remove_background();
		});
		$("body").on("click",".remove_slider",function(){
			slider_id = $(this).parent().attr('id').split('content_slider_');
			$('#design_area')[0].contentWindow.remove_slider(slider_id[1]);
		});
		$.getJSON('<?php echo get_site_url();?>/?do=get_pages',function(data){
			$.each(data,function(i){
				$('#link_page').append('<option value="'+data[i].url+'">'+data[i].title+'</option>');
			});
		});
		$("#link_type").change(function(){
			$('#link_section').parent().hide();
			$('#link_page').parent().hide();
			$('#link_url').parent().hide();
			$('#link_'+$(this).val()).parent().show();
		});
		$("#set_link").click(function(){
			$('#link_type').parent().removeClass('has-error');
			$('#link_section').parent().removeClass('has-error');
			$('#link_page').parent().removeClass('has-error');
			$('#link_url').parent().removeClass('has-error');
			if($('#link_type').val()==0){
				$('#link_type').parent().addClass('has-error');
			} else {
				link = '';
				if($('#link_type').val()=='section'){
					if($('#link_section').val()==0){
						$('#link_section').parent().addClass('has-error');
					} else {
						link = $('#link_section').val();
					}
				} else if($('#link_type').val()=='page'){
					if($('#link_page').val()==0){
						$('#link_page').parent().addClass('has-error');
					} else {
						link = $('#link_page').val();
					}
				} else if($('#link_type').val()=='url'){
					if($('#link_url').val()==''){
						$('#link_url').parent().addClass('has-error');
					} else {
						if($('#link_url').val().search('http://')<0 && $('#link_url').val().search('https://')<0){
							$('#link_url').val('http://'+$('#link_url').val());
						}
						link = $('#link_url').val();
					}
				}
				if(link!=''){
					if(menu_item_id==''){
						$('#design_area')[0].contentWindow.update_link(link);
					} else {
						$('#design_area')[0].contentWindow.update_menu_link(menu_item_id,link);
						menu_item_id = '';
					}
					$('#linkModal').modal('hide');
				}
			}
		});
		$("#remove_link").click(function(){
			if(menu_item_id==''){
				$('#design_area')[0].contentWindow.remove_link();
			} else {
				$('#design_area')[0].contentWindow.remove_menu_link(menu_item_id);
				menu_item_id = '';
			}
			$('#linkModal').modal('hide');
		});
		menu_item_id = '';
		$('body').on('click','.menu_link',function(){
			menu_item_id = $(this).parent().attr('id').split('content_menu_');
			menu_item_id = menu_item_id[1];
			current_link = $(this).attr('data-href');
			$('#design_area')[0].contentWindow.update_section_list();
			if(current_link!='#'){
				if($('option[value="'+current_link+'"]').length==1){
					$('#link_type').val($('option[value="'+current_link+'"]').parent().attr('id').split('_')[1]);
					$('#link_'+$('option[value="'+current_link+'"]').parent().attr('id').split('_')[1]).val(current_link);
					$('#link_'+$('option[value="'+current_link+'"]').parent().attr('id').split('_')[1]).parent().show();
				} else {
					$('#link_type').val('url');
					$('#link_url').val(current_link);
					$('#link_url').parent().show();
				}
			}
			$('#linkModal').modal('show');
		});
		$("body").on("click",".set_icon_size",function(){
			icon_size = $('#design_area')[0].contentWindow.set_icon_size($(this).attr('rel'));
			$('#icon_size').val(icon_size);
		});
		form_id = '';
		$("body").on("click",".add_form",function(){
			$('#design_area')[0].contentWindow.add_form(form_id);
		});
		$("body").on("input","#content_send_button",function(){
			$('#design_area')[0].contentWindow.content_send_button();
		});
		$("body").on("click",".remove_form",function(){
			form_id = $(this).parent().attr('id').split('form_input_');
			form_id = form_id[1];
			$('#design_area')[0].contentWindow.remove_form(form_id);
		});
		$("body").on("click",".form_edit_item",function(){
			form_id = $(this).parent().attr('id').split('form_input_');
			form_id = form_id[1];
			$('#design_area')[0].contentWindow.form_edit_item(form_id);
		});
		$("body").on("click",".add_id",function(){
			if($('#section_id').val()!=''){
				$('#section_id').parent().removeClass('has-error');
				$('#section_id').attr('placeholder','Section ID');
				regexp = /^[a-zA-Z]+$/;
				if($('#section_id').val().search(regexp)==-1){
					$('#section_id').parent().addClass('has-error');
					$('#section_id').val('');
					$('#section_id').attr('placeholder','Only these characters; a-z A-Z');
				} else {
					$('#design_area')[0].contentWindow.add_id($('#section_id').val());
					alert('Success');
				}
			}
		});
		$('#linkModal').on('hidden.bs.modal',function(){
			$('#link_type').parent().removeClass('has-error');
			$('#link_section').parent().removeClass('has-error');
			$('#link_page').parent().removeClass('has-error');
			$('#link_url').parent().removeClass('has-error');
			$('#link_type').val(0);
			$('#link_section').val(0);
			$('#link_page').val(0);
			$('#link_url').val('');
			$('#link_section').parent().hide();
			$('#link_page').parent().hide();
			$('#link_url').parent().hide();
		});
		$("body").on("click","#set_content",function(){
			i = 0;
			categories = [];
			$("#content_categories > .checkbox > label > input:checked").each(function(){
				categories[i] = $(this).val();
				i++;
			});
			i = 0;
			tags = [];
			$("#content_tags > .checkbox > label > input:checked").each(function(){
				tags[i] = $(this).val();
				i++;
			});
			$('#design_area')[0].contentWindow.set_content(categories,tags,1);
			$('#contentModal').modal('hide');
		});
		$("#menu").click(function(){
			$('#design_area')[0].contentWindow.get_menu_settings();
		});
		$("#save_email").click(function(){
			$.get("<?php echo get_site_url();?>/?do=update_email&email="+$('#form_email').val(),function(data){
				alert('Success');
			});
		});
	});
	function load_add_section_drag(){
		$(".add_section").draggable({
			connectToSortable: $('#design_area').contents().find(".main_sortable").sortable({
				opacity:0.5,
				tolerance:'pointer',
				placeholder:'place_holder',
				cancel:'*'
			}),
			helper: "clone",
			appendTo: "body",
			iframeFix: true,
			start:function(e,ui){
				$('#add_menu').css('display','none');
				$('.open_window').addClass('left_menu_icon');
				$('.open_window').css('background-color','');
				$('.open_window > a').css('color','#677888');
			},
			stop:function(e,ui){
				section_name = $(this).attr('data-section-name');
				$.get("<?php echo get_stylesheet_directory_uri();?>/sections/"+section_name+".html",function(data){
					data = data.replace(/PATH/g,"<?php echo get_stylesheet_directory_uri();?>");
					$('#design_area').contents().find('.add_section').replaceWith(data);
					$('#design_area')[0].contentWindow.load_element_panel();
					load_add_item_drag();
					if(section_name == 'blog'){
						$('#contentModal').modal('show');
					}
				});
			}
		});
		load_add_item_drag();
	}
	function load_add_item_drag(){
		load_sortable();
		$(".add_item").draggable({
			connectToSortable: $('#design_area').contents().find(".sortable"),
			helper:"clone",
			appendTo: "body",
			iframeFix: true,
			start:function(e,ui){
				$('#add_menu').css('display','none');
				$('.open_window').addClass('left_menu_icon');
				$('.open_window').css('background-color','');
				$('.open_window > a').css('color','#677888');
				$('#design_area').contents().find('.grid').css('padding-top','5px');
				$('#design_area').contents().find('.grid').css('padding-bottom','5px');
			},
			stop:function(e,ui){
				$('#design_area').contents().find('.grid').css('padding-top','0');
				$('#design_area').contents().find('.grid').css('padding-bottom','0');
				if($(this).attr('data-item')=='button'){
					$('#design_area').contents().find('.add_item').replaceWith('<div class="element button" style="text-align:center;"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil button_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link button_link" style="font-size:26px;margin:5px;"></span></div><a href="#" class="btn" style="padding:10px;padding-left:20px;padding-right:20px;background-color:#EEE;color:#000;text-decoration:none;">New button</a></div>');
				} else if($(this).attr('data-item')=='form'){
					$('#design_area').contents().find('.add_item').replaceWith('<div class="element form"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil form_edit" style="font-size:26px;margin:5px;"></span></div><div class="form_content"></div><div class="form-group" style="text-align:center;"><a class="btn send_button" style="color:#000;background-color:#EEE;">Send</a></div></div>');
				} else if($(this).attr('data-item')=='grid'){
					$('#design_area').contents().find('.add_item').replaceWith('<div class="element grid"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-columns column_settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-plus add_row" style="font-size:26px;margin:5px;"></span><span class="fa fa-minus remove_row" style="font-size:26px;margin:5px;"></span></div><div class="row"><div class="col-md-12 sortable" style="min-height:50px;"></div></div></div>');
				} else if($(this).attr('data-item')=='icon'){
					$('#design_area').contents().find('.add_item').replaceWith('<div class="element icon" style="text-align:center;"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil icon_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link icon_link" style="font-size:26px;margin:5px;"></span></div><a href="#"><i class="fa fa-font-awesome" aria-hidden="true" style="font-size:50px;"></i></a></div>');
					$('#iconModal').modal('show');
					$('#design_area')[0].contentWindow.select_active_icon();
				} else if($(this).attr('data-item')=='image'){
					$('#design_area').contents().find('.add_item').replaceWith('<div class="element image"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil image_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link image_link" style="font-size:26px;margin:5px;"></span></div><a href="#"><img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/blank.gif" class="img-responsive" /></a></div>');
					$('#imageModal').modal('show');
					$('#design_area')[0].contentWindow.select_active_element();
				} else if($(this).attr('data-item')=='slider'){
					id = 1;
					$("#design_area").contents().find('.slider').each(function(){
						slider_tag_id = $(this).children('.carousel').attr('id').split('_');
						if(slider_tag_id[1]>=id){
							id = slider_tag_id[1]+1;
						}
					});
					$('#design_area').contents().find('.add_item').replaceWith('<div class="element slider"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span></div><div id="slider_'+id+'" class="carousel slide" data-ride="carousel" data-interval="false" style="min-height:100px;"><ol class="carousel-indicators"></ol><div class="carousel-inner" role="listbox"></div><a class="left carousel-control" href="#slider_'+id+'" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#slider_'+id+'" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span></a></div></div>');
				} else if($(this).attr('data-item')=='text'){
					$('#design_area').contents().find('.add_item').replaceWith('<div class="element text"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil text_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-check text_edit_done" style="font-size:26px;margin:5px;display:none;"></span></div><div class="editable" style="text-align:center;font-size:20px;" spellcheck="false">Text.<br />Double click me.</div></div>');
				}
				load_sortable();
			}
		});
	}
	function load_sortable(){
		$('#design_area').contents().find(".sortable").sortable({
			connectWith:'.sortable',
			opacity:0.5,
			tolerance:'pointer',
			placeholder:'place_holder',
			cancel:'.text > .editable[contenteditable=true],input',
			start:function(event,ui){
				$('#design_area').contents().find('.grid').css('padding-top','5px');
				$('#design_area').contents().find('.grid').css('padding-bottom','5px');
			},
			stop:function(event,ui){
				$('#design_area').contents().find('.grid').css('padding-top','0');
				$('#design_area').contents().find('.grid').css('padding-bottom','0');
			}
		});
	}
	function image_edit(){
		$('#imageModal').modal('show');
		if($('#site_images').html()==''){
			$.getJSON('<?php echo get_site_url();?>/?do=get_images',function(data){
				$.each(data,function(i){
					$('#site_images').append('<div class="select" data-id="'+data[i].id+'" style="width:100px;height:100px;overflow:hidden;float:left;margin:10px;"><img src="'+data[i].thumb+'" height="100" /></div>');
				});
			});
		}
	}
	function add_new_image(id){
		$.get("<?php echo get_site_url();?>/?do=get_image&size=thumbnail&id="+id,function(data){
			$('#site_images').prepend('<div class="select" data-id="'+id+'" style="width:100px;height:100px;overflow:hidden;float:left;margin:10px;"><img src="'+data+'" height="100" /></div>');
		});
	}
	function icon_edit(){
		$('#iconModal').modal('show');
	}
	function link_edit(current_link){
		$('#design_area')[0].contentWindow.update_section_list();
		if(current_link!='#'){
			if($('option[value="'+current_link+'"]').length==1){
				$('#link_type').val($('option[value="'+current_link+'"]').parent().attr('id').split('_')[1]);
				$('#link_'+$('option[value="'+current_link+'"]').parent().attr('id').split('_')[1]).val(current_link);
				$('#link_'+$('option[value="'+current_link+'"]').parent().attr('id').split('_')[1]).parent().show();
			} else {
				$('#link_type').val('url');
				$('#link_url').val(current_link);
				$('#link_url').parent().show();
			}
		}
		$('#linkModal').modal('show');
	}
	</script>
	<style type="text/css">
		.picker { margin:0;padding:0;border:0;width:70px;height:20px;border-right:20px solid green;line-height:20px;}
        .left_menu_icon:hover > a > span { color:#00ccff;}
	</style>
</head>
<body>
	<div id="all_content" style="width:100%;height:100%;">
		<div style="width:80px;height:100%;float:left;background-color:#243343;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;">
			<div class="open_window left_menu_icon" style="margin-top:20px;margin-bottom:20px;text-align:center;padding-top:5px;padding-bottom:5px;"><a href="#" rel="add" style="color:#677888;font-weight:bold;text-decoration:none;"><span class="fa fa-plus" style="font-size:26px;margin-bottom:10px;"></span><br /><span>Add</span></a></div>
			<div id="menu" class="left_menu_icon" style="margin-bottom:20px;text-align:center;padding-top:5px;padding-bottom:5px;"><a href="#" style="color:#677888;font-weight:bold;text-decoration:none;"><span class="fa fa-bars" style="font-size:26px;margin-bottom:10px;"></span><br /><span>Menu</span></a></div>
			<div id="save" class="left_menu_icon" style="margin-bottom:20px;text-align:center;padding-top:5px;padding-bottom:5px;"><a href="#" style="color:#677888;font-weight:bold;text-decoration:none;"><span class="fa fa-floppy-o" style="font-size:26px;margin-bottom:10px;"></span><br /><span>Save</span></a></div>
			<div class="left_menu_icon" style="margin-bottom:20px;text-align:center;padding-top:5px;padding-bottom:5px;"><a href="<?php echo get_site_url();?>/wp-admin" style="color:#677888;font-weight:bold;text-decoration:none;"><span class="fa fa-wordpress" style="font-size:26px;margin-bottom:10px;"></span><br /><span>Admin</span></a></div>
		</div>
		<div id="iframe" style="position:fixed;left:80px;overflow:auto;float:left;">
			<iframe id="design_area" style="width:100%;height:100%;border:0;" src="<?php echo get_site_url();?>/?p=<?php echo $id;?>&editor" onload="load_add_section_drag()"></iframe>
		</div>
		<div id="settings_menu" style="width:300px;height:100%;position:fixed;float:left;background-color:#243343;padding:10px;display:none;">
			<div id="margin_settings" style="display:none;">
				<table width="100%" style="color:#677888;font-weight:bold;font-size:14px;margin-bottom:20px;">
					<tr>
						<td>Top space:</td><td align="center"><a href="#" class="set_margin" rel="top_minus"><span class="fa fa-minus"></span></a> <input type="text" id="top_margin" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_margin" rel="top_plus"><span class="fa fa-plus"></span></a></td>
					</tr>
					<tr>
						<td>Bottom space:</td><td align="center"><a href="#" class="set_margin" rel="bottom_minus"><span class="fa fa-minus"></span></a> <input type="text" id="bottom_margin" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_margin" rel="bottom_plus"><span class="fa fa-plus"></span></a></td>
					</tr>
					<tr>
						<td>Left space:</td><td align="center"><a href="#" class="set_margin" rel="left_minus"><span class="fa fa-minus"></span></a> <input type="text" id="left_margin" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_margin" rel="left_plus"><span class="fa fa-plus"></span></a></td>
					</tr>
					<tr>
						<td>Right space:</td><td align="center"><a href="#" class="set_margin" rel="right_minus"><span class="fa fa-minus"></span></a> <input type="text" id="right_margin" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_margin" rel="right_plus"><span class="fa fa-plus"></span></a></td>
					</tr>
				</table>
			</div>
			<div id="grid_settings" style="display:none;">
				<table width="100%" style="color:#677888;font-weight:bold;font-size:14px;">
					<tr>
						<td>Background color:</td><td align="center"># <input type="text" class="picker" data-type="grid_background"></input></td>
					</tr>
					<tr>
						<td>Opacity:</td><td align="center"><a href="#" class="set_grid_opacity" rel="minus"><span class="fa fa-minus"></span></a> <input type="text" id="grid_opacity" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_grid_opacity" rel="plus"><span class="fa fa-plus"></span></a></td>
					</tr>
				</table>
				<br /><a href="#" class="remove_grid_background" style="color:#cc0000;">Delete background</a>
			</div>
			<div id="column_settings" style="color:#677888;font-weight:bold;font-size:14px;display:none;">
				Column options:<br />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_01.png" class="set_column" rel="1" style="margin:4px;border:1px solid #EEE;" />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_02.png" class="set_column" rel="2" style="margin:4px;border:1px solid #666;" />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_03.png" class="set_column" rel="3" style="margin:4px;border:1px solid #666;" />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_04.png" class="set_column" rel="4" style="margin:4px;border:1px solid #666;" />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_05.png" class="set_column" rel="5" style="margin:4px;border:1px solid #666;" /><br />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_06.png" class="set_column" rel="6" style="margin:4px;border:1px solid #666;" />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_07.png" class="set_column" rel="7" style="margin:4px;border:1px solid #666;" />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_08.png" class="set_column" rel="8" style="margin:4px;border:1px solid #666;" />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_09.png" class="set_column" rel="9" style="margin:4px;border:1px solid #666;" />
				<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_10.png" class="set_column" rel="10" style="margin:4px;border:1px solid #666;" />
				<div id="customize_layout"></div>
			</div>
			<div id="icon_settings" style="display:none;">
				<table width="100%" style="color:#677888;font-weight:bold;font-size:14px;">
					<tr>
						<td>Align:</td><td align="center"><a href="#" class="set_align" rel="left"><span class="fa fa-align-left"></span></a> <a href="#" class="set_align" rel="center"><span class="fa fa-align-center"></span></a> <a href="#" class="set_align" rel="right"><span class="fa fa-align-right"></span></a></td>
					</tr>
					<tr>
						<td>Color:</td><td align="center"># <input type="text" class="picker" data-type="icon"></input></td>
					</tr>
					<tr>
						<td>Size:</td><td align="center"><a href="#" class="set_icon_size" rel="minus"><span class="fa fa-minus"></span></a> <input type="text" id="icon_size" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_icon_size" rel="plus"><span class="fa fa-plus"></span></a></td>
					</tr>
				</table>
			</div>
			<div id="image_settings" style="display:none;">
				<table width="100%" style="color:#677888;font-weight:bold;font-size:14px;">
					<tr>
						<td>Align:</td><td align="center"><a href="#" class="set_align" rel="left"><span class="fa fa-align-left"></span></a> <a href="#" class="set_align" rel="center"><span class="fa fa-align-center"></span></a> <a href="#" class="set_align" rel="right"><span class="fa fa-align-right"></span></a></td>
					</tr>
					<tr>
						<td>Shape:</td><td align="center"><a href="#" class="set_shape" rel="circle"><span class="fa fa-circle" style="font-size:26px;"></span></a> <a href="#" class="set_shape" rel="square"><span class="fa fa-square" style="font-size:26px;"></span></a></td>
					</tr>
					<tr>
						<td>Border:</td><td align="center"><a href="#" class="set_image_border" rel="minus"><span class="fa fa-minus"></span></a> <input type="text" id="image_border" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_image_border" rel="plus"><span class="fa fa-plus"></span></a></td>
					</tr>
					<tr>
						<td>Border color:</td><td align="center"># <input type="text" class="picker" data-type="image_border"></input></td>
					</tr>
				</table>
			</div>
			<div id="form_settings" style="display:none;">
				<div class="input-group">
					<input type="text" id="form_email" class="form-control" placeholder="Email of receiver" value="<?php echo get_option('form_email');?>">
					<span class="input-group-btn">
			    		<button class="btn btn-default" id="save_email" type="button">Save</button>
			    	</span>
			    </div>
			</div>
			<div id="form_edit_settings" style="display:none;">
				<div class="input-group">
					<input type="text" id="input_name" class="form-control" placeholder="New input">
					<span class="input-group-btn">
						<button class="btn btn-default add_form" type="button">Add</button>
					</span>
			    </div>
			    <div id="form_inputs" style="margin-top:10px;color:#677888;font-weight:bold;font-size:14px;"></div>
			    <input type="text" id="content_send_button" class="form-control" value="Send" style="display:none;">
			</div>
			<div id="slider_settings" style="display:none;">
				<a href="#" class="image_edit" style="color:#cc0000;">Select image</a><br /><br />
				<div class="input-group" style="margin-bottom:10px;display:none;">
					<input type="text" id="slider_caption" class="form-control" placeholder="Slider caption">
			    	<span class="input-group-btn">
			    		<button class="btn btn-default add_slider" type="button">Add</button>
			    	</span>
			    </div>
			    <div style="max-height:300px;overflow:scroll;">
					<div id="slider_content" style="margin-top:10px;"></div>
				</div>
		    </div>
		    <div id="button_settings" style="display:none;">
		    	<table width="100%" style="color:#677888;font-weight:bold;font-size:14px;">
		    		<tr>
						<td>Align:</td><td align="center"><a href="#" class="set_align" rel="left"><span class="fa fa-align-left"></span></a> <a href="#" class="set_align" rel="center"><span class="fa fa-align-center"></span></a> <a href="#" class="set_align" rel="right"><span class="fa fa-align-right"></span></a></td>
					</tr>
		    		<tr>
						<td>Background color:</td><td align="center"># <input type="text" class="picker" data-type="button_background"></input></td>
					</tr>
					<tr>
						<td>Opacity:</td><td align="center"><a href="#" class="set_button_opacity" rel="minus"><span class="fa fa-minus"></span></a> <input type="text" id="button_opacity" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_button_opacity" rel="plus"><span class="fa fa-plus"></span></a></td>
					</tr>
				</table>
				<select class="form-control" id="button_change_font" style="width:50%;float:left;">
					<option value="0">Select font</option>
					<option value="Georgia, serif">Georgia</option>
					<option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif">Palatino</option>
					<option value="'Times New Roman', Times, serif">Times New Roman</option>
					<option value="Arial, Helvetica, sans-serif">Arial</option>
					<option value="'Arial Black', Gadget, sans-serif">Arial Black</option>
					<option value="'Comic Sans MS', cursive, sans-serif">Comic Sans MS</option>
					<option value="Impact, Charcoal, sans-serif">Impact</option>
					<option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif">Lucida Sans Unicode</option>
					<option value="Tahoma, Geneva, sans-serif">Tahoma</option>
					<option value="'Trebuchet MS', Helvetica, sans-serif">Trebuchet MS</option>
					<option value="Verdana, Geneva, sans-serif">Verdana</option>
					<option value="'Courier New', Courier, monospace">Courier New</option>
					<option value="'Lucida Console', Monaco, monospace">Lucida Console</option>
				</select>
				<select class="form-control" id="button_change_size" style="width:50%;">
					<option value="0">Size</option>
					<option value="8">8</option>
					<option value="9">9</option>
					<option value="10">10</option>
					<option value="11">11</option>
					<option value="12">12</option>
					<option value="14">14</option>
					<option value="16">16</option>
					<option value="18">18</option>
					<option value="20">20</option>
					<option value="22">22</option>
					<option value="24">24</option>
					<option value="26">26</option>
					<option value="28">28</option>
					<option value="36">36</option>
					<option value="48">48</option>
					<option value="72">72</option>
				</select>
				<table width="100%" style="color:#677888;font-weight:bold;font-size:14px;">
					<tr>
						<td>Font color:</td><td align="center"># <input type="text" class="picker" data-type="button_font"></input></td>
					</tr>
					<tr>
						<td>Border:</td><td align="center"><a href="#" class="set_button_border" rel="minus"><span class="fa fa-minus"></span></a> <input type="text" id="button_border" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_button_border" rel="plus"><span class="fa fa-plus"></span></a></td>
					</tr>
					<tr>
						<td>Border color:</td><td align="center"># <input type="text" class="picker" data-type="button_border"></input></td>
					</tr>
				</table>
			</div>
			<div id="menu_settings" style="height:100%;overflow:scroll;display:none;">
				<a href="#" class="image_edit" style="color:#cc0000;">Select logo</a>
				<table width="100%" style="margin-top:15px;margin-bottom:15px;color:#677888;font-weight:bold;font-size:14px;">
					<tr>
						<td>Background color:</td>
						<td align="right"># <input type="text" class="picker" data-type="menu_background"></input></td>
					</tr>
					<tr>
						<td>Opacity:</td><td align="center"><a href="#" class="set_menu_opacity" rel="minus"><span class="fa fa-minus"></span></a> <input type="text" id="menu_opacity" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_menu_opacity" rel="plus"><span class="fa fa-plus"></span></a></td>
					</tr>
				</table>
				<select class="form-control" id="menu_change_font">
					<option value="0">Select font</option>
					<option value="Georgia, serif">Georgia</option>
					<option value="'Palatino Linotype', 'Book Antiqua', Palatino, serif">Palatino</option>
					<option value="'Times New Roman', Times, serif">Times New Roman</option>
					<option value="Arial, Helvetica, sans-serif">Arial</option>
					<option value="'Arial Black', Gadget, sans-serif">Arial Black</option>
					<option value="'Comic Sans MS', cursive, sans-serif">Comic Sans MS</option>
					<option value="Impact, Charcoal, sans-serif">Impact</option>
					<option value="'Lucida Sans Unicode', 'Lucida Grande', sans-serif">Lucida Sans Unicode</option>
					<option value="Tahoma, Geneva, sans-serif">Tahoma</option>
					<option value="'Trebuchet MS', Helvetica, sans-serif">Trebuchet MS</option>
					<option value="Verdana, Geneva, sans-serif">Verdana</option>
					<option value="'Courier New', Courier, monospace">Courier New</option>
					<option value="'Lucida Console', Monaco, monospace">Lucida Console</option>
				</select>
				<table width="100%" style="margin-top:15px;margin-bottom:15px;color:#677888;font-weight:bold;font-size:14px;">
					<tr>
						<td>Font color:</td>
						<td align="right"># <input type="text" class="picker" data-type="menu_font"></input></td>
					</tr>
				</table>
				<div class="input-group" style="margin-bottom:10px;">
					<input type="text" id="button_name" class="form-control" placeholder="New button">
					<span class="input-group-btn">
			    		<button class="btn btn-default add_menu" type="button">Add</button>
			    	</span>
			    </div>
				<div id="sortable" style="margin-top:10px;color:#677888;font-weight:bold;font-size:14px;"></div>
			</div>
			<div id="section_settings" style="display:none;">
				<a href="#" class="image_edit" style="color:#cc0000;">Background image</a>
				<div class="input-group" style="margin-top:10px;">
					<input type="text" id="section_id" class="form-control" placeholder="Section ID">
					<span class="input-group-btn">
			    		<button class="btn btn-default add_id" type="button">Save</button>
			    	</span>
			    </div>
				<table width="100%" style="margin-top:15px;margin-bottom:15px;color:#677888;font-weight:bold;font-size:14px;">
					<tr>
						<td>Background color:</td>
						<td align="right"># <input type="text" class="picker" data-type="section_background"></input></td>
					</tr>
					<tr>
						<td>Opacity:</td><td align="center"><a href="#" class="set_section_opacity" rel="minus"><span class="fa fa-minus"></span></a> <input type="text" id="section_opacity" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_section_opacity" rel="plus"><span class="fa fa-plus"></span></a></td>
					</tr>
				</table>
				<a href="#" class="remove_background" style="color:#cc0000;">Delete background</a>
			</div>
		</div>
		<div id="add_menu" style="width:300px;height:100%;overflow:scroll;padding:10px;position:fixed;left:80px;float:left;background-color:#FFF;border-left:5px solid #ffcc00;box-shadow:5px 0px 5px 0px rgba(0,0,0,0.2);display:none;">
			<select id="add_list" class="form-control" style="margin-bottom:10px;">
				<option value="ready_made">Ready-made</option>
				<option value="basic_elements">Basic elements</option>
			</select>
			<div id="ready_made"></div>
			<div id="basic_elements" style="display:none;">
				<div style="width:50%;float:left;margin-bottom:15px;"><div class="add_item" data-item="grid" style="width:100px;padding:10px;background-color:#EEE;border-radius:5px;text-align:center;border:1px solid #CCC;cursor:pointer;margin:auto;"><span class="fa fa-th" style="font-size:26px;margin-bottom:10px;"></span><br />Grid</div></div>
				<div style="width:50%;float:left;margin-bottom:15px;"><div class="add_item" data-item="text" style="width:100px;padding:10px;background-color:#EEE;border-radius:5px;text-align:center;border:1px solid #CCC;cursor:pointer;margin:auto;"><span class="fa fa-font" style="font-size:26px;margin-bottom:10px;"></span><br />Text</div></div>
				<div style="width:50%;float:left;margin-bottom:15px;"><div class="add_item" data-item="image" style="width:100px;padding:10px;background-color:#EEE;border-radius:5px;text-align:center;border:1px solid #CCC;cursor:pointer;margin:auto;"><span class="fa fa-picture-o" style="font-size:26px;margin-bottom:10px;"></span><br />Image</div></div>
				<div style="width:50%;float:left;margin-bottom:15px;"><div class="add_item" data-item="icon" style="width:100px;padding:10px;background-color:#EEE;border-radius:5px;text-align:center;border:1px solid #CCC;cursor:pointer;margin:auto;"><span class="fa fa-font-awesome" style="font-size:26px;margin-bottom:10px;"></span><br />Icon</div></div>
				<div style="width:50%;float:left;margin-bottom:15px;"><div class="add_item" data-item="button" style="width:100px;padding:10px;background-color:#EEE;border-radius:5px;text-align:center;border:1px solid #CCC;cursor:pointer;margin:auto;"><span class="fa fa-square-o" style="font-size:26px;margin-bottom:10px;"></span><br />Button</div></div>
				<div style="width:50%;float:left;margin-bottom:15px;"><div class="add_item" data-item="slider" style="width:100px;padding:10px;background-color:#EEE;border-radius:5px;text-align:center;border:1px solid #CCC;cursor:pointer;margin:auto;"><span class="glyphicon glyphicon-option-horizontal" style="font-size:26px;margin-bottom:10px;"></span><br />Slider</div></div>
				<div style="width:50%;float:left;margin-bottom:15px;"><div class="add_item" data-item="form" style="width:100px;padding:10px;background-color:#EEE;border-radius:5px;text-align:center;border:1px solid #CCC;cursor:pointer;margin:auto;"><span class="fa fa-check-square-o" style="font-size:26px;margin-bottom:10px;"></span><br />Form</div></div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:400px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Define link</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<select id="link_type" class="form-control">
							<option value="0">Select link type</option>
							<option value="section">Link to section</option>
							<option value="page">Link to page</option>
							<option value="url">Link to URL</option>
						</select>
					</div>
					<div class="form-group" style="display:none;">
						<select id="link_section" class="form-control">
							<option value="0">Select section</option>
						</select>
					</div>
					<div class="form-group" style="display:none;">
						<select id="link_page" class="form-control">
							<option value="0">Select page</option>
						</select>
					</div>
					<div class="form-group" style="display:none;">
						<input type="text" id="link_url" class="form-control" placeholder="Link URL">
					</div>
					<button type="button" class="btn btn-default" id="set_link" style="width:100%;margin-bottom:10px;">Save</button>
					<a href="#" id="remove_link" style="color:#cc0000;">Remove link</a>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:1000px;">
			<div class="modal-content">
				<div class="modal-header">
					<iframe src="<?php echo get_site_url();?>/?do=upload_screen" style="width:100%;height:40px;border:0;"></iframe>
				</div>
				<div id="site_images" class="modal-body" style="height:500px;overflow:scroll;"></div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="select_image" disabled="disabled">Select</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="iconModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:1000px;">
			<div class="modal-content">
				<div class="modal-body" style="height:500px;overflow:scroll;">
					<div class="form-group form-group-lg">
						<input type="text" id="icon_search" class="form-control" placeholder="Search">
					</div>
					<?php include('assets/icons.php');?>
					<div id="icon_search_results" class="row fontawesome-icon-list"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="contentModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog" style="width:400px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title" id="myModalLabel">Content source</h4>
				</div>
				<div class="modal-body">
					<b>Get content from selected categories:</b><br />
					<div id="content_categories">
					<?php
					$categories = get_categories();
					foreach($categories as $category){
					?>
					<div class="checkbox"><label><input type="checkbox" value="<?php echo $category->term_id;?>"> <?php echo $category->name;?></label></div>
					<?php
					}
					?>
					</div>
					<b>Get content from selected tags:</b><br />
					<div id="content_tags">
					<?php
					$tags = get_tags();
					foreach($tags as $tag){
					?>
					<div class="checkbox"><label><input type="checkbox" value="<?php echo $tag->term_id;?>"> <?php echo $tag->name;?></label></div>
					<?php
					}
					?>
					</div>
					<button type="button" class="btn btn-default" id="set_content" style="width:100%;margin-bottom:10px;">Save</button>
				</div>
			</div>
		</div>
	</div>
	<div class="editor" style="width:100%;text-align:center;background-color:rgba(0,204,255,0.8);padding:5px;position:absolute;z-index:1030;display:none;">
		<div class="btn-group">
			<button type="button" class="btn btn-default set_bold">
				<span class="fa fa-bold" style="font-size:20px;"></span>
			</button>
			<button type="button" class="btn btn-default set_italic">
				<span class="fa fa-italic" style="font-size:20px;"></span>
			</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-default set_align_left">
				<span class="fa fa-align-left" style="font-size:20px;"></span>
			</button>
			<button type="button" class="btn btn-default set_align_center">
				<span class="fa fa-align-center" style="font-size:20px;"></span>
			</button>
			<button type="button" class="btn btn-default set_align_right">
				<span class="fa fa-align-right" style="font-size:20px;"></span>
			</button>
			<button type="button" class="btn btn-default set_align_justify">
				<span class="fa fa-align-justify" style="font-size:20px;"></span>
			</button>
		</div>
		<button type="button" class="btn btn-default set_link_settings">
			<span class="fa fa-link" style="font-size:20px;"></span>
		</button>
		<div class="btn-group">
			<button type="button" class="btn btn-default set_font_settings">Select font</button>
			<button type="button" class="btn btn-default set_size_settings">Size</button>
		</div>
		<div class="btn-group">
			<button type="button" class="btn btn-default set_color">Font color</button>
			<button type="button" class="btn btn-default set_bg_color">Background color</button>
		</div>
		<button type="button" class="btn btn-default reset_format">Reset format</button>
	</div>
</body>
</html>