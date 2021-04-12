<?php

if(!( class_exists('TommusRhodus_Recent_Widget') )){
	class TommusRhodus_Recent_Widget extends WP_Widget {
		
		public function __construct() {
			$widget_ops = array(
				'description'                 => __( 'Add a recent post feed your sidebar.' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'tommusrhodus_recent_posts', __( 'TommusRhodus Recent Posts' ), $widget_ops );
		}
		
		function widget($args, $instance)
		{
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);
	
			echo $before_widget;
	
			if($title) {
				echo  $before_title.$title.$after_title;
			} ?>
	
		    	<ul class="list-unstyled list-articles">
			    	<?php 
			    		$widget_query = new WP_Query(
			    			array(
			    				'post_type' => 'post',
			    				'posts_per_page' => $instance['amount']
			    			)
			    		);
			    		if( $widget_query->have_posts() ) : while ( $widget_query->have_posts() ): $widget_query->the_post(); 
			    	?>
			    	  	
			    	  	<?php $categories = get_the_category(); ?>

						<li class="row row-tight">
							<a href="<?php the_permalink(); ?>" class="col-3 col-md-4">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'rounded' ) ); ?>
							</a>
							<div class="col d-flex flex-column justify-content-between">
								<div>
									<a href="<?php the_permalink(); ?>">
										<h6 class="mb-1"><?php the_title(); ?></h6>
									</a>
									<div class="d-flex text-small">
										<?php
											if( is_array( $categories ) ){
												foreach( $categories as $cat ){
													echo '<a href="'. esc_url( get_category_link( $cat->term_id ) ) .'">'. $cat->name . '</a>';
												}
											}
										?>
										<span class="text-muted ml-1"><?php the_time( get_option('date_format') ); ?></span>
									</div>
								</div>
							</div>
						</li>
			    	              
			    	<?php 
			    		endwhile; 
			    		endif; 
			    		wp_reset_postdata(); 
			    	?>
		    	</ul>
			
			<?php echo $after_widget;
		}
		
		function update($new_instance, $old_instance)
		{
			$instance = $old_instance;
	
			$instance['title'] = strip_tags($new_instance['title']);
			if( is_numeric($new_instance['amount']) ){
				$instance['amount'] = $new_instance['amount'];
			} else {
				$new_instance['amount'] = '3';
			}
	
			return $instance;
		}
	
		function form($instance)
		{
			$defaults = array('title' => 'Recent Posts', 'amount' => '3');
			$instance = wp_parse_args((array) $instance, $defaults); ?>
			
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
				<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('amount'); ?>">Amount of Posts:</label>
				<input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('amount'); ?>" name="<?php echo $this->get_field_name('amount'); ?>" value="<?php echo $instance['amount']; ?>" />
			</p>
		<?php
		}
	}

	function tommusrhodus_register_tommusrhodus_recent(){
	     register_widget( 'TommusRhodus_Recent_Widget' );
	}
	add_action( 'widgets_init', 'tommusrhodus_register_tommusrhodus_recent', 20);

}