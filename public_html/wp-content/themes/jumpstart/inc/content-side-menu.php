<?php 
	if( !isset( $post ) ){
		return false;
	}
	
	$selected_menu = get_post_meta( $post->ID, '_tommusrhodus_side_menu', 1 );
	
	if( empty( $selected_menu ) || 'no' == $selected_menu  ){
		return false;
	} 
?>

<div class="side-menu d-none d-md-flex">

	<a href="#" data-toggle="button" class="btn rounded-circle btn-primary">
		<img src="<?php echo get_theme_file_uri( '/style/img/icon-menu.svg' ); ?>" alt="<?php esc_attr_e( 'Open Sidebar', 'jumpstart' ); ?>" class="icon bg-white" data-inject-svg>
		<img src="<?php echo get_theme_file_uri( '/style/img/icon-x.svg' ); ?>" alt="<?php esc_attr_e( 'Open Sidebar', 'jumpstart' ); ?>" class="icon bg-white" data-inject-svg>
	</a>
	
	<div class="side-menu-body bg-white border-left shadow p-4 text-small" id="side-menu">
		<?php
			wp_nav_menu( 
				array(
				  'menu'              => $selected_menu,
				  'depth'             => 1,
				  'container'         => false,
				  'container_class'   => false,
				  'menu_class'        => 'nav flex-column mb-4',
				  'menu_id'           => false,
				  'items_wrap'        => '<ul class="%2$s">%3$s</ul>',
				  'walker'            => new WP_Bootstrap_Navwalker()
				)
			);
		?>
	</div>

</div>