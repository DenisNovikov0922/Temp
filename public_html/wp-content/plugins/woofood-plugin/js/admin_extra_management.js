



  jQuery(document).ready(function($){



     $(document).on('submit','.wf_extra_manage_new_option_form', function(e){

       e.preventDefault();

      //  var button = $(this).find('button');
      //      button.button('loading');

      console.log($('.wf_extra_manage_new_option_form').serialize());

        $.post(woofoodextramng.ajaxurl, $('.wf_extra_manage_new_option_form').serialize(), function(data){
            
            var obj = data;

         //   $('#ajax-order-list').html(obj);
             $('.wf_extra_options_list').append(obj);


               var extra_options_order="";
    jQuery(".wf_extra_options_list li").each(function(i) {
        if (extra_options_order=='')
            extra_options_order = jQuery(this).attr('item-id');
        else
            extra_options_order += "," + jQuery(this).attr('item-id');
    });

    var data = {};
        data["action"] = "wf_extra_option_update_order_ajax";
        data["wf_extra_order"] = extra_options_order ;

    if(extra_options_order!="")
    {
         jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      dataType: 'json',
      success: function(response) {
                   
                    console.log(response);

      }
    });   
    }

             $('.wf_extra_options_list').sortable('refresh');
            

            
        });

    });






jQuery(document).on('click', '.wf_add_extra_option_category_btn', function(){
      var id = "new";

    
      if( jQuery('.wf_extra_option_edit_popup').hasClass("show"))
      {
        jQuery('.wf_extra_option_edit_popup').toggleClass("show");
      }

        jQuery('.woofood-overlay').addClass("show");

      var vthis = jQuery(this);
        var data = {};
        data["action"] = "wf_extra_option_category_open_popup_ajax";
        data["wf_extra_category_id"] = id ;
        



         jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      success: function(response) {
              wf_refresh_extra_option_categories();

                  jQuery('.wf_extra_option_edit_popup').html(response);

                    jQuery('.wf_extra_option_edit_popup').toggleClass("show");
                    //jQuery(vthis).parent().parent().parent().replaceWith(response);

      }
    });   


    });


      function wf_refresh_extra_option_categories()
{
                                                  jQuery('.woofood-overlay').addClass("show");

   var data = {};
        data["action"] = "wf_extra_option_categories_refresh";

    jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      success: function(response) {


                  jQuery('.wf_extra_options_content').html(response);
                jQuery('.woofood-overlay').removeClass("show");


      }
    });  
}


  jQuery(document).on('click', '.wf_extra_option_list_item .edit', function(){

      jQuery(this).parent().parent().next().toggleClass('show');



    });



    jQuery(document).on('click', '.wf_extra_option_list_item_settings .save_option', function(){

    	var title = jQuery(this).parent().parent().find("input[name='name']").val();
    	var price = jQuery(this).parent().parent().find("input[name='extra_option_price']").val();
      var prechecked = jQuery(this).parent().parent().find("input[name='prechecked']:checked").val();



    	var vthis = jQuery(this);
     		var extra_option_id = jQuery(this).attr("item-id");
     		var data = {};
     		data["action"] = "wf_extra_option_update_ajax";
     		data["extra_option_id"] = extra_option_id ;
     		data["extra_option_title"] = title ;
     		data["extra_option_price"] = price ;
        data["prechecked"] = prechecked ;

     		console.log(jQuery(data).serializeArray());



         jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      success: function(response) {
                   
                    jQuery(vthis).parent().parent().parent().replaceWith(response);

      }
    });   


    });
jQuery(document).on('click', '.wf_extra_option_edit_popup_header_close', function(){
                                      	jQuery('.wf_extra_option_edit_popup').toggleClass("show");
                                        jQuery('.woofood-overlay').removeClass("show");

});

      


    jQuery(document).on('change', '#wf_extra_option_category_type', function(){

    var type =  jQuery(this).val();
    if(type=="checkbox-limitedchoice")
    {
      jQuery('.wf_maximum_options_wrapper').removeClass("hidden");
            jQuery('.wf_minimum_options_wrapper').removeClass("hidden");

    }
    else if(type=="checkbox-multiplechoice")
    {
      jQuery('.wf_maximum_options_wrapper').removeClass("hidden");
                  jQuery('.wf_minimum_options_wrapper').removeClass("hidden");


    }
    else
    {
            jQuery('.wf_maximum_options_wrapper').addClass("hidden");
            jQuery('.wf_minimum_options_wrapper').addClass("hidden");

    }

});





    jQuery(document).on('click', '.wf_extra_option_category_save', function(){
    	var id = jQuery(this).attr("term-id");

    	var title = jQuery("#wf_extra_option_category_title").val();
    	var type = jQuery("#wf_extra_option_category_type option:selected").val();
    	var style = jQuery("#wf_extra_option_category_style option:selected").val();
    	var maximum_options = jQuery("#wf_extra_option_category_maximum_options").val();
      var global_categories = jQuery("#wf_extra_option_global_categories").val();
      var required = jQuery("#wf_extra_option_category_required:checked").val();
      var minimum_options = jQuery("#wf_extra_option_category_minimum_options").val();
      var hide_prices = jQuery("#wf_extra_option_category_hide_prices:checked").val();



    	var vthis = jQuery(this);
     		var data = {};
     		data["action"] = "wf_extra_option_category_update_ajax";
     		data["wf_extra_category_id"] = id ;
     		data["wf_extra_category_title"] = title ;
     		data["wf_extra_category_style"] = style ;
     		data["wf_extra_category_type"] = type ;
     		data["wf_extra_category_maximum_options"] = maximum_options ;
        data["wf_extra_category_global_categories"] = global_categories ;
        data["wf_extra_option_category_required"] = required ;
        data["wf_extra_category_minimum_options"] = minimum_options ;
        data["wf_extra_option_category_hide_prices"] = hide_prices ;

     		console.log(data);




         jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      success: function(response) {
                   
                                      	jQuery('.wf_extra_option_edit_popup').toggleClass("show");
                                        wf_refresh_extra_option_categories();


      }
    });   


    });




     jQuery(document).on('click', '.wf_extra_option_category_list_item .wf_extra_option_category.edit', function(){
    	var id = jQuery(this).attr("cat-id");

    
    	if(	jQuery('.wf_extra_option_edit_popup').hasClass("show"))
    	{
    		jQuery('.wf_extra_option_edit_popup').toggleClass("show");
    	}




    	var vthis = jQuery(this);
     		var data = {};
     		data["action"] = "wf_extra_option_category_open_popup_ajax";
     		data["wf_extra_category_id"] = id ;
     		                                        jQuery('.woofood-overlay').addClass("show");




         jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      success: function(response) {
                                      	jQuery('.wf_extra_option_edit_popup').html(response);
                  jQuery('#wf_extra_option_global_categories').select2();


                   	jQuery('.wf_extra_option_edit_popup').toggleClass("show");
                    //jQuery(vthis).parent().parent().parent().replaceWith(response);

      }
    });   


    });



 





      jQuery(document).on('click', '.wf_extra_option_category_list_item .wf_extra_option_category.delete', function(){
      var id = jQuery(this).attr("cat-id");

    
      if( jQuery('.wf_extra_option_edit_popup').hasClass("show"))
      {
        jQuery('.wf_extra_option_edit_popup').toggleClass("show");
      }
                                                jQuery('.woofood-overlay').addClass("show");


      var vthis = jQuery(this);
        var data = {};
        data["action"] = "wf_extra_option_category_delete";
        data["wf_extra_category_id"] = id ;
        



         jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      success: function(response) {
                                        
                      wf_refresh_extra_option_categories();
                    //jQuery(vthis).parent().parent().parent().replaceWith(response);

      }
    });   


    });



      
      jQuery(document).on('click', '.wf_extra_option_category_remove', function(){
      var id = jQuery(this).attr("cat-id");

    
      if( jQuery('.wf_extra_option_edit_popup').hasClass("show"))
      {
        jQuery('.wf_extra_option_edit_popup').toggleClass("show");
      }


      var vthis = jQuery(this);
        var data = {};
        data["action"] = "wf_extra_option_category_delete";
        data["wf_extra_category_id"] = id ;
        



         jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      success: function(response) {
                                        
                      wf_refresh_extra_option_categories();
                    //jQuery(vthis).parent().parent().parent().replaceWith(response);

      }
    });   


    });



    

     jQuery(document).on('click', '.wf_extra_option_list_item .remove', function(){

     		var vthis = jQuery(this);
     		var extra_option_id = jQuery(this).attr("id");
     		var data = {};
     		data["action"] = "wf_extra_option_remove_ajax";
     		data["extra_option_id"] = extra_option_id ;
     		console.log(jQuery(data).serializeArray());



         jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      dataType: 'json',
      success: function(response) {
                   
                    jQuery(vthis).parent().parent().parent().remove();

      }
    });     



    });


      jQuery(document).on('click', '.wf-add-extra-button', function(){

      jQuery(this).parent().next().toggleClass('show');

    });



jQuery('.wf_extra_option_list_item .remove').on('click', function(){

    

    });


  });


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
  


    jQuery(".wf_extra_options_list").sortable({
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





    },
    start: function(event, ui){
          console.log(ui.item.index());


    },
    update: function(event, ui){
    	     console.log(ui.item.index());
    	     console.log(event);


    	      var extra_options_order="";
    jQuery(".wf_extra_options_list li").each(function(i) {
        if (extra_options_order=='')
            extra_options_order = jQuery(this).attr('item-id');
        else
            extra_options_order += "," + jQuery(this).attr('item-id');
    });

    var data = {};
     		data["action"] = "wf_extra_option_update_order_ajax";
     		data["wf_extra_order"] = extra_options_order ;

    if(extra_options_order!="")
    {
    	   jQuery.ajax({
      type: 'POST',
      url: woofoodextramng.ajaxurl,
      data: data,
      dataType: 'json',
      success: function(response) {
                   
                    console.log(response);

      }
    });   
    }







    }
});




}






})(jQuery);