<?php

function woofood_menu_category($atts)
{
if(!is_array($atts))
{
  $atts = array();
}

$transient = get_transient( 'woofood_accordion_'.sanitize_key(json_encode($atts)));
if($transient)
{
  return $transient;
}
$html_export = "";
$taxonomy     = 'product_cat';
//$orderby      = '';  
$show_count   = 0;      
$pad_counts   = 0;      
$hierarchical = 1;      
$title        = '';  
$empty        = 0;

$args = array(
'taxonomy'     => $taxonomy,
//'orderby'      => $orderby,
'show_count'   => $show_count,
'pad_counts'   => $pad_counts,
'hierarchical' => $hierarchical,
'title_li'     => $title,
'hide_empty'   => $empty
);


$all_categories = array();

$text_color ="";
$background_color ="";
$border_color = "";
$open ="";
if ( !empty( $atts['text_color'] ))
{
  $text_color =$atts['text_color'];
}
if ( !empty( $atts['open'] ))
{
  $open =$atts['open'];
}
if ( !empty( $atts['background_color'] ))
{
  $background_color = $atts['background_color'];
}
$border_style="";
if ( !empty( $atts['border_color'] ))
{
  $border_color = $atts['border_color'];
  $border_style ='border: 1px solid '.$border_color.';';
}

if ( !empty( $atts['ids'] ))
{
$atts['ids'] = trim($atts['ids']); 


}

if ( !empty( $atts['category_slug'] ))
{
$atts['category_slug'] = array_map( 'trim', str_getcsv( $atts['category_slug'], ',' ) );

foreach($atts['category_slug'] as $cat_slug)
{
$all_categories[] = get_term_by( 'slug', $cat_slug, 'product_cat' );

}

}
else
{
  if ( !empty( $atts['ids'] ))
{
    $custom_category = new stdClass();

  if ( !empty( $atts['title'] ))
  {
   $custom_category->name = $atts['title'];
   $custom_category->slug = strtolower($atts['title']);

  }
  else
  {
       $custom_category->name = '';
       $custom_category->slug = mt_rand(100000, 999999);

  }

  $all_categories[] = $custom_category;


}
else
{
  $all_categories = get_categories( $args );
}


}

foreach ($all_categories as $cat) {
if($cat->category_parent == 0) {
$category_id = $cat->term_id; 
$collapsed = "";  
$panel_collapse = "panel-collapse collapse show";
$aria_expanded = 'aria-expanded="true"';    
if($open!="yes")
{
  $collapsed = "collapsed";
  $aria_expanded = 'aria-expanded="false"';    
  $panel_collapse = "panel-collapse collapse";

}

$html_export .=' <div class="woofood-accordion">';
$html_export.= ' <a class="'.$collapsed .'" data-toggle="collapse" data-target="#wf-accordion-'.sanitize_key(rawurlencode($cat->slug)).'" href="#wf-accordion-'.sanitize_key(rawurlencode($cat->slug)).'" '.$aria_expanded.' aria-controls="collapseThree"> ';

$html_export .=' <div class="panel-heading panel-heading-title" style="
          '.$border_style.'
              background:'.$background_color.';
            "
            >';
  if (is_array($atts)):       
 if (array_key_exists("icon", $atts) && count($all_categories) == 1):
  $html_export .='<img src="'.$atts["icon"].'"/>';

endif;
endif;
$html_export .='<h4 class="panel-title" style="color: '.$text_color.'">';
$html_export .=$cat->name;
$html_export .= '</h4>';
$html_export .=' <div class="accordion-plus-icon" >
<i class="woofood-icon-plus-circled" style="color: '.$text_color.'" ></i> 
</div>  ';
$html_export .= '</div>';
$html_export .= '</a>';


$html_export .= '  <div id="wf-accordion-'.sanitize_key(rawurlencode($cat->slug)).'" class="'.$panel_collapse.'" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">';

//$html_export .= do_shortcode('[product_category category="'.$cat->slug.'" per_page="-1"]');
  $attributes= array();

  if(!empty($atts["category_slug"]))
  {
    $attributes["category"] = $cat->slug;


  }
  else if(empty($atts["category_slug"]) && !empty($atts["ids"]) )
  {
    
  }
  else if(empty($atts["category_slug"]) && empty($atts["ids"]))
  {
    $attributes["category"] = $cat->slug;

  }
  else
  {

  }

  if(!empty($atts["ids"]))
  {
    $attributes["ids"] = $atts["ids"];


  }

  if(!empty($atts["orderby"]))
  {
    $attributes["orderby"] = $atts["orderby"];


  }
  else
  {
        $attributes["orderby"] ="menu_order";

  }
  
  
  if(!empty($atts["order"]))
  {
    $attributes["order"] = $atts["order"];


  }
  else
  {
        $attributes["order"] ="ASC";

  }

  ob_start();
woofood_products($attributes);
$html_export .= ob_get_clean();
$html_export .='
</div>
</div>
</div>';                     
}}//end foreach

$all_transients = get_transient('woofood_all_transient_keys');
if(!is_array($all_transients)) 
{
  $all_transients = array();
}
else
{
  $all_transients[]= 'woofood_accordion_'.sanitize_key(json_encode($atts));
}
set_transient( 'woofood_all_transient_keys', $all_transients, 0);

set_transient( 'woofood_accordion_'.sanitize_key(json_encode($atts)), $html_export, 3600);
return $html_export;

} 





function woofood_menu($atts)
{

$taxonomy     = 'product_cat';
//$orderby      = '';  
$show_count   = 0;      
$pad_counts   = 0;      
$hierarchical = 1;      
$title        = '';  
$empty        = 0;

$args = array(
'taxonomy'     => $taxonomy,
//'orderby'      => $orderby,
'show_count'   => $show_count,
'pad_counts'   => $pad_counts,
'hierarchical' => $hierarchical,
'title_li'     => $title,
'hide_empty'   => $empty
);

$all_categories = array();
if ( !empty( $atts['url'] ))
{
$all_categories[] = get_term_by( 'slug', $atts['url'], 'product_cat' );

}
else
{
$all_categories = get_categories( $args );


}












$woofood_theme_style ="style-1";
if ($woofood_theme_style=="default" || $woofood_theme_style=="style-1" || $woofood_theme_style=="style-3")
{
?>
<?php
foreach ($all_categories as $cat) {
if($cat->category_parent == 0) {
$category_id = $cat->term_id;       
?>
<div class="woofood-accordion">

<a class="collapsed" data-toggle="collapse" data-target="#<?php echo $cat->slug;?>" href="#<?php echo $cat->slug;?>" aria-expanded="false" aria-controls="collapseThree"> 
<div class="panel-heading panel-heading-title ">


<h4 class="panel-title">


<?php echo $cat->name;?>
</h4>

<div class="accordion-plus-icon" >
<i class="woofood-icon-plus-circled" ></i> 
</div>  

</div>
</a>   











<div id="<?php echo $cat->slug;?>" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
<div class="panel-body">
<?php 
  $attributes= array('category'=>$cat->slug);
  woofood_products($attributes);
?>

</div>
</div>
</div>


<?php


}       
}
?>









<?php 

}//end if selected theme style



if ($woofood_theme_style == "style-2")
{
?>
<div class="square-container">

<?php


foreach ($all_categories as $cat) {
if($cat->category_parent == 0) {

$category_id = $cat->term_id;  

$thumbnail_id = get_woocommerce_term_meta($category_id, 'thumbnail_id', true);
// get the image URL for parent category
$category_image = wp_get_attachment_url($thumbnail_id);
$category_name = $cat->name;  
$category_slug  = $cat->slug;
?>


<a qv-id="<?php echo $category_slug;?>" class="button woofood-quickview-category-button">
<div class="square"  >
<div class="name"><?php echo $category_name; ?></div>

<div class="content" style="background:url(<?php echo $category_image; ?>); background-size:contain; background-position:center;">

</div>

</div>
</a>

<?php







}//end if is parent cateory
}//end for each
?>
</div>
<?php


}//end is theme style2



}
add_shortcode('woofood_menu', 'woofood_menu');
add_shortcode('woofood_menu_category', 'woofood_menu_category');
add_shortcode('woofood_accordion', 'woofood_menu_category');


add_shortcode('foodmaster_accordion', 'woofood_menu_category');
add_shortcode('foodmaster_menu', 'woofood_menu_category');
add_shortcode('foodmaster_menu_category', 'woofood_menu_category');


function woofood_product_loop($product, $ajax_enabled=false, $show_description =false)
{ 
  $has_extra_options = woofood_check_if_has_extra_options($product->get_id());
  $additional_classes = "";
  if(!$product->is_purchasable())
  {
    $additional_classes = " unavailable";
  }
  ?>
  <li class="woofood-product-loop <?php echo $additional_classes; ?>">
    <div class="product-image">
        <?php echo get_the_post_thumbnail($product->get_id(), apply_filters('woofood_accordion_image_size','woofood-accordion')); ?>
  </div>
    <div class="product-title">
<span><?php echo $product->get_title(); ?></span>
    <?php if($show_description): ?>
    <div class="product-short-descr">
    <span><?php echo $product->get_short_description(); ?></span>
    </div>
    <?php endif; ?>
  </div>
  <div class="product-price">
  <?php echo $product->get_price_html(); ?>
  </div>
  <div class="product-button">
  <?php if($ajax_enabled):?>
    <a qv-id="<?php echo $product->get_id(); ?>" class="woofood-quickview-button button"><?php echo  apply_filters( 'woofood_product_add_to_cart_text_ajax', __( 'Select', 'woofood-plugin' ) ) ?></a>
    <?php else:?>
<?php
        $link = array(
            'url'   => '',
            'label' => '',
            'class' => ''
        );
        switch ( $product->get_type() ) {
            case "variable" :
                $link['url']    = apply_filters( 'woofood_product_add_to_cart_link_variable', get_permalink( $product->get_id() ) );
                $link['label']  = apply_filters( 'woofood_product_add_to_cart_text_variable', __( 'Select', 'woofood-plugin' ) );
            break;
            case "grouped" :
                $link['url']    = apply_filters( 'grouped_add_to_cart_url', get_permalink( $product->get_id() ) );
                $link['label']  = apply_filters( 'grouped_add_to_cart_text', __( 'View', 'woofood-plugin' ) );
            break;
            case "external" :
                $link['url']    = apply_filters( 'external_add_to_cart_url', get_permalink( $product->get_id() ) );
                $link['label']  = apply_filters( 'external_add_to_cart_text', __( 'Read More', 'woofood-plugin' ) );
            break;
            default :
                if ( $product->is_purchasable() && !$has_extra_options ) {
                    $link['url']    = apply_filters( 'woofood_product_add_to_cart_link_simple', esc_url( $product->add_to_cart_url() ) );
                    $link['label']  = apply_filters( 'woofood_product_add_to_cart_text_simple', __( 'Add To Cart', 'woofood-plugin' ) );
                    $link['class']  = apply_filters( 'add_to_cart_class', 'add_to_cart_button' );
                } else if ( $product->is_purchasable() && $has_extra_options ) {
                    $link['url']    = apply_filters( 'woofood_product_add_to_cart_link_variable', get_permalink( $product->get_id() ) );
                    $link['label']  = apply_filters( 'woofood_product_add_to_cart_text_variable', __( 'Select', 'woofood-plugin' ) );
                    $link['class']  = apply_filters( 'add_to_cart_class', 'add_to_cart_button' );
                }

                else {
                    $link['url']    = apply_filters( 'not_purchasable_url', get_permalink( $product->get_id() ) );
                    $link['label']  = apply_filters( 'not_purchasable_text', __( 'Read More', 'woofood-plugin' ) );
                }
            break;
        }
        ?>

    <?php 


        if ( $product->get_type() === 'simple' && !$has_extra_options) {
            ?>
            <form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" class="cart" method="post" enctype="multipart/form-data">
<?php
                   
                    echo sprintf( '<button type="submit" data-product_id="%s" data-product_sku="%s" data-quantity="1" class="%s button product_type_simple ajax_add_to_cart ">%s</button>', esc_attr( $product->get_id() ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_html( $link['label'] ) );
                ?>
            </form>
<?php
        } else {
          echo apply_filters( 'woocommerce_loop_add_to_cart_link', sprintf('<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s button product_type_%s">%s</a>', esc_url( $link['url'] ), esc_attr( $product->get_id() ), esc_attr( $product->get_sku() ), esc_attr( $link['class'] ), esc_attr( $product->get_type() ), esc_html( $link['label'] ) ), $product, $link );
        }

        ?>

    <?php endif;?>

</div>
</li>
<?php

}
function woofood_products($attributes)
{
  $ajax_enabled = false;
  $short_description = false;
  $woofood_options = get_option('woofood_options');
  $woofood_enable_ajax_option = isset($woofood_options['woofood_enable_ajax_option']) ? $woofood_options['woofood_enable_ajax_option'] : null ;
  $woofood_enable_product_short_description_option = isset($woofood_options['woofood_enable_product_short_description_option']) ? $woofood_options['woofood_enable_product_short_description_option'] : null ;

if ($woofood_enable_ajax_option) 
{
  $ajax_enabled = true;
}
if($woofood_enable_product_short_description_option)
{
  $short_description = true;

}
  $category = "";
  $ids = array();
  $order = "DESC";
  $orderby="date";
  $args = array();
  $args["orderby"] = "date";
  $args["order"] = "DESC";
  $args["limit"] = -1;


    if (array_key_exists("category", $attributes)  && $attributes["category"]!="")
  {
    $args["category"] = array($attributes["category"]);

  }
  if( array_key_exists("ids", $attributes)  && $attributes["ids"]!="")
  {
    $args["include"] = explode(",", $attributes["ids"]);

  }
  if(array_key_exists("order", $attributes) && $attributes["order"]!="")
  {
  $args["order"]=$attributes["order"];

  }
  if(array_key_exists("orderby", $attributes) && $attributes["orderby"]!="")
  {
  $args["orderby"]=$attributes["orderby"];

  }
  

    

  
  $args["status"]="publish";


  $products = wc_get_products($args);
  $products = wc_products_array_orderby( $products, $args["orderby"], $args["order"] );
  echo '<div class="woofood-products-wrapper">';
      echo '<ul class="woofood-products">';
  foreach($products as $product)
  

  {

    woofood_product_loop($product, $ajax_enabled, $short_description);
  }
  echo '</ul>';
  echo '</div>';


}
function woofood_check_if_has_extra_options($id)
{
  global $woocommerce;
  $product =  wc_get_product($id);
   $terms = get_the_terms( $id, 'product_cat' );
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
 $extra_option_category_global_categories =  get_term_meta( $current_extra_option_category->term_id, 'global_categories', true );

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

      $variable_product = new WC_Product_Variable( $id);
      $variations = $variable_product->get_available_variations();
     // $extra_options_for_all_variations = get_post_meta( $post->ID, 'extra_options_select', true ); 
      $extra_options_for_all_variations = array();
    
    
    if(is_array($extra_options_for_all_variations) && !empty($extra_options_for_all_variations))
    {
      $global_variation_extra_option_categories = $extra_options_for_all_variations;
        // $all_selected_extra_option_categories[] = $extra_options_for_all_variations;
          
      foreach($extra_options_for_all_variations as $current_extra_options_for_all_variations)
      {
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
       $simple_selected_extra_option_categories = get_post_meta($id, 'extra_options_select', true ); 




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
  if(!empty($all_selected_extra_option_categories))
  {
    return true;
  }
  else
  {
        return false;

  }
  
}



function woofood_tabs_shortcode($atts, $content = null)
{

$taxonomy     = 'product_cat';
//$orderby      = '';  
$show_count   = 0;      
$pad_counts   = 0;      
$hierarchical = 1;      
$title        = '';  
$empty        = 0;

$args = array(
'taxonomy'     => $taxonomy,
//'orderby'      => $orderby,
'show_count'   => $show_count,
'pad_counts'   => $pad_counts,
'hierarchical' => $hierarchical,
'title_li'     => $title,
'hide_empty'   => $empty
);

if(!is_array($atts))
{
  $atts = array();
}

$all_categories = array();

$text_color ="";
$background_color ="";
$border_color = "";
if ( !empty( $atts['text_color'] ))
{
  $text_color =$atts['text_color'];
}
if ( !empty( $atts['background_color'] ))
{
  $background_color = $atts['background_color'];
}
$border_style="";
if ( !empty( $atts['border_color'] ))
{
  $border_color = $atts['border_color'];
  $border_style ='border: 1px solid '.$border_color.';';
}

if ( !empty( $atts['ids'] ))
{
$atts['ids'] = trim($atts['ids']); 


}

if ( !empty( $atts['category_slug'] ))
{
$atts['category_slug'] = array_map( 'trim', str_getcsv( $atts['category_slug'], ',' ) );

foreach($atts['category_slug'] as $cat_slug)
{
$all_categories[] = get_term_by( 'slug', $cat_slug, 'product_cat' );

}

}
else
{
  if ( !empty( $atts['ids'] ))
{
    $custom_category = new stdClass();

  if ( !empty( $atts['title'] ))
  {
   $custom_category->name = $atts['title'];
   $custom_category->slug = strtolower($atts['title']);

  }
  else
  {
       $custom_category->name = '';
       $custom_category->slug = mt_rand(100000, 999999);

  }

  $all_categories[] = $custom_category;


}
else
{
  $all_categories = get_categories( $args );
}


}

  if(!isset($atts["orderby"]))
  {
        $atts["orderby"] ="menu_order";


  }
  
  
  
  if(!isset($atts["order"]))
  {
  $atts["order"] ="ASC";


  }


 
  $attribute_mapping = woofood_map_shortcodes($content);
  if(is_array($attribute_mapping) && array_key_exists("woofood_tab_item", $attribute_mapping))
  {
      $tab_items_attributes = $attribute_mapping["woofood_tab_item"];

  }
  if(!empty($tab_items_attributes))
  {


  ob_start();
  ?>
  <div class="woofood-tabs-wrapper">
    <ul class="nav justify-content-center woofood-tabs-menu">
                              <?php foreach($tab_items_attributes as $index => $attributes): ?>

                           <?php  $attributes["index"] = $index; 
                                  if(!isset($attributes["orderby"]))
                                  {
                                    $attributees["orderby"] = $atts["orderby"];

                                  }
                                  if(!isset($attributes["order"]))
                                  {
                                    $attributees["order"] = $atts["order"];

                                  }

                           ?>

                                <?php echo  woofood_tab_menu_item($attributes); ?>


                              <?php endforeach; ?>  



    </ul>
                      <div class="tab-content" id="nav-tabContent">
                        <?php foreach($tab_items_attributes as $index => $attributes): ?>
                           <?php  $attributes["index"] = $index;
                                if(!isset($attributes["orderby"]))
                                  {
                                    $attributees["orderby"] = $atts["orderby"];

                                  }
                                  if(!isset($attributes["order"]))
                                  {
                                    $attributees["order"] = $atts["order"];

                                  }



                            ?>
                            <?php echo  woofood_tab_content($attributes); ?>
                          <?php endforeach; ?>  


                      </div>
  </div>

  <?php
   }
   else
   {
    ob_start();
    ?>


 <div class="woofood-tabs-wrapper">
    <ul class="nav justify-content-center woofood-tabs-menu">
                              <?php foreach($all_categories as $index => $category): ?>


                           <?php 
                            $attributes = array();
                            $attributes["index"] = $index;
                            $attributes["category_slug"] = $category->slug;
                            $attributes["title"] = $category->name;
                            $attributes["orderby"] = $atts["orderby"];
                            $attributes["order"] = $atts["order"];


                             ?>

                                <?php echo  woofood_tab_menu_item($attributes); ?>


                              <?php endforeach; ?>  



    </ul>
                      <div class="tab-content" id="nav-tabContent">
                        <?php foreach($all_categories as $index => $category): ?>
                           <?php  

                             $attributes = array();
                            $attributes["index"] = $index;
                            $attributes["category_slug"] = $category->slug;
                            $attributes["title"] = $category->name;
                            $attributes["orderby"] = $atts["orderby"];
                            $attributes["order"] = $atts["order"];


                            ?>
                            <?php echo  woofood_tab_content($attributes); ?>
                          <?php endforeach; ?>  


                      </div>
  </div>
<?php
   }
  return ob_get_clean();

}

add_shortcode('woofood_tabs', 'woofood_tabs_shortcode');
add_shortcode('woofood_tab_menu_item', 'woofood_tab_menu_item');
add_shortcode('woofood_tab_item', 'woofood_tab_item');

function woofood_tab_item($atts)
{
  return "";
}

function woofood_tab_menu_item($atts)
{
  ob_start();
  ?>
   <li class="nav-item">
    <a class="nav-link <?php if($atts["index"] ==0){echo "active show";} ?>" data-toggle="tab" role="tab" aria-controls="wf-tab-<?php echo $atts["index"]; ?>" id="nav-wf-tab-<?php echo $atts["index"]; ?>" href="#wf-tab-<?php echo $atts["index"]; ?>" style="
              background:<?php echo isset($atts["BackgroundColor"]) ? $atts["BackgroundColor"] : '';  ?>;
              border-color:<?php echo isset($atts["borderColor"]) ? $atts["borderColor"] : ''; ?>;
              color: <?php echo isset($atts["titleTextColor"]) ? $atts["titleTextColor"] : ''; ; ?>;"
            > <?php if (isset($atts["icon"])): ?>
            <img src="<?php echo $atts["icon"];?>"/>
<?php endif;?><?php echo isset($atts["title"]) ? esc_attr($atts["title"]) : "" ; ?></a>
  </li>
  <?php
  return ob_get_clean();
}





function woofood_tab_content($atts)
{ 
  ob_start();
  ?>
  <div class="tab-pane fade  <?php if($atts["index"] ==0){echo "show active";} ?>" id="wf-tab-<?php echo $atts["index"]; ?>" role="tabpanel" aria-labelledby="nav-wf-tab-<?php echo $atts["index"]; ?>">
 <?php
  $attributes = array('columns'=> isset($atts["columns"]) ? $atts["columns"] : "2"  , "orderby"=>isset($atts["orderby"]) ? $atts["orderby"] : "",  "order"=>isset($atts["order"]) ? $atts["order"] : "" , 'category'=>isset($atts["category_slug"]) ? $atts["category_slug"] :"",   'ids'=>isset($atts["ids"])? $atts["ids"] : "");
            woofood_products($attributes);
              ?>
    </div>
  <?php
  return ob_get_clean();
}
function woofood_map_shortcodes($str, $att = null) {
    $res = array();
    $reg = get_shortcode_regex(array('woofood_tab_item'));
    preg_match_all('~'.$reg.'~',$str, $matches);
 



    foreach($matches[2] as $key => $name) {
        $parsed = shortcode_parse_atts($matches[3][$key]);
        $parsed = is_array($parsed) ? $parsed : array();

        if(array_key_exists($name, $res)) {
            $arr = array();
            if(is_array($res[$name])) {
                $arr = $res[$name];
            } else {
                $arr[] = $res[$name];
            }

            $arr[] = array_key_exists($att, $parsed) ? $parsed[$att] : $parsed;
            $res[$name] = $arr;

        } else {
            $res[$name][] = array_key_exists($att, $parsed) ? $parsed[$att] : $parsed;
        }
    }

    return $res;
}



?>