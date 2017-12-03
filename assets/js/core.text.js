$(document).ready(function(){
	$("body").on("click",".text_edit",function(){
		active_element = $(this).parent().parent();
		get_text_settings();
	});
	$("body").on("input",".editable",function(){
		$(active_element).children('.element_panel').css('margin-top',$(active_element).css('height'));
	});
	$("body").on("click",".text_edit_done",function(){
		active_element = $(this).parent().parent();
		$(".editable").removeAttr('contenteditable');
		$(active_element).children('.element_panel').children('span').show();
		$(active_element).children('.element_panel').children('.text_edit_done').hide();
	});
	$("body").on("dblclick",".editable",function(){
		active_element = $(this).parent();
		get_text_settings();
	});
});
function get_text_settings(){
	$(".editable").removeAttr('contenteditable');
	$(active_element).children('.editable').attr('contenteditable','true');
	$(active_element).children('.editable').focus();
	$('.element_panel > span').show();
	$('.element_panel > .text_edit_done').hide();
	$(active_element).children('.element_panel').children('span').hide();
	$(active_element).children('.element_panel').children('.text_edit_done').show();
	$(active_element).children('.element_panel').css('margin-top',$(active_element).css('height'));
	$(active_element).children('.editable').css('outline','none');
	tinymce.init({
        selector: '.editable',
        menubar: false,
        plugins: "colorpicker,textcolor",
        font_formats: 'Abril Fatface=Abril Fatface,cursive;Alegreya=Alegreya,serif;Alfa Slab One=Alfa Slab One,cursive;Amatic SC=Amatic SC,cursive;Amiri=Amiri,serif;Anton=Anton,sans-serif;Arimo=Arimo,sans-serif;Audiowide=Audiowide,cursive;Bangers=Bangers,cursive;Berkshire Swash=Berkshire Swash,cursive;Bevan=Bevan,cursive;Bitter=Bitter,serif;Bree Serif=Bree Serif,serif;Cabin=Cabin,sans-serif;Cardo=Cardo,serif;Catamaran=Catamaran,sans-serif;Caveat Brush=Caveat Brush,cursive;Caveat=Caveat,cursive;Cinzel=Cinzel,serif;Coda=Coda,cursive;Comfortaa=Comfortaa,cursive;Concert One=Concert One,cursive;Cormorant Garamond=Cormorant Garamond,serif;Courgette=Courgette,cursive;Crete Round=Crete Round,serif;Dancing Script=Dancing Script,cursive;Domine=Domine,serif;Dosis=Dosis,sans-serif;EB Garamond=EB Garamond,serif;Exo 2=Exo 2,sans-serif;Fira Sans=Fira Sans,sans-serif;Fjalla One=Fjalla One,sans-serif;Forum=Forum,cursive;Glegoo=Glegoo,serif;Great Vibes=Great Vibes,cursive;Hind=Hind,sans-serif;Inconsolata=Inconsolata,monospace;Josefin Sans=Josefin Sans,sans-serif;Kalam=Kalam,cursive;Kaushan Script=Kaushan Script,cursive;Lato=Lato,sans-serif;Libre Baskerville=Libre Baskerville,serif;Lobster=Lobster,cursive;Lora=Lora,serif;Marck Script=Marck Script,cursive;Merienda=Merienda,cursive;Merriweather=Merriweather,serif;Montserrat=Montserrat,sans-serif;Muli=Muli,sans-serif;Neuton=Neuton,serif;Noticia Text=Noticia Text,serif;Noto Sans=Noto Sans,sans-serif;Noto Serif=Noto Serif,serif;Nunito=Nunito,sans-serif;Old Standard TT=Old Standard TT,serif;Oleo Script=Oleo Script,cursive;Open Sans Condensed=Open Sans Condensed,sans-serif;Open Sans=Open Sans,sans-serif;Oswald=Oswald,sans-serif;Overlock=Overlock,cursive;Oxygen=Oxygen,sans-serif;Pacifico=Pacifico,cursive;Passion One=Passion One,cursive;Patrick Hand=Patrick Hand,cursive;Playball=Playball,cursive;Playfair Display SC=Playfair Display SC,serif;Playfair Display=Playfair Display,serif;Poiret One=Poiret One,cursive;Poppins=Poppins,sans-serif;Press Start 2P=Press Start 2P,cursive;PT Mono=PT Mono,monospace;PT Sans Narrow=PT Sans Narrow,sans-serif;PT Sans=PT Sans,sans-serif;PT Serif=PT Serif,serif;Quattrocento=Quattrocento,serif;Quicksand=Quicksand,sans-serif;Raleway=Raleway,sans-serif;Righteous=Righteous,cursive;Roboto Condensed=Roboto Condensed,sans-serif;Roboto Mono=Roboto Mono,monospace;Roboto Slab=Roboto Slab,serif;Roboto=Roboto,sans-serif;Rokkitt=Rokkitt,serif;Sacramento=Sacramento,cursive;Sanchez=Sanchez,serif;Shadows Into Light Two=Shadows Into Light Two,cursive;Shrikhand=Shrikhand,cursive;Sigmar One=Sigmar One,cursive;Slabo 27px=Slabo 27px,serif;Sorts Mill Goudy=Sorts Mill Goudy,serif;Source Code Pro=Source Code Pro,monospace;Source Sans Pro=Source Sans Pro,sans-serif;Source Serif Pro=Source Serif Pro,serif;Tinos=Tinos,serif;Titillium Web=Titillium Web,sans-serif;Ubuntu=Ubuntu,sans-serif;Unica One=Unica One,cursive;Vollkorn=Vollkorn,serif;VT323=VT323,monospace;Work Sans=Work Sans,sans-serif',
        fontsize_formats: '8pt 10pt 12pt 14pt 18pt 24pt 36pt',
        inline: true,
        toolbar: 'bold italic | alignleft aligncenter alignright alignjustify | advanced_link | advanced_image | fontselect fontsizeselect formatselect forecolor removeformat',
        setup: function(editor) {
            editor.addButton('advanced_link', {
                icon: 'link',
                tooltip: "Link",
                onclick: function () {
                    storedSelections = [];
                    if(window.getSelection){
                        currSelection = window.getSelection();
                        for(i=0;i<currSelection.rangeCount;i++){
                            storedSelections.push(currSelection.getRangeAt(i));
                        }
                    }
                    parent.link_edit();
                }
            });
            editor.addButton('advanced_image', {
                icon: 'image',
                tooltip: "Image",
                onclick: function () {
                    parent.image_edit('text');
                }
            });
			editor.on('change', function(e) {
				if(e.originalEvent && e.originalEvent.command=='FontName'){
					font_name = e.originalEvent.value.split(',')[0];
					WebFont.load({
						google: {
							families: [font_name]
						}
					});
				}
			});
        }
    });
}
function change_text_font(value){
	$(active_element).children('div').last().find('a').css('color',value);
	apply_to_all('text','color',value);
}
function set_text_font(value,font_name){
	WebFont.load({
		google: {
			families: [font_name]
		}
	});
	$(active_element).children('div').last().find('a').css('font-family',value);
	apply_to_all('text','font-family',value);
}
function set_text_size(value){
	$(active_element).children('div').last().find('a').css('font-size',value+'px');
	apply_to_all('text','font-size',value+'px');
}