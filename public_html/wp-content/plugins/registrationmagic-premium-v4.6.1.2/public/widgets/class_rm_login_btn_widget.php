<?php

/**
 * Adds Login Button Widget
 */
class RM_Login_Btn_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    function __construct() {
        parent::__construct(
                'rm_login_btn_widget', // Base ID
                __('RegistrationMagic Login Button', 'registrationmagic-gold'), // Name
                array('description' => __('Login Button', 'registrationmagic-gold'),) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];

        include RM_PUBLIC_DIR . "widgets/html/login_btn.php";

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form($instance) {
        wp_enqueue_script('rm_login_btn_widget',RM_BASE_URL."public/js/login_btn_widget.js",array('jquery'));
        $title = !empty($instance['title']) ? $instance['title'] : __('RegistrationMagic Login Button', 'registrationmagic-gold');
        $login_label= isset($instance['login_label']) ? $instance['login_label'] : __('Login', 'registrationmagic-gold');
        $login_method= isset($instance['login_method']) ? $instance['login_method'] : __('popup', 'registrationmagic-gold');
        $login_url= isset($instance['login_url']) ? $instance['login_url'] : 0;
        $logout_label= isset($instance['logout_label']) ? $instance['logout_label'] : __('Logout', 'registrationmagic-gold');
        $display_card= isset($instance['display_card'])  ? $instance['display_card'] : 1;
    ?>

           <p> <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'registrationmagic-gold'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
                   name="<?php echo $this->get_field_name('title'); ?>" type="text"
                   value="<?php echo esc_attr($title); ?>"></p>

        <div>
            <div class="rm-logged-out-view">
                <div>
                    <h3><?php _e('Logged Out View', 'registrationmagic-gold') ?></h3>
                </div>
                <p>
                    <label for="<?php echo $this->get_field_name('rm_login_label'); ?>"><?php _e('Login Label', 'registrationmagic-gold'); ?></label>
                    <input type="text" name="<?php echo $this->get_field_name('login_label'); ?>" id="<?php echo $this->get_field_name('rm_login_label'); ?>" value="<?php echo $login_label; ?>" class="widefat" />
                    <span class="rm-widget-helptext"><?php _e('Label of the button when user is in logged out state.', 'registrationmagic-gold') ?></span>
                </p>
                <p>
                    <label for="rm_login_method" class="rm-widget-label-fw"><?php _e('Login Method', 'registrationmagic-gold'); ?></label>
                    <input class="rm_login_method" onchange="rmw_login_method_change(this)" type="radio" name="<?php echo $this->get_field_name('login_method'); ?>" <?php echo $login_method=='popup' ? 'checked' : '';  ?> value="popup" /> <?php _e('Popup', 'registrationmagic-gold'); ?>
                    <input class="rm_login_method" onchange="rmw_login_method_change(this)" type="radio" value="url" name="<?php echo $this->get_field_name('login_method'); ?>" <?php echo $login_method=='url' ? 'checked' : '';  ?> id="rm_login_method_url" /> <?php _e('URL', 'registrationmagic-gold'); ?>
                    
                </p>
                <p id="<?php echo $this->get_field_name('url_options'); ?>" style="<?php if($login_method=='url') echo 'display:none;'; else 'display:block;'; ?>">
                    <span class="rm-widget-helptext"><?php _e('Define what happens when user clicks login button. Popup will open popup box with login fields.', 'registrationmagic-gold'); ?></span>
                </p>
                <p id="<?php echo $this->get_field_name('url_options'); ?>" style="<?php if($login_method!='url') echo 'display:none;'; else 'display:block;'; ?>">
                    <label for="rm_logout_label"><?php _e('Login Page URL', 'registrationmagic-gold'); ?></label>
                    <?php $pages= RM_Utilities::wp_pages_dropdown();?>
                    <select name="<?php echo $this->get_field_name('login_url'); ?>" class="widefat">
                        <?php foreach($pages as $index=>$page): ?>
                                <option <?php echo $login_url==$index ? 'selected':''; ?> value="<?php echo $index; ?>"><?php echo $page; ?></option>
                        <?php endforeach; ?>    
                    </select>
                    <span class="rm-widget-helptext"><?php _e('Make sure the page you selected has login box.', 'registrationmagic-gold'); ?></span>
                </p>
            </div>
            
            <div class="rm-logged-in-view">
                <div>
                    <h3><?php _e('Logged In View', 'registrationmagic-gold'); ?></h3>
                </div>
                
                <p>
                    <label for="rm_logout_label"><?php _e('Logout Label', 'registrationmagic-gold'); ?></label>
                    <input type="text" name="<?php echo $this->get_field_name('logout_label'); ?>" id="rm_logout_label" value="<?php echo $logout_label; ?>" class="widefat"/>
                    <span class="rm-widget-helptext"><?php _e('Label of the button when user is in logged in state.', 'registrationmagic-gold'); ?></span>
                </p>
                
                <p>
                    <label for="rm_user_card"><?php _e('Display User card on hover', 'registrationmagic-gold'); ?></label>
                    <input type="checkbox" id="rm_user_card" value="1" name="<?php echo $this->get_field_name('display_card'); ?>" <?php echo $display_card==1 ? 'checked' : ''; ?>  />
                    <span class="rm-widget-helptext"><?php _e('Displays user information card when user hovers cursor above the button.', 'registrationmagic-gold'); ?></span>
                </p>
                
            </div>

        </div>

        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        $instance['login_label'] = (!empty($new_instance['login_label'])) ? strip_tags($new_instance['login_label']) : __('Login', 'registrationmagic-gold');
        $instance['login_method']= (!empty($new_instance['login_method'])) ? strip_tags($new_instance['login_method']) : __('popup', 'registrationmagic-gold');
        $instance['login_url']= (!empty($new_instance['login_url'])) ? strip_tags($new_instance['login_url']) : '';
        $instance['logout_label']= (!empty($new_instance['logout_label'])) ? strip_tags($new_instance['logout_label']) : __('Logout', 'registrationmagic-gold');
        $instance['display_card']= isset($new_instance['display_card']) ? 1 : 0;
        return $instance;
    }

}

// class Foo_Widget
