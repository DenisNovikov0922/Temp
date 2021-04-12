<?php
if (!defined('WPINC')) {
    die('Closed');
}
if(isset($data->user,$data->user->ID))
    echo do_action('rm_front_tabcontent', $data->user->ID,$data->extened_view);
?>