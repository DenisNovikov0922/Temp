<?php

if(!( class_exists('TommusRhodus_Instagram_Widget') )){

	class TommusRhodus_Instagram_Widget extends WP_Widget {
	
		/**
		 * Sets up the widgets name etc
		 */
		public function __construct(){
			parent::__construct(
				'tommusrhodus-instagram-widget', // Base ID
				esc_html__('TommusRhodus Instagram Widget', 'creatink'), // Name
				array( 'description' => esc_html__( 'Add a simple Instagram feed widget', 'creatink' ), ) // Args
			);
		}
	
		/**
		 * Outputs the content of the widget
		 *
		 * @param array $args
		 * @param array $instance
		 */
		public function widget( $args, $instance ) {
			$defaults = array(
				'title' => '',
				'id' => '', 
				'username' => ''
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			extract($instance);
			echo $args['before_widget'];
			
			if($title)
				echo  $args['before_title'].$title.$args['after_title'];
				
			echo '
				<div class="tiles tiles-s">
                	<div id="instafeed-widget" class="items row" data-instagram-number-items="6" data-instagram-user-id="'. $id .'" data-instagram-access-token="'. $username .'"></div>
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
				'title' => 'Instagram', 
				'id' => '',
				'username' => ''
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			extract($instance);
		?>
		
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Widget Title', 'creatink' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'id' ); ?>"><?php esc_html_e( 'Numeric User ID', 'creatink' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'id' ); ?>" name="<?php echo $this->get_field_name( 'id' ); ?>" type="text" value="<?php echo esc_attr( $id ); ?>">
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php esc_html_e( 'Access Token', 'creatink' ); ?></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" type="text" value="<?php echo esc_attr( $username ); ?>">
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
	function tommusrhodus_register_instagram_widget(){

	     register_widget( 'TommusRhodus_Instagram_Widget' );

	}
	add_action( 'widgets_init', 'tommusrhodus_register_instagram_widget', 20 );
}
