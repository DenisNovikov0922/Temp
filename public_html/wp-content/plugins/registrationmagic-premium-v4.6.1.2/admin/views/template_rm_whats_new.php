<?php
if (!defined('WPINC')) {
    die('Closed');
}
$html = file_get_contents(RM_EXTERNAL_DIR.'whatisnew/index.html');
$ext_dir_as_url = plugin_dir_url(RM_EXTERNAL_DIR).'external/';

$html = str_replace("src='images/", "src='".$ext_dir_as_url.'whatisnew/images/', $html);
$html = str_replace('src="images/', 'src="'.$ext_dir_as_url.'whatisnew/images/', $html);
echo $html;