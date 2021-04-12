<?php
$menu_align = get_option("woofood_header_menu_align");
$cart_icon = get_option("woofood_header_cart_icon_selected");

?>
<header role="banner" class="header">



<div class="container">

      <div class="navbar navbar-expand-sm bsnav">
          <button class="navbar-toggler toggler-spring"><span class="navbar-toggler-icon"></span></button>

    <?php if ( get_theme_mod( 'woofood_logo' ) ) : ?>
    <a  class="navbar-brand mx-auto" href="<?php echo esc_url( home_url( '/' ) ); ?>" id="site-logo" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
 
        <img src="<?php echo get_theme_mod( 'woofood_logo' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
 
    </a>
 
    <?php else : ?>
               
    <hgroup>
        <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
        <p class="site-description"><?php bloginfo( 'description' ); ?></p>
    </hgroup>
               
<?php endif; ?>
    <div class="collapse navbar-collapse">
        
                 <?php bootstrap_menu_nav("navbar-nav navbar-mobile ".$menu_align ); ?>

    </div>
     <a href="<?php  echo wc_get_cart_url();?>">
        <div class="header-cart">
<div class="d-flex align-items-center justify-content-center" >
    <div class="cart-icon">
                      
                     <i class="<?php echo $cart_icon; ?>"><div class="header-cart-count"><?php echo WC()->cart->get_cart_contents_count();?></div></i>
                                 </div>
                                 <span class="m-3 p-0 float-left">   <?php echo WC()->cart->get_cart_contents_count(). " ".__('items in cart', 'woofood'); ?> </span>
                                 </div>
                                 </div>
                                 </a>


          
</div>


</div>

    </header>

    <?php

if ( is_user_logged_in()) {
$user = wp_get_current_user();
$billing_address_1 = get_user_meta($user->ID, 'billing_address_1', true);
$billing_city = get_user_meta($user->ID, 'billing_city', true);
$billing_postcode = get_user_meta($user->ID, 'billing_postcode', true);
$total_address = $billing_address_1.",".$billing_city.",".$billing_postcode;
  ?>
  

  <?php
      }

  else {
    ?>
<script>
setTimeout(
  function() 
  {
       //pt_open_login_dialog('#pt-login');

  }, 6000);

</script>

    <?php



  }
?>  

  <div class="bsnav-mobile">
  <div class="bsnav-mobile-overlay"></div>
  <div class="navbar"></div>
</div>