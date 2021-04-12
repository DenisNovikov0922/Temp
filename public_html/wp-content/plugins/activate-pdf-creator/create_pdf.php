<?php

$key = 'T4xzP9ddKziEPaVeBt5yC4UauFeZgxbFAVQ3MxNAViU7tU7MAzD7R8I95p2Jo9Fp';

$filename = dirname(__FILE__) . "/template.pdf";
$md5sum = md5_file($filename);



$request = curl_init('https://pdf.ninja/api/v1/info?fileId=source&md5sum='.$md5sum.'&key='.$key);
curl_setopt( $request, CURLOPT_RETURNTRANSFER, true );
$response = curl_exec($request);
curl_close($request);
$res = json_decode($response);



if(!$res->success){	
	$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://pdf.ninja/api/v1/file",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => array(
            'file'=> new CURLFILE($filename),
            'fileId' => 'source',
            'key' => $key,
            'md5sum' => $md5sum
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
}


$senddata = '{"Nome":"'.$data->name.'","Cognome":"'.$data->surename.'","EMail":"'.$data->email.'","Telefono":"'.$data->phonenumber.'","Partita IVACodice Fiscale":"'.$data->vat_number.'","Ragone Sociale":"'.$data->company.'","Codice Punto Vendita":"'.$data->sale_code.'","Codice Terminale":"'.$data->terminal_code.'","Richiedo attivazione del servizio Easy Delivery":"On","Richiedo attivazione del servizio Easy Calendar":"Off","data_gg":"'.date("d").'","data_mm":"'.date("m").'","data_aaaa":"'.date("Y").'"}';

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => "https://pdf.ninja/api/v1/fill",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => array(
  		'key' => $key,
  		'md5sum' => $md5sum,
  		'data' => $senddata,
  		'fileId' => 'source'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$res = json_decode($response);
if($res->success){
	file_put_contents($fname, fopen($res->fileUrl, "r"));
}


