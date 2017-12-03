<?php
if(isset($_GET['p'])){
	$id = $_GET['p'];
} else {
	$id = get_the_ID();
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Kodyok</title>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/bootstrap/css/bootstrap-theme.min.css">
	<script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery.min.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri();?>/assets/js/Sortable.min.js"></script>
	<script src="<?php echo get_stylesheet_directory_uri();?>/assets/bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/jquery-ui.min.js"></script>
	<link type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/assets/css/jquery-ui.min.css" rel="stylesheet" />
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri();?>/assets/js/colpick.js"></script>
    <link href="<?php echo get_stylesheet_directory_uri();?>/assets/css/colpick.css" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri();?>/assets/font-awesome/css/font-awesome.min.css">
	<script src="<?php echo get_stylesheet_directory_uri();?>/assets/swal/sweetalert.min.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri();?>/assets/swal/sweetalert.css">
	<script type="text/javascript">
	$(document).ready(function(){
		$('#iframe').css('width',($('#all_content').width()-80)+'px');
		$('#settings_menu').css('left',($('#all_content').width()-300)+'px');
		$(window).on('resize',function(){
			if($('#settings_menu').css('display')=='none'){
				$('#iframe').css('width',($('#all_content').width()-80)+'px');
			} else {
				$('#iframe').css('width',($('#all_content').width()-380)+'px');
			}
			$('#settings_menu').css('left',($('#all_content').width()-300)+'px');
		});
		$("#save").click(function(){
			if($("#design_area").contents().find('#post_content_area').length==1){
				post_page = 1;
			} else {
				post_page = 0;
			}
			$('#design_area')[0].contentWindow.run_before_save(post_page);
			menu_style = {};
			menu_style["font"] = $("#design_area").contents().find('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('font-family');
			menu_style["color"] = $("#design_area").contents().find('nav').children('.container').children('.collapse').children('.nav').children('li').children('a').css('color');
			menu_style["bgcolor"] = $("#design_area").contents().find('nav').css('background-color');
			menu_style["logo"] = $("#design_area").contents().find('nav').children('.container').children('.navbar-header').children('.navbar-brand').children('img').attr('src');
			if($("#design_area").contents().find('#post_content_area').length==1){
				content_html = $("#design_area").contents().find('#post_content_area').html();
			} else {
				content_html = $("#design_area").contents().find('#content_area').html();
			}
			$.ajax({
				type: "POST",
				url: "<?php echo get_site_url();?>/?do=save&id=<?php echo $id;?>",
				data: {content:content_html,menu:JSON.stringify(menu_style),footer:$("#design_area").contents().find('footer').children('.container').html()},
				success: function(html){
					swal(
						'Success',
						'',
						'success'
					);
					$('#design_area')[0].contentWindow.run_after_save(post_page);
				}
			});
			return false;
		});
		$('.open_window').click(function(){
			if($('#'+$(this).children('a').attr('id')+'_menu').css('display')=='none'){
				$('.open_window').addClass('left_menu_icon');
				$(this).removeClass('left_menu_icon');
				$('.open_window').css('background-color','');
				$(this).css('background-color','#ffcc00');
				$('.open_window > a').css('color','#677888');
				$(this).children('a').css('color','#000');
				$('#add_menu').css('display','none');
				$('#'+$(this).children('a').attr('id')+'_menu').css('display','');
			} else {
				$('.open_window').addClass('left_menu_icon');
				$('.open_window').css('background-color','');
				$('.open_window > a').css('color','#677888');
				$('#'+$(this).children('a').attr('id')+'_menu').css('display','none');
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
				$('#ready_made').append('<div class="add_section" data-section-name="'+data.sections[i]['file']+'" style="text-align:center;cursor:pointer;font-size:10px;margin-bottom:10px;box-shadow:inset 0 0 0 1px #EEE;"><img src="<?php echo get_stylesheet_directory_uri();?>/sections/'+data.sections[i]['file']+'.gif" alt="'+data.sections[i]['name']+'" style="width:100%;" /><br />'+data.sections[i]['name']+'</div>');
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
			$('#design_area')[0].contentWindow.update_image(selected_image,selected_image_id,image_type);
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
				} else if($(el).attr('data-type')=='icon_background'){
					$('#design_area')[0].contentWindow.change_icon_background_color('#'+hex);
				} else if($(el).attr('data-type')=='image_border'){
					$('#design_area')[0].contentWindow.change_image_border('#'+hex);
				} else if($(el).attr('data-type')=='menu_background'){
					$('#design_area')[0].contentWindow.change_menu_background('rgba('+rgb['r']+','+rgb['g']+','+rgb['b']+','+$('#menu_opacity').val()/100+')');
				} else if($(el).attr('data-type')=='menu_font'){
					$('#design_area')[0].contentWindow.change_menu_font('#'+hex);
				} else if($(el).attr('data-type')=='section_background'){
					$('#design_area')[0].contentWindow.change_section_background('rgba('+rgb['r']+','+rgb['g']+','+rgb['b']+','+$('#section_opacity').val()/100+')');
				} else if($(el).attr('data-type')=='text_font'){
					$('#design_area')[0].contentWindow.change_text_font('#'+hex);
				}
				$(el).css('border-color','#'+hex);
				// Fill the text box just if the color was set using the picker, and not the colpickSetColor function.
				if(!bySetColor) $(el).val(hex);
			}
		}).keyup(function(){
			$(this).colpickSetColor(this.value);
		});
		$("body").on("click",".set_button_opacity",function(){
			opacity_value = $('#design_area')[0].contentWindow.set_button_opacity($(this).attr('data-type'));
			$('#button_opacity').val(opacity_value);
		});
		$("body").on("click",".set_button_border",function(){
			border_value = $('#design_area')[0].contentWindow.set_button_border($(this).attr('data-type'));
			$('#button_border').val(border_value);
		});
		$("#button_change_font").change(function(){
			if($("#button_change_font").val()!=0){
				$('#design_area')[0].contentWindow.set_button_font($("#button_change_font").val(),$("#button_change_font > option:selected").text());
			}
		});
		$("#button_change_size").change(function(){
			if($("#button_change_size").val()!=0){
				$('#design_area')[0].contentWindow.set_button_size($("#button_change_size").val());
			}
		});
		$("#text_change_font").change(function(){
			if($("#text_change_font").val()!=0){
				$('#design_area')[0].contentWindow.set_text_font($("#text_change_font").val(),$("#text_change_font > option:selected").text());
			}
		});
		$("#text_change_size").change(function(){
			if($("#text_change_size").val()!=0){
				$('#design_area')[0].contentWindow.set_text_size($("#text_change_size").val());
			}
		});
		$("body").on("click",".set_grid_opacity",function(){
			opacity_value = $('#design_area')[0].contentWindow.set_grid_opacity($(this).attr('data-type'));
			$('#grid_opacity').val(opacity_value);
		});
		$("body").on("click",".set_column",function(){
			$('.set_column').css('border','1px solid #EEE');
			$(this).css('border','1px solid #666');
			set_column = $('#design_area')[0].contentWindow.set_column($(this).attr('data-type'));
			$('#customize_layout').html(set_column);
		});
		$("body").on("click",".remove_grid_background",function(){
			$('#design_area')[0].contentWindow.remove_grid_background();
		});
		$('#imageModal').on('hidden.bs.modal',function(){
			$('#design_area')[0].contentWindow.remove_last_image();
		});
		$("body").on("click",".set_image_border",function(){
			border_value = $('#design_area')[0].contentWindow.set_image_border($(this).attr('data-type'));
			$('#image_border').val(border_value);
		});
		$("body").on("click",".set_shape",function(){
			$('#design_area')[0].contentWindow.set_shape($(this).attr('data-type'));
		});
		$("body").on("click",".set_align",function(){
			$('#design_area')[0].contentWindow.set_align($(this).attr('data-type'));
		});
		$("body").on("click",".set_margin",function(){
			margin_side = $(this).attr('data-type').split('_');
			margin_value = $('#design_area')[0].contentWindow.set_margin($(this).attr('data-type'));
			$('#'+margin_side[0]+'_margin').val(margin_value);
		});
		$("#menu_change_font").change(function(){
			if($("#menu_change_font").val()!=0){
				$('#design_area')[0].contentWindow.set_menu_font($("#menu_change_font").val(),$("#menu_change_font > option:selected").text());
			}
		});
		$("body").on("click",".set_menu_opacity",function(){
			opacity_value = $('#design_area')[0].contentWindow.set_menu_opacity($(this).attr('data-type'));
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
			$('#design_area')[0].contentWindow.set_section_opacity($(this).attr('data-type'));
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
						blank = 0;
					}
				} else if($('#link_type').val()=='page'){
					if($('#link_page').val()==0){
						$('#link_page').parent().addClass('has-error');
					} else {
						link = $('#link_page').val();
						blank = 0;
					}
				} else if($('#link_type').val()=='url'){
					if($('#link_url').val()==''){
						$('#link_url').parent().addClass('has-error');
					} else {
						if($('#link_url').val().search('http://')<0 && $('#link_url').val().search('https://')<0){
							$('#link_url').val('http://'+$('#link_url').val());
						}
						link = $('#link_url').val();
						blank = 1;
					}
				}
				if(link!=''){
					if(menu_item_id!=''){
						$('#design_area')[0].contentWindow.update_menu_link(menu_item_id,link,blank);
						menu_item_id = '';
					} else if(icon_item_id!=''){
						$('#design_area')[0].contentWindow.update_icon_link(icon_item_id,link,blank);
						icon_item_id = '';
					} else {
						$('#design_area')[0].contentWindow.update_link(link,blank);
					}
					$('#linkModal').modal('hide');
				}
			}
		});
		$("#remove_link").click(function(){
			if(menu_item_id!=''){
				$('#design_area')[0].contentWindow.remove_menu_link(menu_item_id);
				menu_item_id = '';
			} else if(icon_item_id!=''){
				$('#design_area')[0].contentWindow.remove_icon_link(icon_item_id);
				icon_item_id = '';
			} else {
				$('#design_area')[0].contentWindow.remove_link();
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
			icon_size = $('#design_area')[0].contentWindow.set_icon_size($(this).attr('data-type'));
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
					swal(
						'Success',
						'',
						'success'
					);
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
			menu_item_id = '';
			icon_item_id = '';
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
			$('#design_area')[0].contentWindow.set_content(categories,tags,1,1);
			$('#contentModal').modal('hide');
		});
		$("#menu").click(function(){
			$('#design_area')[0].contentWindow.get_menu_settings();
		});
		$("#save_email").click(function(){
			$.get("<?php echo get_site_url();?>/?do=update_email&email="+$('#form_email').val(),function(data){
				swal(
					'Success',
					'',
					'success'
				);
			});
		});
		$(".close_panel").click(function(){
			$('#settings_menu > div').hide();
			$('#iframe').css('width',($('#all_content').width()-80)+'px');
			$('#settings_menu').hide();
		});
		$("body").on("click",".set_slider_height",function(){
			slider_height = $('#design_area')[0].contentWindow.set_slider_height($(this).attr('data-type'));
			$('#slider_height').val(slider_height);
		});
		$("#save_seo").click(function(){
			seo_settings = {};
			seo_settings["title"] = $("#seo_title").val();
			seo_settings["keywords"] = $("#seo_keywords").val();
			seo_settings["description"] = $("#seo_description").val();
			$.ajax({
				type: "POST",
				url: "<?php echo get_site_url();?>/?do=save_seo&id=<?php echo $id;?>",
				data: {seo_settings:JSON.stringify(seo_settings)},
				success: function(html){
					swal(
						'Success',
						'',
						'success'
					);
				}
			});
			return false;
		});
		$('#content_type').change(function(){
			if($(this).val()=='categories'){
				$('#content_tags').find('input').attr('checked',false);
				$('#content_tags').hide();
				$('#content_categories').show();
			} else if($(this).val()=='tags'){
				$('#content_categories').find('input').attr('checked',false);
				$('#content_categories').hide();
				$('#content_tags').show();
			}
		});
		$('#contentModal').on('hidden.bs.modal',function(){
			$("#design_area").contents().find('.last_grid').parent().parent().remove();
		});
		$("body").on("click",".add_icon",function(){
			if($("#social_icon").val()!=0){
				$('#design_area')[0].contentWindow.add_icon($('#social_icon').val(),$('#social_icon > option[value='+$('#social_icon').val()+']').attr('data-color'));
			}
		});
		$("body").on("click",".remove_icon",function(){
			$('#design_area')[0].contentWindow.remove_icon($(this).parent().index());
			$(this).parent().remove();
		});
		icon_item_id = '';
		$('body').on('click','.icon_link',function(){
			icon_item_id = $(this).parent().index().toString();
			current_link = $('#design_area')[0].contentWindow.get_href($(this).parent().index());
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
	});
	function load_add_section_drag(){
		Sortable.create(document.getElementById('ready_made'), {
		    group: { name: "section-sortable", pull: "clone", put: false },
		    ghostClass: "place_holder",
		    sort: false,
		    onChoose: function (evt) {
		    	active_section = $(evt.item).attr('data-section-name');
		    },
		    onStart: function (evt) {
				$('#add_menu').css('display','none');
				$('.open_window').addClass('left_menu_icon');
				$('.open_window').css('background-color','');
				$('.open_window > a').css('color','#677888');
				$('#design_area').contents().find('#content_area').prepend('<div class="blank_section" style="background-color:#ffcc00;color:#00ccff;"><div class="container section_placeholder" style="min-height:50px;font-size:40px;text-align:center;">DROP HERE</div></div>');
				$('<div class="blank_section" style="background-color:#ffcc00;color:#00ccff;"><div class="container section_placeholder" style="min-height:50px;font-size:40px;text-align:center;">DROP HERE</div></div>').insertAfter($('#design_area').contents().find('section'));
				sortable_id = 0;
				$("#design_area").contents().find('.section_placeholder').each(function(){
					Sortable.create(document.getElementById('design_area').contentWindow.document.getElementsByClassName('section_placeholder')[sortable_id], {
					    group: "section-sortable",
					    filter: "div",
					    preventOnFilter: false,
					    ghostClass: "place_holder"
					});
					sortable_id++;
				});
			},
			onEnd: function (evt) {
				$.get("<?php echo get_stylesheet_directory_uri();?>/sections/"+active_section+".html",function(data){
					data = data.replace(/PATH/g,"<?php echo get_stylesheet_directory_uri();?>");
					if(active_section=='slider'){
						id = 1;
						$("#design_area").contents().find('.slider').each(function(){
							slider_tag_id = $(this).children('.carousel').attr('id').split('_');
							if(slider_tag_id[1]>=id){
								id = parseInt(slider_tag_id[1])+1;
							}
						});
						data = data.replace(/slider_ID/g,'slider_'+id);
					}
					$('#design_area').contents().find('.add_section').parent().parent().replaceWith(data);
					$('#design_area').contents().find('.blank_section').remove();
					$('#design_area')[0].contentWindow.load_element_panel();
					load_add_item_drag();
					if(active_section=='blog'){
						$('#contentModal').modal('show');
						$('#content_categories').find('input').attr('checked',false);
						$('#content_tags').find('input').attr('checked',false);
					}
				});
			},
		    onMove: function (evt) {
				$('.place_holder').css('width','100%');
				$('.place_holder').html('');
				$("#design_area").contents().find('.place_holder').css('width','100%');
				$("#design_area").contents().find('.place_holder').html('');
			}
		});
		load_add_item_drag();
	}
	function load_add_item_drag(){
		load_sortable();
		Sortable.create(document.getElementById('basic_elements'), {
		    group: { name: "element-sortable", pull: "clone", put: false },
		    ghostClass: "place_holder",
		    sort: false,
		    onChoose: function (evt) {
		    	active_element = $(evt.item).children().attr('data-item');
		    },
		    onStart: function (evt) {
				$('#add_menu').css('display','none');
				$('.open_window').addClass('left_menu_icon');
				$('.open_window').css('background-color','');
				$('.open_window > a').css('color','#677888');
				$('#design_area').contents().find('.grid').css('padding-top','5px');
				$('#design_area').contents().find('.grid').css('padding-bottom','5px');
			},
			onEnd: function (evt) {
				$('#design_area').contents().find('.grid').css('padding-top','0');
				$('#design_area').contents().find('.grid').css('padding-bottom','0');
				if($(evt.item).parent().hasClass('sortable')){
					if(active_element=='button'){
						$(evt.item).replaceWith('<div class="element button" style="text-align:center;"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil button_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link button_link" style="font-size:26px;margin:5px;"></span></div><a href="#" class="btn" style="padding:10px 20px;background-color:#EEE;color:#000;text-decoration:none;">New button</a></div>');
					} else if(active_element=='form'){
						$(evt.item).replaceWith('<div class="element form"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil form_edit" style="font-size:26px;margin:5px;"></span></div><div class="form_content"></div><div class="form-group" style="text-align:center;"><a class="btn send_button" style="padding:10px 20px;color:#000;background-color:#EEE;">Send</a></div></div>');
					} else if(active_element=='grid'){
						$(evt.item).replaceWith('<div class="element grid"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-columns column_settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-plus add_row" style="font-size:26px;margin:5px;"></span><span class="fa fa-minus remove_row" style="font-size:26px;margin:5px;"></span></div><div class="row"><div class="col-md-12 sortable" style="min-height:50px;"></div></div></div>');
					} else if(active_element=='icon'){
						$(evt.item).replaceWith('<div class="element icon" style="text-align:center;"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil icon_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link icon_link" style="font-size:26px;margin:5px;"></span></div><a href="#"><i class="fa fa-font-awesome" aria-hidden="true" style="font-size:50px;"></i></a></div>');
						$('#iconModal').modal('show');
						$('#design_area')[0].contentWindow.select_active_icon();
					} else if(active_element=='image'){
						$(evt.item).replaceWith('<div class="element image"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil image_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link image_link" style="font-size:26px;margin:5px;"></span></div><a href="#"><img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/blank.gif" alt="" class="img-responsive" /></a></div>');
						image_edit();
						$('#design_area')[0].contentWindow.select_active_element();
					} else if(active_element=='slider'){
						id = 1;
						$("#design_area").contents().find('.slider').each(function(){
							slider_tag_id = $(this).children('.carousel').attr('id').split('_');
							if(slider_tag_id[1]>=id){
								id = parseInt(slider_tag_id[1])+1;
							}
						});
						$(evt.item).replaceWith('<div class="element slider"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span></div><div id="slider_'+id+'" class="carousel slide" data-ride="carousel" data-interval="false" style="min-height:100px;"><ol class="carousel-indicators"></ol><div class="carousel-inner" role="listbox"></div><a class="left carousel-control" href="#slider_'+id+'" role="button" data-slide="prev"><span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#slider_'+id+'" role="button" data-slide="next"><span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span><span class="sr-only">Next</span></a></div></div>');
					} else if(active_element=='text'){
						$(evt.item).replaceWith('<div class="element text"><div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil text_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-check text_edit_done" style="font-size:26px;margin:5px;display:none;"></span></div><div class="editable" style="text-align:center;font-size:20px;" spellcheck="false">Text.<br />Double click me.</div></div>');
					}
					load_sortable();
				}
			},
		    onMove: function (evt) {
				$('.place_holder').css('width','100%');
				$('.place_holder').html('');
				$("#design_area").contents().find('.place_holder').css('width','100%');
				$("#design_area").contents().find('.place_holder').html('');
			}
		});
	}
	function load_sortable(){
		sortable_id = 0;
		$("#design_area").contents().find('.sortable').each(function(){
			Sortable.create(document.getElementById('design_area').contentWindow.document.getElementsByClassName('sortable')[sortable_id], {
			    group: "element-sortable",
			    filter: ".text > .editable[contenteditable=true],input,.ui-resizable-handle",
			    preventOnFilter: false,
			    ghostClass: "place_holder",
			    onStart: function (evt) {
			    	margin_top = $(evt.item).css('margin-top');
			    	margin_bottom = $(evt.item).css('margin-bottom');
			    	$(evt.item).css('margin-top',0);
			    	$(evt.item).css('margin-bottom',0);
					div_content = $(evt.item).html();
					$(evt.item).html('');
					$('#design_area').contents().find('.grid').css('padding-top','5px');
					$('#design_area').contents().find('.grid').css('padding-bottom','5px');
				},
				onEnd: function (evt) {
					$(evt.item).css('margin-top',margin_top);
			    	$(evt.item).css('margin-bottom',margin_bottom);
					$(evt.item).html(div_content);
					$('#design_area').contents().find('.grid').css('padding-top','0');
					$('#design_area').contents().find('.grid').css('padding-bottom','0');
					if($(evt.item).hasClass('grid')){
						$(evt.item).find('.image').each(function(){
							$(this).addClass('load_resize');
							$('#design_area')[0].contentWindow.image_resizable('.load_resize');
						});
						load_sortable();
					} else if($(evt.item).hasClass('image')){
						$(evt.item).addClass('load_resize');
						$('#design_area')[0].contentWindow.image_resizable('.load_resize');
					}
				},
				onClone: function (evt) {
					$(evt.item).children('.element_panel').hide();
			    	if($(evt.item).hasClass('grid')){
						$(evt.item).children('.row').children('.sortable').css('box-shadow','');
					} else {
						$(evt.item).css('box-shadow','');
					}
				}
			});
			sortable_id++;
		});
	}
	function image_edit(type){
		image_type = type;
		$('#imageModal').modal('show');
		if($('#site_images').html()==''){
			$.getJSON('<?php echo get_site_url();?>/?do=get_images',function(data){
				$.each(data,function(i){
					$('#site_images').append('<div class="select" data-id="'+data[i].id+'" style="width:100px;height:100px;overflow:hidden;float:left;margin:10px;"><img src="'+data[i].thumb+'" alt="" height="100" /></div>');
				});
			});
		}
	}
	function add_new_image(id){
		$.get("<?php echo get_site_url();?>/?do=get_image&size=thumbnail&id="+id,function(data){
			$('#site_images').prepend('<div class="select" data-id="'+id+'" style="width:100px;height:100px;overflow:hidden;float:left;margin:10px;"><img src="'+data+'" alt="" height="100" /></div>');
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
	window.onbeforeunload = function (e) {
		e = e || window.event;
		if(e){
			e.returnValue = 'Note: Any unsaved changes will be lost.';
		}
		return 'Note: Any unsaved changes will be lost.';
	};
	</script>
	<style type="text/css">
		.picker { margin:0;padding:0;border:0;width:70px;height:20px;border-right:20px solid green;line-height:20px;}
        .left_menu_icon:hover > a > span { color:#00ccff;}
        .page-header { padding-bottom:9px;margin:40px 0 20px;border-bottom:1px solid #eee;}
		.fontawesome-icon-list { margin-top:22px;}
		.fontawesome-icon-list .fa-hover a { overflow:hidden;text-overflow:ellipsis;white-space:nowrap;display:block;color:#222;line-height:32px;height:32px;padding-left:10px;border-radius:4px;}
		.fontawesome-icon-list .fa-hover a .fa { width:32px;font-size:14px;display:inline-block;text-align:right;margin-right:10px;}
		.fontawesome-icon-list .fa-hover a:hover { background-color:#1d9d74;color:#fff;text-decoration:none;}
		.fontawesome-icon-list .fa-hover a:hover .fa { font-size:28px;vertical-align:-6px;}
		.fontawesome-icon-list .fa-hover a:hover .text-muted { color:#bbe2d5;}
	</style>
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Montserrat" media="all">
</head>
<body style="font-family:'Montserrat', sans-serif;">
	<div id="all_content" style="width:100%;height:100%;">
		<div style="position:fixed;width:80px;height:100%;float:left;background-color:#243343;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;">
			<div class="open_window left_menu_icon" style="margin-top:20px;margin-bottom:20px;text-align:center;padding-top:5px;padding-bottom:5px;"><a href="#" id="add" style="color:#677888;font-weight:bold;text-decoration:none;"><span class="fa fa-plus" style="font-size:26px;margin-bottom:10px;"></span><br /><span>Add</span></a></div>
			<div id="menu" class="left_menu_icon" style="margin-bottom:20px;text-align:center;padding-top:5px;padding-bottom:5px;"><a href="#" style="color:#677888;font-weight:bold;text-decoration:none;"><span class="fa fa-bars" style="font-size:26px;margin-bottom:10px;"></span><br /><span>Menu</span></a></div>
			<div class="left_menu_icon" style="margin-bottom:20px;text-align:center;padding-top:5px;padding-bottom:5px;"><a href="#" data-toggle="modal" data-target="#seoModal" style="color:#677888;font-weight:bold;text-decoration:none;"><span class="fa fa-search" style="font-size:26px;margin-bottom:10px;"></span><br /><span>SEO</span></a></div>
			<div id="save" class="left_menu_icon" style="margin-bottom:20px;text-align:center;padding-top:5px;padding-bottom:5px;"><a href="#" style="color:#677888;font-weight:bold;text-decoration:none;"><span class="fa fa-floppy-o" style="font-size:26px;margin-bottom:10px;"></span><br /><span>Save</span></a></div>
			<div class="left_menu_icon" style="margin-bottom:20px;text-align:center;padding-top:5px;padding-bottom:5px;"><a href="<?php echo get_site_url();?>/wp-admin" style="color:#677888;font-weight:bold;text-decoration:none;"><span class="fa fa-wordpress" style="font-size:26px;margin-bottom:10px;"></span><br /><span>Admin</span></a></div>
		</div>
		<div id="iframe" style="position:fixed;left:80px;float:left;height:100%;">
			<iframe id="design_area" style="width:100%;height:100%;border:0;" src="<?php echo get_site_url();?>/?p=<?php echo $id;?>&editor" onload="load_add_section_drag()"></iframe>
		</div>
		<div id="settings_menu" style="width:300px;height:100%;position:fixed;float:left;background-color:#243343;padding:10px;display:none;">
			<div id="margin_settings" style="display:none;">
				<h3 class="panel-title" style="color:#677888;border-bottom:2px solid #677888;padding-bottom:10px;">Settings <i class="fa fa-times close_panel" style="font-size:20px;float:right;"></i></h3>
				<div class="panel panel-default" style="border-radius:0;margin-top:10px;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Margin</h3>
					</div>
					<div class="panel-body">
		    			<table style="width:100%;color:#677888;font-size:14px;">
							<tr>
								<td>Top space:</td><td style="text-align:right;"><a href="#" class="set_margin" data-type="top_minus"><span class="fa fa-minus"></span></a> <input type="text" id="top_margin" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_margin" data-type="top_plus"><span class="fa fa-plus"></span></a></td>
							</tr>
							<tr>
								<td>Bottom space:</td><td style="text-align:right;"><a href="#" class="set_margin" data-type="bottom_minus"><span class="fa fa-minus"></span></a> <input type="text" id="bottom_margin" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_margin" data-type="bottom_plus"><span class="fa fa-plus"></span></a></td>
							</tr>
							<tr>
								<td>Left space:</td><td style="text-align:right;"><a href="#" class="set_margin" data-type="left_minus"><span class="fa fa-minus"></span></a> <input type="text" id="left_margin" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_margin" data-type="left_plus"><span class="fa fa-plus"></span></a></td>
							</tr>
							<tr>
								<td>Right space:</td><td style="text-align:right;"><a href="#" class="set_margin" data-type="right_minus"><span class="fa fa-minus"></span></a> <input type="text" id="right_margin" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_margin" data-type="right_plus"><span class="fa fa-plus"></span></a></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div id="grid_settings" style="display:none;">
				<div class="panel panel-default" style="border-radius:0;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Style</h3>
					</div>
					<div class="panel-body">
						<table style="width:100%;color:#677888;font-size:14px;">
							<tr>
								<td>Background color:</td><td style="text-align:right;"># <input type="text" class="picker" data-type="grid_background"></td>
							</tr>
							<tr>
								<td>Opacity:</td><td style="text-align:right;"><a href="#" class="set_grid_opacity" data-type="minus"><span class="fa fa-minus"></span></a> <input type="text" id="grid_opacity" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_grid_opacity" data-type="plus"><span class="fa fa-plus"></span></a></td>
							</tr>
						</table>
						<br /><a href="#" class="remove_grid_background" style="color:#cc0000;">Delete background</a>
					</div>
				</div>
			</div>
			<div id="gallery_settings" style="display:none;">
				<div class="panel panel-default" style="border-radius:0;margin-top:10px;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Content</h3>
					</div>
					<div class="panel-body">
						<button class="btn btn-default btn-block image_edit" type="button">Add image</button>
					    <div id="gallery_content" style="margin-top:10px;font-weight:bold;font-size:14px;"></div>
					</div>
				</div>
			</div>
			<div id="column_settings" style="display:none;">
				<h3 class="panel-title" style="color:#677888;border-bottom:2px solid #677888;padding-bottom:10px;">Settings <i class="fa fa-times close_panel" style="font-size:20px;float:right;"></i></h3>
				<div class="panel panel-default" style="border-radius:0;margin-top:10px;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Column</h3>
					</div>
					<div class="panel-body" style="padding-left:5px;padding-right:5px;">
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_01.png" alt="" class="set_column" data-type="1" style="margin:4px;border:1px solid #666;" />
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_02.png" alt="" class="set_column" data-type="2" style="margin:4px;border:1px solid #EEE;" />
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_03.png" alt="" class="set_column" data-type="3" style="margin:4px;border:1px solid #EEE;" />
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_04.png" alt="" class="set_column" data-type="4" style="margin:4px;border:1px solid #EEE;" />
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_10.png" alt="" class="set_column" data-type="10" style="margin:4px;border:1px solid #EEE;" /><br />
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_05.png" alt="" class="set_column" data-type="5" style="margin:4px;border:1px solid #EEE;" />
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_06.png" alt="" class="set_column" data-type="6" style="margin:4px;border:1px solid #EEE;" />
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_07.png" alt="" class="set_column" data-type="7" style="margin:4px;border:1px solid #EEE;" />
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_08.png" alt="" class="set_column" data-type="8" style="margin:4px;border:1px solid #EEE;" />
						<img src="<?php echo get_stylesheet_directory_uri();?>/assets/img/column_09.png" alt="" class="set_column" data-type="9" style="margin:4px;border:1px solid #EEE;" />
						<div id="customize_layout" style="color:#677888;font-size:14px;margin-top:10px;text-align:center;"></div>
					</div>
				</div>
			</div>
			<div id="icon_settings" style="display:none;">
				<div class="panel panel-default" style="border-radius:0;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Style</h3>
					</div>
					<div class="panel-body">
						<table style="width:100%;color:#677888;font-size:14px;">
							<tr>
								<td>Align:</td><td style="text-align:right;"><a href="#" class="set_align" data-type="left"><span class="fa fa-align-left"></span></a> <a href="#" class="set_align" data-type="center"><span class="fa fa-align-center"></span></a> <a href="#" class="set_align" data-type="right"><span class="fa fa-align-right"></span></a></td>
							</tr>
							<tr id="icon_color">
								<td>Color:</td><td style="text-align:right;"># <input type="text" class="picker" data-type="icon"></td>
							</tr>
							<tr id="icon_background_color">
								<td>Background color:</td><td style="text-align:right;"># <input type="text" class="picker" data-type="icon_background"></td>
							</tr>
							<tr>
								<td>Size:</td><td style="text-align:right;"><a href="#" class="set_icon_size" data-type="minus"><span class="fa fa-minus"></span></a> <input type="text" id="icon_size" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_icon_size" data-type="plus"><span class="fa fa-plus"></span></a></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div id="social_icons_settings" style="display:none;">
				<div class="panel panel-default" style="border-radius:0;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Content</h3>
					</div>
					<div class="panel-body">
						<div class="input-group" style="margin-top:10px;">
							<select class="form-control" id="social_icon">
								<option value="0">Select icon</option>
								<option value="behance">Behance</option>
								<option value="dribbble" data-color="ea4c8d">Dribbble</option>
								<option value="at">Email</option>
								<option value="facebook" data-color="3b5998">Facebook</option>
								<option value="flickr" data-color="ff0084">Flickr</option>
								<option value="foursquare">Foursquare</option>
								<option value="github">Github</option>
								<option value="google-plus" data-color="dd4b39">Google+</option>
								<option value="instagram" data-color="517fa4">Instagram</option>
								<option value="linkedin" data-color="007bb6">Linkedin</option>
								<option value="medium">Medium</option>
								<option value="pinterest" data-color="cb2027">Pinterest</option>
								<option value="rss" data-color="ff8a3c">RSS</option>
								<option value="skype" data-color="12a5f4">Skype</option>
								<option value="snapchat">Snapchat</option>
								<option value="soundcloud">Soundcloud</option>
								<option value="spotify">Spotify</option>
								<option value="steam">Steam</option>
								<option value="tumblr" data-color="32506d">Tumblr</option>
								<option value="twitch">Twitch</option>
								<option value="twitter" data-color="00aced">Twitter</option>
								<option value="vimeo" data-color="45bbff">Vimeo</option>
								<option value="vine">Vine</option>
								<option value="vk">Vk</option>
								<option value="link">Website</option>
								<option value="whatsapp">Whatsapp</option>
								<option value="wordpress">Wordpress</option>
								<option value="youtube" data-color="a82400">Youtube</option>
							</select>
							<span class="input-group-btn">
								<button class="btn btn-default add_icon" type="button">Add</button>
							</span>
					    </div>
					    <div id="sortable_social_icons" style="margin-top:10px;font-weight:bold;font-size:14px;"></div>
					</div>
				</div>
			</div>
			<div id="image_settings" style="display:none;">
				<div class="panel panel-default" style="border-radius:0;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Style</h3>
					</div>
					<div class="panel-body">
						<table style="width:100%;color:#677888;font-size:14px;">
							<tr>
								<td>Align:</td><td style="text-align:right;"><a href="#" class="set_align" data-type="left"><span class="fa fa-align-left"></span></a> <a href="#" class="set_align" data-type="center"><span class="fa fa-align-center"></span></a> <a href="#" class="set_align" data-type="right"><span class="fa fa-align-right"></span></a></td>
							</tr>
							<tr>
								<td>Shape:</td><td style="text-align:right;"><a href="#" class="set_shape" data-type="circle"><span class="fa fa-circle" style="font-size:26px;"></span></a> <a href="#" class="set_shape" data-type="square"><span class="fa fa-square" style="font-size:26px;"></span></a></td>
							</tr>
							<tr>
								<td>Border:</td><td style="text-align:right;"><a href="#" class="set_image_border" data-type="minus"><span class="fa fa-minus"></span></a> <input type="text" id="image_border" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_image_border" data-type="plus"><span class="fa fa-plus"></span></a></td>
							</tr>
							<tr>
								<td>Border color:</td><td style="text-align:right;"># <input type="text" class="picker" data-type="image_border"></td>
							</tr>
						</table>
					</div>
				</div>
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
				<h3 class="panel-title" style="color:#677888;border-bottom:2px solid #677888;padding-bottom:10px;">Settings <i class="fa fa-times close_panel" style="font-size:20px;float:right;"></i></h3>
				<div class="input-group" style="margin-top:10px;">
					<input type="text" id="input_name" class="form-control" placeholder="New input">
					<span class="input-group-btn">
						<button class="btn btn-default add_form" type="button">Add</button>
					</span>
			    </div>
			    <div id="form_inputs" style="margin-top:10px;color:#677888;font-weight:bold;font-size:14px;"></div>
			    <input type="text" id="content_send_button" class="form-control" value="Send" style="display:none;">
			</div>
			<div id="slider_settings" style="display:none;">
				<h3 class="panel-title" style="color:#677888;border-bottom:2px solid #677888;padding-bottom:10px;">Settings <i class="fa fa-times close_panel" style="font-size:20px;float:right;"></i></h3>
				<div class="panel panel-default" style="border-radius:0;margin-top:10px;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Style</h3>
					</div>
					<div class="panel-body">
						<table style="width:100%;color:#677888;font-size:14px;">
							<tr>
								<td>Height:</td><td style="text-align:right;"><a href="#" class="set_slider_height" data-type="minus"><span class="fa fa-minus"></span></a> <input type="text" id="slider_height" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_slider_height" data-type="plus"><span class="fa fa-plus"></span></a></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="panel panel-default" style="border-radius:0;margin-top:10px;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Content</h3>
					</div>
					<div class="panel-body">
						<button class="btn btn-default btn-block image_edit" type="button">Add image</button>
						<div class="input-group" style="margin-bottom:10px;display:none;">
							<input type="text" id="slider_caption" class="form-control" placeholder="Slider caption">
					    	<span class="input-group-btn">
					    		<button class="btn btn-default add_slider" type="button">Add</button>
					    	</span>
					    </div>
					    <div id="slider_content" style="margin-top:10px;font-weight:bold;font-size:14px;"></div>
					</div>
				</div>
		    </div>
		    <div id="button_settings" style="display:none;">
		    	<div class="panel panel-default" style="border-radius:0;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Style</h3>
					</div>
					<div class="panel-body">
				    	<table style="width:100%;color:#677888;font-size:14px;">
				    		<tr>
								<td>Align:</td><td style="text-align:right;"><a href="#" class="set_align" data-type="left"><span class="fa fa-align-left"></span></a> <a href="#" class="set_align" data-type="center"><span class="fa fa-align-center"></span></a> <a href="#" class="set_align" data-type="right"><span class="fa fa-align-right"></span></a></td>
							</tr>
				    		<tr>
								<td>Background color:</td><td style="text-align:right;"># <input type="text" class="picker" data-type="button_background"></td>
							</tr>
							<tr>
								<td>Opacity:</td><td style="text-align:right;"><a href="#" class="set_button_opacity" data-type="minus"><span class="fa fa-minus"></span></a> <input type="text" id="button_opacity" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_button_opacity" data-type="plus"><span class="fa fa-plus"></span></a></td>
							</tr>
						</table>
						<select class="form-control" id="button_change_font" style="width:50%;float:left;">
							<option value="0">Select font</option>
							<option value="Abril Fatface,cursive">Abril Fatface</option>
							<option value="Alegreya,serif">Alegreya</option>
							<option value="Alfa Slab One,cursive">Alfa Slab One</option>
							<option value="Amatic SC,cursive">Amatic SC</option>
							<option value="Amiri,serif">Amiri</option>
							<option value="Anton,sans-serif">Anton</option>
							<option value="Arimo,sans-serif">Arimo</option>
							<option value="Audiowide,cursive">Audiowide</option>
							<option value="Bangers,cursive">Bangers</option>
							<option value="Berkshire Swash,cursive">Berkshire Swash</option>
							<option value="Bevan,cursive">Bevan</option>
							<option value="Bitter,serif">Bitter</option>
							<option value="Bree Serif,serif">Bree Serif</option>
							<option value="Cabin,sans-serif">Cabin</option>
							<option value="Cardo,serif">Cardo</option>
							<option value="Catamaran,sans-serif">Catamaran</option>
							<option value="Caveat Brush,cursive">Caveat Brush</option>
							<option value="Caveat,cursive">Caveat</option>
							<option value="Cinzel,serif">Cinzel</option>
							<option value="Coda,cursive">Coda</option>
							<option value="Comfortaa,cursive">Comfortaa</option>
							<option value="Concert One,cursive">Concert One</option>
							<option value="Cormorant Garamond,serif">Cormorant Garamond</option>
							<option value="Courgette,cursive">Courgette</option>
							<option value="Crete Round,serif">Crete Round</option>
							<option value="Dancing Script,cursive">Dancing Script</option>
							<option value="Domine,serif">Domine</option>
							<option value="Dosis,sans-serif">Dosis</option>
							<option value="EB Garamond,serif">EB Garamond</option>
							<option value="Exo 2,sans-serif">Exo 2</option>
							<option value="Fira Sans,sans-serif">Fira Sans</option>
							<option value="Fjalla One,sans-serif">Fjalla One</option>
							<option value="Forum,cursive">Forum</option>
							<option value="Glegoo,serif">Glegoo</option>
							<option value="Great Vibes,cursive">Great Vibes</option>
							<option value="Hind,sans-serif">Hind</option>
							<option value="Inconsolata,monospace">Inconsolata</option>
							<option value="Josefin Sans,sans-serif">Josefin Sans</option>
							<option value="Kalam,cursive">Kalam</option>
							<option value="Kaushan Script,cursive">Kaushan Script</option>
							<option value="Lato,sans-serif">Lato</option>
							<option value="Libre Baskerville,serif">Libre Baskerville</option>
							<option value="Lobster,cursive">Lobster</option>
							<option value="Lora,serif">Lora</option>
							<option value="Marck Script,cursive">Marck Script</option>
							<option value="Merienda,cursive">Merienda</option>
							<option value="Merriweather,serif">Merriweather</option>
							<option value="Montserrat,sans-serif">Montserrat</option>
							<option value="Muli,sans-serif">Muli</option>
							<option value="Neuton,serif">Neuton</option>
							<option value="Noticia Text,serif">Noticia Text</option>
							<option value="Noto Sans,sans-serif">Noto Sans</option>
							<option value="Noto Serif,serif">Noto Serif</option>
							<option value="Nunito,sans-serif">Nunito</option>
							<option value="Old Standard TT,serif">Old Standard TT</option>
							<option value="Oleo Script,cursive">Oleo Script</option>
							<option value="Open Sans Condensed,sans-serif">Open Sans Condensed</option>
							<option value="Open Sans,sans-serif">Open Sans</option>
							<option value="Oswald,sans-serif">Oswald</option>
							<option value="Overlock,cursive">Overlock</option>
							<option value="Oxygen,sans-serif">Oxygen</option>
							<option value="Pacifico,cursive">Pacifico</option>
							<option value="Passion One,cursive">Passion One</option>
							<option value="Patrick Hand,cursive">Patrick Hand</option>
							<option value="Playball,cursive">Playball</option>
							<option value="Playfair Display SC,serif">Playfair Display SC</option>
							<option value="Playfair Display,serif">Playfair Display</option>
							<option value="Poiret One,cursive">Poiret One</option>
							<option value="Poppins,sans-serif">Poppins</option>
							<option value="Press Start 2P,cursive">Press Start 2P</option>
							<option value="PT Mono,monospace">PT Mono</option>
							<option value="PT Sans Narrow,sans-serif">PT Sans Narrow</option>
							<option value="PT Sans,sans-serif">PT Sans</option>
							<option value="PT Serif,serif">PT Serif</option>
							<option value="Quattrocento,serif">Quattrocento</option>
							<option value="Quicksand,sans-serif">Quicksand</option>
							<option value="Raleway,sans-serif">Raleway</option>
							<option value="Righteous,cursive">Righteous</option>
							<option value="Roboto Condensed,sans-serif">Roboto Condensed</option>
							<option value="Roboto Mono,monospace">Roboto Mono</option>
							<option value="Roboto Slab,serif">Roboto Slab</option>
							<option value="Roboto,sans-serif">Roboto</option>
							<option value="Rokkitt,serif">Rokkitt</option>
							<option value="Sacramento,cursive">Sacramento</option>
							<option value="Sanchez,serif">Sanchez</option>
							<option value="Shadows Into Light Two,cursive">Shadows Into Light Two</option>
							<option value="Shrikhand,cursive">Shrikhand</option>
							<option value="Sigmar One,cursive">Sigmar One</option>
							<option value="Slabo 27px,serif">Slabo 27px</option>
							<option value="Sorts Mill Goudy,serif">Sorts Mill Goudy</option>
							<option value="Source Code Pro,monospace">Source Code Pro</option>
							<option value="Source Sans Pro,sans-serif">Source Sans Pro</option>
							<option value="Source Serif Pro,serif">Source Serif Pro</option>
							<option value="Tinos,serif">Tinos</option>
							<option value="Titillium Web,sans-serif">Titillium Web</option>
							<option value="Ubuntu,sans-serif">Ubuntu</option>
							<option value="Unica One,cursive">Unica One</option>
							<option value="Vollkorn,serif">Vollkorn</option>
							<option value="VT323,monospace">VT323</option>
							<option value="Work Sans,sans-serif">Work Sans</option>
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
						<table style="width:100%;color:#677888;font-size:14px;">
							<tr>
								<td>Font color:</td><td style="text-align:right;"># <input type="text" class="picker" data-type="button_font"></td>
							</tr>
							<tr>
								<td>Border:</td><td style="text-align:right;"><a href="#" class="set_button_border" data-type="minus"><span class="fa fa-minus"></span></a> <input type="text" id="button_border" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_button_border" data-type="plus"><span class="fa fa-plus"></span></a></td>
							</tr>
							<tr>
								<td>Border color:</td><td style="text-align:right;"># <input type="text" class="picker" data-type="button_border"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div id="text_settings" style="display:none;">
				<h3 class="panel-title" style="color:#677888;border-bottom:2px solid #677888;padding-bottom:10px;">Settings <i class="fa fa-times close_panel" style="font-size:20px;float:right;"></i></h3>
		    	<div class="panel panel-default" style="border-radius:0;margin-top:10px;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Style</h3>
					</div>
					<div class="panel-body">
				    	<table style="width:100%;color:#677888;font-size:14px;">
				    		<tr>
								<td>Align:</td><td style="text-align:right;"><a href="#" class="set_align" data-type="left"><span class="fa fa-align-left"></span></a> <a href="#" class="set_align" data-type="center"><span class="fa fa-align-center"></span></a> <a href="#" class="set_align" data-type="right"><span class="fa fa-align-right"></span></a></td>
							</tr>
						</table>
						<select class="form-control" id="text_change_font" style="width:50%;float:left;">
							<option value="0">Select font</option>
							<option value="Abril Fatface,cursive">Abril Fatface</option>
							<option value="Alegreya,serif">Alegreya</option>
							<option value="Alfa Slab One,cursive">Alfa Slab One</option>
							<option value="Amatic SC,cursive">Amatic SC</option>
							<option value="Amiri,serif">Amiri</option>
							<option value="Anton,sans-serif">Anton</option>
							<option value="Arimo,sans-serif">Arimo</option>
							<option value="Audiowide,cursive">Audiowide</option>
							<option value="Bangers,cursive">Bangers</option>
							<option value="Berkshire Swash,cursive">Berkshire Swash</option>
							<option value="Bevan,cursive">Bevan</option>
							<option value="Bitter,serif">Bitter</option>
							<option value="Bree Serif,serif">Bree Serif</option>
							<option value="Cabin,sans-serif">Cabin</option>
							<option value="Cardo,serif">Cardo</option>
							<option value="Catamaran,sans-serif">Catamaran</option>
							<option value="Caveat Brush,cursive">Caveat Brush</option>
							<option value="Caveat,cursive">Caveat</option>
							<option value="Cinzel,serif">Cinzel</option>
							<option value="Coda,cursive">Coda</option>
							<option value="Comfortaa,cursive">Comfortaa</option>
							<option value="Concert One,cursive">Concert One</option>
							<option value="Cormorant Garamond,serif">Cormorant Garamond</option>
							<option value="Courgette,cursive">Courgette</option>
							<option value="Crete Round,serif">Crete Round</option>
							<option value="Dancing Script,cursive">Dancing Script</option>
							<option value="Domine,serif">Domine</option>
							<option value="Dosis,sans-serif">Dosis</option>
							<option value="EB Garamond,serif">EB Garamond</option>
							<option value="Exo 2,sans-serif">Exo 2</option>
							<option value="Fira Sans,sans-serif">Fira Sans</option>
							<option value="Fjalla One,sans-serif">Fjalla One</option>
							<option value="Forum,cursive">Forum</option>
							<option value="Glegoo,serif">Glegoo</option>
							<option value="Great Vibes,cursive">Great Vibes</option>
							<option value="Hind,sans-serif">Hind</option>
							<option value="Inconsolata,monospace">Inconsolata</option>
							<option value="Josefin Sans,sans-serif">Josefin Sans</option>
							<option value="Kalam,cursive">Kalam</option>
							<option value="Kaushan Script,cursive">Kaushan Script</option>
							<option value="Lato,sans-serif">Lato</option>
							<option value="Libre Baskerville,serif">Libre Baskerville</option>
							<option value="Lobster,cursive">Lobster</option>
							<option value="Lora,serif">Lora</option>
							<option value="Marck Script,cursive">Marck Script</option>
							<option value="Merienda,cursive">Merienda</option>
							<option value="Merriweather,serif">Merriweather</option>
							<option value="Montserrat,sans-serif">Montserrat</option>
							<option value="Muli,sans-serif">Muli</option>
							<option value="Neuton,serif">Neuton</option>
							<option value="Noticia Text,serif">Noticia Text</option>
							<option value="Noto Sans,sans-serif">Noto Sans</option>
							<option value="Noto Serif,serif">Noto Serif</option>
							<option value="Nunito,sans-serif">Nunito</option>
							<option value="Old Standard TT,serif">Old Standard TT</option>
							<option value="Oleo Script,cursive">Oleo Script</option>
							<option value="Open Sans Condensed,sans-serif">Open Sans Condensed</option>
							<option value="Open Sans,sans-serif">Open Sans</option>
							<option value="Oswald,sans-serif">Oswald</option>
							<option value="Overlock,cursive">Overlock</option>
							<option value="Oxygen,sans-serif">Oxygen</option>
							<option value="Pacifico,cursive">Pacifico</option>
							<option value="Passion One,cursive">Passion One</option>
							<option value="Patrick Hand,cursive">Patrick Hand</option>
							<option value="Playball,cursive">Playball</option>
							<option value="Playfair Display SC,serif">Playfair Display SC</option>
							<option value="Playfair Display,serif">Playfair Display</option>
							<option value="Poiret One,cursive">Poiret One</option>
							<option value="Poppins,sans-serif">Poppins</option>
							<option value="Press Start 2P,cursive">Press Start 2P</option>
							<option value="PT Mono,monospace">PT Mono</option>
							<option value="PT Sans Narrow,sans-serif">PT Sans Narrow</option>
							<option value="PT Sans,sans-serif">PT Sans</option>
							<option value="PT Serif,serif">PT Serif</option>
							<option value="Quattrocento,serif">Quattrocento</option>
							<option value="Quicksand,sans-serif">Quicksand</option>
							<option value="Raleway,sans-serif">Raleway</option>
							<option value="Righteous,cursive">Righteous</option>
							<option value="Roboto Condensed,sans-serif">Roboto Condensed</option>
							<option value="Roboto Mono,monospace">Roboto Mono</option>
							<option value="Roboto Slab,serif">Roboto Slab</option>
							<option value="Roboto,sans-serif">Roboto</option>
							<option value="Rokkitt,serif">Rokkitt</option>
							<option value="Sacramento,cursive">Sacramento</option>
							<option value="Sanchez,serif">Sanchez</option>
							<option value="Shadows Into Light Two,cursive">Shadows Into Light Two</option>
							<option value="Shrikhand,cursive">Shrikhand</option>
							<option value="Sigmar One,cursive">Sigmar One</option>
							<option value="Slabo 27px,serif">Slabo 27px</option>
							<option value="Sorts Mill Goudy,serif">Sorts Mill Goudy</option>
							<option value="Source Code Pro,monospace">Source Code Pro</option>
							<option value="Source Sans Pro,sans-serif">Source Sans Pro</option>
							<option value="Source Serif Pro,serif">Source Serif Pro</option>
							<option value="Tinos,serif">Tinos</option>
							<option value="Titillium Web,sans-serif">Titillium Web</option>
							<option value="Ubuntu,sans-serif">Ubuntu</option>
							<option value="Unica One,cursive">Unica One</option>
							<option value="Vollkorn,serif">Vollkorn</option>
							<option value="VT323,monospace">VT323</option>
							<option value="Work Sans,sans-serif">Work Sans</option>
						</select>
						<select class="form-control" id="text_change_size" style="width:50%;">
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
						<table style="width:100%;color:#677888;font-size:14px;">
							<tr>
								<td>Font color:</td><td style="text-align:right;"># <input type="text" class="picker" data-type="text_font"></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div id="menu_settings" style="height:100%;overflow:scroll;display:none;">
				<h3 class="panel-title" style="color:#677888;border-bottom:2px solid #677888;padding-bottom:10px;">Settings <i class="fa fa-times close_panel" style="font-size:20px;float:right;"></i></h3>
				<div class="panel panel-default" style="border-radius:0;margin-top:10px;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Style</h3>
					</div>
					<div class="panel-body">
						<a href="#" class="image_edit" style="color:#cc0000;">Select logo</a>
						<table style="width:100%;margin-top:15px;margin-bottom:15px;color:#677888;font-size:14px;">
							<tr>
								<td>Background color:</td>
								<td style="text-align:right;"># <input type="text" class="picker" data-type="menu_background"></td>
							</tr>
							<tr>
								<td>Opacity:</td><td style="text-align:right;"><a href="#" class="set_menu_opacity" data-type="minus"><span class="fa fa-minus"></span></a> <input type="text" id="menu_opacity" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_menu_opacity" data-type="plus"><span class="fa fa-plus"></span></a></td>
							</tr>
						</table>
						<select class="form-control" id="menu_change_font">
							<option value="0">Select font</option>
							<option value="Abril Fatface,cursive">Abril Fatface</option>
							<option value="Alegreya,serif">Alegreya</option>
							<option value="Alfa Slab One,cursive">Alfa Slab One</option>
							<option value="Amatic SC,cursive">Amatic SC</option>
							<option value="Amiri,serif">Amiri</option>
							<option value="Anton,sans-serif">Anton</option>
							<option value="Arimo,sans-serif">Arimo</option>
							<option value="Audiowide,cursive">Audiowide</option>
							<option value="Bangers,cursive">Bangers</option>
							<option value="Berkshire Swash,cursive">Berkshire Swash</option>
							<option value="Bevan,cursive">Bevan</option>
							<option value="Bitter,serif">Bitter</option>
							<option value="Bree Serif,serif">Bree Serif</option>
							<option value="Cabin,sans-serif">Cabin</option>
							<option value="Cardo,serif">Cardo</option>
							<option value="Catamaran,sans-serif">Catamaran</option>
							<option value="Caveat Brush,cursive">Caveat Brush</option>
							<option value="Caveat,cursive">Caveat</option>
							<option value="Cinzel,serif">Cinzel</option>
							<option value="Coda,cursive">Coda</option>
							<option value="Comfortaa,cursive">Comfortaa</option>
							<option value="Concert One,cursive">Concert One</option>
							<option value="Cormorant Garamond,serif">Cormorant Garamond</option>
							<option value="Courgette,cursive">Courgette</option>
							<option value="Crete Round,serif">Crete Round</option>
							<option value="Dancing Script,cursive">Dancing Script</option>
							<option value="Domine,serif">Domine</option>
							<option value="Dosis,sans-serif">Dosis</option>
							<option value="EB Garamond,serif">EB Garamond</option>
							<option value="Exo 2,sans-serif">Exo 2</option>
							<option value="Fira Sans,sans-serif">Fira Sans</option>
							<option value="Fjalla One,sans-serif">Fjalla One</option>
							<option value="Forum,cursive">Forum</option>
							<option value="Glegoo,serif">Glegoo</option>
							<option value="Great Vibes,cursive">Great Vibes</option>
							<option value="Hind,sans-serif">Hind</option>
							<option value="Inconsolata,monospace">Inconsolata</option>
							<option value="Josefin Sans,sans-serif">Josefin Sans</option>
							<option value="Kalam,cursive">Kalam</option>
							<option value="Kaushan Script,cursive">Kaushan Script</option>
							<option value="Lato,sans-serif">Lato</option>
							<option value="Libre Baskerville,serif">Libre Baskerville</option>
							<option value="Lobster,cursive">Lobster</option>
							<option value="Lora,serif">Lora</option>
							<option value="Marck Script,cursive">Marck Script</option>
							<option value="Merienda,cursive">Merienda</option>
							<option value="Merriweather,serif">Merriweather</option>
							<option value="Montserrat,sans-serif">Montserrat</option>
							<option value="Muli,sans-serif">Muli</option>
							<option value="Neuton,serif">Neuton</option>
							<option value="Noticia Text,serif">Noticia Text</option>
							<option value="Noto Sans,sans-serif">Noto Sans</option>
							<option value="Noto Serif,serif">Noto Serif</option>
							<option value="Nunito,sans-serif">Nunito</option>
							<option value="Old Standard TT,serif">Old Standard TT</option>
							<option value="Oleo Script,cursive">Oleo Script</option>
							<option value="Open Sans Condensed,sans-serif">Open Sans Condensed</option>
							<option value="Open Sans,sans-serif">Open Sans</option>
							<option value="Oswald,sans-serif">Oswald</option>
							<option value="Overlock,cursive">Overlock</option>
							<option value="Oxygen,sans-serif">Oxygen</option>
							<option value="Pacifico,cursive">Pacifico</option>
							<option value="Passion One,cursive">Passion One</option>
							<option value="Patrick Hand,cursive">Patrick Hand</option>
							<option value="Playball,cursive">Playball</option>
							<option value="Playfair Display SC,serif">Playfair Display SC</option>
							<option value="Playfair Display,serif">Playfair Display</option>
							<option value="Poiret One,cursive">Poiret One</option>
							<option value="Poppins,sans-serif">Poppins</option>
							<option value="Press Start 2P,cursive">Press Start 2P</option>
							<option value="PT Mono,monospace">PT Mono</option>
							<option value="PT Sans Narrow,sans-serif">PT Sans Narrow</option>
							<option value="PT Sans,sans-serif">PT Sans</option>
							<option value="PT Serif,serif">PT Serif</option>
							<option value="Quattrocento,serif">Quattrocento</option>
							<option value="Quicksand,sans-serif">Quicksand</option>
							<option value="Raleway,sans-serif">Raleway</option>
							<option value="Righteous,cursive">Righteous</option>
							<option value="Roboto Condensed,sans-serif">Roboto Condensed</option>
							<option value="Roboto Mono,monospace">Roboto Mono</option>
							<option value="Roboto Slab,serif">Roboto Slab</option>
							<option value="Roboto,sans-serif">Roboto</option>
							<option value="Rokkitt,serif">Rokkitt</option>
							<option value="Sacramento,cursive">Sacramento</option>
							<option value="Sanchez,serif">Sanchez</option>
							<option value="Shadows Into Light Two,cursive">Shadows Into Light Two</option>
							<option value="Shrikhand,cursive">Shrikhand</option>
							<option value="Sigmar One,cursive">Sigmar One</option>
							<option value="Slabo 27px,serif">Slabo 27px</option>
							<option value="Sorts Mill Goudy,serif">Sorts Mill Goudy</option>
							<option value="Source Code Pro,monospace">Source Code Pro</option>
							<option value="Source Sans Pro,sans-serif">Source Sans Pro</option>
							<option value="Source Serif Pro,serif">Source Serif Pro</option>
							<option value="Tinos,serif">Tinos</option>
							<option value="Titillium Web,sans-serif">Titillium Web</option>
							<option value="Ubuntu,sans-serif">Ubuntu</option>
							<option value="Unica One,cursive">Unica One</option>
							<option value="Vollkorn,serif">Vollkorn</option>
							<option value="VT323,monospace">VT323</option>
							<option value="Work Sans,sans-serif">Work Sans</option>
						</select>
						<table style="width:100%;margin-top:15px;color:#677888;font-size:14px;">
							<tr>
								<td>Font color:</td>
								<td style="text-align:right;"># <input type="text" class="picker" data-type="menu_font"></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="panel panel-default" style="border-radius:0;margin-top:10px;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Content</h3>
					</div>
					<div class="panel-body">
						<div class="input-group" style="margin-bottom:10px;">
							<input type="text" id="button_name" class="form-control" placeholder="New button">
							<span class="input-group-btn">
					    		<button class="btn btn-default add_menu" type="button">Add</button>
					    	</span>
					    </div>
						<div id="sortable" style="margin-top:10px;font-weight:bold;font-size:14px;"></div>
					</div>
				</div>
			</div>
			<div id="section_settings" style="display:none;">
				<h3 class="panel-title" style="color:#677888;border-bottom:2px solid #677888;padding-bottom:10px;">Settings <i class="fa fa-times close_panel" style="font-size:20px;float:right;"></i></h3>
				<div class="panel panel-default" style="border-radius:0;margin-top:10px;margin-bottom:10px;">
					<div class="panel-heading">
						<h3 class="panel-title" style="color:#677888;">Style</h3>
					</div>
					<div class="panel-body">
						<a href="#" class="image_edit" style="color:#cc0000;">Background image</a>
						<table style="width:100%;margin-top:15px;margin-bottom:15px;color:#677888;font-size:14px;">
							<tr>
								<td>Background color:</td>
								<td style="text-align:right;"># <input type="text" class="picker" data-type="section_background"></td>
							</tr>
							<tr>
								<td>Opacity:</td><td style="text-align:right;"><a href="#" class="set_section_opacity" data-type="minus"><span class="fa fa-minus"></span></a> <input type="text" id="section_opacity" size="5" style="text-align:center;" disabled="disabled"> <a href="#" class="set_section_opacity" data-type="plus"><span class="fa fa-plus"></span></a></td>
							</tr>
						</table>
						<a href="#" class="remove_background" style="color:#cc0000;">Delete background</a>
					</div>
				</div>
				<div class="input-group" style="margin-top:10px;">
					<input type="text" id="section_id" class="form-control" placeholder="Section ID">
					<span class="input-group-btn">
			    		<button class="btn btn-default add_id" type="button">Save</button>
			    	</span>
			    </div>
			</div>
		</div>
		<div id="add_menu" style="width:300px;height:100%;overflow:scroll;padding:10px;position:fixed;left:80px;float:left;background-color:#FFF;border-left:5px solid #ffcc00;box-shadow:5px 0px 5px 0px rgba(0,0,0,0.2);display:none;">
			<select id="add_list" class="form-control" style="margin-bottom:10px;">
				<option value="ready_made">Sections</option>
				<option value="basic_elements">Elements</option>
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
	<div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" style="width:400px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Define link</h4>
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
	<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-hidden="true">
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
	<div class="modal fade" id="iconModal" tabindex="-1" role="dialog" aria-hidden="true">
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
	<?php
	if(metadata_exists('post',$id,'seo_settings')){
		$seo_settings = json_decode(get_post_meta($id,'seo_settings')[0]);
		$title = $seo_settings->title;
		$keywords = $seo_settings->keywords;
		$description = $seo_settings->description;
	} else {
		$title = '';
		$keywords = '';
		$description = '';
	}
	?>
	<div class="modal fade" id="seoModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="form-group">
						<input type="text" id="seo_title" class="form-control" placeholder="Title" value="<?php echo $title;?>">
					</div>
					<div class="form-group">
						<input type="text" id="seo_keywords" class="form-control" placeholder="Keywords" value="<?php echo $keywords;?>">
					</div>
					<div class="form-group">
						<textarea class="form-control" rows="3" id="seo_description" placeholder="Description"><?php echo $description;?></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="save_seo">Save</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="contentModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" style="width:400px;">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					<h4 class="modal-title">Content source</h4>
				</div>
				<div class="modal-body">
					<select class="form-control" id="content_type">
						<option value="categories">Get content from selected categories</option>
						<option value="tags">Get content from selected tags</option>
					</select>
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
					<div id="content_tags" style="display:none;">
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
</body>
</html>