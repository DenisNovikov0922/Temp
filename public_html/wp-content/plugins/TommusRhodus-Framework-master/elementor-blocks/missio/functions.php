<?php 

function tommusrhodus_framework_add_elementor_widget_categories( $elements_manager ) {
	
	$elements_manager->add_category(
		'missio-elements',
		array(
			'title' => 'Missio Elements'
		)
	);

}
add_action( 'elementor/elements/categories_registered', 'tommusrhodus_framework_add_elementor_widget_categories', 10, 1 );

/* SOCIAL SHARING */
if(!( function_exists('tommusrhodus_social_sharing') )) {
	function tommusrhodus_social_sharing() {

		$share_text = get_theme_mod( 'share_text', 'Share this:' );

		$output = '
			<div class="d-flex align-items-center">
				<span class="text-small mr-1">'. esc_html__( $share_text ) .'</span>
				<div class="d-flex mx-2">';

				if( 'yes' == get_theme_mod( 'show_twitter_sharing', 'yes' ) ) {
					$output .= '
					<a href="#" class="btn btn-round btn-primary mx-1" data-social="twitter">
						<svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      		<title>Twitter icon</title>
                     		 <path d="M23.954 4.569c-.885.389-1.83.654-2.825.775 1.014-.611 1.794-1.574 2.163-2.723-.951.555-2.005.959-3.127 1.184-.896-.959-2.173-1.559-3.591-1.559-2.717 0-4.92 2.203-4.92 4.917 0 .39.045.765.127 1.124C7.691 8.094 4.066 6.13 1.64 3.161c-.427.722-.666 1.561-.666 2.475 0 1.71.87 3.213 2.188 4.096-.807-.026-1.566-.248-2.228-.616v.061c0 2.385 1.693 4.374 3.946 4.827-.413.111-.849.171-1.296.171-.314 0-.615-.03-.916-.086.631 1.953 2.445 3.377 4.604 3.417-1.68 1.319-3.809 2.105-6.102 2.105-.39 0-.779-.023-1.17-.067 2.189 1.394 4.768 2.209 7.557 2.209 9.054 0 13.999-7.496 13.999-13.986 0-.209 0-.42-.015-.63.961-.689 1.8-1.56 2.46-2.548l-.047-.02z"></path>
                		</svg>
                	</a>
                    '; 
				}

				if( 'yes' == get_theme_mod( 'show_facebook_sharing', 'yes' ) ) {
					$output .= '
					<a href="#" class="btn btn-round btn-primary mx-1" data-social="facebook">
						<svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				      		<title>Facebook icon</title>
				     		 <path d="M22.676 0H1.324C.593 0 0 .593 0 1.324v21.352C0 23.408.593 24 1.324 24h11.494v-9.294H9.689v-3.621h3.129V8.41c0-3.099 1.894-4.785 4.659-4.785 1.325 0 2.464.097 2.796.141v3.24h-1.921c-1.5 0-1.792.721-1.792 1.771v2.311h3.584l-.465 3.63H16.56V24h6.115c.733 0 1.325-.592 1.325-1.324V1.324C24 .593 23.408 0 22.676 0"></path>
						</svg>
					</a>
				    '; 
				}

				if( 'yes' == get_theme_mod( 'show_linkedin_sharing', 'yes' ) ) {
					$output .= '
					<a href="#" class="btn btn-round btn-primary mx-1" data-social="linkedin">
						<svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				      		<title>LinkedIn icon</title>
				     		 <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
						</svg>
					</a>
				    '; 
				}

				if( 'yes' == get_theme_mod( 'show_pinterest_sharing', 'yes' ) ) {
					$output .= '
					<a href="#" class="btn btn-round btn-primary mx-1" data-social="pinterest">
						<svg class="icon icon-sm" role="img" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
				      		<title>Pinterest icon</title>
                     	 <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"></path>
						</svg>
					</a>
				    '; 
				}

		$output .= '
				</div>
			</div>';    
	     
	    return $output;
	 
	}	
}
