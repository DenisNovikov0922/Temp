<?php
 
 class Add_Blog_ID {
	
	public static function init() {
		$class = __CLASS__ ;

		if ( empty( $GLOBALS[ $class ] ) )
			$GLOBALS[ $class ] = new $class;
	}
	
	public function __construct() {
		
		add_filter( 'wpmu_blogs_columns', array( $this, 'get_id' ) );
		add_action( 'manage_sites_custom_column', array( $this, 'add_columns' ), 10, 2 );
		add_action( 'manage_blogs_custom_column', array( $this, 'add_columns' ), 10, 2 );
		add_action( 'admin_footer', array( $this, 'add_style' ) );
	}
	
	public function add_columns( $column_name, $blog_id ) {
		
		if ( 'blog_id' === $column_name )
			echo $blog_id;
		
		return $column_name;
	}

	// Add in a column header
	public function get_id( $columns ) {
		
		$columns['blog_id'] = __('ID');
		
		return $columns;
	}
	
	public function add_style() {
		
		echo '<style>#blog_id { width:7%; }</style>';
	}
}
add_action( 'init', array( 'Add_Blog_ID', 'init' ) );