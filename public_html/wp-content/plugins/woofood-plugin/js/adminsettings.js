 jQuery(document).ready(function($){

 	var woofood_delivery_fee_type =  jQuery('#woofood_delivery_fee_type').val();
 	if(woofood_delivery_fee_type =="distance")
 	{
 		jQuery('.woofood_distance_based_fees').addClass("show");
 		jQuery('#woofood_delivery_fee').addClass("hidden");



 	}
 	else
 	{

 	}
 	jQuery(document).on('change', '#woofood_delivery_fee_type', function(event)
 		{

			woofood_delivery_fee_type = jQuery(this).val();
 			if(woofood_delivery_fee_type =="distance")
 	{
 		jQuery('.woofood_distance_based_fees').addClass("show");
 		jQuery('#woofood_delivery_fee').addClass("hidden");

 	}
 	else
 		{
 			 		jQuery('.woofood_distance_based_fees').removeClass("show");
 			 		 		jQuery('#woofood_delivery_fee').removeClass("hidden");


 		}




 		});


 	jQuery(document).on('click', '.woofood_distance_fee_add', function(event)
 		{

			var  woofood_km_new_from = jQuery('#woofood_km_new_from').val();
		    var  woofood_km_new_to = jQuery('#woofood_km_new_to').val();

			var  woofood_fee_new = jQuery('#woofood_fee_new').val();

			if(woofood_fee_new && woofood_fee_new)
			{
				var element = distance_fee_element.replace('%%km_from%%', woofood_km_new_from).replace('%%km_to%%', woofood_km_new_to).replace('%%charge%%', woofood_fee_new);
					jQuery('.woofood_distance_based_fees_list').append(element);
				jQuery('#woofood_km_new_from').val("");
								jQuery('#woofood_km_new_to').val("");

				jQuery('#woofood_fee_new').val("");
			}



 //alert(JSON.stringify(distance_object_array, escape_json));		


woofood_update_distance_based();

 		});



 		jQuery(document).on('click', '.woofood_distance_fee_delete', function(event)
 		{
 			jQuery(this).parent().remove();
 			
woofood_update_distance_based();




 		});


 		jQuery(document).on('change', '.woofood_distance_based_fees_item input', function(event)
 		{
 			
woofood_update_distance_based();




 		});





function escape_json (key, val) {
    if (typeof(val)!="string") return val;
    return val      
        .replace(/[\\]/g, '\\\\')
        .replace(/[\/]/g, '\\/')
        .replace(/[\b]/g, '\\b')
        .replace(/[\f]/g, '\\f')
        .replace(/[\n]/g, '\\n')
        .replace(/[\r]/g, '\\r')
        .replace(/[\t]/g, '\\t')
        .replace(/[\"]/g, '\\"')
        .replace(/\\'/g, "\\'"); 
}

function woofood_update_distance_based()
{

var distance_object_array =[];

jQuery('input[name^="km_from"]').each(function(i, val) {
 		var km_from =  parseFloat(jQuery(this).val()); 
 		var km_to  = null;
 		var fee  = null;

    jQuery('input[name^="km_to"]').each(function(i_2, val_2) {
     if( i == i_2) {  
 		 km_to =  parseFloat(jQuery(this).val()); 

     	console.log(jQuery(this).val());  
     }  

      });


    jQuery('input[name^="charge"]').each(function(i_2, val_2) {
     if( i == i_2) {  
 		 fee =  parseFloat(jQuery(this).val()); 

     	console.log(jQuery(this).val());  
     }  

      });

    var distance_object = {km_from:km_from, km_to:km_to, fee:fee};
    distance_object_array.push(distance_object);

});
jQuery('#woofood_delivery_fee_distance_based').val(JSON.stringify(distance_object_array, escape_json))
}
 	



});
  


