<?php
/*
 * Include file as per the active mailpoet plugin
 *
 *
 */
if (is_plugin_active('mailpoet/mailpoet.php') ) {
    require_once 'rm_mailpoet3.php';
}else if (is_plugin_active('wysija-newsletters/index.php') ) {
    require_once 'rm_mailpoet2.php';
}else{
    require_once 'rm_mailpoet3.php';
}