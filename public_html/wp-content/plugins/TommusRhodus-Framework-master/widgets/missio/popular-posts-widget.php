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
	
		    	<ul class="image-list">
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

						<li>
							<figure class="rounded">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'thumbnail' ); ?>
								</a>
							</figure>
							<div class="post-content">
								<h6 class="post-title"> 
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a> 
								</h6>
								<div class="meta">
									<span class="date"><?php the_time( 'M d, Y' ); ?></span>
									<span class="comments"><a href="<?php comments_link(); ?>"><?php comments_number( esc_html__( '0', 'missio' ), esc_html__( '1', 'missio' ), esc_html__( '%', 'missio' ) ); ?></a></span>
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