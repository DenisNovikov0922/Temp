<?php
  $footer_cta_background_style  = get_theme_mod( 'footer_cta_background_style', 'bg-gradient' );
  $text_colour                  = false;
  $cta_content                  = get_theme_mod( 'portfolio_footer_cta' );
  $cta_form                     = get_theme_mod( 'portfolio_footer_cta_form_shortcode' );
  
  if( 'bg-primary' == $footer_cta_background_style || 'bg-dark' == $footer_cta_background_style || 'bg-gradient' == $footer_cta_background_style ) {
    $text_colour = 'text-white';
  } 
  
  if( $cta_content ) :
?>

  <section class="<?php echo esc_attr( $footer_cta_background_style ); ?> <?php echo esc_attr( $text_colour ); ?> pb-0 o-hidden">
  
    <div class="container">
      
      <div class="row section-title justify-content-center text-center">
          <div class="col-md-9 col-lg-8 col-xl-7">
              <?php echo wp_kses_post( $cta_content ); ?>
          </div>
      </div>
    
      <?php if( $cta_form ) : ?>
        <div class="row justify-content-center text-center">
            <div class="col-xl-7 col-lg-8 col-md-10">
              <?php echo do_shortcode( $cta_form ); ?>
            </div>
        </div>
      <?php endif; ?>
      
    </div>
    
    <?php if( 'bg-primary' == $footer_cta_background_style ) : ?>
      <div class="h-75 w-50 position-absolute bottom left" data-jarallax-element="-60">
        <div class="blob bottom left w-100 h-100 bg-white opacity-10"></div>
      </div>
    <?php endif; ?>
    
     <div class="divider divider-bottom bg-primary-3"></div>
   
  </section>

<?php else : ?>

  <div class="divider divider-bottom bg-primary-3 up-5vw"></div>

<?php endif;