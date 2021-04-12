<?php
	$upvotes     = get_post_meta( $post->ID, 'tommusrhodus_docs_upvotes', 1 );
	$downvotes   = get_post_meta( $post->ID, 'tommusrhodus_docs_downvotes', 1 );
	
	if( false == $upvotes ){
		$upvotes = '';
	}
	
	if( false == $downvotes ){
		$downvotes = '';
	}
?>

<div class="my-4 my-sm-5 card card-body flex-sm-row justify-content-between align-items-center">

	<h5 class="h5 mb-sm-0"><?php esc_html_e( 'Did you find this article helpful?', 'jumpstart' ); ?></h5>
				
    <div class="d-flex">
		
        <button class="btn btn-sm btn-outline-primary mx-1 btn-upvote" type="submit" data-id="<?php echo esc_attr( $post->ID ); ?>">
			<?php esc_html_e( 'Yes', 'jumpstart' ); ?> <span data-js-upvote-count="<?php echo esc_attr( $upvotes ); ?>"><?php echo esc_html( $upvotes ); ?></span>
		</button>
		
        <button class="btn btn-sm btn-outline-primary mx-1 btn-downvote" type="submit" data-id="<?php echo esc_attr( $post->ID ); ?>">
			<?php esc_html_e( 'No', 'jumpstart' ); ?> <span data-js-downvote-count="<?php echo esc_attr( $downvotes ); ?>" ><?php echo esc_html( $downvotes ); ?></span>
		</button>
		
    </div>
    
</div>