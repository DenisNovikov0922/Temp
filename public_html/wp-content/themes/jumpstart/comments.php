<?php
	$show_page_sharing = get_post_meta( $post->ID, '_tommusrhodus_show_page_sharing', 1 );

	$updated_fields = array(
		'author' => '<div class="form-row"><div class="col-sm"><div class="form-group"><input type="text" class="form-control" id="author" name="author" placeholder="' . esc_attr__( 'Name', 'jumpstart' ) . '" value="' . esc_attr( $commenter['comment_author'] ) . '" /></div></div>',
		'email'  => '<div class="col-sm"><div class="form-group"><input name="email" class="form-control" type="text" id="email" placeholder="' . esc_attr__( 'Email Address', 'jumpstart' ) . '" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" /></div></div></div>',
		'url'    => '<div class="form-group"><input name="url" class="form-control" type="text" id="url" placeholder="' . esc_attr__( 'Website', 'jumpstart' ) . '" value="' . esc_attr(  $commenter['comment_author_url'] ) . '" /></div>',
	);
?>

<?php if( is_page() && 'yes' == $show_page_sharing || !is_page() || get_comments_number() ) : ?>

<section class="pt-0">
  	<div class="container">
        <div class="row justify-content-center">
          	<div class="col-xl-7 col-lg-8 col-md-10">
				
				<?php get_template_part( 'inc/content', 'sharing' ); ?>
				
				<?php if( !post_password_required() && comments_open() || !post_password_required() && get_comments_number() ) : ?>
					
					<h3 class="h2 mb-4 mb-md-5 comments-title">
						<?php 
							comments_number( 
								esc_html__( '0 Comments', 'jumpstart' ), 
								esc_html__( '1 Comment', 'jumpstart' ), 
								esc_html__( '% Comments', 'jumpstart' )
							); 
						?>
					</h3>
					
					<ol class="comments article-comments mb-4 mb-sm-5">
						<?php 
							wp_list_comments( array(
								'type'              => 'all',
								'callback'          => 'tommusrhodus_custom_comment'
							) ); 
						?>
					</ol>
					
					<?php paginate_comments_links(); ?>
					
					<?php 
						comment_form( array(
							'fields'               => apply_filters( 'comment_form_default_fields', $updated_fields ),
							'title_reply_before'   => '<h4 id="reply-title" class="h3 mb-4">',
							'title_reply_after'    => '</h4>',
							'comment_notes_before' => '',
							'comment_notes_after'  => '',
							'class_submit'         => 'btn btn-outline-primary',
							'comment_field'        => '<div class="form-group"><textarea id="comment" class="form-control" name="comment" rows="10" placeholder="'. esc_attr__( 'Comment', 'jumpstart' ) .'" aria-required="true"></textarea></div>'
						) ); 
					?>
				
				<?php endif; ?>
				
			</div>
		</div>
	</div>
	
</section>

<?php endif;