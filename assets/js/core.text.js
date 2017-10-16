$(document).ready(function(){
	$("body").on("click",".text_edit",function(){
		active_element = $(this).parent().parent();
		get_text_settings();
	});
	$('body').on('click','.set_size_settings',function(){
		if($('#'+$(this).attr('aria-describedby')).length){
			$('.popover[id="'+$(this).attr('aria-describedby')+'"]').remove();
		} else {
			$(this).popover('show');
			$('.popover[id!="'+$(this).attr('aria-describedby')+'"]').remove();
		}
	});
	$('body').on('click','.set_font_settings',function(){
		if($('#'+$(this).attr('aria-describedby')).length){
			$('.popover[id="'+$(this).attr('aria-describedby')+'"]').remove();
		} else {
			$(this).popover('show');
			$('.popover[id!="'+$(this).attr('aria-describedby')+'"]').remove();
		}
	});
	storedSelections = [];
	$('body').on('click','.set_link_settings',function(){
		storedSelections = [];
		if(window.getSelection){
			currSelection = window.getSelection();
			for(i=0;i<currSelection.rangeCount;i++){
				storedSelections.push(currSelection.getRangeAt(i));
			}
		}
		parent.link_edit();
	});
	$("body").on("input",".editable",function(){
		$(active_element).children('.element_panel').css('margin-top',$(active_element).height());
	});
	$("body").on("click",".text_edit_done",function(){
		active_element = $(this).parent().parent();
		$(".editable").removeAttr('contenteditable');
		$(active_element).children('.element_panel').children('span').css('display','');
		$(active_element).children('.element_panel').children('.text_edit_done').css('display','none');
		$('.editor').css('display','none');
	});
	$("body").on("click",".set_bold",function(){
		document.execCommand('bold',false,null);
	});
	$("body").on("click",".set_italic",function(){
		document.execCommand('italic',false,null);
	});
	$("body").on("click",".set_align_left",function(){
		document.execCommand('justifyLeft',false,null);
	});
	$("body").on("click",".set_align_center",function(){
		document.execCommand('justifyCenter',false,null);
	});
	$("body").on("click",".set_align_right",function(){
		document.execCommand('justifyRight',false,null);
	});
	$("body").on("click",".set_align_justify",function(){
		document.execCommand('justifyFull',false,null);
	});
	$("body").on("click",".set_font",function(){
		WebFont.load({
			google: {
				families: [$(this).html()]
			}
		});
		document.execCommand('fontName',false,$(this).attr('data-font'));
		$('.popover[id="'+$(this).parent().parent().parent().attr('id')+'"]').remove();
	});
	$("body").on("click",".set_size",function(){
		document.execCommand("fontSize",false,"7");
	    fontElements = document.getElementsByTagName("font");
	    for(i=0;i<fontElements.length;++i){
	        if(fontElements[i].size=="7"){
	            fontElements[i].removeAttribute("size");
	            fontElements[i].style.fontSize = $(this).text()+"px";
	        }
	    }
	    $('.popover[id="'+$(this).parent().parent().parent().attr('id')+'"]').remove();
	});
	$("body").on("click",".reset_format",function(){
		document.execCommand("removeFormat",false,false);
	});
	$("body").on("dblclick",".editable",function(){
		active_element = $(this).parent();
		get_text_settings();
	});
	$(window).scroll(function(){
		if($('.editable[contenteditable=true]').height()>$(window.parent.document).height()){
			if($(window).scrollTop()>$(".editable[contenteditable=true]").offset().top){
				$('.editor').css('position','fixed');
				$('.editor').css('top','0');
				$('.editor').css('margin-top','0');
			} else {
				$('.editor').css('position','absolute');
				$('.editor').css('top','auto');
				$('.editor').css('margin-top','-'+($('.editor').height()+10));
			}
		} else {
			$('.editor').css('position','absolute');
			$('.editor').css('top','auto');
			$('.editor').css('margin-top','-'+($('.editor').height()+10));
		}
	});
});
function get_text_settings(){
	$(".editable").removeAttr('contenteditable');
	$(active_element).children('.editable').attr('contenteditable','true');
	$(active_element).children('.editable').focus();
	$('.element_panel > span').css('display','');
	$('.element_panel > .text_edit_done').css('display','none');
	$(active_element).children('.element_panel').children('span').css('display','none');
	$(active_element).children('.element_panel').children('.text_edit_done').css('display','');
	$(active_element).children('.element_panel').css('margin-top',$(active_element).height());
	if($('.editor').length==0){
		$('.editor',window.parent.document).insertBefore($(active_element).children('.editable'));
	} else {
		if($(active_element).children('.editor').length==0){
			$('.editor').insertBefore($(active_element).children('.editable'));
		}
	}
	$('.editor').css('width',$(active_element).children('.editable').css('width'));
	$(active_element).children('.editable').css('outline','none');
	if($('.editor').css('display')=='none'){
		$('.editor').css('display','');
	}
	if($(active_element).children('.editable').height()>$(window.parent.document).height()){
		if($(window).scrollTop()>$(active_element).children('.editable').offset().top){
			$('.editor').css('position','fixed');
			$('.editor').css('top','0');
			$('.editor').css('margin-top','0');
		} else {
			$('.editor').css('position','absolute');
			$('.editor').css('top','auto');
			$('.editor').css('margin-top','-'+($('.editor').height()+10));
		}
	} else {
		$('.editor').css('position','absolute');
		$('.editor').css('top','auto');
		$('.editor').css('margin-top','-'+($('.editor').height()+10));
	}
	$('.set_color').colpick({
		layout:'hex',
		submit:0,
		colorScheme:'dark',
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			document.execCommand('foreColor',false,'rgba('+rgb['r']+','+rgb['g']+','+rgb['b']+',1)');
		}
	}).keyup(function(){
		$(this).colpickSetColor(this.value);
	});
	$('.set_bg_color').colpick({
		layout:'hex',
		submit:0,
		colorScheme:'dark',
		onChange:function(hsb,hex,rgb,el,bySetColor) {
			document.execCommand('backColor',false,'rgba('+rgb['r']+','+rgb['g']+','+rgb['b']+',1)');
		}
	}).keyup(function(){
		$(this).colpickSetColor(this.value);
	});
	$('.set_size_settings').popover({
		placement:'bottom',
		html:true,
		trigger:'manuel',
		template:'<div class="popover" role="tooltip" style="z-index:99;"><div class="arrow"></div><div class="popover-content"></div></div>',
		content:'<div style="width:100px;"><a href="#" class="set_size" style="font-size:8px;">8</a><br /><a href="#" class="set_size" style="font-size:9px;">9</a><br /><a href="#" class="set_size" style="font-size:10px;">10</a><br /><a href="#" class="set_size" style="font-size:11px;">11</a><br /><a href="#" class="set_size" style="font-size:12px;">12</a><br /><a href="#" class="set_size" style="font-size:14px;">14</a><br /><a href="#" class="set_size" style="font-size:16px;">16</a><br /><a href="#" class="set_size" style="font-size:18px;">18</a><br /><a href="#" class="set_size" style="font-size:20px;">20</a><br /><a href="#" class="set_size" style="font-size:22px;">22</a><br /><a href="#" class="set_size" style="font-size:24px;">24</a><br /><a href="#" class="set_size" style="font-size:26px;">26</a><br /><a href="#" class="set_size" style="font-size:28px;">28</a><br /><a href="#" class="set_size" style="font-size:36px;">36</a><br /><a href="#" class="set_size" style="font-size:48px;">48</a><br /><a href="#" class="set_size" style="font-size:72px;">72</a></div>'
	});
	$('.set_font_settings').popover({
		placement:'bottom',
		html:true,
		trigger:'manuel',
		template:'<div class="popover" role="tooltip" style="z-index:99;"><div class="arrow"></div><div class="popover-content"></div></div>',
		content:function(){
			if(stored_font_data==''){
				get_font_list();
				return '<div id="font_list" style="width:150px;height:400px;overflow:scroll;"></div>';
			} else {
				return '<div id="font_list" style="width:150px;height:400px;overflow:scroll;">'+stored_font_data+'</div>';
			}
	    }
	});
}
stored_font_data = '';
function get_font_list(){
	if($('#button_change_font',window.parent.document).children('option').length==1){
		$.getJSON('https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyAWMs4UYX4dkcHl2HfIpshB3SrXhoL7HjU&sort=popularity',function(data){
			$.each(data.items,function(i){
				stored_font_data += '<a href="#" class="set_font" data-font="\''+data.items[i]['family']+'\', serif">'+data.items[i]['family']+'</a><br />';
				$('#font_list').append('<a href="#" class="set_font" data-font="\''+data.items[i]['family']+'\', serif">'+data.items[i]['family']+'</a><br />');
				$('#menu_change_font',window.parent.document).append('<option value="\''+data.items[i]['family']+'\', serif">'+data.items[i]['family']+'</option>');
				$('#button_change_font',window.parent.document).append('<option value="\''+data.items[i]['family']+'\', serif">'+data.items[i]['family']+'</option>');
				$('#text_change_font',window.parent.document).append('<option value="\''+data.items[i]['family']+'\', serif">'+data.items[i]['family']+'</option>');
			});
			stored_font_data += '<a href="#" class="set_font" data-font="Georgia, serif">Georgia</a><br /><a href="#" class="set_font" data-font="\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif">Palatino</a><br /><a href="#" class="set_font" data-font="\'Times New Roman\', Times, serif">Times New Roman</a><br /><a href="#" class="set_font" data-font="Arial, Helvetica, sans-serif">Arial</a><br /><a href="#" class="set_font" data-font="\'Arial Black\', Gadget, sans-serif">Arial Black</a><br /><a href="#" class="set_font" data-font="\'Comic Sans MS\', cursive, sans-serif">Comic Sans MS</a><br /><a href="#" class="set_font" data-font="Impact, Charcoal, sans-serif">Impact</a><br /><a href="#" class="set_font" data-font="\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif">Lucida Sans Unicode</a><br /><a href="#" class="set_font" data-font="Tahoma, Geneva, sans-serif">Tahoma</a><br /><a href="#" class="set_font" data-font="\'Trebuchet MS\', Helvetica, sans-serif">Trebuchet MS</a><br /><a href="#" class="set_font" data-font="Verdana, Geneva, sans-serif">Verdana</a><br /><a href="#" class="set_font" data-font="\'Courier New\', Courier, monospace">Courier New</a><br /><a href="#" class="set_font" data-font="\'Lucida Console\', Monaco, monospace">Lucida Console</a>';
			$('#font_list').append('<a href="#" class="set_font" data-font="Georgia, serif">Georgia</a><br /><a href="#" class="set_font" data-font="\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif">Palatino</a><br /><a href="#" class="set_font" data-font="\'Times New Roman\', Times, serif">Times New Roman</a><br /><a href="#" class="set_font" data-font="Arial, Helvetica, sans-serif">Arial</a><br /><a href="#" class="set_font" data-font="\'Arial Black\', Gadget, sans-serif">Arial Black</a><br /><a href="#" class="set_font" data-font="\'Comic Sans MS\', cursive, sans-serif">Comic Sans MS</a><br /><a href="#" class="set_font" data-font="Impact, Charcoal, sans-serif">Impact</a><br /><a href="#" class="set_font" data-font="\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif">Lucida Sans Unicode</a><br /><a href="#" class="set_font" data-font="Tahoma, Geneva, sans-serif">Tahoma</a><br /><a href="#" class="set_font" data-font="\'Trebuchet MS\', Helvetica, sans-serif">Trebuchet MS</a><br /><a href="#" class="set_font" data-font="Verdana, Geneva, sans-serif">Verdana</a><br /><a href="#" class="set_font" data-font="\'Courier New\', Courier, monospace">Courier New</a><br /><a href="#" class="set_font" data-font="\'Lucida Console\', Monaco, monospace">Lucida Console</a>');
			$('#menu_change_font',window.parent.document).append('<option value="Georgia, serif">Georgia</option><option value="\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif">Palatino</option><option value="\'Times New Roman\', Times, serif">Times New Roman</option><option value="Arial, Helvetica, sans-serif">Arial</option><option value="\'Arial Black\', Gadget, sans-serif">Arial Black</option><option value="\'Comic Sans MS\', cursive, sans-serif">Comic Sans MS</option><option value="Impact, Charcoal, sans-serif">Impact</option><option value="\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif">Lucida Sans Unicode</option><option value="Tahoma, Geneva, sans-serif">Tahoma</option><option value="\'Trebuchet MS\', Helvetica, sans-serif">Trebuchet MS</option><option value="Verdana, Geneva, sans-serif">Verdana</option><option value="\'Courier New\', Courier, monospace">Courier New</option><option value="\'Lucida Console\', Monaco, monospace">Lucida Console</option>');
			$('#button_change_font',window.parent.document).append('<option value="Georgia, serif">Georgia</option><option value="\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif">Palatino</option><option value="\'Times New Roman\', Times, serif">Times New Roman</option><option value="Arial, Helvetica, sans-serif">Arial</option><option value="\'Arial Black\', Gadget, sans-serif">Arial Black</option><option value="\'Comic Sans MS\', cursive, sans-serif">Comic Sans MS</option><option value="Impact, Charcoal, sans-serif">Impact</option><option value="\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif">Lucida Sans Unicode</option><option value="Tahoma, Geneva, sans-serif">Tahoma</option><option value="\'Trebuchet MS\', Helvetica, sans-serif">Trebuchet MS</option><option value="Verdana, Geneva, sans-serif">Verdana</option><option value="\'Courier New\', Courier, monospace">Courier New</option><option value="\'Lucida Console\', Monaco, monospace">Lucida Console</option>');
			$('#text_change_font',window.parent.document).append('<option value="Georgia, serif">Georgia</option><option value="\'Palatino Linotype\', \'Book Antiqua\', Palatino, serif">Palatino</option><option value="\'Times New Roman\', Times, serif">Times New Roman</option><option value="Arial, Helvetica, sans-serif">Arial</option><option value="\'Arial Black\', Gadget, sans-serif">Arial Black</option><option value="\'Comic Sans MS\', cursive, sans-serif">Comic Sans MS</option><option value="Impact, Charcoal, sans-serif">Impact</option><option value="\'Lucida Sans Unicode\', \'Lucida Grande\', sans-serif">Lucida Sans Unicode</option><option value="Tahoma, Geneva, sans-serif">Tahoma</option><option value="\'Trebuchet MS\', Helvetica, sans-serif">Trebuchet MS</option><option value="Verdana, Geneva, sans-serif">Verdana</option><option value="\'Courier New\', Courier, monospace">Courier New</option><option value="\'Lucida Console\', Monaco, monospace">Lucida Console</option>');
		});
	}
}
function change_text_font(value){
	$(active_element).children('div').last().css('color',value);
	apply_to_all('text','color',value);
}
function set_text_font(value,font_name){
	WebFont.load({
		google: {
			families: [font_name]
		}
	});
	$(active_element).children('div').last().css('font-family',value);
	apply_to_all('text','font-family',value);
}
function set_text_size(value){
	$(active_element).children('div').last().css('font-size',value);
	apply_to_all('text','font-size',value);
}