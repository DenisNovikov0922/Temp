<?php 
	get_header(); 
	
	// Documentation Specific Hero Area
	get_template_part( 'inc/content-documentation', 'hero' );
	
	// Get each Documentation Category
	$terms = get_terms( array(
		'taxonomy'   => 'documentation_category',
		'hide_empty' => false
	) );
	
	if( is_array( $terms ) ) :
?>

<section class="bg-light">
	<div class="container">
		<div class="row justify-content-between align-items-start">

          	<div class="col-lg-8">
				
				<?php foreach( $terms as $term ) : ?>
					
					<?php
						$date    = false;
						$authors = array();
						
						if( $term->count > 0 ){
						
							$term_posts = get_posts( array(
								'posts_per_page' => '-1',
								'post_type'      => 'documentation',
								'tax_query'      => array(
									array(
										'taxonomy' => 'documentation_category',
										'field'    => 'ID',
										'terms'    => $term->term_id
									)
								)
							) );
							
							if( is_array( $term_posts ) ){
								
								$date = $term_posts[0];
								
								// Create an array of post authors in this category
								foreach( $term_posts as $term_post ){
									$authors[$term_post->post_author] = $term_post->post_author;
								}
								
							}
						
						}

						$category_icon = get_term_meta( $term->term_id, '_tommusrhodus_documentation_category_icon', true );
					?>

					<div class="mb-3 mb-sm-4">
						<a href="<?php echo get_term_link( $term ); ?>" class="card card-body flex-md-row align-items-start align-items-md-center hover-box-shadow">
							
							<?php
								if( array_key_exists( $category_icon, tommusrhodus_get_svg_icons() ) ) {
									echo tommusrhodus_svg_icons_pluck( $category_icon, "icon icon-lg bg-primary" );
								} else {
									echo tommusrhodus_svg_icons_pluck( 'Thunder-move', "icon icon-lg bg-primary" );
								}
							?>
							
							<div class="mt-3 mt-sm-4 mt-md-0 ml-md-5 ml-lg-4 ml-xl-5">
								
								<h4><?php echo esc_html( $term->name ); ?></h4>
								<p><?php echo esc_html( $term->description ); ?></p>
								
								<div class="d-sm-flex">
									
									<ul class="avatar-group">
										<?php
											foreach( $authors as $id => $author ){
												echo '<li>'. get_avatar( $id, 48, false, false, array( 'class' => 'avatar avatar-sm' ) ) .'</li>';
											}
										?>
									</ul>
									
									<div class="text-small mt-3 mt-sm-0 ml-sm-3">
										<div><?php echo esc_html( $term->count ); ?> <?php esc_html_e( 'articles in this collection', 'jumpstart' ); ?></div>
										<div><?php esc_html_e( 'Written by', 'jumpstart' ); ?>&nbsp;<strong><?php echo get_the_author(); ?></strong></div>
									</div>
									
								</div>
								
							</div>
						</a>
					</div>
				
				<?php endforeach; ?>
			
			</div>

			<?php get_sidebar( 'documentation' ); ?>

		</div>
	</div>
</section>

<?php 
	endif;
	
	get_footer();