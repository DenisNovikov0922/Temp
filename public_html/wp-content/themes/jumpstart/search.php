<?php 
	get_header(); 
	
	$total_results = $wp_query->found_posts;	
?>

<section>
	<div class="row section-title justify-content-center text-center mb-0">
		<div class="col-md-9 col-lg-8 col-xl-7">
			
			<h1 class="display-3"><?php echo esc_html( $total_results ) . esc_html__( ' items found', 'jumpstart' ); ?></h1>
			
			<div class="lead mb-4"><?php esc_html_e( 'Showing results for: ', 'jumpstart' ); echo ucfirst( get_search_query() ); ?></div>
			
			<?php
				if( !$total_results ){
					get_search_form();
				}
			?>
			
		</div>
	</div>
</section>
        
<?php
	if( $total_results ){
		get_template_part( 'loop/loop-post', get_theme_mod( 'blog_layout', 'listing-2' ) ); 
	}

	get_template_part( 'inc/footer-cta' ); 

	get_footer();