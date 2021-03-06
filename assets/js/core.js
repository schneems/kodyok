$(document).ready(function(){
	if($('#post_content_area').length==1){
		$('#add_list',window.parent.document).hide();
		$('#ready_made',window.parent.document).hide();
		$('#basic_elements',window.parent.document).show();
	}
	$("body").on("click",".settings",function(){
		active_element = $(this).parent().parent();
		$('#settings_menu > div',window.parent.document).hide();
		$('#margin_settings',window.parent.document).show();
		if($(active_element).hasClass('button')){
			get_button_settings();
    		$('#button_settings',window.parent.document).show();
		} else if($(active_element).hasClass('form')){
    		$('#form_settings',window.parent.document).show();
		} else if($(active_element).hasClass('grid')){
			get_grid_settings();
    		$('#grid_settings',window.parent.document).show();
		} else if($(active_element).hasClass('icon')){
    		$('#icon_settings',window.parent.document).show();
    		$('#icon_color',window.parent.document).show();
    		$('#icon_background_color',window.parent.document).hide();
		} else if($(active_element).hasClass('social_icons')){
			get_social_icons_settings();
			$('#icon_settings',window.parent.document).show();
    		$('#social_icons_settings',window.parent.document).show();
    		$('#icon_color',window.parent.document).hide();
    		$('#icon_background_color',window.parent.document).show();
		} else if($(active_element).hasClass('image')){
			get_image_settings();
    		$('#image_settings',window.parent.document).show();
		} else if($(active_element).hasClass('slider')){
			get_slider_settings();
    		$('#slider_settings',window.parent.document).show();
    		$('#slider_settings > h3',window.parent.document).hide();
		} else if($(active_element).hasClass('text')){
			if($(active_element).parent().parent().parent().parent().hasClass('dynamic_content')){
				$('#margin_settings',window.parent.document).hide();
				$('#text_settings',window.parent.document).show();
			}
		}
		$('#top_margin',window.parent.document).val($(active_element).css('margin-top'));
		$('#bottom_margin',window.parent.document).val($(active_element).css('margin-bottom'));
		$('#left_margin',window.parent.document).val($(active_element).css('margin-left'));
		$('#right_margin',window.parent.document).val($(active_element).css('margin-right'));
		$('#iframe',window.parent.document).css('width',($('#all_content',window.parent.document).width()-380)+'px');
		$('#settings_menu',window.parent.document).show();
	});
	$("body").on("click",".remove",function(){
		if(confirm('This element will be deleted. Are you sure?')){
			$(this).parent().parent().remove();
		}
	});
	$("body").on("click",".duplicate",function(){
		$(this).parent().parent().clone().insertAfter($(this).parent().parent());
		if($(this).parent().parent().hasClass('slider')){
			id = 1;
			$("#design_area").find('.slider').each(function(){
				slider_tag_id = $(this).children('.carousel').attr('id').split('_');
				if(slider_tag_id[1]>=id){
					id = parseInt(slider_tag_id[1])+1;
				}
			});
			$(this).parent().parent().next().children('.carousel').attr('id','slider_'+id);
			$(this).parent().parent().next().children('.carousel').children('.carousel-indicators').children('li').attr('data-target','#slider_'+id);
			$(this).parent().parent().next().children('.carousel').children('a').attr('href','#slider_'+id);
		} else if($(this).parent().parent().hasClass('text')){
			$(this).parent().parent().next().find('.editable').removeAttr('id');
		}
	});
	$("body").on("mouseenter",".element",function(){
		$(this).children('.element_panel').css('display','');
		$(this).children('.element_panel').css('position','absolute');
		$(this).children('.element_panel').css('z-index','96');
		if($(this).hasClass('grid')){
			$(this).children('.row').children('div').css('box-shadow','inset 0 0 0 1px #ffcc00');
			$(this).children('.element_panel').css('background-color','rgba(255,204,0,0.8)');
			$(this).children('.element_panel').css('margin-top','-'+$(this).children('.element_panel').css('height'));
		} else {
			$(this).css('box-shadow','inset 0 0 0 1px #00ccff');
			$(this).children('.element_panel').css('background-color','rgba(0,204,255,0.8)');
			$(this).children('.element_panel').css('margin-top',$(this).css('height'));
		}
		$(this).children('.element_panel').css('width',$(this).css('width'));
		if($(this).hasClass('text')){
			if($(this).children('.element_panel').children('.text_edit_done').css('display')!='none'){
				$('.editor').css('display','');
			}
		}
	});
	$("body").on("mouseleave",".element",function(){
		$(this).children('.element_panel').css('display','none');
		if($(this).hasClass('grid')){
			$(this).children('.row').children('div').css('box-shadow','');
		} else {
			$(this).css('box-shadow','');
		}
		if($(this).hasClass('text')){
			$('.editor').css('display','none');
		}
	});
	load_element_panel();
});
function load_element_panel(){
	$('.element').each(function(){
		if(!$(this).children('.element_panel').length){
			if($(this).hasClass('button')){
				$(this).prepend('<div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil button_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link button_link" style="font-size:26px;margin:5px;"></span></div>');
			} else if($(this).hasClass('form')){
				$(this).prepend('<div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil form_edit" style="font-size:26px;margin:5px;"></span></div>');
			} else if($(this).hasClass('grid')){
				$(this).prepend('<div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-columns column_settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-plus add_row" style="font-size:26px;margin:5px;"></span><span class="fa fa-minus remove_row" style="font-size:26px;margin:5px;"></span></div>');
				if($(this).hasClass('dynamic_content') || $(this).hasClass('gallery')){
					$(this).children('.element_panel').children('.column_settings').remove();
					$(this).children('.element_panel').children('.add_row').remove();
					$(this).children('.element_panel').children('.remove_row').remove();
				}
			} else if($(this).hasClass('icon')){
				$(this).prepend('<div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil icon_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link icon_link" style="font-size:26px;margin:5px;"></span></div>');
			} else if($(this).hasClass('social_icons')){
				$(this).prepend('<div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span></div>');
			} else if($(this).hasClass('image')){
				$(this).prepend('<div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil image_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-link image_link" style="font-size:26px;margin:5px;"></span></div>');
				image_resizable($(this));
			} else if($(this).hasClass('slider')){
				$(this).prepend('<div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span></div>');
				$(this).replaceWith('<div class="element slider">'+$(this).html()+'</div>');
				$(this).children('.carousel').attr('data-interval','false');
			} else if($(this).hasClass('text')){
				$(this).prepend('<div class="element_panel" style="width:100%;text-align:center;cursor:default;display:none;"><span class="fa fa-clone duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash remove" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span><span class="fa fa-pencil text_edit" style="font-size:26px;margin:5px;"></span><span class="fa fa-check text_edit_done" style="font-size:26px;margin:5px;display:none;"></span></div>');
			}
		}
	});
	$('.dynamic_content').find('.text').children('.element_panel').html('<span class="fa fa-cog settings" style="font-size:26px;margin:5px;"></span>');
}
function update_link(link,blank){
	if($(active_element).hasClass('text')){
		if(window.getSelection){
			currSelection = window.getSelection();
			currSelection.removeAllRanges();
			for(i=0;i<storedSelections.length;i++){
				currSelection.addRange(storedSelections[i]);
			}
		}
		document.execCommand('createLink',false,link);
	} else if($(active_element).hasClass('image') || $(active_element).hasClass('button') || $(active_element).hasClass('icon')){
		$(active_element).children('a').attr('href',link);
		$(active_element).children('a').removeAttr('target');
		if(blank==1){
			$(active_element).children('a').attr('target','_blank');
		}
	}
}
function remove_link(){
	if($(active_element).hasClass('text')){
		if(window.getSelection){
			currSelection = window.getSelection();
			currSelection.removeAllRanges();
			for(i=0;i<storedSelections.length;i++){
				currSelection.addRange(storedSelections[i]);
			}
		}
		document.execCommand("unlink",false,false);
	} else if($(active_element).hasClass('image')){
		$(active_element).children('a').attr('href','#');
		$(active_element).children('a').removeAttr('target');
	} else if($(active_element).hasClass('button')){
		$(active_element).children('a').attr('href','#');
		$(active_element).children('a').removeAttr('target');
	} else if($(active_element).hasClass('icon')){
		$(active_element).children('a').attr('href','#');
		$(active_element).children('a').removeAttr('target');
	}
}
function componentToHex(c){
    hex = c.toString(16);
    return hex.length == 1 ? "0" + hex : hex;
}
function rgbToHex(r,g,b){
    return componentToHex(r) + componentToHex(g) + componentToHex(b);
}
function set_align(value){
	if($(active_element).hasClass('button')){
		$(active_element).css('text-align',value);
	} else if($(active_element).hasClass('icon')){
		$(active_element).css('text-align',value);
	} else if($(active_element).hasClass('social_icons')){
		$(active_element).css('text-align',value);
	} else if($(active_element).hasClass('image')){
		if($(active_element).hasClass('image_in_text')){
			if(value=='center'){
				$(active_element).css('margin','auto');
				element_style = $(active_element).attr('style');
				element_style = element_style.replace('float: left;','');
				element_style = element_style.replace('float: right;','');
				$(active_element).attr('style',element_style);
			} else if(value=='left'){
				$(active_element).css('margin','0');
				$(active_element).css('float','left');
			} else if(value=='right'){
				$(active_element).css('margin','0');
				$(active_element).css('float','right');
			}
		} else {
			if(value=='center'){
				$(active_element).css('margin-left','auto');
				$(active_element).css('margin-right','auto');
			} else if(value=='left'){
				$(active_element).css('margin-left','0');
				$(active_element).css('margin-right','auto');
			} else if(value=='right'){
				$(active_element).css('margin-left','auto');
				$(active_element).css('margin-right','0');
			}
		}
	} else if($(active_element).hasClass('text')){
		$(active_element).children('div').last().css('text-align',value);
		apply_to_all('text_align','text-align',value);
	}
}
function set_margin(value){
	margin_side = value.split('_');
	margin_value = $(active_element).css('margin-'+margin_side[0]);
	margin_value = margin_value.split('px');
	if(margin_side[1]=='minus'){
		margin_value = parseInt(margin_value[0])-10;
	} else if(margin_side[1]=='plus'){
		margin_value = parseInt(margin_value[0])+10;
	}
	$(active_element).css('margin-'+margin_side[0],margin_value+'px');
	return margin_value+'px';
}
function update_section_list(){
	$('#link_section',window.parent.document).html('<option value="0">Select section</option>');
	$('section').each(function(){
		if($(this)[0].hasAttribute('id')){
			$('#link_section',window.parent.document).append('<option value="#'+$(this).attr('id')+'">'+$(this).attr('id')+'</option>');
		}
	});
}
function run_before_save(post_page){
	if(post_page==0){
		$('section').css('border','0');
		$('section').children('.sortable').css('border-left','0');
		$('section').children('.sortable').css('border-right','0');
		$('section').each(function(){
			$(this).children('div:first').remove();
		});
	}
	$('.grid').find('.sortable').css('border','0');
	$(".editor").remove();
	$(".element_panel").remove();
	$(".ui-resizable-handle").remove();
	$('.editable').removeAttr('contenteditable');
	$('.button').find('a').removeAttr('contenteditable');
	$("body").find('.dynamic_content').each(function(){
		$(this).find('.row:first > div:gt(7)').remove();
		if($(this).find('.row:last').css('display')=='none' && $(this).find('.row:first > div').length==6){
			$(this).find('.row:last').show();
		}
	});
}
function run_after_save(post_page){
	if(post_page==0){
		$('section').css('border','1px dashed #00ccff');
		$('section').children('.sortable').css('border-left','1px dashed #ffcc00');
		$('section').children('.sortable').css('border-right','1px dashed #ffcc00');
		$('section').each(function(){
			$(this).prepend('<div style="position:absolute;z-index:97;background:rgba(255,204,0,0.8);cursor:default;"><span class="fa fa-arrow-up section_up" style="font-size:26px;margin:5px;"></span><span class="fa fa-arrow-down section_down" style="font-size:26px;margin:5px;"></span><span class="fa fa-clone section_duplicate" style="font-size:26px;margin:5px;"></span><span class="fa fa-trash section_delete" style="font-size:26px;margin:5px;"></span><span class="fa fa-cog section_settings" style="font-size:26px;margin:5px;"></span></div>');
		});
	}
	load_element_panel();
}
function apply_to_all(element_type,feature,value){
	if($(active_element).parent().parent().parent().parent().hasClass('dynamic_content')){
		grid_element = $(active_element).parent().parent().parent().parent();
		$(grid_element).children('.row').children('div').each(function(){
			if(element_type=='text'){
				$(this).find('.text > div:last > a').css(feature,value);
			} else if(element_type=='text_align'){
				$(this).find('.text').find('div:last').css(feature,value);
			}
		});
	}
}