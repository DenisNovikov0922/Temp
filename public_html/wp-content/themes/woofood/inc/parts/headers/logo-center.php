<?php global $woocommerce ;
$menu_align = get_option("woofood_header_menu_align");
$cart_icon = get_option("woofood_header_cart_icon_selected");
?>

<header class="header" role="banner">
            <div class="row">
        <div class="container">

        <div class="col-md-12 col-lg-12 col-xl-12 d-none d-md-flex justify-content-center header-center-logo p-3 mx-auto">

        <div class="col-md-4 col-lg-4 col-xl-4 d-none d-md-flex justify-content-center">
        <div class="form-group m-auto p-0">
        <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                
                       <input type="text" class="form-control searchform" id="searchform" value="<?php echo get_search_query() ?>" name="s"  placeholder="<?php esc_html_e('Search....', 'woofood'); ?>">
                        <input type="hidden" name="post_type" value="product"/>
  
</form>
              </div>
        </div>
                <div class="col-md-4 col-lg-4 col-xl-4 d-none d-md-flex justify-content-center">

                <a class="navbar-brand mx-auto" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_theme_mod( 'woofood_logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
                </div>
                                <div class="col-md-4 col-lg-4 col-xl-4 d-none d-md-flex justify-content-center">
                                                                    <div class="header-cart-center">

                                <div class="cart-icon mx-auto m-auto p-0">
                      
                      <a href="<?php echo wc_get_cart_url(); ?>"><i class="<?php echo $cart_icon; ?>"><div class="header-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></div></i></a>

                                 </div>
                                 </div>

                                



</div>
</div>
        </div>

              <div class="col-md-12 col-lg-12 col-xl-12">
    <nav class="navbar navbar-expand-sm bsnav">
    <button class="navbar-toggler toggler-spring"><span class="navbar-toggler-icon"></span></button>

        <a class="navbar-brand d-inline d-sm-none" href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo get_theme_mod( 'woofood_logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"></a>
       
              <div class="d-md-none">
              <a href="<?php echo wc_get_cart_url(); ?>">
            <div class="header-cart-center ">

                                <div class="cart-icon mx-auto m-auto p-0">
                      
                     <i class="<?php echo $cart_icon; ?>"><div class="header-cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></div></i>

                                 </div>
                                 </div>
            </a><!--navbar-toggle-->
       </div>

   


        <div class="collapse navbar-collapse justify-content-sm-end" id="navbarsExample07">
         <?php bootstrap_menu_nav("navbar-nav navbar-mobile ".$menu_align); ?>



        </div>
            </nav>

        </div>



            </div>


            </header>
            <div class="bsnav-mobile">
  <div class="bsnav-mobile-overlay"></div>
  <div class="navbar"></div>
</div>