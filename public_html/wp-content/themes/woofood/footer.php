<?php
/**
* The template for displaying the footer
*
* Contains the closing of the #content div and all content after.
*
* @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
*
* @package WooFood
*/
$woofood_footer_left = get_theme_mod( 'woofood_footer_left' );
$woofood_footer_right = get_theme_mod( 'woofood_footer_right' );

?>

<footer class="footer side-collapse-container">
<div class="container">
 <div class="row">

                <div class="col-sm-3">
                   <?php
if(is_active_sidebar('footer-1')){
dynamic_sidebar('footer-1');
}
                   ?>
                </div>
                <div class="col-sm-3">
                     <?php
if(is_active_sidebar('footer-2')){
dynamic_sidebar('footer-2');
}
                   ?>
                </div>
                <div class="col-sm-3">
                     <?php
if(is_active_sidebar('footer-3')){
dynamic_sidebar('footer-3');
}
                   ?>
                </div>
                <div class="col-sm-3 info">
                    <?php
if(is_active_sidebar('footer-4')){
dynamic_sidebar('footer-4');
}
                   ?>
                </div>
            </div>
	<div class="footer-bottom">
		<div class="footer-bottom-left col-md-6 col-xs-6 col-sm-6 float-left">
			<?php echo $woofood_footer_left; ?>
		</div><!-- .footer-bottom-right -->


		<div class="footer-bottom-right col-md-6 col-xs-6 col-sm-6 float-left">
			<?php echo $woofood_footer_right; ?>
		</div><!-- .footer-bottom-right -->
	</div>
	</div>
</footer><!-- #colophon -->

    <div align="center" width="100%" style="background: #FFFFFF;">
<p>
P.IVA <?php
$users = get_users( array( 'role' => 'administrator' ) );
foreach ( $users as $user ) { 
   $email = get_user_meta($user->ID, "billing_wcj_checkout_field_1", true);
   $email2 = get_user_meta($user->ID, "billing_company", true);
   echo $email .", ". $email2;
}
?> | 
<?php
// The main address pieces:
$store_address     = get_option( 'woocommerce_store_address' );
$store_address_2   = get_option( 'woocommerce_store_address_2' );
$store_city        = get_option( 'woocommerce_store_city' );
$store_postcode    = get_option( 'woocommerce_store_postcode' );

echo $store_address . " - ";
echo ( $store_address_2 ) ? $store_address_2 . " - " : '';
echo $store_city . ', ' . $store_state . ' ' . $store_postcode . "";
?></p><br>&nbsp;<br>
</div>
<?php



?>

<script>




 
</script>



<?php wp_footer(); ?>

</body>
</html>
