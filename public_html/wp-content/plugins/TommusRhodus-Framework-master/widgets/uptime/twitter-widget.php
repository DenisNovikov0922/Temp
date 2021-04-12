<?php

if(!( class_exists('TommusRhodus_Twitter_Widget') )){

	class TommusRhodus_Twitter_Widget extends WP_Widget {
	
		/**
		 * Sets up a new Navigation Menu widget instance.
		 *
		 * @since 3.0.0
		 */
		public function __construct() {
			$widget_ops = array(
				'description'                 => __( 'Add a twitter feed your sidebar.' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'tommusrhodus_twitter', __( 'TommusRhodus Twitter' ), $widget_ops );
		}
	
		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			
			$defaults = array( 
				'user_name' => '',
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			extract($instance);
			
			echo $args['before_widget'];
			
			if ( ! empty( $instance['title'] ) )
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			
			if ( isset( $instance['user_name'] ) )
				echo '
					<div data-twitter-flickity=\'{"wrapAround": true, "autoPlay": false, "prevNextButtons": false, "adaptiveHeight": true}\' data-twitter-fetcher data-username="'. $instance['user_name'] .'">
						<div class="carousel-cell">
							<div class="tweet mb-2"></div>
							<div class="d-flex align-items-center">
								<svg class="icon icon-sm mr-2 opacity-30" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
									<title>Twitter icon</title>
									<path d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z"
									/>
								</svg>
								<div class="timePosted text-small"></div>
							</div>
						</div>
					</div>
				';
			
			echo $args['after_widget'];
		}
	
		/**
		 * Outputs the options form on admin
		 *
		 * @param array $instance The widget options
		 */
		public function form( $instance ) {
			
			$defaults = array(
				'title' => 'Twitter Feed', 
				'username' => '', 
				'user_name' => '',
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			extract($instance);
		?>
		
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'tommusrhodus' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'user_name' ); ?>">Twitter Username <code>e.g: tommusrhodus</code>
				<p class="description">Do not use @, plain text username only!</p></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'user_name' ); ?>" name="<?php echo $this->get_field_name( 'user_name' ); ?>" type="text" value="<?php echo esc_attr( $user_name ); ?>">
			</p>
			
		<?php 
		}
	
		/**
		 * Processing widget options on save
		 *
		 * @param array $new_instance The new options
		 * @param array $old_instance The previous options
		 */
		public function update( $new_instance, $old_instance ) {
			return $new_instance;
		}
	}

	function tommusrhodus_register_tommusrhodus_twitter(){
	     register_widget( 'TommusRhodus_Twitter_Widget' );
	}

	add_action( 'widgets_init', 'tommusrhodus_register_tommusrhodus_twitter', 20 );

}