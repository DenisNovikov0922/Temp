<?php
if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

global $wpscfunction;

$logo_src   = get_option('wpsc-print-ticket_logo');
// $ticket_id  = get_post_meta($post_id,'ticket_id',true);
$file_name  = get_option('wpsc_ticket_alice').$ticket_id;

// Header
$header_height     = stripslashes(get_option('wpsc_print_page_header_height'));
ob_start();
?>
<style type="text/css">
  #header_right_info{
    width: 100%;
    position: relative;
    margin-top: -60px;
  }
  #tbl_header_info{
    width: 300px;
    position: absolute;
    right: 0;
  }
  #tbl_header_info, #tbl_header_info tr, #tbl_header_info td {
    border: none;
  }
</style>
<img style="width:100px;" src="<?php site_url('/').$logo_src ?>"> 

<?php
echo html_entity_decode(stripslashes(get_option('wpsc_print_ticket_header')));
$header_html = ob_get_clean();

// Footer
$footer_height     = stripslashes(get_option('wpsc_print_page_footer_height'));
ob_start();
echo html_entity_decode(stripslashes(get_option('wpsc_print_ticket_footer')));
$footer_html       = ob_get_clean();

// Body
ob_start();
echo html_entity_decode(stripslashes(get_option('wpsc_print_ticket_body')));
$body_html = ob_get_clean();

// Rendering
ob_start();
?>
<html>
  
  <head>
    <style>
      @page { margin: <?php echo ($header_height+25)?>px 25px <?php echo ($footer_height+25)?>px 25px; }
      header { 
        position: relative; 
        top: -<?php echo $header_height?>px; 
        left: 0px; 
        right: 0px; 
        height: <?php echo $header_height?>px; 
      }
      footer { 
        position: fixed; 
        bottom: -<?php echo $footer_height?>px; 
        left: 0px; 
        right: 0px; 
        height: <?php echo $footer_height?>px; 
      }
      body {
        font-family: DejaVu Sans, sans-serif;
      }
    </style>
  </head>
  
  <body>
    
    <header>
      <?php echo $wpscfunction->replace_macro($header_html,$ticket_id)?>
    </header>
    
    <footer>
      <?php echo $wpscfunction->replace_macro($footer_html,$ticket_id)?>
    </footer>
    
    <main style="padding:10px 0;">
      <?php echo $wpscfunction->replace_macro($body_html,$ticket_id)?>
    </main>
    
  </body>
  
</html>
<?php
$html_to_render = ob_get_clean();
require WPSC_PT_ABSPATH . 'asset/lib/dompdf/autoload.inc.php' ;
use Dompdf\Dompdf;
$dompdf = new Dompdf();
$dompdf->set_option('isRemoteEnabled', TRUE);
$dompdf->loadHtml($html_to_render);
$dompdf->render();
$canvas = $dompdf ->get_canvas();
$canvas->page_text(300, 770, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
$dompdf->stream($file_name.".pdf");
exit;