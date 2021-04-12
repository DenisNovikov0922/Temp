<div class="top-bar">
<div class="container">
<div class="row">
<?php $theme_defaults = WOOFOOD_THEME_DEFAULTS ; ?>
<?php if (get_option('woofood_top_bar_style', $theme_defaults["woofood_top_bar_style"]) =="default"): ?>



<div class="col-6 text-left">	
<?php echo get_theme_mod('woofood_top_bar_left', $theme_defaults["woofood_top_bar_left"]); ?>


</div>

<div class="col-6 text-right">
<?php echo get_theme_mod('woofood_top_bar_right', $theme_defaults["woofood_top_bar_right"]); ?>
</div>







<?php elseif (get_option('woofood_top_bar_style') =="text-social"): ?>

	<div class="col-6 text-left">	
<?php echo get_theme_mod('woofood_top_bar_left', $theme_defaults["woofood_top_bar_left"]); ?>
</div>

<div class="col-6 text-right woofood-social-icons">
<?php if (!empty(get_theme_mod('woofood_social_facebook', $theme_defaults["woofood_social_facebook"]))):?>
<a class="woofood-icon-facebook" target="_blank" href="<?php echo get_theme_mod('woofood_social_facebook', $theme_defaults["woofood_social_facebook"]); ?>"></a>
<?php endif; ?>
<?php if (!empty(get_theme_mod('woofood_social_instagram', $theme_defaults["woofood_social_instagram"]))):?>
<a class="woofood-icon-instagram" target="_blank" href="<?php echo get_theme_mod('woofood_social_instagram',  $theme_defaults["woofood_social_instagram"]); ?>"></a>
<?php endif; ?>

<?php if (!empty(get_theme_mod('woofood_social_youtube', $theme_defaults["woofood_social_youtube"]))):?>

<a class="woofood-icon-youtube" target="_blank" href="<?php echo get_theme_mod('woofood_social_youtube', $theme_defaults["woofood_social_youtube"]); ?>"></a>
<?php endif; ?>

<?php if (!empty(get_theme_mod('woofood_social_pinterest', $theme_defaults["woofood_social_pinterest"]))):?>

<a class="woofood-icon-pinterest" target="_blank" href="<?php echo get_theme_mod('woofood_social_pinterest', $theme_defaults["woofood_social_pinterest"]); ?>"></a>
<?php endif; ?>

<?php if (!empty(get_theme_mod('woofood_social_twitter', $theme_defaults["woofood_social_twitter"]))):?>

<a class="woofood-icon-twitter" target="_blank" href="<?php echo get_theme_mod('woofood_social_twitter', $theme_defaults["woofood_social_twitter"]); ?>"></a>
<?php endif; ?>

<?php if (!empty(get_theme_mod('woofood_social_email', $theme_defaults["woofood_social_email"]))):?>

<a class="woofood-icon-email" target="_blank" href="<?php echo get_theme_mod('woofood_social_email', $theme_defaults["woofood_social_email"]); ?>"></a>
<?php endif; ?>


</div>




<?php elseif (get_option('woofood_top_bar_style') =="availability"): ?>
	<?php
	$delivery_available = woofood_check_if_within_delivery_hours();
	$pickup_available = woofood_check_if_within_pickup_hours();
	$types_accepting  = array();
	if($delivery_available)
	{
		$types_accepting[] = esc_html__('Delivery', 'woofood'); 

	}
	if($pickup_available)
	{
		$types_accepting[] = esc_html__('Pickup', 'woofood'); 

	}

	?>

	<div class="col-6 text-left">	
		<?php if(function_exists("woofood_check_if_within_delivery_hours")) : ?>
			<?php if($delivery_available || $pickup_available ): ?>
		
				<span class="woofood-top-open-msg"><?php echo get_theme_mod('woofood_top_bar_left_available', $theme_defaults["woofood_top_bar_left_available"]); ?></span>

		
			<?php else: ?>
			
		     	<span class="woofood-top-closed-msg"><?php echo get_theme_mod('woofood_top_bar_left_unavailable', $theme_defaults["woofood_top_bar_left_unavailable"]); ?></span>
			<?php endif; ?>

		
		<?php endif; ?>
</div>

<div class="col-6 text-right woofood-social-icons">

		<?php if(function_exists("woofood_check_if_within_delivery_hours")) : ?>
			<?php if($delivery_available || $pickup_available ): ?>
		
				<?php echo get_theme_mod('woofood_top_bar_right_available', $theme_defaults["woofood_top_bar_right_available"]); ?>

		
			<?php else: ?>
			
				<?php echo get_theme_mod('woofood_top_bar_right_unavailable', $theme_defaults["woofood_top_bar_right_unavailable"]); ?>
			<?php endif; ?>

		
		<?php endif; ?>



</div>






<?php elseif (get_option('woofood_top_bar_style') =="menu-social"): ?>

	<div class="col-6 text-left">	
<?php
if ( has_nav_menu( "topbar" ) ) {
wp_nav_menu( array(
	 'theme_location' => 'topbar',
    'container_class'=>'foodmaster-top-bar-container-menu'
) );
}
?>
</div>

<div class="col-6 text-right woofood-social-icons">
<?php if (!empty(get_theme_mod('woofood_social_facebook', $theme_defaults["woofood_social_facebook"]))):?>
<a class="woofood-icon-facebook" target="_blank" href="<?php echo get_theme_mod('woofood_social_facebook'); ?>"></a>
<?php endif; ?>
<?php if (!empty(get_theme_mod('woofood_social_instagram', $theme_defaults["woofood_social_instagram"]))):?>
<a class="woofood-icon-instagram" target="_blank" href="<?php echo get_theme_mod('woofood_social_instagram'); ?>"></a>
<?php endif; ?>

<?php if (!empty(get_theme_mod('woofood_social_youtube', $theme_defaults["woofood_social_youtube"]))):?>

<a class="woofood-icon-youtube" target="_blank" href="<?php echo get_theme_mod('woofood_social_youtube'); ?>"></a>
<?php endif; ?>

<?php if (!empty(get_theme_mod('woofood_social_pinterest', $theme_defaults["woofood_social_pinterest"]))):?>

<a class="woofood-icon-pinterest" target="_blank" href="<?php echo get_theme_mod('woofood_social_pinterest'); ?>"></a>
<?php endif; ?>

<?php if (!empty(get_theme_mod('woofood_social_twitter', $theme_defaults["woofood_social_twitter"]))):?>

<a class="woofood-icon-twitter" target="_blank" href="<?php echo get_theme_mod('woofood_social_twitter'); ?>"></a>
<?php endif; ?>

<?php if (!empty(get_theme_mod('woofood_social_email', $theme_defaults["woofood_social_email"]))):?>

<a class="woofood-icon-email" target="_blank" href="<?php echo get_theme_mod('woofood_social_email'); ?>"></a>
<?php endif; ?>


</div>







<?php endif; ?>








</div>
</div>
</div>
