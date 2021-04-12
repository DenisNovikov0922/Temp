<?php $icons = get_post_meta( $post->ID, '_tommusrhodus_team_social_icons', true ); ?>

<div id="post-<?php the_ID(); ?>" <?php post_class( 'col-sm-4 col-md-3 col-6 text-center mb-5' ); ?>>
	<div class="d-flex flex-column align-items-center">

		<?php the_post_thumbnail( 'full', array( 'class' => 'avatar avatar-xl mb-4 round' ) ); ?>

		<?php the_title( '<h5 class="mb-2">', '</h5>' ); ?>
        <div class="mb-3"><?php echo get_post_meta($post->ID, '_tommusrhodus_the_job_title', 1); ?></div>

         <?php if( is_array($icons) ) : ?>
            <ul class="list-unstyled d-flex mb-0">
            	<?php 
            		foreach( $icons as $key => $icon ){
            			if(!( isset( $icon['_tommusrhodus_social_icon_url'] ) ))
            				continue;
            				
            			echo '<li class="mx-2">
            					<a href="'. $icon['_tommusrhodus_social_icon_url'] .'" target="_blank" class="hover-fade-out">
            						'. tommusrhodus_svg_icons_pluck( $icon['_tommusrhodus_social_icon'], "icon icon-xs" ) .'
                      			</a>
                      		</li>';
            		}
            	?>
            </ul>
        <?php endif; ?>

	</div>
</div>