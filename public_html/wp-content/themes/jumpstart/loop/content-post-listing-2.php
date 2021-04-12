<?php 
	$thumbnail  = has_post_thumbnail(); 
	$word_count = ( $thumbnail ) ? 20 : 50;
	$class      = ( $thumbnail ) ? 'col-xl-7' : 'col-xl-12';
	$sticky_class = ( is_sticky() ) ? 'bg-dark text-white' : 'bg-white';
?>

<div id="post-<?php the_ID(); ?>" class="mb-4 mb-md-5" data-aos="fade-up">
	<div class="d-flex flex-column flex-lg-row no-gutters border rounded o-hidden <?php echo esc_attr( $sticky_class ); ?>">

		<?php if( $thumbnail ) : ?>
		
			<a href="<?php the_permalink(); ?>" class="d-block position-relative bg-gradient col-xl-5">
				<?php the_post_thumbnail( 'large', array( 'class' => 'flex-fill hover-fade-out' ) ); ?>
				<div class="divider divider-side bg-white d-none d-lg-block"></div>
			</a>
		
		<?php endif; ?>

		<div class="p-4 p-md-5 <?php echo esc_attr( $class ); ?> d-flex align-items-center">
			<div class="p-lg-4 p-xl-5">
				
				<div class="d-flex justify-content-between align-items-center mb-3 mb-xl-4">
					<?php get_template_part( 'inc/content-post', 'meta' ); ?>
				</div>
				
				<?php the_title( '<a href="'. get_permalink() .'"><h1>', '</h1></a>' ); ?>
				
				<p class="lead">
					<?php echo tommusrhodus_get_excerpt( $word_count ); ?>
				</p>
				
				<a href="<?php echo get_permalink(); ?>" class="lead"><?php echo esc_html__( 'Read Story', 'jumpstart' ); ?></a>
				
			</div>
		</div>
		
	</div>
</div>