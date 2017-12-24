function dialog(object2)
{
	if(typeof object2 !="object")
	{
		object2 ={};
	}
	var  object1 ={
		message:"",
		title:"系統提示訊息",
		buttons: [
			{
			  text: "close",
			  click: function() {
				$( this ).dialog( "close" );
			  }
			}
		]
	};
	$.extend( object1, object2 );
	$( "#dialog").attr('title', object1.title); 
	$( "#dialog p").text(object1.message); 
	$( "#dialog" ).dialog(object1);
}
(function() {
    "use strict";

    // custom scrollbar

    $("html").niceScroll({styler:"fb",cursorcolor:"#1ABC9C", cursorwidth: '6', cursorborderradius: '10px', background: '#424f63', spacebarenabled:false, cursorborder: '0',  zindex: '1000'});

    $(".scrollbar1").niceScroll({styler:"fb",cursorcolor:"rgba(97, 100, 193, 0.78)", cursorwidth: '6', cursorborderradius: '0',autohidemode: 'false', background: '#F1F1F1', spacebarenabled:false, cursorborder: '0'});

	
	
    $(".scrollbar1").getNiceScroll();
    if ($('body').hasClass('scrollbar1-collapsed')) {
        $(".scrollbar1").getNiceScroll().hide();
    }

})(jQuery);

                     
     
  