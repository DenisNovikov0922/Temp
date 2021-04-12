<?php



//add extra options field
function wf_add_extra_options_field() {
  global $post;
  global $product;
    $terms = get_the_terms( $post->ID, 'product_cat' );
    $product_categories = array();

    if(is_array($terms))
  {
        foreach($terms as $term)
    {
       $product_categories[] = $term->term_id;

    }


  }







//old extra option categories compativility//
    $extra_option_categories = get_terms('extra_option_categories' ,  array('hide_empty' => false, 'orderby'=>'name', 'order'=>'ASC'));

    $all_selected_extra_option_categories = array();

    //new code//
    $global_extra_option_categories = array();
    $global_variation_extra_option_categories = array();
      foreach($extra_option_categories as $current_extra_option_category) {      

  $args = array(
  'numberposts' => -1,
  'post_type'   => 'extra_option',
  'suppress_filters' => false,



         'orderby' => array( 'meta_value_num' => 'ASC', 'title' => 'ASC' ),
    'order' => 'ASC',
    'meta_query' => array(
        'relation' => 'OR',
        array( 
            'key'=>'_wf_order',
            'compare' => 'EXISTS'           
        ),
        array( 
            'key'=>'_wf_order',
            'compare' => 'NOT EXISTS'           
        )
    ),

  'tax_query' => array(
    'relation' => 'AND',
    array(
        'taxonomy' => 'product_cat',
        'field'    => 'term_id',
        'terms'    => $terms[0]->term_id,
    ),
    array(
        'taxonomy' => 'extra_option_categories',
        'field'    => 'term_id',
        'terms'    => $current_extra_option_category->term_id,
    ),
),
 
);

$all_extra_options = get_posts( $args );
    if (!empty($all_extra_options)){

      $global_extra_option_categories[] =  $current_extra_option_category->term_id;



    }

  }
unset($extra_option_categories);

//old extra options structure compatiblity//


$args_new = array(
'hide_empty' => false, // also retrieve terms which are not used yet
'taxonomy'  => 'extra_option_categories',
);
$global_extra_option_categories_new = get_terms( $args_new );
 foreach($global_extra_option_categories_new as $current_extra_option_category) { 
   $term_meta = get_option( "taxonomy_$current_extra_option_category->term_id" );
  //$extra_option_category_global_categories =  get_term_meta( $current_extra_option_category->term_id, 'global_categories', true );

     $extra_option_category_global_categories =  array();

     if(is_array($term_meta))
     {
         if(array_key_exists("global_categories", $term_meta) && !empty($term_meta["global_categories"]))
  {
$extra_option_category_global_categories =  $term_meta["global_categories"];
}
     }




 if(is_array($product_categories) && !empty($product_categories) && is_array($extra_option_category_global_categories) && !empty($extra_option_category_global_categories))
 {
   if(in_array($product_categories[0], $extra_option_category_global_categories))   
 {
         $global_extra_option_categories[] = $current_extra_option_category->term_id;

 } 

 }

  

 }
    //new code//


//process global selected categories and add them to array //
if(!empty($global_extra_option_categories) && is_array($global_extra_option_categories))
{
  foreach($global_extra_option_categories as $current_global_category)
  {
      $all_selected_extra_option_categories[] = $current_global_category;

  }

}
//process global selected categories and add them to array //




    //check if the product is variable and get selected extra options selected on variable//



    if ( $product->is_type( 'variable' ) ) {

      $variable_product = new WC_Product_Variable( $post->ID);
      $variations = $variable_product->get_available_variations();
     // $extra_options_for_all_variations = get_post_meta( $post->ID, 'extra_options_select', true ); 
      $extra_options_for_all_variations = array();
    
    
    if(is_array($extra_options_for_all_variations) && !empty($extra_options_for_all_variations))
    {
      $global_variation_extra_option_categories = $extra_options_for_all_variations;
        // $all_selected_extra_option_categories[] = $extra_options_for_all_variations;
          
      foreach($extra_options_for_all_variations as $current_extra_options_for_all_variations)
      {
            //array_splice( $all_selected_extra_option_categories, ($index+1), 0, $current_extra_options_for_variation );

           $all_selected_extra_option_categories[] =  $current_extra_options_for_all_variations;

      }

      
    }
  

      //foreach variation //
      foreach($variations as $current_variation)

      {
          if(is_array($current_variation['variation_custom_select']))
      {
        if(!empty($current_variation['variation_custom_select']))
        {

          foreach($current_variation['variation_custom_select'] as $current_extra_options_for_variation)
              {
              $all_selected_extra_option_categories[] =  $current_extra_options_for_variation;

                  }

        }

    
    }


      }



    

   


    }

    if ($product->is_type('simple') )
    {
       $simple_selected_extra_option_categories = get_post_meta( $post->ID, 'extra_options_select', true ); 




         if(is_array($simple_selected_extra_option_categories))
      {
        if(!empty($simple_selected_extra_option_categories))
        {

          foreach($simple_selected_extra_option_categories as $current_extra_options_for_variation)
              {
              $all_selected_extra_option_categories[] =  $current_extra_options_for_variation;

                  }

        }

         if(in_array("0", $simple_selected_extra_option_categories) || in_array("no", $simple_selected_extra_option_categories)   )
        {
     $all_selected_extra_option_categories = array();

       }

    
    }
    
    

       
    




    }
        //end if product is simple//

  if(is_array($all_selected_extra_option_categories))
  {


    $all_selected_extra_option_categories = array_unique($all_selected_extra_option_categories);
  }
  


    
?>
<script>
var wf_global_extra_options = '<?php echo json_encode($global_extra_option_categories);?>';
var wf_global_extra_options_array = JSON.parse(wf_global_extra_options);

var wf_global_variations_extra_options = '<?php echo json_encode($global_variation_extra_option_categories);?>';
var wf_global_variations_extra_options_array = JSON.parse(wf_global_variations_extra_options);
jQuery(document).ready(function($){

jQuery('.extra-options-accordion .toggle').click(function(e) {
    e.preventDefault();
  
    var $this = $(this);
  
    if ($this.next().hasClass('shown')) {
        $this.next().removeClass('shown');
        $('.extra-options-accordion .plus').removeClass('shown');
        $this.next().slideUp(350);
    } else {
        $this.parent().parent().find('li .inner').removeClass('shown');
         $this.parent().parent().find('li .inner .plus').removeClass('shown');

        $this.parent().parent().find('li .inner').slideUp(350);
        //$this.next().toggleClass('show');
              //  $('.extra-options-accordion .plus').addClass('show');
             

             $this.next().addClass('shown');

        $this.next().slideToggle(350);

    }
});

   



});

</script>
<ul class="extra-options-accordion">


<?php
  //if user have selected custom options per product use them instead of category//
if (!empty($all_selected_extra_option_categories))

{



   foreach($all_selected_extra_option_categories as $current_extra_option_category) 
   {
    if($current_extra_option_category!="no")
    {

   

    //get category extra option name by id//
   $current_extra_option_category_object = get_term_by( 'id', absint( $current_extra_option_category ), 'extra_option_categories' );

   if($current_extra_option_category_object)
   {
  $current_extra_option_category_name = $current_extra_option_category_object->name;  
$term_meta = get_option( "taxonomy_".$current_extra_option_category);
$required = false;
$category_type = "";
$minimum_options = 0;
$hide_prices = false;
if(is_array($term_meta) && array_key_exists("minimum_options", $term_meta))
{
$minimum_options = $term_meta["minimum_options"];

}
if(is_array($term_meta) && array_key_exists("required", $term_meta))
{
$required = true;

}
if(is_array($term_meta) && array_key_exists("category_type", $term_meta))
{
$category_type = $term_meta["category_type"];

if( ($category_type =="radio" || $category_type =="select") && $required  )
{
  $minimum_options = 1;
}

}
$maximum_options = 99999999;
if(is_array($term_meta) && array_key_exists("maximum_options", $term_meta))
{
$maximum_options = $term_meta["maximum_options"];

}

$category_style = "";
if(is_array($term_meta) && array_key_exists("category_style", $term_meta))
{
$category_style = $term_meta["category_style"];

}

if(is_array($term_meta) && array_key_exists("hide_prices", $term_meta))
{
$hide_prices = true;

}



if (empty($maximum_options))
{
$maximum_options = 99999;


}
  $args = array(
  'numberposts' => -1,
  'post_type'   => 'extra_option',
  'suppress_filters' => false,


         'orderby' => array( 'meta_value_num' => 'ASC', 'title' => 'ASC' ),
    'order' => 'ASC',
    'meta_query' => array(
        'relation' => 'OR',
        array( 
            'key'=>'_wf_order',
            'compare' => 'EXISTS'           
        ),
        array( 
            'key'=>'_wf_order',
            'compare' => 'NOT EXISTS'           
        )
    ),
    
  'tax_query' => array(
    'relation' => 'AND',
    
    array(
        'taxonomy' => 'extra_option_categories',
        'field'    => 'term_id',
        'terms'    =>  $current_extra_option_category_object->term_id,
    ),
),
 
);
  
$all_extra_options = get_posts( $args );

    if (!empty($all_extra_options)){
      ?>

      <?php if($category_style === "flat"): ?>

           <li id="extra_option_category_id[<?php echo $current_extra_option_category;?>]" class="woofood_flat_category" <?php if($required) {echo " required";} ?> <?php if($minimum_options > 0) {echo ' min-options="'.$minimum_options.'"';} ?> <?php if($required) {echo " required";} ?> <?php if($maximum_options > 0) {echo ' max-options="'.$maximum_options.'"';} ?> cat-id="<?php echo $current_extra_option_category;?>">

      <a class="wf-flat-style-title" ><?php echo apply_filters('woofood_extra_option_prefix', esc_html__('Select','woofood-plugin')).' '. $current_extra_option_category_name;?></a>
<ul>

      <?php else: ?>

      <li id="extra_option_category_id[<?php echo $current_extra_option_category;?>]" <?php if($required) {echo " required";} ?> <?php if($minimum_options > 0) {echo ' min-options="'.$minimum_options.'"';} ?> <?php if($maximum_options > 0) {echo ' max-options="'.$maximum_options.'"';} ?>   cat-id="<?php echo $current_extra_option_category;?>">

      <a class="toggle" href="javascript:void(0);"><?php echo apply_filters('woofood_extra_option_prefix', esc_html__('Select','woofood-plugin')).' '. $current_extra_option_category_name;?><span class="woofood-icon-down-light float-right"></span></a>
<ul class="inner">
      <?php endif; ?>

      <?php
      //echo ini_get("default_charset");

          //if is checkbox multichoice//
        if($category_type =="checkbox-multichoice" || $category_type =="") {
          foreach ($all_extra_options as $current_extra_option){
          ?>

        
          <?php
          $current_extra_option_visible_as = get_post_meta( $current_extra_option->ID, 'extra_option_visible_as', true );
          $prechecked = get_post_meta( $current_extra_option->ID, 'woofood_prechecked', true );
          $prechecked_text = "";
          $prechecked_class= "";
          if($prechecked)
          {
                     $prechecked_text = ' checked';
                     $prechecked_class = ' checked_by_default';
          }

            if (!empty($current_extra_option_visible_as))
            {
              $current_extra_option_title = $current_extra_option_visible_as;

            }
            else {
                          $current_extra_option_title = $current_extra_option->post_title;


            }

            $current_extra_option_price = get_post_meta( $current_extra_option->ID, 'extra_option_price', true );
             $current_extra_option_id_normal = $current_extra_option->ID;
            $current_extra_option_id = $current_extra_option_id_normal.rand(1, 999999); 
 if(!$current_extra_option_price)
            {
              $current_extra_option_price = "0";
            }


           $current_extra_option_array = array('name'=>$current_extra_option_title,'price'=> html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'), "price_float"=> floatval($current_extra_option_price), 'id'=>$current_extra_option_id  );       
               

                $total_values = array( "id"=> $current_extra_option_id_normal, "price"=> html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'), "price_float"=> floatval($current_extra_option_price), "category"=> $current_extra_option_category_name, "name"=> $current_extra_option_title, "hide_prices"=>$hide_prices );
                  $price_text = ($hide_prices) ? '': '+'.html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');

                echo '          <div class="extra_options_checkbox">
                               <div class="extra_options_label">
                               <label>'.$current_extra_option_title.'</label>

                               </div>
                                      <div class="extra_options_value">

                                      <div class="woofood-cbx-wrapper">
                       
<input class="inp-woofood-cbx '.$prechecked_class.'"  type="checkbox" style="display: none;" name="add_extra_option['.$current_extra_option_id.']" value="'.rawurlencode(json_encode($total_values)).'"  id="'.$current_extra_option_id.'" '.$prechecked_text.'>
<label class="woofood-cbx" for="'.$current_extra_option_id.'"><span>
    <svg width="12px" height="10px" viewBox="0 0 12 10">
      <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
    </svg></span> <span>'.$price_text.'</span></label>
</div>

                                      
                                       </div>
                             </div>                           
                       ';

                       echo '';









?>


<?php
            } //end foreach
          }//end if is multichoice checkbox

           //if is limited-choice checkbox//
        if($category_type =="checkbox-limitedchoice") {
          ?>
          <script>
       
/*jQuery( document ).ready(function() {


          
jQuery('#extra_option_category_id\\[<?php echo $current_extra_option_category;?>\\] input').on('change', function(evt) {
   if(jQuery('#extra_option_category_id\\[<?php echo $current_extra_option_category;?>\\] input:checked').length > <?php echo $maximum_options;?>) {
//jQuery('#extra_option_category_id\\[<?php echo $current_extra_option_category;?>\\] input:checkbox:not(:checked)').attr('disabled', true);
   }

    if(jQuery('#extra_option_category_id\\[<?php echo $current_extra_option_category;?>\\] input:checked').length == <?php echo $maximum_options;?>) {

jQuery('#extra_option_category_id\\[<?php echo $current_extra_option_category;?>\\] input:checkbox:not(:checked)').attr('disabled', true);
   }

    if(jQuery('#extra_option_category_id\\[<?php echo $current_extra_option_category;?>\\] input:checked').length < <?php echo $maximum_options;?>) {

jQuery('#extra_option_category_id\\[<?php echo $current_extra_option_category;?>\\] input:checkbox:not(:checked)').attr('disabled', false);
   }

});
  });*/
          </script>

          <?php
          foreach ($all_extra_options as $current_extra_option){
          ?>

        
          <?php
          $current_extra_option_visible_as = get_post_meta( $current_extra_option->ID, 'extra_option_visible_as', true );
            if (!empty($current_extra_option_visible_as))
            {
              $current_extra_option_title = $current_extra_option_visible_as;

            }
            else {
                          $current_extra_option_title = $current_extra_option->post_title;


            }

            $current_extra_option_price = get_post_meta( $current_extra_option->ID, 'extra_option_price', true );
             $current_extra_option_id_normal = $current_extra_option->ID;
            $current_extra_option_id = $current_extra_option_id_normal.rand(1, 999999); 


            $prechecked = get_post_meta( $current_extra_option->ID, 'woofood_prechecked', true );
          $prechecked_text = "";
          $prechecked_class= "";

          if($prechecked)
          {
                     $prechecked_text = ' checked';
                     $prechecked_class = ' checked_by_default';
          }



 if(!$current_extra_option_price)
            {
              $current_extra_option_price = "0";
            }

           $current_extra_option_array = array('name'=>$current_extra_option_title,'price'=> html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'), 'id'=>$current_extra_option_id  );       
               

                $total_values = array( "id"=> $current_extra_option_id_normal, "price"=> html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'), "price_float"=> floatval($current_extra_option_price), "category"=> $current_extra_option_category_name, "name"=> $current_extra_option_title, "hide_prices"=>$hide_prices );
                                  $price_text = ($hide_prices) ? '': '+'.html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');


                echo '          <div class="extra_options_checkbox">
                               <div class="extra_options_label"><label>'.$current_extra_option_title.'</label></div>
                                      <div class="extra_options_value">
                                      


                                      <div class="woofood-cbx-wrapper">
                       
<input class="inp-woofood-cbx '.$prechecked_class.'"  type="checkbox" style="display: none;" name="add_extra_option['.$current_extra_option_id.']" value="'.rawurlencode(json_encode($total_values)).'"  id="'.$current_extra_option_id.'" '.$prechecked_text.'>
<label class="woofood-cbx" for="'.$current_extra_option_id.'"><span>
    <svg width="12px" height="10px" viewBox="0 0 12 10">
      <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
    </svg></span> <span> '.$price_text.'</span></label>
</div>





                                       </div>
                             </div>                           
                       ';









?>


<?php
            } //end foreach
          }//end if is limited-choice checkbox

     //if is radio
        if($category_type =="radio") {
          foreach ($all_extra_options as $current_extra_option){
          ?>

        
          <?php
          $current_extra_option_visible_as = get_post_meta( $current_extra_option->ID, 'extra_option_visible_as', true );
            if (!empty($current_extra_option_visible_as))
            {
              $current_extra_option_title = $current_extra_option_visible_as;

            }
            else {
                          $current_extra_option_title = $current_extra_option->post_title;


            }

            $current_extra_option_price = get_post_meta( $current_extra_option->ID, 'extra_option_price', true );

            $current_extra_option_id_normal = $current_extra_option->ID;
            $current_extra_option_id = $current_extra_option_id_normal.rand(1, 999999); 

             $prechecked = get_post_meta( $current_extra_option->ID, 'woofood_prechecked', true );
          $prechecked_text = "";
          $prechecked_class= "";
          if($prechecked)
          {
                     $prechecked_text = ' checked';
                     $prechecked_class = ' checked_by_default';
          }

 if(!$current_extra_option_price)
            {
              $current_extra_option_price = "0";
            }

           $current_extra_option_array = array('name'=>$current_extra_option_title,'price'=> $current_extra_option_price, 'id'=>$current_extra_option_id  );       
             
$total_values = array( "id"=> $current_extra_option_id_normal, "price"=> html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'), "price_float"=> floatval($current_extra_option_price), "category"=> $current_extra_option_category_name, "name"=> $current_extra_option_title, "hide_prices"=>$hide_prices );
                                  $price_text = ($hide_prices) ? '': '+'.html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');

echo '<div class="extra_options_checkbox">
                               <div class="extra_options_label">
                               <label>'.$current_extra_option_title.'</label>
                               </div>
                               <div class="extra_options_value">

                                  <div class="woofood-cbx-wrapper">
                       
<input class="inp-woofood-cbx '.$prechecked_class.'"  type="radio" style="display: none;" name="add_extra_option_radio['.$current_extra_option_category.']" value="'.rawurlencode(json_encode($total_values)).'"  id="'.$current_extra_option_id.'" '.$prechecked_text.'>
<label class="woofood-cbx radio" for="'.$current_extra_option_id.'"><span>
    <svg width="12px" height="10px" viewBox="0 0 12 10">
      <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
    </svg></span> <span> '.$price_text.'</span></label>
</div>

          
          

          </div>
       

   </div> ';








?>


<?php
            } //end foreach
          }//end if is radio 









           if($category_type =="select") {
            $options = "";
           
          $checked_by_default = "";

          foreach ($all_extra_options as $current_extra_option){
          ?>

        
          <?php
          $current_extra_option_visible_as = get_post_meta( $current_extra_option->ID, 'extra_option_visible_as', true );
            if (!empty($current_extra_option_visible_as))
            {
              $current_extra_option_title = $current_extra_option_visible_as;

            }
            else {
            
              $current_extra_option_title = $current_extra_option->post_title;


            }

            $current_extra_option_price = get_post_meta( $current_extra_option->ID, 'extra_option_price', true );

            $current_extra_option_id_normal = $current_extra_option->ID;
            $current_extra_option_id = $current_extra_option_id_normal.rand(1, 999999); 
 if(!$current_extra_option_price)
            {
              $current_extra_option_price = "0";
            }

             $prechecked = get_post_meta( $current_extra_option->ID, 'woofood_prechecked', true );
          $prechecked_text = "";
 $prechecked_class = "";
          if($prechecked)
          {
                     $prechecked_text = ' selected';
                     $prechecked_class = ' checked_by_default';
          }

           $current_extra_option_array = array('name'=>$current_extra_option_title,'price'=> $current_extra_option_price, 'id'=>$current_extra_option_id  );       
             
$total_values = array( "id"=> $current_extra_option_id_normal, "price"=> html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'), "price_float"=> floatval($current_extra_option_price), "category"=> $current_extra_option_category_name, "name"=> $current_extra_option_title, "hide_prices" => $hide_prices );
                                  $price_text = ($hide_prices) ? '': ''.html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8');

/*echo '<div class="extra_options_checkbox">
                               <div class="extra_options_label">
                               <label>'.$current_extra_option_title.'</label>
                               </div>
                               <div class="extra_options_value">

                                  <div class="woofood-cbx-wrapper">
                       
<input class="inp-woofood-cbx"  type="radio" style="display: none;" name="add_extra_option_radio['.$current_extra_option_category.']" value="'.rawurlencode(json_encode($total_values)).'"  id="'.$current_extra_option_id.'">
<label class="woofood-cbx radio" for="'.$current_extra_option_id.'"><span>
    <svg width="12px" height="10px" viewBox="0 0 12 10">
      <polyline points="1.5 6 4.5 9 10.5 1"></polyline>
    </svg></span> <span> + '.html_entity_decode(strip_tags(wc_price($current_extra_option_price, ENT_COMPAT, 'UTF-8'))).'</span></label>
</div>

          
          

          </div>
       

   </div> ';*/

$options .= '<option value="'.rawurlencode(json_encode($total_values)).'" '.$prechecked_text.'>'.$current_extra_option_title.' '.$price_text.'</option>';






?>


<?php
            } //end foreach
             echo '<div class="wf_select_wrapper">';

            echo '<select name="add_extra_option_radio['.$current_extra_option_category.']" class="'.$prechecked_class.'">';
                          echo '<option value="">'.apply_filters('woofood_extra_option_prefix', esc_html__('Select','woofood-plugin')).' '.$current_extra_option_category_name.'</option>';
            echo $options;
            echo '</select>';
           echo '</div>';

          }//end if is select 



     echo '<input type="hidden" name="add_extra_option_radio_hidden" />'; 
  

     ?>
     </ul>
     </li>

     <?php
        }// end if not empty


      }//end if is extra option category exist


      }//end if category is not "no"

}//end foreach extra option category






}//end if 





?>
</ul>

<textarea name="extra_comments_field" class="woofood_additional_comments" class="form-control" rows="3" placeholder="<?php esc_html_e('Additional Comments....','woofood-plugin');?>" checked ></textarea>


<?php

}

    
add_action( 'woocommerce_before_add_to_cart_button', 'wf_add_extra_options_field' );







//save extra options fee

function wf_save_extra_options( $cart_item_data, $product_id, $variation_id ) {

  $woofood_data = array();
   if( $variation_id > 0)
   {
        $product = wc_get_product($variation_id);

   }
   else
   {
        $product = wc_get_product($product_id);

   }

    $product_price = $product->get_price();

     if( isset( $_POST['add_extra_option'] ) || isset($_POST['add_extra_option_radio']) ) {

        //start for each extra option fee//

      $all_extra_categories_array = array();
    
     foreach($_POST as $key=> $posted) {
       if($key =="add_extra_option" || $key =="add_extra_option_radio" )
       {
         
       
    
         foreach($posted as $extra_option) {
       
       
              if(!empty($extra_option))
              {


       
     

           $extra_option_object = json_decode(rawurldecode($extra_option));
            if($extra_option_object->name || $extra_option_object->price)
            {
       
            $woofood_data["extra_options"][$extra_option_object->category][] = $extra_option_object;
          }


  
 }


 

        }//end for each extra option fee//
       }
    
          }//end for each post

        $final_extra_options_array =array();
        


      
            

    
    }//end isset add_extra_option

    if( isset( $_POST['extra_comments_field'] ) && !empty($_POST['extra_comments_field']) ) {

                    $woofood_data["additional_comments"] = $_POST['extra_comments_field'];


      }


           if(!empty($woofood_data["extra_options"]))
                {
                  $additionalPrice = 0;
                 

                            foreach($woofood_data["extra_options"] as $current_extra_option_categroy)


                     {  


                        foreach($current_extra_option_categroy as $current_extra_option)
                        {
                           $current_extra_option  = (object)$current_extra_option;
 
                             if (is_numeric($current_extra_option->price_float)) {

                                                $additionalPrice += floatval($current_extra_option->price_float);
                                                                 }



                        }
                                     

                     }


       $woofood_data["extra_options_price"] = $additionalPrice;


                }
       $woofood_data["original_price"] = $product_price;




$cart_item_data["woofood_data"] = $woofood_data;


    return $cart_item_data;
     
}
add_filter( 'woocommerce_add_cart_item_data', 'wf_save_extra_options', 99, 3 );



add_filter("woocommerce_cart_item_price", "woofood_modify_cart_item_price", 99, 3);

function woofood_modify_cart_item_price($price, $cart_item, $cart_item_key)
{         

 if(!empty($cart_item["woofood_data"]["extra_options_price"]))
{

  return wc_price($cart_item["woofood_data"]["original_price"] + $cart_item["woofood_data"]["extra_options_price"]);
}



  return wc_price($cart_item["woofood_data"]["original_price"]);

}


//calculate extra options fees/
function wf_calculate_extra_options_fee( $cart_object ) {

  /*if(did_action( "woocommerce_before_calculate_totals" ) >= 2)
  {
    return;
  }
   if ( is_admin() && ! defined( 'DOING_AJAX' ) ) {
 return;
 }*/

    if( !WC()->session->__isset( "reload_checkout" ) ) {
        
        

        foreach ( $cart_object->cart_contents as $key => $value ) {
            if( isset( $value["woofood_data"]["extra_options"] ) && !empty($value["woofood_data"]["extra_options"]) && empty($value["_woofood_changed"] )) {  
                $additionalPrice = 0;

              //get additional price//
                $orgPrice = floatval($value['data']->get_price());
                $product = null;
              
            
                $product_price = $orgPrice;
                //start adding to price //
                if(!empty($value["woofood_data"]["extra_options"]))
                {
                 

                            foreach($value["woofood_data"]["extra_options"] as $current_extra_option_categroy)


                     {  


                        foreach($current_extra_option_categroy as $current_extra_option)
                        {
                           $current_extra_option  = (object)$current_extra_option;
 
                             if (is_numeric($current_extra_option->price_float)) {

                                                $additionalPrice += floatval($current_extra_option->price_float);
                                                                 }



                        }
                                     

                     }



                }
                                $final_price = $additionalPrice + $product_price;
                                $value['data']->set_price( $final_price);

                                $value["_woofood_changed"] = array("changed");

                 

                 }
                
            }


        }
          remove_action( 'woocommerce_before_calculate_totals', 'wf_calculate_extra_options_fee',99 );

    
}
add_action( 'woocommerce_before_calculate_totals', 'wf_calculate_extra_options_fee',99 );








//render meta data on cart//
function wf_render_meta_on_cart_and_checkout( $cart_data, $cart_item = null ) {


    $meta_items = array();
    /* Woo 2.4.2 updates */
    if( !empty( $cart_data ) ) {
        $meta_items = $cart_data;
    }


  $woofood_options = get_option('woofood_options');
  $woofood_enable_hide_extra_cat_title_option = isset($woofood_options['woofood_enable_hide_extra_cat_title_option']) ? $woofood_options['woofood_enable_hide_extra_cat_title_option'] : null ;


if( isset( $cart_item["woofood_data"] ) ) {

    if( isset( $cart_item["woofood_data"]["extra_options"] ) ) {

        foreach($cart_item["woofood_data"]["extra_options"]  as $current_extra_option_category_name => $current_extra_option_category)
        { 
         
  if (!$woofood_enable_hide_extra_cat_title_option){


                                $meta_items[] = array( "name" => null, "value" => $current_extra_option_category_name);
                              }

              foreach($current_extra_option_category  as $current_extra_option)
            {
              $current_extra_option = (object)$current_extra_option;


                      if($current_extra_option->hide_prices == true)
                      {
                          $meta_items[] = array( "name" => '', "value" => $current_extra_option->name);

                      }
                      else
                      {
                          $meta_items[] = array( "name" => $current_extra_option->name.'', "value" => html_entity_decode($current_extra_option->price, ENT_COMPAT, 'UTF-8'));

                      }





            }




        }
      


      }

      if( isset( $cart_item["woofood_data"]["additional_comments"] ) ) {

                $meta_items[] = array( "name" => esc_html__('Additional Comments','woofood-plugin'), "value" => $cart_item["woofood_data"]["additional_comments"] );



      }
        
     
       
    }








    if( isset( $cart_item["extra_options"] ) ) {
        
        foreach($cart_item["extra_options"] as $current_extra_option){


          if( (array_key_exists('0', $current_extra_option)) && isset( $current_extra_option )  && $current_extra_option[0] =="cat_name"  ) {
               
        $meta_items[] = array( "name" => null ,"value" => $current_extra_option[1].'');
             


     }//end if isset each

             if( (array_key_exists('0', $current_extra_option)) && isset( $current_extra_option )  && is_numeric($current_extra_option[0])   ) {
               
        $meta_items[] = array( "name" => $current_extra_option[1].'', "value" => "".html_entity_decode(strip_tags(wc_price($current_extra_option[0], ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') );
              



                }//end if isset each





                //check if have additional comments for the product//

                 if( (array_key_exists('additional_comments', $current_extra_option))&& isset( $current_extra_option )  && isset($current_extra_option['additional_comments'] ))  {
               
        $meta_items[] = array( "name" => esc_html__('Additional Comments','woofood-plugin'), "value" => $current_extra_option['additional_comments'] );
              



                }//end if isset each
                                //check if have additional comments for the product//





        }//end foreach
       
    }


             return $meta_items;


}
add_filter( 'woocommerce_get_item_data', 'wf_render_meta_on_cart_and_checkout', 99, 2 );


function wf_extra_options_order_meta_handler( $item_id, $item, $orderId ) {
$woofood_meta_data = array();
if( isset( $item->legacy_values["extra_options"] ) ) {

    foreach($item->legacy_values["extra_options"] as $current_extra_option){

      if( isset( $current_extra_option ) && $current_extra_option[0] =="cat_name"  ) {
       // wc_add_order_item_meta( $item_id, "cat", $current_extra_option[1] );

       $woofood_meta_data["extra_options"][] = array("id"=>$current_extra_option[2], "name"=>"cat", "price"=> $current_extra_option[1], "category"=>$current_extra_option[3] ) ;


    }

    if( isset( $current_extra_option ) && is_numeric($current_extra_option[0])  ) {
        //wc_add_order_item_meta( $item_id, $current_extra_option[1], html_entity_decode(strip_tags(wc_price($current_extra_option[0], ENT_COMPAT, 'UTF-8'))) );
        $woofood_meta_data["extra_options"][] = array("id"=>$current_extra_option[2], "name"=>$current_extra_option[1], "price"=> html_entity_decode(strip_tags(wc_price($current_extra_option[0], ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8'), "category"=>$current_extra_option[3] ) ;

    }





      //check if have additional comments for the product//

                 if( isset( $current_extra_option )  && isset($current_extra_option['additional_comments'] ))  {
               
                    //  wc_add_order_item_meta( $item_id, esc_html__('Additional Comments','woofood-plugin'), $current_extra_option['additional_comments'] );
        $woofood_meta_data["additional_comments"] = $current_extra_option['additional_comments'];

                }//end if isset each
    //check if have additional comments for the product//
  






    }//end foreach extra options//

        //wc_add_order_item_meta( $item_id, "woofood_meta", json_encode($woofood_meta_data) );



}//end if isset
        wc_add_order_item_meta( $item_id, "woofood_meta", json_encode($item->legacy_values["woofood_data"]) );




}
add_action( 'woocommerce_new_order_item', 'wf_extra_options_order_meta_handler', 10, 3 );





add_filter('woocommerce_display_item_meta' , 'woofood_display_extra_options_item_meta', 10, 3);
function  woofood_display_extra_options_item_meta($html, $item, $args )
{   $html    = '';
    $strings = array();


foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
      $value     = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );
      if($meta->key =="cat")
      {
           

          //  $strings[] = $display_value;

      }
         if(!$meta->key)
      {
              $strings[] = $value;

      }
      else
      {
        if(empty($value))
        {
              $strings[] = wp_kses_post( $meta->display_key );
        }
        else
        {
              $strings[] = $args['label_before'] . wp_kses_post( $meta->display_key ) . $args['label_after'] . $value;

        }

      }
    }
    if ( $strings ) {
      $html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
    }


  return $html;

}

//render meta data on cart//

//hide woofood_meta from admin and from user//
add_filter('woocommerce_hidden_order_itemmeta', 'wf_hidden_order_itemmeta', 10, 1);

function wf_hidden_order_itemmeta($args) {
  $args[] = 'woofood_meta';
  return $args;
}


add_filter( 'woocommerce_order_item_get_formatted_meta_data', 'wf_hide_woofood_order_item_meta', 10, 1 );

function wf_hide_woofood_order_item_meta($formatted_meta){

    $woofood_options = get_option('woofood_options');
  $woofood_enable_hide_extra_cat_title_option = isset($woofood_options['woofood_enable_hide_extra_cat_title_option']) ? $woofood_options['woofood_enable_hide_extra_cat_title_option'] :null ;

    $temp_metas = [];
    $woofood_meta = array();
    foreach($formatted_meta as $key => $meta) {
        if ( isset( $meta->key ) && ! in_array( $meta->key, [
                'woofood_meta'
                
            ] ) ) {
            $temp_metas[ $key ] = $meta;
        }
        if ( isset( $meta->key ) &&  in_array( $meta->key, [
                'woofood_meta'
                
            ] ) ) {
            $woofood_meta = json_decode($meta->value);
          


        }
    }

    if(!empty($woofood_meta))
    {

      if(!empty($woofood_meta->extra_options))
    {
      foreach($woofood_meta->extra_options as $current_extra_option_category_name => $current_extra_option_category)
      { 

          if (!$woofood_enable_hide_extra_cat_title_option){

                                $myObj = new stdClass;
$myObj->key = null;
$myObj->value = $current_extra_option_category_name;
$myObj->display_key = null;
$myObj->display_value = $current_extra_option_category_name;
                $temp_metas[] = $myObj;
                              }

        foreach($current_extra_option_category as $current_extra_option)
        {


        if ($current_extra_option->name == "cat" )
        {
          $current_extra_option->name =  $current_extra_option->price;
           $current_extra_option->price = null;

        }


          $myObj = new stdClass;
$myObj->key = $current_extra_option->name;
$myObj->value = $current_extra_option->price;
$myObj->display_key = $current_extra_option->name;
$myObj->display_value = $current_extra_option->price;
                $temp_metas[] = $myObj;

     
        }
      }
      
    }

    if(!empty($woofood_meta->additional_comments))
    {

       $myObj = new stdClass;
$myObj->key = esc_html__('Additional Comments', 'woofood-plugin');
$myObj->value = $woofood_meta->additional_comments;
$myObj->display_key = esc_html__('Additional Comments', 'woofood-plugin');
$myObj->display_value = $woofood_meta->additional_comments;
                $temp_metas[] = $myObj;


    }

    }
  
              
    return $temp_metas;
}
//hide woofood_meta from admin and from user//


add_filter( 'woocommerce_order_again_cart_item_data', 'woofood_order_again_cart_item_meta', 50, 3 );

function woofood_order_again_cart_item_meta($cart_item_meta, $product, $order){
       $woofood_meta = array();      

    //Create an array of all the missing custom field keys that needs to be added in cart item.
    global $woocommerce;
    remove_all_filters( 'woocommerce_add_to_cart_validation' );
    
    $all_meta =  $product->get_meta_data();
  
  /*  echo "<pre>";
    print_r($all_meta);
    echo "</pre>";*/
    foreach($all_meta as $meta)
    {

      if($meta->key =="woofood_meta")
      {
         
         $woofood_meta = json_decode($meta->value, true);


      }

    }



$cart_item_meta["woofood_data"] = $woofood_meta;




   



   // print_r($cart_item_meta);
    return $cart_item_meta;
}



//create custom taxonomy extra_option_categories//


function wf_create_extra_option_categories_taxonomy() {

// Add new taxonomy, make it hierarchical like categories
//first do the translations part for GUI

  $labels = array(
    'name' => esc_html_x( 'Extra Option Categories', 'taxonomy general name', 'woofood-plugin'  ),
    'singular_name' => esc_html_x( 'Extra Option Category', 'taxonomy singular name', 'woofood-plugin'  ),
    'search_items' =>  esc_html__( 'Search Extra Option Categories', 'woofood-plugin'  ),
    'all_items' => esc_html__( 'All Extra Option Categories', 'woofood-plugin'  ),
    'parent_item' => esc_html__( 'Parent Extra Option Category', 'woofood-plugin'  ),
    'parent_item_colon' => esc_html__( 'Parent Extra Option Category:' ),
    'edit_item' => esc_html__( 'Edit Extra Option Category', 'woofood-plugin'  ), 
    'update_item' => esc_html__( 'Update Extra Option Category', 'woofood-plugin' ),
    'add_new_item' => esc_html__( 'Add New Extra Option Category' ,'woofood-plugin' ),
    'new_item_name' => esc_html__( 'New Extra Option Category' ,'woofood-plugin' ),
    'menu_name' => esc_html__( 'Extra Option Categories', 'woofood-plugin' ),
  );  

// Now register the taxonomy

  register_taxonomy('extra_option_categories',array('extra_option'), array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'show_in_menu' => false,

    'rewrite' => array( 'slug' => 'extra_option_category' ),

    'capabilities' => array(
    'manage_terms' => 'manage_extra_option_category',
    'edit_terms' => 'edit_extra_option_category',
    'delete_terms' => 'delete_extra_option_category',
    'assign_terms' => 'assign_extra_option_category',
)

  ));

}

add_action( 'init', 'wf_create_extra_option_categories_taxonomy', 0 );



//create custom taxonomy extra_option_categories//


//Create Custom Post Type extra_option//
function wf_register_extra_option_post_type() {
    $labels = array(
        'name'                  => esc_html_x( 'Extra Options', 'Post type general name', 'woofood-pugin' ),
        'singular_name'         => esc_html_x( 'Extra Option', 'Post type singular name', 'woofood-plugin' ),
        'menu_name'             => esc_html_x( 'Extra Options', 'Admin Menu text', 'woofood-plugin' ),
        'name_admin_bar'        => esc_html_x( 'Extra Option', 'Add New on Toolbar', 'woofood-plugin' ),
        'add_new'               => esc_html__( 'Add Extra Option', 'woofood-plugin' ),
        'add_new_item'          => esc_html__( 'Add New Extra Option', 'woofood-plugin' ),
        'new_item'              => esc_html__( 'New Extra Option', 'woofood-plugin' ),
        'edit_item'             => esc_html__( 'Edit Extra Option', 'woofood-plugin' ),
        'view_item'             => esc_html__( 'View Extra Option', 'woofood-plugin' ),
        'all_items'             => esc_html__( 'All Extra Options', 'woofood-plugin' ),
        'search_items'          => esc_html__( 'Search Extra Options', 'woofood-plugin' ),
        'parent_item_colon'     => esc_html__( 'Parent Extra Options:', 'woofood-plugin' ),
        'not_found'             => esc_html__( 'No Extra Options found.', 'woofood-plugin' ),
        'not_found_in_trash'    => esc_html__( 'No Extra Options found in Trash.', 'woofood-plugin' ),
        'featured_image'        => esc_html_x( 'Extra Option Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'woofood-plugin' ),
        'set_featured_image'    => esc_html_x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'woofood-plugin' ),
        'remove_featured_image' => esc_html_x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'woofood-plugin' ),
        'use_featured_image'    => esc_html_x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'woofood-plugin' ),
        'archives'              => esc_html_x( 'Extra Option archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'woofood-plugin' ),
        'insert_into_item'      => esc_html_x( 'Insert into Extra Option', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'woofood-plugin' ),
        'uploaded_to_this_item' => esc_html_x( 'Uploaded to this Extra Option', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'woofood-plugin' ),
        'filter_items_list'     => esc_html_x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'woofood-plugin' ),
        'items_list_navigation' => esc_html_x( 'Extra Options list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'woofood-plugin' ),
        'items_list'            => esc_html_x( 'Extra Options list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'woofood-plugin' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => false,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'extra_option' ),
        'capability_type'    => array('extra_option','extra_options' ),
       
        'map_meta_cap'    => true,

        'has_archive'        => true,
        'hierarchical'       => true,
        'menu_position'      => null,
        //'menu_icon'           => get_template_directory_uri().'/icons/woofood_logo_black/res/mipmap-mdpi/woofood_logo_black.png',
        'taxonomies' => array('product_cat', 'extra_option_categories'),
        'supports'           => array( 'title' ),
        //'show_in_menu' =>'edit.php?post_type=extra_option',
    );
 
    register_post_type( 'extra_option', $args );
}


$roles = array('shop_manager', 'administrator');

$caps = array(
    //* Meta capabilities
    'read'                   => true,
    'edit_extra_option'              => true,
    'read_extra_option'              => true,
    'delete_extra_option'            => true,

    //* Primitive capabilities used outside of map_meta_cap()
    'edit_extra_option'             => true,
    'edit_others_extra_options'      => true,
    'publish_extra_options'          => true,
    'read_private_extra_options'     => true,

    //* Primitive capabilities used within of map_meta_cap()
    'delete_extra_options'           => true,
    'delete_private_extra_options'   => true,
    'delete_published_extra_options' => true,
    'delete_others_extra_options'    => true,
    'edit_private_extra_options'     => true,
    'edit_published_extra_options'   => true,
  );
  foreach($roles as $current_role)
{

  $role = get_role($current_role);
 if($role)
 {
   $role->add_cap( 'read' );

  $role->add_cap( 'read_extra_option');
              $role->add_cap( 'read_private_extra_options' );
              $role->add_cap( 'edit_extra_option' );
              $role->add_cap( 'edit_extra_options' );
              $role->add_cap( 'edit_others_extra_options' );
              $role->add_cap( 'edit_published_extra_options' );
              $role->add_cap( 'publish_extra_options' );
              $role->add_cap( 'delete_others_extra_options' );
              $role->add_cap( 'delete_private_extra_options' );
              $role->add_cap( 'delete_published_extra_options' );



              $role->add_cap("manage_extra_option_category");
    $role->add_cap("edit_extra_option_category");
    $role->add_cap("delete_extra_option_category");
     $role->add_cap("assign_extra_option_category");


 }
              
}


add_action( 'init', 'wf_register_extra_option_post_type' );

//Create Custom Post Type extra_option//



//Customize Columns //

add_filter( 'manage_edit-extra_option_columns', 'wf_extra_option_columns' ) ;

function wf_extra_option_columns( $columns ) {

/*  $columns = array(
    'title' => esc_html__( 'Extra Option Name' , 'woofood-plugin'),
    'extra_option_price' => esc_html__( 'Extra Price', 'woofood-plugin' ),
  
    
  );*/
  $columns["title"] = esc_html__( 'Extra Option Name' , 'woofood-plugin');
  $columns["extra_option_price"] = esc_html__( 'Extra Price', 'woofood-plugin' );

  return $columns;
}

//load custom columns data  //


add_action( 'manage_extra_option_posts_custom_column', 'wf_manage_extra_option_columns', 10, 2 );

function wf_manage_extra_option_columns( $column, $post_id ) {
  global $post;

  switch( $column ) {

    /* If displaying the 'duration' column. */
    case 'extra_option_price' :

      /* Get the post meta. */
      $extra_option_price = get_post_meta( $post_id, 'extra_option_price', true );

      
        echo '<strong>+'.$extra_option_price.'</strong>';

      break;

    /* If displaying the 'genre' column. */
    case 'genre' :

      /* Get the genres for the post. */
      $terms = get_the_terms( $post_id, 'genre' );

      /* If terms were found. */
      if ( !empty( $terms ) ) {

        $out = array();

        /* Loop through each term, linking to the 'edit posts' page for the specific term. */
        foreach ( $terms as $term ) {
          $out[] = sprintf( '<a href="%s">%s</a>',
            esc_url( add_query_arg( array( 'post_type' => $post->post_type, 'genre' => $term->slug ), 'edit.php' ) ),
            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, 'genre', 'display' ) )
          );
        }

        /* Join the terms, separating them with a comma. */
        echo join( ', ', $out );
      }

      /* If no terms were found, output a default message. */
      else {
        esc_html_e( 'No Extra Options' );
      }

      break;

    /* Just break out of the switch statement for everything else. */
    default :
      break;
  }
}

//load custom columns data  //


//add meta box extra price //

function wf_extra_option_price_meta() {
    add_meta_box( 'extra_option_price', esc_html__( 'Extra Price', 'woofood-plugin' ), 'wf_extra_option_price_callback', 'extra_option' );
}
add_action( 'add_meta_boxes', 'wf_extra_option_price_meta' );
//add meta box extra price //


//add meta box extra price //

function wf_extra_option_visible_as_meta() {
    add_meta_box( 'extra_option_visible_as', esc_html__( 'Visible As', 'woofood-plugin' ), 'wf_extra_option_visible_as_callback', 'extra_option', 'normal', "high" );
}
add_action( 'add_meta_boxes', 'wf_extra_option_visible_as_meta' );
//add meta box extra price //



//metabox extra_price callback//
function wf_extra_option_price_callback() {


  // Noncename needed to verify where the data originated
      wp_nonce_field( basename(__FILE__), 'extra_option_meta_nonce' );

  
  global $post;

  //Get extra_option_price if already exists
  $extra_option_price = get_post_meta($post->ID, 'extra_option_price', true);
  //display the extra_option_price //
  echo '<div class="extra-option-field">'.esc_html_e('Extra Price', 'woofood-plugin').'<input type="text" name="extra_option_price" value="' . $extra_option_price  . '"  /></div>';

  }
//metabox extra_price callback//


  //metabox extra_option_visible_as callback//
function wf_extra_option_visible_as_callback() {


  // Noncename needed to verify where the data originated
      wp_nonce_field( basename(__FILE__), 'extra_option_meta_nonce' );

  
  global $post;

  //Get extra_option_price if already exists
  $extra_option_visible_as = get_post_meta($post->ID, 'extra_option_visible_as', true);
  //display the extra_option_price //
  echo '<div class="extra-option-field">'.esc_html_e('Visible As', 'woofood-plugin').'<input type="text" name="extra_option_visible_as" value="' . $extra_option_visible_as  . '"  /></div>';

  }
//metabox extra_option_visible_as callback//

//save meta data //
   function wf_extra_option_meta_save($post_id) {
    if (!isset($_POST['extra_option_meta_nonce']) || !wp_verify_nonce($_POST['extra_option_meta_nonce'], basename(__FILE__))) return;
    if (!current_user_can('edit_post', $post_id)) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
   
    //check and save extra_option_price meta//
    if(isset($_POST['extra_option_price'])) {
      update_post_meta($post_id, 'extra_option_price', $_POST['extra_option_price']);
    } else {
      delete_post_meta($post_id, 'extra_option_price');
    }
    //check and save extra_option_price meta//

    //check and save extra_option_visible_as meta//
    if(isset($_POST['extra_option_visible_as'])) {
      update_post_meta($post_id, 'extra_option_visible_as', $_POST['extra_option_visible_as']);
    } else {
      delete_post_meta($post_id, 'extra_option_visible_as');
    }
    //check and save extra_option_visible_as meta//

    

  }
    add_action('save_post', 'wf_extra_option_meta_save');

//save meta data //






add_action( 'woocommerce_before_add_to_cart_button', 'wf_total_product_price_calculate', 31 );

    /*Total Product Price Calculate     */
//add_action( 'woocommerce_single_product_summary', 'wf_total_product_price_calculate', 31 );
function wf_total_product_price_calculate() {
    global $woocommerce, $product;
    // let's setup our divs
       if ($product->is_type("variable"))
    {
      ?>
<script>
               jQuery('.extra-options-accordion li[id^="extra_option_category_id"]').css('display', 'none');

</script>

      <?php




    }
       if ($product->is_type("variable") ||  $product->is_type("simple") )
    {
 
    ?>
        <script>
              function woofood_format_price(price)
        {
              var   price_decimals = <?php echo wc_get_price_decimals(); ?>;
              var thousand_seperator = '<?php echo wc_get_price_thousand_separator(); ?>';
              var decimal_seperator = '<?php echo wc_get_price_decimal_separator(); ?>';
              var currency_position = '<?php echo get_option( 'woocommerce_currency_pos' ); ?>';
              var currency =  '<?php echo get_woocommerce_currency_symbol(); ?>';

                var new_price = parseFloat(price.toString()).toFixed(price_decimals).replace('.',decimal_seperator);

               new_price =    new_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousand_seperator);

              if(currency_position == "right")
              {
                return new_price+currency;

              }

              if(currency_position == "right_space")
              {
                return new_price+" "+currency;

              }
               if(currency_position == "left")
              {
                return currency+new_price;

              }
               if(currency_position == "left_space")
              {
                return currency+" "+new_price;

              }

        }

        function urldecode(str) {
   return decodeURIComponent((str+'').replace(/\+/g, '%20'));
}
  jQuery(this).on( 'reset_data', function() {

    jQuery('.extra-options-accordion').css('display', 'none');

     jQuery('.single_add_to_cart_button.button').addClass('disabled wc-variation-selection-needed');

  });
        // when variation is found, do something
        jQuery(this).on( 'found_variation', function( event, variation ) {
                                jQuery('.wf_requiremnts_error').remove();

              jQuery('.single_add_to_cart_button.button').removeClass('disabled');

/*                  console.log(variation);
*/                  jQuery('.css-checkbox').attr('checked', false); // Unchecks it
                  jQuery('input:checkbox:checked:not(.checked_by_default)').prop('checked', false);
                    jQuery('input:radio:checked:not(.checked_by_default)').prop('checked', false);


                  jQuery('input:checkbox:disabled').prop('disabled', false);

                  jQuery("select[name^='add_extra_option_radio']:not(.checked_by_default)").prop('selectedIndex',0);
    jQuery('.extra-options-accordion').css('display', 'block');


    wf_variation_extra_options =[];
       if (variation.variation_custom_select){
               jQuery('.extra-options-accordion li[id^="extra_option_category_id"]').css('display', 'none');

                  var el = jQuery('.extra-options-accordion');
var map = {};

jQuery('.extra-options-accordion li').each(function() { 
    var el = jQuery(this);
    map[el.attr('cat-id')] = el;
});

for (var i = 0, l = variation.variation_custom_select.length; i < l; i ++) {
    if (map[variation.variation_custom_select[i]]) {
        el.append(map[variation.variation_custom_select[i]]);
    }
}


               



            wf_variation_id = variation.variation_id;
        


    jQuery.each(variation.variation_custom_select, function (i, value) {
/*        console.log(value);
*/        wf_variation_extra_options.push(value);

       // jQuery('li #extra_option_category_id\\['+value+'\\]').css('display', 'block');

                jQuery('.extra-options-accordion li[id^="extra_option_category_id\\['+value.toString()+'\\]"]').css('display', 'block');

    });

     }//end if user have selected extra options for variations
     else
     {
       wf_variation_id = 0 ;
           wf_variation_extra_options =[];

                     jQuery('.extra-options-accordion li[id^="extra_option_category_id"]').css('display', 'none');

     }




     jQuery.each(wf_global_extra_options_array, function (i, value) {

       // jQuery('li #extra_option_category_id\\['+value+'\\]').css('display', 'block');
       if(value >0)
       {
                console.log(value);

                        jQuery('.extra-options-accordion li[id^="extra_option_category_id\\['+value.toString()+'\\]"]').css('display', 'block');

       }

    });  


         jQuery.each(wf_global_variations_extra_options_array, function (i, value) {
        console.log(value);

       // jQuery('li #extra_option_category_id\\['+value+'\\]').css('display', 'block');

                jQuery('.extra-options-accordion li[id^="extra_option_category_id\\['+value.toString()+'\\]"]').css('display', 'block');

    });  

/*                    if the user have selected extra options for variations*/                  
              
           




            jQuery(function($){
              var   price_decimals = <?php echo wc_get_price_decimals(); ?>;
              var thousand_seperator = '<?php echo wc_get_price_thousand_separator(); ?>';
              var decimal_seperator = '<?php echo wc_get_price_decimal_separator(); ?>';
              var currency_position = '<?php echo get_option( 'woocommerce_currency_pos' ); ?>';
                var price = <?php echo $product->get_price(); ?>,
                    currency = '<?php echo get_woocommerce_currency_symbol(); ?>';

                      price= variation.display_price;

                      var additional_price = 0;
                      var product_total = 0;
                      var product_total_single =0;




                  jQuery(".extra-options-accordion input[type=checkbox]:checked, .extra-options-accordion select:selected").each(function(){

                     var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price += parseFloat(obj_current_extra.price_float);   

                 //  additional_price += parseFloat(this.value);

                     });

               product_total_single = parseFloat(price)+parseFloat(additional_price);


                   

                         product_total = parseFloat(product_total_single * jQuery('[name=quantity]').val());

                        $('.wf_product_view .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));
                        $('.wf_product_view .price:first').html( woofood_format_price(product_total));

                         $('.type-product .summary  .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));

                   $('.type-product .summary .price:first').html(woofood_format_price(product_total));


                jQuery('[name=quantity]').change(function(){

                    additional_price_checkbox=0;
                   additional_price_radio=0;

                  jQuery(".extra-options-accordion input[type=checkbox]:checked,  .extra-options-accordion select:selected").each(function(){

                   //additional_price_checkbox += parseFloat(this.value);

                    var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price_checkbox += parseFloat(obj_current_extra.price_float); 

                     });

                   jQuery(".extra-options-accordion input[type=radio]:checked,  .extra-options-accordion select:selected").each(function(){

                  // additional_price_radio += parseFloat(this.value);
                   var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price_radio += parseFloat(obj_current_extra.price_float); 

                     });

               product_total_single = parseFloat(price)+parseFloat(additional_price_checkbox)+parseFloat(additional_price_radio);


                    if (!(this.value < 1)) {

                         product_total = parseFloat(product_total_single * this.value);

                        $('.wf_product_view .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));
                       $('.wf_product_view .price:first').html( woofood_format_price(product_total));

                         $('.type-product .summary  .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));

                   $('.type-product .summary .price:first').html( woofood_format_price(product_total));


                    }
                });




                jQuery(".extra-options-accordion input[type=checkbox], .extra-options-accordion select").change(function(){
                additional_price_checkbox=0;
                   additional_price_radio=0;
                  jQuery(".extra-options-accordion input[type=checkbox]:checked").each(function(){

                  // additional_price_checkbox += parseFloat(this.value);


                    var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price_checkbox += parseFloat(obj_current_extra.price_float); 

                     });


            jQuery(".extra-options-accordion input[type=radio]:checked, .extra-options-accordion select:selected").each(function(){

                 //  additional_price_radio += parseFloat(this.value);
                 var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price_radio += parseFloat(obj_current_extra.price_float);

                     });


                product_total_single = parseFloat(price)+parseFloat(additional_price_checkbox)+parseFloat(additional_price_radio);

                         product_total = parseFloat(product_total_single * jQuery('[name=quantity]').val());

                $('.wf_product_view .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));
                $('.wf_product_view .price:first').html( woofood_format_price(product_total));


                  $('.type-product .summary  .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));

                   $('.type-product .summary .price:first').html( woofood_format_price(product_total));


              

                   });




                jQuery("input[type=radio], .extra-options-accordion select").change(function(){
                additional_price_checkbox=0;
                   additional_price_radio=0;
                  jQuery(".extra-options-accordion input[type=checkbox]:checked, .extra-options-accordion select:selected").each(function(){

                  // additional_price_checkbox += parseFloat(this.value);

                   var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price_checkbox += parseFloat(obj_current_extra.price_float);

                     });


            jQuery(".extra-options-accordion input[type=radio]:checked, .extra-options-accordion select:selected").each(function(){

                  // additional_price_radio += parseFloat(this.value);

                  var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price_radio += parseFloat(obj_current_extra.price_float);

                     });


                product_total_single = parseFloat(price)+parseFloat(additional_price_checkbox)+parseFloat(additional_price_radio);

                         product_total = parseFloat(product_total_single * jQuery('[name=quantity]').val());

                $('.wf_product_view .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));
                $('.wf_product_view .price:first').html( woofood_format_price(product_total));

                  $('.type-product .summary  .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));

                   $('.type-product .summary .price:first').html( woofood_format_price(product_total));


              

                   });









            });

              });



    



///simple product here/

        jQuery(function($){

            var   price_decimals = <?php echo wc_get_price_decimals(); ?>;
              var thousand_seperator = '<?php echo wc_get_price_thousand_separator(); ?>';
              var decimal_seperator = '<?php echo wc_get_price_decimal_separator(); ?>';
              var currency_position = '<?php echo get_option( 'woocommerce_currency_pos' ); ?>';

                var price = <?php echo $product->get_price(); ?>,
                    currency = '<?php echo get_woocommerce_currency_symbol(); ?>';

                      var additional_price = 0;
                      var product_total = 0;
                      var product_total_single =0;
                      var additional_price_radio = 0;
                      var additional_price_checkbox = 0;





                  jQuery(".extra-options-accordion input:checked, .extra-options-accordion select:selected").each(function(){

                  // additional_price += parseFloat(this.value);

                   var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price += parseFloat(obj_current_extra.price_float);

                     });

               product_total_single = parseFloat(price)+parseFloat(additional_price);


                   

                         product_total = parseFloat(product_total_single * jQuery('[name=quantity]').val());

                        $('.wf_product_view .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));
                        $('.wf_product_view .price:first').html( woofood_format_price(product_total));


                          $('.type-product .summary  .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));

                   $('.type-product .summary .price:first').html( woofood_format_price(product_total));


                   


                jQuery('[name=quantity]').change(function(){
                    additional_radio_price=0;

                    additional_price=0;
                  jQuery(".extra-options-accordion input:checked , .extra-options-accordion select:selected").each(function(){

                  // additional_price += parseFloat(this.value);
                  var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price += parseFloat(obj_current_extra.price_float);

                     });

               product_total_single = parseFloat(price)+parseFloat(additional_price);


                    if (!(this.value < 1)) {

                         product_total = parseFloat(product_total_single * this.value);

                        $('.wf_product_view .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));
                        $('.wf_product_view .price:first').html( woofood_format_price(product_total));

                          $('.type-product .summary  .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));

                   $('.type-product .summary .price:first').html( woofood_format_price(product_total));


                    }
                });




                jQuery(".extra-options-accordion input, .extra-options-accordion select").change(function(){
                  additional_price = 0;
                  jQuery(".extra-options-accordion input:checked").each(function(){

                   //additional_price += parseFloat(this.value);

                   var current_extra =  urldecode(this.value);
                     var obj_current_extra = jQuery.parseJSON(current_extra);
                     additional_price += parseFloat(obj_current_extra.price_float);

                     });

                product_total_single = parseFloat(price)+parseFloat(additional_price);

                         product_total = parseFloat(product_total_single * jQuery('[name=quantity]').val());

                $('.wf_product_view .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));
                $('.wf_product_view .price:first').html( woofood_format_price(product_total));


                  $('.type-product .summary  .woocommerce-variation-price .price .woocommerce-Price-amount:first').html( woofood_format_price(product_total));

                   $('.type-product .summary .price:first').html( woofood_format_price(product_total));


              

                   });






            });

        </script>

    <?php
  }
}

    /*Total Product Price Calculate     */


//Add Simple Product Custom Fields//


    add_filter( 'woocommerce_product_data_tabs', 'woofood_add_extra_options_tab' , 99 , 1 );
function woofood_add_extra_options_tab( $product_data_tabs ) {
    $product_data_tabs['woofood-extra-options'] = array(
        'label' => __( 'WooFood Extra Options', 'woofood-plugin' ),
        'target' => 'woofood_extra_options_product_data',
          'class'     => array( 'show_if_simple' ),

    );
    return $product_data_tabs;
}


add_action('woocommerce_product_data_panels', 'wf_product_extra_options_select_fields');

//save extra_options_select on simple//
add_action('woocommerce_process_product_meta', 'wf_save_extra_options_select_simple');


function wf_product_extra_options_select_fields()
{
    global $woocommerce, $post;
   echo '<div id="woofood_extra_options_product_data" class="panel woocommerce_options_panel">';
    
      

      //woocommerce multiselect
            $extra_option_categories = get_terms('extra_option_categories' ,  array('hide_empty' => false));
            $allextraoptionscategories = array();
            foreach ($extra_option_categories as $current_extra_option_category){

 $allextraoptionscategories[$current_extra_option_category->term_id] = $current_extra_option_category->name;

            }
            //add value no selected category to array///
 $allextraoptionscategories["0"] = esc_html__('No Extra Category','woofood-plugin');

      
  
    $selected_extra_options = get_post_meta($post->ID, 'extra_options_select', true) ;

     woocommerce_form_field( 'extra_options_select[]', array(
        'type'          => 'multiselect_draggable',
        'label'         => esc_html__('Select Extra Options Categories', 'woofood-plugin'),
        'desc_tip'    => true,
        // 'wrapper_class' => 'form-row',
        'description' => esc_html__( 'Select Extra Option Categories you want to be visible on this product.', 'woofood-plugin' ),
        'placeholder'   => esc_html__('Select Categories', 'woofood-plugin'),
        'options'       => $allextraoptionscategories
        ), $selected_extra_options);

      //woocommerce multiselect

      // Hidden field
      woocommerce_wp_hidden_input(
      array( 
        'id'    => '_hidden_field', 
        'value' => 'hidden_value'
        )
      );
   
  echo "</div>"; 
 
}


/** Save new fields for simple products */
function wf_save_extra_options_select_simple( $post_id) {
    
    

     // Select
    $extra_options_select = $_POST['extra_options_select'];
 
   update_post_meta( $post_id, 'extra_options_select',  $extra_options_select );

}



//Add Simple Product Custom Fields//


// Add Variation Custom fields

//Display Fields in admin on product edit screen
add_action( 'woocommerce_product_after_variable_attributes', 'wf_variable_fields', 10, 3 );



// Create new fields for variations
function wf_variable_fields( $loop, $variation_data, $variation ) {

  echo '<div class="variation-custom-fields">';
    
      

      //woocommerce multiselect
            $extra_option_categories = get_terms('extra_option_categories' ,  array('hide_empty' => false));
            $allextraoptionscategories = array();
            foreach ($extra_option_categories as $current_extra_option_category){

 $allextraoptionscategories[$current_extra_option_category->term_id] = $current_extra_option_category->name;

            }
            //add value no selected category to array///
 $allextraoptionscategories["0"] = esc_html__('No Extra Category','woofood-plugin');


     woocommerce_form_field( 'extra_options_select['. $loop .'][]', array(
        'type'          => 'multiselect_draggable',
        'class'         => array('my-field-class form-row-wide'),
        'label'         => esc_html__('Select Extra Options Categories', 'woofood-plugin'),
        'desc_tip'    => true,
        // 'wrapper_class' => 'form-row',
        'description' => esc_html__( 'Select Extra Option Categories you want to be visible on this variation.', 'woofood-plugin' ),
        'placeholder'   => esc_html__('Select Categories', 'woofood-plugin'),
        'options'       => $allextraoptionscategories
        ), get_post_meta($variation->ID, 'extra_options_select', true));

      //woocommerce multiselect

      // Hidden field
      woocommerce_wp_hidden_input(
      array( 
        'id'    => '_hidden_field['. $loop .']', 
        'value' => 'hidden_value'
        )
      );
   
  echo "</div>"; 



}

//display extra options on variations//
// Custom Product Variation
add_filter( 'woocommerce_available_variation', 'wf_load_variation_custom_select_field', 10 ,99 );

function wf_load_variation_custom_select_field( $variations ) {

 $variations['variation_custom_select'] = get_post_meta( $variations[ 'variation_id' ], 'extra_options_select', true ); 

 return $variations; 


}

//display extra options on variations//

//Save variation fields values
add_action( 'woocommerce_save_product_variation', 'wf_save_variation_fields', 10, 2 );
/** Save new fields for variations */
function wf_save_variation_fields( $variation_id, $i) {
    
    

     // Select
    $extra_options_select = $_POST['extra_options_select'][$i];
 
   update_post_meta( $variation_id, 'extra_options_select',  $extra_options_select );

}





function wf_extra_option_categories_add_meta_fields() {
  // this will add the custom meta field to the add new term page
  ?>

  <div class="form-field">
    <label for="term_meta[category_style]"><?php esc_html_e( 'Extra Option Category Style', 'woofood-plugin' ); ?></label>
     <select  name="term_meta[category_style]" id="term_meta[category_style]" >
      <option value="accordion" >Accordion(Default)</option>
      <option value="flat">Flat</option>

      </select>

      <p class="description"><?php esc_html_e( 'Select type of Extra Option Style.' ,'woofood-plugin' ); ?></p>
  </div>


  <div class="form-field">
    <label for="term_meta[category_type]"><?php esc_html_e( 'Extra Option Category Type', 'woofood-plugin' ); ?></label>
     <select  name="term_meta[category_type]" id="term_meta[category_type]" >
      <option value="checkbox-multichoice" >Checkbox Multichoice</option>
      <option value="checkbox-limitedchoice">Checkbox Limited-Choice</option>
       <option value="radio">Single Choice Radio</option>
       <option value="select">Single Choice Select</option>

      </select>

      <p class="description"><?php esc_html_e( 'Select type of Extra Option Category. CheckBox Multichoice allow the user to select multiple options.CheckBox Limited Choicelimits the number of options that can be selected and Radio is the classic single choice' ,'woofood-plugin' ); ?></p>
  </div>


  <div class="form-field">
    <label for="term_meta[maximum_options]"><?php esc_html_e( 'Maximum Extra Options', 'woofood-plugin' ); ?></label>
     
      <input type ="number" name="term_meta[maximum_options]" id="term_meta[maximum_options]" />

      <p class="description"><?php esc_html_e( 'Select Maximum Extra options can be selected. This applies only to Checkbox Limited-Choice only' ,'woofood-plugin' ); ?></p>
  </div>


<?php
}
add_action( 'extra_option_categories_add_form_fields', 'wf_extra_option_categories_add_meta_fields', 10, 2 );






function wf_extra_option_categories_edit_meta_fields($term) {
 
  // put the term ID into a variable
  $t_id = $term->term_id;
 
  // retrieve the existing value(s) for this meta field. This returns an array
  $term_meta = get_option( "taxonomy_$t_id" ); ?>

  <tr class="form-field">
  <th scope="row" valign="top"><label for="term_meta[category_style]"><?php esc_html_e( 'Extra Option Category Style', 'woofood-plugin' ); ?></label></th>
    <td>
      
       <select  name="term_meta[category_style]" id="term_meta[category_style]" >
      <option value="accordion" <?php if (esc_attr( $term_meta['category_style']=="accordion" )){ echo " selected";}  ?>>Accordion(Default)</option>
      <option value="flat" <?php if (esc_attr( $term_meta['category_style']=="flat" )){ echo " selected";}  ?>>Flat</option>

      </select>

      <p class="description"><?php esc_html_e( 'Select type of Extra Option Style' ,'woofood-plugin' ); ?></p>
    </td>
  </tr>


  <tr class="form-field">
  <th scope="row" valign="top"><label for="term_meta[category_type]"><?php esc_html_e( 'Extra Option Category Type', 'woofood-plugin' ); ?></label></th>
    <td>
      
       <select  name="term_meta[category_type]" id="term_meta[category_type]" >
      <option value="checkbox-multichoice" <?php if (esc_attr( $term_meta['category_type']=="checkbox-multichoice" )){ echo " selected";}  ?>>Checkbox Multichoice</option>
      <option value="checkbox-limitedchoice" <?php if (esc_attr( $term_meta['category_type']=="checkbox-limitedchoice" )){ echo " selected";}  ?>>Checkbox Limited-Choice</option>
       <option value="radio" <?php if (esc_attr( $term_meta['category_type']=="radio" )){ echo " selected";}  ?>>Single Choice Radio</option>
       <option value="select" <?php if (esc_attr( $term_meta['category_type']=="select" )){ echo " selected";}  ?>>Single Choice Select</option>

      </select>

      <p class="description"><?php esc_html_e( 'Select type of Extra Option Category. CheckBox Multichoice allow the user to select multiple options.CheckBox Limited Choice limits the number of options that can be selected and Radio is the classic single choice' ,'woofood-plugin' ); ?></p>
    </td>
  </tr>

    <tr class="form-field">
    <th scope="row" valign="top"><label for="term_meta[category_type]"><?php esc_html_e( 'Maximum Extra Options', 'woofood-plugin' ); ?></label></th>
    <td>
          <input type ="number" name="term_meta[maximum_options]" id="term_meta[maximum_options]" value="<?php echo  $term_meta['maximum_options']; ?>" />


          <p class="description"><?php esc_html_e( 'Select Maximum Extra options can be selected. This applies only to <strong>Checkbox Limited-Choice</strong>' ,'woofood-plugin' ); ?></p>


     </td>
    </tr>
<?php
}
add_action( 'extra_option_categories_edit_form_fields', 'wf_extra_option_categories_edit_meta_fields', 10, 2 );



function wf_save_extra_option_categories_custom_meta( $term_id ) {
  if ( isset( $_POST['term_meta'] ) ) {
    $t_id = $term_id;
    $term_meta = get_option( "taxonomy_$t_id" );
    $cat_keys = array_keys( $_POST['term_meta'] );
    foreach ( $cat_keys as $key ) {
      if ( isset ( $_POST['term_meta'][$key] ) ) {
        $term_meta[$key] = $_POST['term_meta'][$key];
      }
    }
    // Save the option array.
    update_option( "taxonomy_$t_id", $term_meta );
  }
}  
add_action( 'edited_extra_option_categories', 'wf_save_extra_option_categories_custom_meta', 10, 2 );  
add_action( 'create_extra_option_categories', 'wf_save_extra_option_categories_custom_meta', 10, 2 );


function wf_extra_options_validation( ) { 
?>
<script>


  jQuery(document).on('ready', function () {


 jQuery(document).on('click', '.single_add_to_cart_button:not(.disabled)', function (event) {
  if(!woofoodValidateExtra())

  {
    event.preventDefault();
  }
    });
        
    


 function woofoodValidateExtra() {



var extra_option_categories_required = new Array();
  var extra_options_categories_required_ok = true;
    var variation_id = jQuery('form.cart input[name=variation_id]').val();
  




  

    jQuery('form.cart [id^="extra_option_category_id"]').each(function(){
                var response_array = {required:0, selected:0, result:true , id:0};

                var cat_id = parseInt(jQuery(this).attr('cat-id'));
            var required_selected = true;
              response_array.id = cat_id;
        if( jQuery(this).attr('required')){
          required_selected = false;
          var min_options = parseInt(jQuery(this).attr('min-options'));
          response_array.required = min_options;

      var selected_options = jQuery(this).find('input:checked, select option:selected').filter(function() {
        return (this.value.length!=0);
    }).length;
                           response_array.selected = selected_options;





  //is variable//
      if(variation_id > 0)
      {


        if(wf_variation_extra_options.includes(cat_id.toString()) || wf_global_extra_options.includes(cat_id))  
        {
          if(selected_options >= min_options)
      {
        required_selected = true;
      }
      else
      {
                required_selected = false;

      }

        }
        




      }
      else
      {


        if(selected_options >= min_options)
      {
        required_selected = true;
      }

      

      }

      

         
        }
        else
        {
            required_selected = true;

        }

          if(variation_id > 0)
      {



if(wf_variation_extra_options.includes(cat_id.toString()) || wf_global_extra_options.includes(cat_id))  
        {

                                   response_array.result = required_selected;
                                   extra_option_categories_required.push(response_array);


        }





      }
      else
      {
                                   response_array.result = required_selected;
                                           extra_option_categories_required.push(response_array);


      }

        //extra_option_categories_required['cat_'+cat_id+''] = response_array;

    });




    console.log(extra_option_categories_required);





    jQuery.each(extra_option_categories_required,function(i, value){

      //is variable//
      if(variation_id > 0)
      {

        if(wf_variation_extra_options.includes(value.id.toString()) || wf_global_extra_options.includes(value.id))
        {


                    if(value.result == false )
                  {
                    extra_options_categories_required_ok = false;
                  }
                  if(value.result == true)
                  {
                    if(jQuery('.wf_requiremnts_error#error_'+value.id).length)
                    {
                      jQuery('.wf_requiremnts_error#error_'+value.id).remove();


                        jQuery('#extra_option_category_id\\['+value.id+'\\]').removeClass("error");
                    
                    }
                  }

        }






      }
      //is simple
      else
      {
                  if(value.result == false)
                {
                  extra_options_categories_required_ok = false;
                }
                if(value.result == true)
                {
                  if(jQuery('.wf_requiremnts_error#error_'+value.id).length)
                  {
                              jQuery('.wf_requiremnts_error#error_'+value.id).css('display', 'none');

                      jQuery('#extra_option_category_id\\['+value.id+'\\]').removeClass("error");
                  
                  }
                }

      }

      
});


if(extra_options_categories_required_ok ==false)
{
            jQuery.each(extra_option_categories_required,function(i, value){
        if(value.result == false)
        {
          if(!jQuery('.wf_requiremnts_error#error_'+value.id).length)
          {
              jQuery('#extra_option_category_id\\['+value.id+'\\]').addClass("error");
              var option_text = "";  
              if(value.required == 1)
              {
                  option_text = wf_option_text;
              }
              else
              {
                                  option_text = wf_options_text;


              }
          var message  = '<div class="wf_requiremnts_error" id="error_'+value.id+'">'+wf_minimum_options_required.replace("%%options%%", value.required).replace("%%option_text%%", option_text)+'</div>';

          jQuery(message).insertBefore( '#extra_option_category_id\\['+value.id+'\\]' );
          }
        

          //jQuery().insertBefore(  );


          
        }
});


}


            return extra_options_categories_required_ok;



}

    });
   
</script>

<?php
}
add_filter( 'wp_footer', 'wf_extra_options_validation', 10, 5 );  



?>