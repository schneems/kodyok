$(document).ready(function(){
	$("body").on("click",".send_button",function(){
		form_error = 0;
		$(active_element).children('.form_content').children().each(function(){
			if($(this).children('.form-group').children('input').val()==''){
				form_error++;
			}
		});
		if(form_error>0){
			alert('This fields are required.');
		} else {
			dataString = 'data=';
			$(active_element).children('.form_content').children().each(function(){
				dataString += $(this).children('.form-group').children('input').attr('placeholder')+'{}'+$(this).children('.form-group').children('input').val()+'[]';
			});
			$.ajax({
				type: "POST",
				url: "form.php?id="+website_id,
				data: dataString,
				success: function(html) {
					alert('Success!');
				}
			});
			return false;
		}
	});
	$('body').on('click','.form_edit',function(){
		active_element = $(this).parent().parent();
		$('#iframe',window.parent.document).css('width',$('#all_content',window.parent.document).width()-380);
		$('#settings_menu',window.parent.document).show();
		$('#settings_menu > div',window.parent.document).hide();
		$('#form_edit_settings',window.parent.document).show();
		$('#content_send_button',window.parent.document).attr('style','');
		$("#content_send_button",window.parent.document).val($(active_element).children('.form-group').children('a').html());
		a = 0;
		$("#form_inputs",window.parent.document).html('');
		$(active_element).children('.form_content').children().each(function(){
			$("#form_inputs",window.parent.document).append('<div id="form_input_'+a+'" style="border:1px solid #CCC;padding:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+$(active_element).children('.form_content').children('.form-group').eq(a).children('input').attr('placeholder')+'<span class="fa fa-pencil form_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_form" style="font-size:18px;margin-left:10px;"></span></div>');
			a++;
		});
		$("#form_inputs",window.parent.document).sortable({
			update:function(event,ui){
				order = $("#form_inputs",window.parent.document).sortable('toArray').toString().split(',');
	    		form_content = '';
	    		form_content_content = '';
	    		for(a=0;a<order.length;a++){
	    			form_id = order[a].split('form_input_');
	    			form_content += '<div class="form-group">'+$(active_element).children('.form_content').children('.form-group').eq(form_id[1]).html()+'</div>';
	    			form_content_content += '<div id="form_input_'+a+'" style="border:1px solid #CCC;padding:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+$(active_element).children('.form_content').children('.form-group').eq(form_id[1]).children('input').attr('placeholder')+'<span class="fa fa-pencil form_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_form" style="font-size:18px;margin-left:10px;"></span></div>';
	    		}
	    		$(active_element).children('.form_content').html(form_content);
	    		$('#form_inputs',window.parent.document).html(form_content_content);
			},tolerance:'pointer',cursor:'move',axis:'y'
		});
	});
});
function add_form(value){
	if($('#input_name',window.parent.document).val()==''){
		$('#input_name',window.parent.document).attr('placeholder','This field is required.');
		$('#form_edit_settings > .input-group',window.parent.document).attr('class','input-group has-error');
	} else {
		if($('.add_form',window.parent.document).html()=='Add'){
			next_item_id = $(active_element).children('.form_content').children('.form-group').length;
			$(active_element).children('.form_content').append('<div class="form-group"><input type="text" class="form-control" placeholder="'+$('#input_name',window.parent.document).val()+'"></div>');
			$("#form_inputs",window.parent.document).append('<div id="form_input_'+next_item_id+'" style="border:1px solid #CCC;padding:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+$('#input_name',window.parent.document).val()+'<span class="fa fa-pencil form_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_form" style="font-size:18px;margin-left:10px;"></span></div>');
		} else if($('.add_form',window.parent.document).html()=='Save'){
			$(active_element).children('.form_content').children('.form-group').eq(value).children('input').attr('placeholder',$('#input_name',window.parent.document).val());
			$("#form_inputs",window.parent.document).children('div').eq(value).html('<span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+$('#input_name',window.parent.document).val()+'<span class="fa fa-pencil form_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_form" style="font-size:18px;margin-left:10px;"></span>');
			$('.add_form',window.parent.document).html('Add');
		}
		$('#input_name',window.parent.document).val('');
		$('#input_name',window.parent.document).attr('placeholder','New input');
		$('#form_edit_settings > .input-group',window.parent.document).attr('class','input-group');
		$("#form_inputs",window.parent.document).sortable({
			update:function(event,ui){
				order = $("#form_inputs",window.parent.document).sortable('toArray').toString().split(',');
        		form_content = '';
        		form_content_content = '';
        		for(a=0;a<order.length;a++){
        			form_id = order[a].split('form_input_');
        			form_content += '<div class="form-group">'+$(active_element).children('.form_content').children('.form-group').eq(form_id[1]).html()+'</div>';
        			form_content_content += '<div id="form_input_'+a+'" style="border:1px solid #CCC;padding:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+$(active_element).children('.form_content').children('.form-group').eq(form_id[1]).children('input').attr('placeholder')+'<span class="fa fa-pencil form_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_form" style="font-size:18px;margin-left:10px;"></span></div>';
        		}
        		$(active_element).children('.form_content').html(form_content);
        		$('#form_inputs',window.parent.document).html(form_content_content);
			},tolerance:'pointer',cursor:'move',axis:'y'
		});
		$(active_element).children('.element_panel').css('margin-top',$(active_element).height());
	}
}
function content_send_button(){
	$(active_element).children('.form-group').children('a').html($("#content_send_button",window.parent.document).val());
}
function remove_form(value){
	$(active_element).children('.form_content').children('.form-group').eq(value).remove();
	$('#form_input_'+value,window.parent.document).remove();
	a = 0;
	$("#form_inputs",window.parent.document).html('');
	$(active_element).children('.form_content').children().each(function(){
		$("#form_inputs",window.parent.document).append('<div id="form_input_'+a+'" style="border:1px solid #CCC;padding:10px;"><span class="fa fa-sort" style="font-size:18px;margin-right:10px;"></span>'+$(active_element).children('.form_content').children('.form-group').eq(a).children('input').attr('placeholder')+'<span class="fa fa-pencil form_edit_item" style="font-size:18px;margin-left:10px;"></span><span class="fa fa-trash remove_form" style="font-size:18px;margin-left:10px;"></span></div>');
		a++;
	});
}
function form_edit_item(value){
	$('#input_name',window.parent.document).val($(active_element).children('.form_content').children('.form-group').eq(value).children('input').attr('placeholder'));
	$('.add_form',window.parent.document).html('Save');
}