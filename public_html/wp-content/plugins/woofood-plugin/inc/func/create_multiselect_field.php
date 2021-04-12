<?php
//functions multiple select//
add_filter( 'woocommerce_form_field_multiselect', 'wf_multiselect_handler_new', 10, 4 );

function wf_multiselect_handler( $field, $key, $args, $value ) {

    $options = '';

      if(!is_array($value))
    {
        $value = array();
    }

    if ( ! empty( $args['options'] ) ) {
        foreach ( $args['options'] as $option_key => $option_text ) {
          $selected = in_array( $option_key, $value ) ? ' selected="selected" ' : '';


         $options .= '<option value="' . $option_key . '" '. $selected . '>' . $option_text .'</option>';

        }


        $field = '<p class="form-row ' . implode( ' ', $args['class'] ) .'" id="' . $key . '_field">
            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . '</label>
            <select name="' . $key . '" id="' . $key . '" class="select" multiple="multiple">
                ' . $options . '
            </select>
        </p>';
    }

    return $field;
}



    function wf_multiselect_handler_new( $field = '', $key, $args, $value ) {

        if ( ! empty( $args['clear'] ) && version_compare( WC_VERSION, '3.0.0', '<' ) ) {
            $after = '<div class="clear"></div>';
        } else {
            $after = '';
        }

        if ( $args['required'] ) {
            $args['class'][] = 'validate-required';
            $required = ' <abbr class="required" title="' . esc_attr__( 'required', 'woofood-plugin' ) . '">*</abbr>';
        } else {
            $required = '';
        }

        $args['maxlength'] = ( $args['maxlength'] ) ? 'maxlength="' . absint( $args['maxlength'] ) . '"' : '';

        $options = '';

        if ( ! empty( $args['options'] ) ) {
            foreach ( $args['options'] as $option_key => $option_text ) {
                          $selected = in_array( $option_key, $value ) ? ' selected="selected" ' : '';

                $options .= '<option  value="' . $option_key . '"' . $selected . '>' . esc_attr( $option_text ) . '</option>';
            }

            $field = '<p data-priority="' . esc_attr( $args['priority'] ) . '" class="form-row ' . esc_attr( implode( ' ', $args['class'] ) ) . '" id="' . esc_attr( $key ) . '_field">';

            if ( $args['label'] ) {
                $field .= '<label for="' . esc_attr( $key ) . '" class="' . implode( ' ', $args['label_class'] ) . '">' . $args['label'] . $required . '</label>';
            }

            $class = '';

            $field .= '<select data-placeholder="' . __( 'Select some options', 'woofood-plugin' ) . '" multiple="multiple" name="' . esc_attr( $key ) . '[]" id="' . esc_attr( $key ) . '" class="select' . $class . '">
                    ' . $options . '
                </select>
            </p>' . $after;
        }

        return $field;
    }





add_filter( 'woocommerce_form_field_multiselect_draggable', 'wf_multiselect_draggable_handler', 10, 4 );

function wf_multiselect_draggable_handler( $field, $key, $args, $value ) {
    $options_selected = '';
    $options_li_selected = '';

    $options = '';
    $options_li = '';
    if( is_array( $value))
    {

    }
    else
    {
         $value  = array();
    }
    if ( ! empty( $args['options'] ) ) {
        foreach ( $args['options'] as $option_key => $option_text ) {
          $selected = in_array( $option_key, $value ) ? ' selected="selected" ' : '';
            if($selected)
            {
         //$options_selected .= '<option value="' . $option_key . '" '. $selected . '>' . $option_text .'</option>';
         //$options_li_selected .=  '<li value="'.$option_key.'" '. $selected . '><span class="dashicons dashicons-move"></span>'.$option_text.'</li>';

            }
            else
            {
         $options .= '<option value="' . $option_key . '" '. $selected . '>' . $option_text .'</option>';
        $options_li .=  '<li value="'.$option_key.'" '. $selected . '><span class="dashicons dashicons-move"></span>'.$option_text.'</li>';

            }


        }

            if(is_array($value))
            {
                 foreach ( $value as $selected_option ) {
                    if(array_key_exists($selected_option, $args['options']) )
         {


         $options_selected .= '<option value="' . $selected_option . '"  selected="selected" >' . $args['options'][$selected_option] .'</option>';
         $options_li_selected .=  '<li value="'.$selected_option.'"  selected="selected" ><span class="dashicons dashicons-move"></span>'.$args['options'][$selected_option].'</li>';

    }


        }

            }
        

       

        $field = '<div class="woofood_extra_optons_admin"> <h3 class="wf-extra-options-title">'.$args['label'].'</h3><p class="wf-extra-options-description">'.$args['description'].'</p></div>
        <ul id="' . $key . '_ui" class="extra_option_select_ui">
        '.$options_li_selected.'
        '.$options_li.'
        </ul>

        <p class="form-row ' . implode( ' ', $args['class'] ) .'" id="' . $key . '_field" style="display:none;">
            <label for="' . $key . '" class="' . implode( ' ', $args['label_class'] ) .'">' . $args['label'] . '</label>
            <select name="' . $key . '" id="' . $key . '" class="select" multiple="multiple">
                '.$options_selected.'
                ' . $options . '
            </select>
        </p>';
    }
      $woofood_plugin_rtl = woofood_plugin_is_rtl();

        wp_enqueue_style( 'woofood_extra_options_css_admin', WOOFOOD_PLUGIN_URL . 'css/extra_options_multiselect'.$woofood_plugin_rtl.'.css', array(), '1.0.0', 'all' );

         wp_enqueue_script( 'jquery-ui-sortable' );

        wp_enqueue_script(  'woofood_admin_extra_options_scripts', WOOFOOD_PLUGIN_URL . 'js/admin_extra_options.js' , array('jquery','jquery-ui-sortable'), '1.0.0', 'all' );
    return $field;

}

function wf_form_field_radio( $key, $args, $value = '' ) {
    global $woocommerce;
    $defaults = array(
        'type' => 'radio',
        'label' => '',
        'placeholder' => '',
        'required' => false,
        'class' => array( ),
        'label_class' => array( ),
        'return' => false,
        'options' => array( )
        );
    $args     = wp_parse_args( $args, $defaults );
    if ( ( isset( $args[ 'clear' ] ) && $args[ 'clear' ] ) )
    {

        $after = '<div class="clear"></div>';
    }
    else
    {

        $after = '';
    }
    $required = ( $args[ 'required' ] ) ? ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>' : '';
    switch ( $args[ 'type' ] ) {
        case "radio":
        $options = '';
        if ( !empty( $args[ 'options' ] ) )
        {

            foreach ( $args[ 'options' ] as $option_key => $option_text )
            {
                $options .= '<label class="wf_field_wrapper  ' . implode( ' ', $args[ 'class' ] ) . '">'. $option_text.'<input type="radio" name="' . $key . '" id="' . $key . '" value="' . $option_key . '" ' . checked( $value, $option_key, false ) . '><span class="checkmark"></span></label>';

            }
        }
?>
<?php

                //$options .= '<input type="radio" name="' . $key . '" id="' . $key . '" value="' . $option_key . '" ' . selected( $value, $option_key, false ) . 'class="select">' . $option_text . '' . "\r\n";
           /* $field = '<p class="form-row ' . implode( ' ', $args[ 'class' ] ) . '" id="' . $key . '_field">
            <label for="' . $key . '" class="' . implode( ' ', $args[ 'label_class' ] ) . '">' . $args[ 'label' ] . $required . '</label>
            ' . $options . '
        </p>' . $after;*/
        break;
} //$args[ 'type' ]
if ( $args[ 'return' ] )
    return $options;
else
    echo $options;
}
//function multiple select//







?>