 jQuery(document).ready(function($){


var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();


  if(woofood_order_type=="pickup")
  {
           
        jQuery('#billing_country_field').css("display", "none");

        jQuery('#billing_address_1_field').css("display", "none");
        jQuery('#billing_address_2_field').css("display", "none");
        jQuery('#billing_city_field').css("display", "none");
        jQuery('#billing_state_field').css("display", "none");
        jQuery('#billing_postcode_field').css("display", "none");
        jQuery('#billing_county_field').css("display", "none");


         jQuery('#billing_address_1').val("");
        jQuery('#billing_address_2').val("");
        jQuery('#billing_city').val("");
        jQuery('#billing_state').val("");
        jQuery('#billing_postcode').val("");
        jQuery('#billing_county').val("");

    //jQuery('.woofood_store_address_checkout').css('display', 'block');
  }
   else if(woofood_order_type=="delivery")
   {

              


          jQuery('#billing_country_field').css("display", "initial");

        jQuery('#billing_address_1_field').css("display", "initial");
        jQuery('#billing_address_2_field').css("display", "initial");
        jQuery('#billing_city_field').css("display", "initial");
        jQuery('#billing_state_field').css("display", "initial");
        jQuery('#billing_postcode_field').css("display", "initial");
        jQuery('#billing_county_field').css("display", "initial");

    //jQuery('.woofood_store_address_checkout').css('display', 'none');

   }  



    jQuery(document).on('change', 'input[type=radio][name=woofood_order_type]', function (){

        var woofood_order_type = jQuery('input[name=woofood_order_type]:checked').val();

          if(woofood_order_type=="pickup")
  {
            jQuery('#billing_country_field').css("display", "none");

        jQuery('#billing_address_1_field').css("display", "none");
        jQuery('#billing_address_2_field').css("display", "none");
        jQuery('#billing_city_field').css("display", "none");
        jQuery('#billing_state_field').css("display", "none");
        jQuery('#billing_postcode_field').css("display", "none");
        jQuery('#billing_county_field').css("display", "none");


        jQuery('#billing_address_1').val("");
        jQuery('#billing_address_2').val("");
        jQuery('#billing_city').val("");
        jQuery('#billing_state').val("");
        jQuery('#billing_postcode').val("");
        jQuery('#billing_county').val("");
    //jQuery('.woofood_store_address_checkout').css('display', 'block');
  }
   else if(woofood_order_type=="delivery")

   {                jQuery('#billing_country_field').css("display", "initial");

        jQuery('#billing_address_1_field').css("display", "initial");
        jQuery('#billing_address_2_field').css("display", "initial");
        jQuery('#billing_city_field').css("display", "initial");
        jQuery('#billing_state_field').css("display", "initial");
        jQuery('#billing_postcode_field').css("display", "initial");
        jQuery('#billing_county_field').css("display", "initial");

   // jQuery('.woofood_store_address_checkout').css('display', 'none');

   }  

    

        return false;
    });





});
  


