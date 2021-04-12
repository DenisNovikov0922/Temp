<?php 
	$id  = get_the_author_meta( 'ID' ); 
	$url = get_author_posts_url( get_the_author_meta( 'ID' ) );
?>

<a href="<?php echo esc_url( $url ); ?>" class="d-flex align-items-center">
	<?php echo get_avatar( $id, 48, false, false, array( 'class' => 'avatar avatar-sm' ) ); ?>
	<div class="h6 mb-0 ml-3"><?php echo get_the_author(); ?></div>
</a>
