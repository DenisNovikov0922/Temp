<?php $logo = get_post_meta( $post->ID, '_tommusrhodus_portfolio_item_logo_id', 1 ); ?>

<div id="portfolio-<?php the_ID(); ?>" <?php post_class( 'd-flex flex-column flex-lg-row no-gutters border rounded bg-white o-hidden' ); ?>>

  <a href="<?php the_permalink(); ?>" class="d-block position-relative bg-gradient col-xl-6 order-lg-2">
    <?php if( has_post_thumbnail() ) : ?>

      <div class="divider divider-side transform-flip-y d-none d-lg-block"></div>
      <?php the_post_thumbnail( 'large', array( 'class' => 'card-img-top flex-fill hover-fade-out' ) ); ?>

      <?php 
        if( $logo ){
          echo '<div class="badge badge-light badge-pill">'. wp_get_attachment_image( $logo, 'large', 0, array( 'class' => 'icon icon-sm m-1 m-lg-2' ) ) .'</div>';
        }
      ?>

    <?php endif; ?>
  </a>

  <div class="p-4 p-md-5 col-xl">
    <div class="p-lg-4 p-xl-5">
      <?php the_title( '<h1>', '</h1>' ); ?>
      <p class="lead">
        <?php echo tommusrhodus_get_excerpt( 20 ); ?>
      </p>
      <a href="<?php the_permalink(); ?>" class="lead stretched-link"><?php echo esc_html__( 'Read Story', 'jumpstart' ); ?></a>
    </div>
  </div>

</div>
