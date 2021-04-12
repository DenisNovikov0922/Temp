<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WooFood
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, height=device-height" />

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<?php if ( get_theme_mod( 'woofood_top_bar_enabled' ) == 1) : ?>
<?php include_once(get_template_directory().'/inc/parts/headers/top-bar.php'); ?>
<?php endif; ?>
<?php if(get_option('woofood_header_style_selected')): ?>
            <?php include_once( get_template_directory().'/inc/parts/headers/'.get_option('woofood_header_style_selected').'.php');  ?>
        <?php else: ?>
                     <?php include_once( get_template_directory().'/inc/parts/headers/default.php');  ?>
   
        <?php endif;?>

