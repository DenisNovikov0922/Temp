<?php
function wf_plugin_menu_new($atts)
{
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
   if ( !empty( $atts['url'] ))
  {
    $all_categories[] = get_term_by( 'slug', $atts['url'], 'product_cat' );

  }
  else
  {
     $all_categories = get_categories( $args );


  }
      $html_export .= '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';

      foreach ($all_categories as $cat) {
    if($cat->category_parent == 0) {
        $category_id = $cat->term_id;       

        $html_export .=' <div class="panel panel-custom">';
         $html_export .=' <div class="panel-heading panel-heading-title " role="tab" id="headingThree">';
                $html_export .='<h4 class="panel-title">';
$html_export .='<a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#'.$cat->slug.'" aria-expanded="false" aria-controls="collapseThree">'.$cat->name.'</a> ';
$html_export .= '</h4>';
$html_export .= '</div>';

$html_export .=' <div class="panel-heading panel-heading-plus" role="tab" id="headingThree" data-toggle="collapse" href="#'.$cat->slug.'">

                <div >
                     <div class="accordion-plus-icon" >
                    <i class="fa fa-plus-circle" ></i> 
                    </div>  
                    </div>

                                    </div>';
 $html_export .= '  <div id="'.$cat->slug.'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
                    <div class="panel-body">'.do_shortcode('[product_category category="'.$cat->slug.'" per_page="-1"]').'
                    </div>
                </div>
            </div>';                     

          }}//end foreach
 $html_export .="</div><!--panelgroup-->";         


return $html_export;

}
add_shortcode('woofood_plugin_menu_new', 'wf_plugin_menu_new');



?>