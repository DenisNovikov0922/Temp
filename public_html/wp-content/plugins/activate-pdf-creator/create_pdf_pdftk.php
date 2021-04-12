<?php
// FDF header section
$fdf_header = <<<FDF
    %FDF-1.2
    %����
    1 0 obj
    << 
    /FDF <</Fields [
    FDF;
// FDF footer section
$fdf_footer = <<<FDF
    ] /F (socialCommerce.pdf)>>
    >> 
    endobj
    trailer
    <<
    /Root 1 0 R
    >>
    %%EOF
    FDF;

// FDF content section
$fdf_content  = "<</T(Nome)/V({$data->name})>>";
$fdf_content .= "<</T(Cognome)/V({$data->surename})>>";
$fdf_content .= "<</T(EMail)/V({$data->email})>>";
$fdf_content .= "<</T(Telefono)/V({$data->phonenumber})>>";
$fdf_content .= "<</T(Partita IVACodice Fiscale)/V({$data->vat_number})>>";
$fdf_content .= "<</T(Ragone Sociale)/V({$data->company})>>";
$fdf_content .= "<</T(Codice Punto Vendita)/V({$data->sale_code})>>";
$fdf_content .= "<</T(Codice Terminale)/V({$data->terminal_code})>>";
$fdf_content .= "<</T(Richiedo attivazione del servizio Easy Delivery)/V(On)>>";
$fdf_content .= "<</T(Richiedo attivazione del servizio Easy Calendar)/V(On)>>";
$fdf_content .= "<</T(data_gg)/V(" . date("d") . ")>>";
$fdf_content .= "<</T(data_mm)/V(" . date("m") . ")>>";
$fdf_content .= "<</T(data_aaaa)/V(" . date("Y") . ")>>";

$content = $fdf_header . $fdf_content . $fdf_footer;
$FDFfile = tempnam(sys_get_temp_dir(), gethostname());
file_put_contents($FDFfile, $content);

$source = dirname(__FILE__) . "/template.pdf";
$cmd = "pdftk '" . $source . "' fill_form '" . $FDFfile . "' output '" . $fname . "'";
exec ($cmd);

unlink($FDFfile);


    $result['type'] = "success";
    echo $result;
    die();