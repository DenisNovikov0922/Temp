<?php

if(!( class_exists('TommusRhodus_Sticky_Widget') )){

	class TommusRhodus_Sticky_Widget extends WP_Widget {
	
		/**
		 * Sets up a new Navigation Menu widget instance.
		 *
		 * @since 3.0.0
		 */
		public function __construct() {
			$widget_ops = array(
				'description'                 => __( 'Add a sticky widget your sidebar.' ),
				'customize_selective_refresh' => true,
			);
			parent::__construct( 'tommusrhodus_sticky', __( 'TommusRhodus Sticky' ), $widget_ops );
		}
	
		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			
			$defaults = array( 
				'content' => '',
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			extract($instance);

			$before_widget = str_replace('class="', 'class="sticky-top ', $args['before_widget']);
			
			echo $before_widget;
			
			if ( ! empty( $instance['title'] ) )
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
			
			if ( isset( $instance['content'] ) )
				echo '
					<div>
						'. wp_kses_post( do_shortcode( $instance['content'] ) ) .'		              
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
				'title' => 'Sticky Widget', 
				'content' => '',
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			extract($instance);
		?>
		
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title:', 'tommusrhodus' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'content' ); ?>">Content</code>
				<p class="description">Accepts HTML/Shortcodes</p></label> 
				<textarea class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" type="textarea" value="<?php echo esc_attr( $content ); ?>"></textarea>
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

	function tommusrhodus_register_tommusrhodus_sticky(){
	     register_widget( 'TommusRhodus_Sticky_Widget' );
	}

	add_action( 'widgets_init', 'tommusrhodus_register_tommusrhodus_sticky', 20 );

}