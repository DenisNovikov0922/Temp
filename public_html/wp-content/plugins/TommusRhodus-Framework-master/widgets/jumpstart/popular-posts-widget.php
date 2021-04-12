<?php

if(!( class_exists('TommusRhodus_Popular_Widget') )){
	class TommusRhodus_Popular_Widget extends WP_Widget {
		
		public function __construct() {
			$widget_ops = array(
				'description'                 => __( 'Add a post feed your sidebar.' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'tommusrhodus_popular_posts', __( 'TommusRhodus Popular Posts' ), $widget_ops );
		}
		
		function widget($args, $instance)
		{
			extract($args);
			$title = apply_filters('widget_title', $instance['title']);
	
			echo $before_widget;
	
			if($title) {
				echo  $before_title.$title.$after_title;
			} ?>
	
		    	<ul class="list-unstyled d-flex flex-wrap">
			    	<?php 
			    		$widget_query = new WP_Query(
			    			array(
			    				'post_type' => 'post',
			    				'orderby' => 'comment_count',
			    				'order' => 'DESC',
			    				'posts_per_page' => $instance['amount']
			    			)
			    		);
			    		if( $widget_query->have_posts() ) : while ( $widget_query->have_posts() ): $widget_query->the_post(); 
			    	?>
			    	  	
			    	  	<?php $categories = get_the_category(); ?>

						<li class="col-12 col-lg-6 col-xl-12 px-0">
							<div class="row my-2 my-md-3">
								<a href="<?php the_permalink(); ?>" class="col-5">
									<?php the_post_thumbnail( 'jumpstart-card-top', array( 'class' => 'rounded img-fluid hover-fade-out' ) ); ?>
								</a>
								<div class="col">
									<a class="h6" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
									<div class="text-small text-muted mt-2"><?php the_time( get_option('date_format') ); ?></div>
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
			$defaults = array('title' => 'Popular Posts', 'amount' => '3');
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

	function tommusrhodus_register_tommusrhodus_popular(){
	     register_widget( 'TommusRhodus_Popular_Widget' );
	}
	add_action( 'widgets_init', 'tommusrhodus_register_tommusrhodus_popular', 20);

}