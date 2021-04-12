(function($) { 
  "use strict";

  
$("body").on("click",".extra_option_select_ui li",function(e){
e.preventDefault;
var selected_id = $(this).attr('value');
var is_selected = false;
if($(this).attr('selected') =="selected")
{
	is_selected = true;
	$(this).attr("selected",false);

	//$(this).parent().next().children( '[name^="extra_options_select"] option[value="'+selected_id+'"]' ).attr("selected",false);
	

	//$('[name^="extra_options_select"] option[value="'+selected_id+'"]').attr("selected",false);
}
else
{
	$(this).attr("selected","selected");

   	is_selected = false;

   //	$(this).parent().next().children( '[name^="extra_options_select"] option[value="'+selected_id+'"]' ).attr("selected","selected");

	//$('[name^="extra_options_select"] option[value="'+selected_id+'"]').attr("selected","selected");


}
	$(this).parent().next().children( '[name^="extra_options_select"]' ).html();
   
   // $('[name^="extra_options_select"]').html("");


 var options_html = '';
	$(this).parent().children('li[selected="selected"]').each(function () {

//$('.extra_option_select_ui').children('li[selected="selected"]').each(function () {
    //alert(this.value); // "this" is the current element in the loop
     options_html += '<option value="'+$(this).attr('value')+'" selected="selected">'+$(this).text()+'</option>';
});
    $(this).parent().next().children( '[name^="extra_options_select"]' ).html(options_html);

    //$('[name^="extra_options_select"] option[value="22"]').attr("selected","selected");
 $(this).parent().next().children( '[name^="extra_options_select"]' ).change();



});

$(document).ready(woofood_load_scripts);
$(document).ajaxComplete(woofood_load_scripts);


function woofood_load_scripts(){
  


    jQuery(".extra_option_select_ui").sortable({
      revert: true,
    stop: function(event, ui) {  
     console.log(ui.item.index());
     console.log(this);

      	//$('[name^="extra_options_select"]').html("");
        $(this).next().children( '[name^="extra_options_select"]' ).html("");

 var options_html = '';
	 $(this).children('li[selected="selected"]').each(function () {
	//$('.extra_option_select_ui').children('li[selected="selected"]').each(function () {
    //alert(this.value); // "this" is the current element in the loop
     options_html += '<option value="'+$(this).attr('value')+'"" selected="selected">'+$(this).text()+'</option>';
});
  

  //  $('[name^="extra_options_select"]').html(options_html);

  $(this).next().children( '[name^="extra_options_select"]' ).html(options_html);
 $(this).next().children( '[name^="extra_options_select"]' ).change();





    }
});




}






})(jQuery);