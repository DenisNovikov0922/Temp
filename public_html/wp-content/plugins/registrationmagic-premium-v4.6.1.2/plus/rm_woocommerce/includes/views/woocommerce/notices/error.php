<?php
/**
 * Show error messages
 *
 * Overrided Template
 *
 * 
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (!$messages) {
    return;
}
?>
<ul class="woocommerce-error">

<?php
/*
 * Show Woocommerce errors only when RM fields are valid
 */


if (  $messages ){
	foreach ($messages as $message) :
        ?>
            <li><?php echo wp_kses_post($message); ?></li>
        <?php
        endforeach;
}
    
    ?>

    <?php
    /**
     * Showing Registration Magic errors
     */
    if (isset($_SESSION['rm_wc_errors']) && $_POST):
        $validation_errors = unserialize($_SESSION['rm_wc_errors']);
        if (is_wp_error($validation_errors) && count($validation_errors->errors) > 0):
            $error_messages = $validation_errors->get_error_messages();
            foreach ($error_messages as $index=>$error_message):
                if($index==0)
                    continue;
                echo '<li>' . $error_message . '</li>';
            endforeach;
        endif;
    endif;
    ?>
</ul>
