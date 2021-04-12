<?php
$token = '';
$xmlStr = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<getHostedPaymentPageRequest xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd">
    <merchantAuthentication></merchantAuthentication>
    <transactionRequest>
        <transactionType>authCaptureTransaction</transactionType>
        <amount>{$data->pricing_details->total_price}</amount>
        <order>
            <invoiceNumber>{$data->order_id}</invoiceNumber>
            <description>Product Description</description>
        </order>
    </transactionRequest>
    <hostedPaymentSettings>
        <setting>
            <settingName>hostedPaymentIFrameCommunicatorUrl</settingName>
        </setting>
        <setting>
            <settingName>hostedPaymentButtonOptions</settingName>
            <settingValue>{"text": "Pay"}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentReturnOptions</settingName>
        </setting>
        <setting>
            <settingName>hostedPaymentOrderOptions</settingName>
            <settingValue>{"show": false}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentPaymentOptions</settingName>
            <settingValue>{"cardCodeRequired": true}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentBillingAddressOptions</settingName>
            <settingValue>{"show": true, "required":false}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentShippingAddressOptions</settingName>
            <settingValue>{"show": false, "required":false}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentSecurityOptions</settingName>
            <settingValue>{"captcha": false}</settingValue>
        </setting>
        <setting>
            <settingName>hostedPaymentCustomerOptions</settingName>
            <settingValue>{"showEmail": true, "requiredEmail":true}</settingValue>
        </setting>
    </hostedPaymentSettings>
</getHostedPaymentPageRequest>
XML;
$xml = simplexml_load_string($xmlStr, 'SimpleXMLElement', LIBXML_NOWARNING);
// $xml = new SimpleXMLElement($xmlStr);
$xml->merchantAuthentication->addChild('name', $login_id);
$xml->merchantAuthentication->addChild('transactionKey', $trans_key);
$commUrl = json_encode(array('url' => thisPageURL()."IFrameCommunicator.html" ), JSON_UNESCAPED_SLASHES);
$xml->hostedPaymentSettings->setting[0]->addChild('settingValue', $commUrl);
$retUrl = json_encode(array("showReceipt" => false , 'url' => thisPageURL(), "urlText"=>"Continue to site", "cancelUrl" => thisPageURL().'empty.html', "cancelUrlText" => "Cancel" ), JSON_UNESCAPED_SLASHES);
$xml->hostedPaymentSettings->setting[2]->addChild('settingValue', $retUrl);
if($anet_test_mode=='yes'){
    $url = "https://apitest.authorize.net/xml/v1/request.api";
}else{
    $url = "https://api.authorize.net/xml/v1/request.api";
}
try {   //setting the curl parameters.
        $ch = curl_init();
    if (false === $ch) {
        throw new Exception('failed to initialize');
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml->asXML());
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 300);
    // The following two curl SSL options are set to "false" for ease of development/debug purposes only.
    // Any code used in production should either remove these lines or set them to the appropriate
    // values to properly use secure connections for PCI-DSS compliance.
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //for production, set value to true or 1
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //for production, set value to 2
    //curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false);
    //curl_setopt($ch, CURLOPT_PROXY, 'userproxy.visa.com:80');
    $content = curl_exec($ch);
    $content = str_replace('xmlns="AnetApi/xml/v1/schema/AnetApiSchema.xsd"', '', $content);
    $hostedPaymentResponse = new SimpleXMLElement($content);
    if (false === $content) {
            throw new Exception(curl_error($ch), curl_errno($ch));
    }
    
    //echo '<pre>';print_r($hostedPaymentResponse);
    if($hostedPaymentResponse->messages->resultCode=='Ok'){
        $token = $hostedPaymentResponse->token;
    }
    
    curl_close($ch);
} catch (Exception $e) {
        trigger_error(sprintf('Curl failed with error #%d: %s', $e->getCode(), $e->getMessage()), E_USER_ERROR);
}
function thisPageURL()
{
    return RM_BASE_URL . "plus/rm_authorizenet/includes/";
}
?>