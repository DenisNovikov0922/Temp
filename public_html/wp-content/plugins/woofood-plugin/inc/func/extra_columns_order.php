<?php
//add extra columnd store  on orders list//
add_filter( 'manage_edit-shop_order_columns', 'wf_shop_order_column_woofood_order_type',12);
function wf_shop_order_column_woofood_order_type($columns)
{
   //add columns
    $columns['woofood_order_type'] = esc_html__( 'Order Type','woofood-plugin');

    $columns['woofood_deliveryboy'] = esc_html__( 'Richiesto per','woofood-plugin');

                    //to be added on next version //    

   /* $columns['woofood_deliveryboy'] = esc_html__( 'Delivery Boy','woofood-plugin');*/
                //to be added on next version //    

   return $columns;
}

// adding the data for each orders by column (example)
add_action( 'manage_shop_order_posts_custom_column' , 'wf_orders_list_column_woofood_order_type', 10, 3 );
function wf_orders_list_column_woofood_order_type( $column )
{
    global $post, $woocommerce, $the_order;
    $order_id = $post->ID;
    $order_types =  array("delivery"=>__('Delivery', 'woofood-plugin'), "pickup"=>__('Pickup', 'woofood-plugin'));
    $order_type_name ="";
    switch ( $column )
    {
        case 'woofood_order_type' :
            $woofood_order_type =  get_post_meta( $order_id, 'woofood_order_type', true );

            if($woofood_order_type)
            {
              $order_type_name =   $order_types[$woofood_order_type];
            } 
            echo $order_type_name;
            break;
                //to be added on next version //    


            case 'woofood_deliveryboy' :
            $woofood_time_to_deliver  = get_post_meta($order_id, 'woofood_time_to_deliver', true);
            $default_date_format = get_option("date_format");
           $default_time_format = get_option("time_format");
        
          if($woofood_time_to_deliver)
          {
          if($woofood_time_to_deliver!="now" && $woofood_time_to_deliver!="asap"  )
          {
          $woofood_time_to_deliver = date_i18n($default_time_format , strtotime($woofood_time_to_deliver  ) );
          }
        }
        echo '<p><strong>'.esc_html__('Ora').':</strong> ' . $woofood_time_to_deliver . '</p>';
         $woofood_date_to_deliver  = get_post_meta($order_id, 'woofood_date_to_deliver', true);
         if($woofood_date_to_deliver)
         {
         if($woofood_date_to_deliver!=current_time("Y-m-d"))
        {
         $woofood_date_to_deliver = date_i18n( $default_date_format, strtotime($woofood_date_to_deliver  ) );

         }
         else
        {
        $woofood_date_to_deliver = esc_html('Today', 'woofood-plugin');
       }
       echo '<p><strong>'.esc_html__('Giorno').':</strong> ' . $woofood_date_to_deliver . '</p>';
}  
           
            break;
                //to be added on next version //    

     
    }
}
//add extra columnd store  on orders list//
?>