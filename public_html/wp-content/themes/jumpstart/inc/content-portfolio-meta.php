<?php 
	$additional 	    = get_post_meta( $post->ID, '_tommusrhodus_meta_repeat_group', true ); 
	$website_label	= get_post_meta( $post->ID, '_tommusrhodus_porfolio_item_website_label', true ); 
	$website_url 	= get_post_meta( $post->ID, '_tommusrhodus_porfolio_item_website_url', true ); 
?>

<?php if( $additional || $website_label ) : ?>
	<div class="col-md-9 col-lg col-xl-4 sticky-lg-top mb-5 mb-lg-0">
		<ul class="list-group pr-xl-4">
			<?php 
				if( $additional ){
					foreach( $additional as $index => $item ){
						echo '<li class="list-group-item px-4 py-3 d-flex justify-content-between">';
						if( isset ( $item['_tommusrhodus_the_additional_title'] ) ) {
							echo '<h6 class="mb-0">'. wp_kses_post($item['_tommusrhodus_the_additional_title']). '</h6>';
						}
						if( isset ( $item['_tommusrhodus_the_additional_detail'] ) ) {
							echo '<div>' .wp_kses_post($item['_tommusrhodus_the_additional_detail']). '</div>';
						}
						echo '</li>';
					}
				}
				if( $website_label && $website_url ) {
					echo '<li class="list-group-item px-4 py-3 d-flex justify-content-between">';
					if( isset ( $website_label ) ) {
						echo '<h6 class="mb-0">'. wp_kses_post( $website_label ). '</h6>';
					}
					if( isset ( $website_url ) ) {
						$remove = array( 'http://', 'https://', 'mailto:');
						echo '<div><a href="'. esc_url( $website_url ) .'" target="_blank">'. wp_kses_post( str_replace( $remove,'', $website_url ) ) .'</a></div>';
					}
					echo '</li>';			
				}
			?>
		</ul>
	</div>
<?php endif;