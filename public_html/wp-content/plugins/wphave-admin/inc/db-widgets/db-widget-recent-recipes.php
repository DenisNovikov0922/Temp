<?php 

/*
 *******************
 * RECENT RECIPES DASHBOARD WIDGET
 *******************
 *
 *	Show the recent recipes on WordPress dashboard.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_recent_recipes_widget_content') ) :

    function wphave_admin_recent_recipes_widget_content() { 

        $args = array(
            'post_type' => 'recipe',
            'post_status' => 'publish',
            'posts_per_page' => 6,
            'orderby' => 'date',
            'ignore_sticky_posts' => 1,
            'tax_query' => array(),
        ); ?>

        <style>
            .wpat-post-list {margin:-15px}
            .wpat-post-list table {width:100%;border-collapse:collapse}
            .wpat-post-list tr:nth-child(even) {background:#f8f9fb}
            .wpat-post-list tr {margin:0px;padding:10px 15px;border-bottom:1px solid #eee}
            .wpat-post-list tr:last-child {border-bottom:0px}
            .wpat-post-list td {padding:15px;vertical-align: middle}
            .wpat-post-list td:last-child {border:0px}
            .wpat-post-list td.wpat-post-list-img {width:50px;vertical-align:top;padding-right:0px}
            .rtl .wpat-post-list td.wpat-post-list-img {padding-right:15px;padding-left:15px}
            .wpat-post-list img {float:left;width:50px;height:50px}
            .wpat-post-list p {font-size:12px;color:#82878c;margin:6px 0px 0px 0px}
            .wpat-post-list .letter {display:table;width:50px;height:50px;box-shadow: inset 0px 0px 30px rgba(69, 101, 173, 0.1)}
            .wpat-post-list .letter span {display:table-cell;vertical-align:middle;font-size:20px;text-align:center;color:#8e8e8e}
        </style>

        <div class="wpat-post-list">
            <?php $my_posts_query = new WP_Query( $args );
            if ( $my_posts_query->have_posts() ) : ?>

                <table>
                    <?php while ( $my_posts_query->have_posts() ) : $my_posts_query->the_post(); 
                        $edit_url = admin_url( 'post.php?post=' . get_the_ID() . '&action=edit' ); ?>

                        <tr>
                            <td class="wpat-post-list-img">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <a href="<?php echo esc_url( $edit_url ); ?>"><?php echo the_post_thumbnail( 'thumbnail' ); ?></a>
                                <?php else : 
                                    $post_title = get_the_title(); ?>
                                    <a href="<?php echo esc_url( $edit_url ); ?>">
                                        <div class="letter"><span><?php echo mb_strimwidth( esc_html( $post_title ), 0, 1 ); ?></span></div>
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a class="post-title" href="<?php echo esc_url( $edit_url ); ?>"><?php the_title(); ?></a>
                                <p><?php echo get_the_date(); ?> | <?php comments_number( esc_html__( 'No comments', 'wphave-admin' ), esc_html__( 'One comment', 'wphave-admin' ), esc_html__( '% comments', 'wphave-admin' ) ); ?></p>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                </table>

            <?php endif;
            wp_reset_postdata(); ?>            
        </div>

    <?php }

endif;

/*
 *******************
 * INCLUDE DASHBOARD WIDGET
 *******************
 *
 *	Include this db widget to WordPress admin dashboard.
 *
 *  @type	include
 *  @date	06/18/19
 *  @since	3.0
 *
 *  @param	N/A
 *  @return	N/A
 *
*/

if( ! function_exists('wphave_admin_recent_recipes_widget') ) :

	function wphave_admin_recent_recipes_widget() {

		wp_add_dashboard_widget(
			'wp_recent_recipes_db_widget', esc_html__( 'Recent Recipes', 'wphave-admin' ), 'wphave_admin_recent_recipes_widget_content'
		);

	}

endif;

add_action('wp_dashboard_setup', 'wphave_admin_recent_recipes_widget');