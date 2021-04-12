<div id="post-<?php the_ID(); ?>" <?php post_class( 'col-xl-8 col-lg-9 col-md-10 mb-3 mb-sm-4' ); ?> data-aos="fade-up" data-offset="<?php echo esc_attr( $wp_query->current_post + 1 ); ?>00">
	<div class="card card-body hover-box-shadow flex-md-row pl-md-3 bg-white">

		<?php echo get_avatar( get_the_author_meta( 'ID' ), 80, false, false, array( 'class' => 'avatar mr-md-5 ml-md-n5 mb-3 mb-md-0' ) ); ?>

		<div>
			<div class="text-muted mb-3"><?php the_time( get_option( 'date_format' ) ); ?></div>
			<?php the_title( '<a href="'. get_permalink() .'"><h3 class="h2">', '</h3></a>' ); ?>
			<p>
				<?php echo tommusrhodus_get_excerpt( 20 ); ?>
			</p>
			<a href="<?php echo get_permalink(); ?>" class="stretched-link"><?php echo esc_html__( 'Read Story', 'jumpstart' ); ?></a>
		</div>
	</div>
</div>