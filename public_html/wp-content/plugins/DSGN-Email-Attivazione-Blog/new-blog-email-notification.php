<?php
/**
 * Plugin Name: DSGN - Email Attivazione Blog (NEXI)
 * Plugin URI:  https://www.dsgn.cc
 * Description: E-Mail di attivazione nuova piattaforma
 * Version:     1.0.0
 * Author:      DSGN
 * Author URI:  https://www.dsgn.cc
 * Copyright:   2020 DSGN
 *
 * Text Domain: email-notification-new-site
 * Domain Path: /languages/
 */

add_action('init', 'new_blog_do_output_buffer', -1);
function new_blog_do_output_buffer() 
{
	ob_start(); 
	if( !session_id() ){ session_start(); }
}

/**
*
* Function to send email to new multisite owner
*/
add_action( 'wp_initialize_site', 'new_blog_email_notification', 11);
function new_blog_email_notification($current_blog){
		
	$blog_id  = $current_blog->blog_id;
	switch_to_blog($blog_id); 
	
	$to_email = get_blog_option( $blog_id, 'admin_email');	
	$new_blog_url = get_blog_option( $blog_id, 'siteurl');
	
	restore_current_blog();
	
	add_filter( 'wp_mail_content_type','new_blog_email_content_type');
	ob_start();
?>	
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!--[if !mso]><!-->
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<!--<![endif]-->
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
<title>Nexi</title>
<!--[if (gte mso 9)|(IE)]>
<style type="text/css">
	table {border-collapse: collapse !important;}
</style>
<![endif]-->
<style type="text/css">
body {
    margin: 0;
    padding: 0;
    min-width: 100%;
    background-color: #ffffff;
}
table {
    border-spacing: 0;
    font-family: Arial, Helvetica, sans-serif;
}
td {
/*padding: 0;*/
            /*border-collapse: collapse !important;*/
}
img {
    border: 0;
}
.wrapper {
    width: 100%;
    table-layout: fixed;
    -webkit-text-size-adjust: 100%;
    -ms-text-size-adjust: 100%;
}
.webkit {
    max-width: 600px;
    margin: 0 auto;
}
.outer {
    margin: 0 auto;
    width: 100%;
    max-width: 600px;
}
.contents {
    width: 100%;
}
.center {
    text-align: center;
}
.italic {
    font-style: italic;
}
.bold {
    font-weight: bold;
}
p, h1, h2 {
    margin: 0;
}
p {
    font-size: 14px;
    line-height: 18px;
}
.one-column p, .two-column p, .one-column-article, #featured-article-1-text p, #featured-article-2 p {
    color: #000000;
}
#featured-article-1-text p {
    color: #000000;
}
a[x-apple-data-detectors] {
    color: inherit !important;
    text-decoration: none !important;
    font-size: inherit !important;
    font-family: inherit !important;
    font-weight: inherit !important;
    line-height: inherit !important;
}
a {
    color: #000000;/*text-decoration: none;*/
}
.padding-5 {
    padding: 5px;
}
.padding-10 {
    padding: 10px;
}
.padding-30 {
    padding: 30px;
}
.padding-toprightleft-30-bottom-10 {
    padding: 30px 30px 10px 30px;
}
.padding-top-10 {
    padding: 10px 0 0 0;
}
.padding-topbottom-10 {
    padding: 10px 0 10px 0;
}
.padding-topbottom-10-rightleft-25 {
    padding: 10px 25px 10px 25px;
}
.padding-topbottom-25-rightleft-25 {
    padding: 25px 25px 25px 25px;
}
.padding-topbottom-25-rightleft-0 {
    padding: 25px 0 25px 0;
}
.padding-topbottom-17-rightleft-30 {
    padding: 17px 30px 17px 30px;
}
.padding-topbottom-17-leftright-10 {
    padding: 17px 10px 17px 10px;
}
.padding-top-0-bottom-17-leftright-10 {
    padding: 0px 10px 17px 10px;
}
.padding-topleftright-10-bottom-40 {
    padding-top: 10px;
    padding-bottom: 40px;
    padding-right: 10;
    padding-left: 10
}
.padding-topbottom-0-leftright-30 {
    padding: 0 30px 0 30px;
}
.padding-topbottomrightleft-30-bottom-40 {
    padding: 30px 30px 40px 30px;
}
.padding-20 {
    padding: 20px;
}
.padding-top-20 {
    padding: 20px 0 0 0;
}
.padding-topbottom-20 {
    padding: 20px 0 20px 0;
}
.padding-toprightleft-20 {
    padding: 20px 20px 0 20px;
}
.padding-rightleft-20 {
    padding: 0px 20px 0 20px;
}
.padding-toprightleft-30 {
    padding: 30px 20px 0 20px;
}
.padding-toprightleft-35 {
    padding: 35px 20px 0 20px;
}
.paddin-top-17 {
    padding-top: 17px;
}
.paddin-bottom-17 {
    padding-botton: 17px;
}
.paddin-leftright-10 {
    padding-left: 10px;
    padding-right: 10px;
}
.two-column img, .three-column img, .full-width-image img, #logo img, #hero-image img, #cta-image img, .left-sidebar img, .right-sidebar img {
    width: 100%;
    height: auto;
}
.two-column-padding img {
/*width: 100%;
    height: auto;*/
}
.two-column img {
    max-width: 260px;
}
.two-column-padding img {
    max-width: 265px;
}
.three-column-padding img {
/*max-width: 166px;*/
}
.full-width-image img {
    max-width: 600px;
}
.one-column .contents, .one-column-article .contents, #featured-article-2-back .contents {
    text-align: left;
}
.one-column-article .contents2 {
    text-align: center;
}
.two-column {
    text-align: center;
    font-size: 0;
}
.two-column .column {
    width: 100%;
    max-width: 300px;
    display: inline-block;
    vertical-align: top;
}
.two-column-padding {
/*background-color: red;*/
}
.two-column-padding .column {
    width: 100%;
    /*max-width: 250px;*****************************/
    max-width: 265px;
    display: inline-block;
    vertical-align: top;
}
.two-column-padding .column1 {
    width: 100%;
    max-width: 150px;
    display: inline-block;
    vertical-align: top;
}
.two-column-padding .column2 {
    width: 100%;
    max-width: 390px;
    display: inline-block;
    vertical-align: top;
}
.two-column .contents, .two-column-padding .contents {
    font-size: 14px;
    text-align: left;
}
.grey06 {
    background-color: #f0f0f0;
}
.white {
    background-color: #ffffff;
}
.blu-nexi {
    background-color: #2d32aa;
}
#two-column-featured {
    text-align: center;
    font-size: 0;
    padding-top: 10px;
    padding-bottom: 10px;
}
.three-column {
    text-align: center;
    font-size: 0;
    padding-top: 10px;
    padding-bottom: 10px;
}
.three-column .column {
    width: 100%;
    max-width: 200px;
    display: inline-block;
    vertical-align: top;
}
.three-column .contents {
    font-size: 14px;
    text-align: center;
}
.three-column-padding .column {
    width: 100%;
    max-width: 135px;
    display: inline-block;
    vertical-align: top;
}
.three-column-padding .contents {
    font-size: 14px;
    text-align: center;
}

@media screen and (max-width: 620px) {
/*scarto barra di scorrimento*/
.two-column .column, .three-column .column {
    max-width: 100% !important;
}
.two-column-padding .column {
    max-width: 100% !important;
    min-width: 100% !important;
}
.two-column-padding .column1 {
    max-width: 100% !important;
    min-width: 100% !important;
}
.two-column-padding .column2 {
    max-width: 100% !important;
    min-width: 100% !important;
}
.three-column-padding .column {
    max-width: 100% !important;
    min-width: 100% !important;
}
.two-column img {
    max-width: 100% !important;
}
.two-column-padding img {
    max-width: 100% !important;
}
#social-media img {
    max-width: 22px !important;
}
#cta-image img {
    max-width: 30px !important;
}
.three-column img {
    max-width: 50% !important;
}
.center-mobile {
    text-align: center !important;
}
}

/*sostituzione header*/
        
@media only screen and (max-width: 500px) {
.onlydesktop {
    display: none !important;
    width: 0 !important;
    height: 0 !important;
}
.mobileversion {
    display: block !important;
    float: none !important;
    width: 100% !important;
    height: auto !important;
    overflow: visible !important;
    line-height: normal !important;
    max-height: none !important;
    margin: 0;
    padding: 0px;
}
}

@media screen and (min-width: 620px) {
.col-box-iovinco {
    display: inline-block !important;
    vertical-align: top !important;
    width: 130px !important;
}
.col-box-space {
    display: inline-block !important;
    vertical-align: top !important;
    width: 50px !important;
}
.col-box-half-space {
    display: inline-block !important;
    vertical-align: top !important;
    width: 25px !important;
}
.col-box01-01 {
    display: inline-block !important;
    vertical-align: top !important;
    width: 134px !important;
}
.col-box01-02 {
    display: inline-block !important;
    vertical-align: top !important;
    width: 122px !important;
}
.col-box01-03 {
    display: inline-block !important;
    vertical-align: top !important;
    width: 115px !important;
}
.col-box01-04 {
    display: inline-block !important;
    vertical-align: top !important;
    width: 120px !important;
}
.arrow-right-down {
    display: inline-block !important;
    vertical-align: top !important;
    width: 15px !important;
    height: 30px !important;
}
}
</style>
</head>

<body style="margin:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;min-width:100%;background-color:#e0e1dd;">
<!----> 
<!--[if (gte mso 9)|(IE)]>
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left" bgcolor="#e0e1dd" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif; " >
                            <tr>
                            <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                            <![endif]-->

<div style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;background-color:#e0e1dd;width:100%;" > 
  <!---->
  
  <center class="wrapper" style="width:100%;table-layout:fixed;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;">
    <div class="webkit" style="max-width:600px; background-color: #ffffff;"> 
      
      <!--[if (gte mso 9)|(IE)]>
							<table width="600" align="center" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
							<tr>
							<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
							<![endif]-->
      <table border="0" align="center" cellpadding="0" cellspacing="0" class="outer" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px;">
        <tbody>
          <tr>
            <!-- <td align="center" bgcolor="#e0e1dd" style="padding: 10px;"><a href="#" style="font-family: Arial, Helvetica, sans-serif; font-size: 11px; mso-height-rule: exactly; color:#000000; text-decoration:underline;">Guarda la versione online</a></td> -->
          </tr>
        </tbody>
      </table>
      <!--[if (gte mso 9)|(IE)]>
							</td>
							</tr>
							</table>
							<![endif]--> 
 <!--[if (gte mso 9)|(IE)]>
<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
  <tr>
     <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
        <![endif]-->

<table class="outer" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px;background-color: #2D32AA;"  bgcolor="#2D32AA">
  <tr>
    <td class="full-width-image" align="left" valign="middle"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/69c7a7b3-f43e-46f3-8949-062a3d752c51.png" alt="Nexi" width="600" height="50"></td>
  </tr>
</table>

<!--[if (gte mso 9)|(IE)]>
     </td>
  </tr>
</table>
<![endif]-->     

      
      <!--[if (gte mso 9)|(IE)]>
							<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
							<tr>
							<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
							<![endif]-->
      
      <table class="outer" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px;" >
        <tr>
          <td class="white" style="padding-top:30px;padding-bottom:0px;padding-right:30px;padding-left:30px;text-align:center;font-size:0; background-color: #ffffff;" ><div class="column" style="width:100%;display:inline-block;vertical-align:top;" >
              <table width="100%" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
                <tr>
                  <td><table class="contents" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;width:100%;font-size:14px;line-height: 18px;text-align:left;" >
                      <tr>
                        <td align="left" style="padding-top:0;padding-bottom:15px;padding-right:0;padding-left:0;"><p style="margin:0;font-size:14px;color:#000000;"><strong>Gentile Cliente,</strong><br />
                            <br />ti confermiamo che il servizio <strong style="color: #2d32aa;">Easy Delivery</strong> &egrave; attivo.<br>Da questo momento puoi configurare e personalizzare la tua vetrina digitale con immagini, video e contenuti, iniziare a gestire le tue prenotazioni e vendere i tuoi servizi online.</p></td>
                      </tr>
                    </table></td>
                </tr>
              </table>
            </div></td>
        </tr>
      </table>
      
      <!--[if (gte mso 9)|(IE)]>
                           </td>
                        </tr>
                     </table>
                     <![endif]-->
      <!--[if (gte mso 9)|(IE)]>
							<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
							<tr>
							<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;background-color:#f0f0f0;" >
							<![endif]-->
      
      <table class="outer" align="left" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif; margin:0 auto; width:100%; max-width:600px; background-color: #f0f0f0;">
        <tr>
          <td style="padding-top:20px;padding-bottom:0px;padding-right:30px;padding-left:30px;background-color:#fff;">
          	<table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td style="border-collapse:collapse; font-size:14px; line-height:18px;color: #fff; border: 0px none transparent;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
						  <td>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
							  <tr>
							  
							  <td align="center" valign="middle"><p style="margin:0;font-size:22px; line-height:26px; color:#2d32aa;"><strong>Come funziona?</strong></p></td>
							  </tr>
							</table>
						   </td>
              </tr>
                      </tbody>
                    </table></td>
                </tr>
              </tbody>
            </table></td>
        </tr>
      </table>
      <!--[if (gte mso 9)|(IE)]>
							</td>
							</tr>
							</table>
							<![endif]-->

		
      <!--[if (gte mso 9)|(IE)]>
                     <table width="600" align="center" border="0" bgcolor="#fff" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
                        <tr>
                           <td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
                              <![endif]-->
      <table class="outer" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px;height: auto" border="0" align="center" cellspacing="0" cellpadding="0">
        <tbody>
          <tr>
            <td align="center" class="two-column-padding" style="padding-top:10px;padding-bottom:30px;padding-right:30px;padding-left:30px;text-align:center;font-size:0;background-color: #fff;"><!--[if (gte mso 9)|(IE)]>
				   <table width="540" border="0" bgcolor="#fff" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
					  <tr>
						 <td bgcolor="#fff" width="200" valign="top" style="padding: 0px;">
						<![endif]--> 
              <!-- box -->
              
              <div class="column" style="width:100%;max-width:200px;display:inline-block;vertical-align:middle;padding: 0px;margin: 0 auto;background-color: #fff;">
              	<table border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#fff" style="width:100%;">
                  <tbody>
                    <tr>
                      <td style="background-color: #fff; vertical-align: middle;"><table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0 auto;">
                          <tbody>
                            <tr>
                              <td align="center" valign="middle" style="padding-top:0px; padding-bottom:0px; padding-left:0px;padding-right:0px;"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/64bcc149-14df-4522-b121-6f5d192efebe.jpg" width="170" height="190" alt="" /></td>
                            </tr>
							  <tr>
                             <!-- <td align="center" valign="middle" style="padding-top:0px; padding-bottom:10px; padding-left:0px;padding-right:0px;"><a href="#" target="_blank"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/59d9c7dd-1a68-4476-a486-9484c02c43d9.png" width="200" height="32" alt="" border="0" /></a></td> -->
                            </tr>
                        </tbody>
                        </table></td>
					  </tr>
                  </tbody>
                 </table>
              </div>
              
              <!-- /box --> 
              <!--[if (gte mso 9)|(IE)]>
						 </td>
					<![endif]--> 
              
              <!--[if (gte mso 9)|(IE)]>
						 <td bgcolor="#fff" width="330" valign="top" style="padding: 0px;" >
					<![endif]--> 
              
              <!-- box -->
              
              <div class="column" style="width:100%;max-width:330px;display:inline-block;vertical-align:middle;padding: 0px 0px 0px 5px;margin: 0 auto;background-color: #fff;">
                <table border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="width:100%;">
                  <tbody>
                    <tr>
                      
					 <td style="background-color: #fff;" valign="top">
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 0px;">
							<tr>
						  		<td colspan="3" align="left" style="padding-top:10px;padding-bottom:10px;padding-right:0;padding-left:0; text-align: left; font-size: 14px; color: #2d32aa;">Tramite l’utilizzo della piattaforma Easy Delivery, accessibile sia da web che da mobile, puoi:</td>
						  	</tr>
						  	<tr>
						  		<td style="text-align: left" align="left" width="20" valign="top"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/f9985190-72a2-4e17-9be4-3e7abcd3bb05.png" width="20" alt="" height="20"></td>
						  		<td width="10">&nbsp;</td>
						  		<td align="left" style="padding-top:0px;padding-bottom:10px;padding-right:0;padding-left:0; text-align: left; font-size: 14px; color: #2d32aa;">pubblicare il men&ugrave;&#47;catalogo dei tuoi prodotti online aggiungendo foto e descrizioni</td>
						  	</tr>
						  	<tr>
						  		<td style="text-align: left" align="left" width="20" valign="top"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/f9985190-72a2-4e17-9be4-3e7abcd3bb05.png" width="20" alt="" height="20"></td>
						  		<td width="10">&nbsp;</td>
						  		<td align="left" style="padding-top:0px;padding-bottom:10px;padding-right:0;padding-left:0; text-align: left; font-size: 14px;  color: #2d32aa;">avere un QR Code per permettere ai tuoi clienti di visualizzare il men&ugrave; del giorno e per effettuare l&rsquo;ordine al tavolo</td>
						  	</tr>
						  	<tr>
						  		<td style="text-align: left" align="left" width="20" valign="top"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/f9985190-72a2-4e17-9be4-3e7abcd3bb05.png" width="20" alt="" height="20"></td>
						  		<td width="10">&nbsp;</td>
						  		<td align="left" style="padding-top:0px;padding-bottom:10px;padding-right:0;padding-left:0; text-align: left; font-size: 14px;  color: #2d32aa;">ricevere gli ordini a distanza e gestire le consegne a domicilio permettendo ai tuoi clienti di pagare con carta di credito o alla consegna</td>
						  	</tr>
						  	<tr>
						  		<td style="text-align: left" align="left" width="20" valign="top"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/f9985190-72a2-4e17-9be4-3e7abcd3bb05.png" width="20" alt="" height="20"></td>
						  		<td width="10">&nbsp;</td>
						  		<td align="left" style="padding-top:0px;padding-bottom:10px;padding-right:0;padding-left:0; text-align: left; font-size: 14px;  color: #2d32aa;">integrarti ai principali corrieri per il ritiro dal tuo punto vendita e la consegna in prossimit&agrave; o a lungo raggio inserendo un costo aggiuntivo alla spedizione</td>
						  	</tr>
						  </table>
						</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- /box --> 
              <!--[if (gte mso 9)|(IE)]>
						 </td>
					  </tr>
				   </table>
				   <![endif]--></td>
          </tr>
        </tbody>
      </table>
      
      <!--[if (gte mso 9)|(IE)]>
                           </td>
                        </tr>
                     </table>
                     <![endif]--> 		
		
		
		
		
		
		
		
		
 <!--[if (gte mso 9)|(IE)]>
<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
<tr>
<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0; background-color: #fff;" >
<![endif]-->
          
<table class="outer" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px;" border="0" align="center" cellspacing="0" cellpadding="0">
 <tbody>
  <tr>
      <td style="padding:0px 30px 30px 30px;font-size:0; background-color: #fff;">
       <div class="col-1box">
          <table style="border-spacing:0;font-family: Arial, Helvetica, sans-serif; padding: 20px;" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#2d32aa">
              <tbody>
				  <tr>
					  <td>
              			<table width="100%" border="0" cellspacing="0" cellpadding="0">
      						<tr>
								<td align="center" valign="middle" style="font-family: Arial, Helvetica, sans-serif; color:#fff; font-size:16px;text-align: center;" >
									<p style="line-height: 18px; padding-bottom: 10px;"><strong>Per dare visibilit&agrave; alla tua attivit&agrave; anche fuori dal punto vendita</strong> devi solo condividere sulle tue pagine Social oppure via SMS o email questo link:<a href="<?php echo $new_blog_url ; ?>" style="color: #00b8de"><br><strong><?php echo $new_blog_url ; ?></strong></a></p>
									
									<p >I tuoi Clienti potranno consultare il tuo catalogo online e acquistare i prodotti pagando con carta direttamente sulla tua vetrina in modo semplice e sicuro.</p>
								</td>
      						</tr>
						</table>
      </td>
              </tr>
              
           </tbody>
          </table>
        </div>
                </td>
            </tr>
          </tbody></table>


<table class="outer" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px;" border="0" align="center" cellspacing="0" cellpadding="0">
 <tbody>
  <tr>
      <td style="padding:0px 30px 30px 30px;font-size:0; background-color: #fff;">
       <div class="col-1box">
          <table style="border-spacing:0;font-family: Arial, Helvetica, sans-serif; padding: 20px;" width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#2d32aa">
              <tbody>
				  <tr>
					  <td>
              			<table width="100%" border="0" cellspacing="0" cellpadding="0">
      						<tr>
								<td align="center" valign="middle" style="font-family: Arial, Helvetica, sans-serif; color:#fff; font-size:16px;text-align: center;" >
									<p style="line-height: 18px; padding-bottom: 10px;"><strong>Area di Amministrazione</strong><br><a href="<?php echo $new_blog_url ; ?>/accesso" style="color: #00b8de"><br><strong><?php echo $new_blog_url ; ?>/accesso</strong></a></p>
									
									<p >Effettua il login con il tuo indirizzo e-mail e la password ricevuta in fase di attivazione</p>
								</td>
      						</tr>
						</table>
      </td>
              </tr>
              
           </tbody>
          </table>
        </div>
                </td>
            </tr>
          </tbody></table>  


          
          <!--[if (gte mso 9)|(IE)]>
       </td>
       </tr>
       </table>
       <![endif]-->                            
   

     <!--[if (gte mso 9)|(IE)]>
							<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
							<tr>
							<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
							<![endif]-->

	<table class="outer" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px;">
		<tr>
          <td class="white" style="padding-top:0px;padding-bottom:30px;padding-right:30px;padding-left:30px;text-align:center;font-size:0; background-color: #fff;" ><div class="column" style="width:100%;display:inline-block;vertical-align:top;" >
              <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; background-color: #fff;" >
                <tr>
                  <td><table class="contents" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif;width:100%;font-size:14px;line-height: 18px;text-align:left;" >
                      
					  <tr>
					  <td colspan="2">
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 0px;">
							  <tr>
						  <td align="left" style="padding-top:0;padding-bottom:10px;padding-right:0;padding-left:0;" colspan="3"><p style="margin:0;font-size:14px;color:#000000;"><strong style="font-size:22px;color:#2d32aa; line-height: 26px;">Condizioni economiche</strong></p></td>
                      </tr>
						  	<tr>
						  		<td style="text-align: left" align="left" width="20" valign="top"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/f9985190-72a2-4e17-9be4-3e7abcd3bb05.png" width="20" alt="" height="20"></td>
						  		<td width="10">&nbsp;</td>
						  		<td align="left" style="padding-top:0px;padding-bottom:10px;padding-right:0;padding-left:0; text-align: left; font-size: 14px;"><strong style="color: #2d32aa; font-size: 16px;">Canone mensile: 0 € fino al 31 Dicembre 2020</strong><br> 
					  		  Dal 1 Gennaio 2021: 20 &euro;&#47;mese con fattura emessa da Nexi</td>
						  	</tr>
						  	<tr>
						  		<td style="text-align: left" align="left" width="20" valign="top"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/f9985190-72a2-4e17-9be4-3e7abcd3bb05.png" width="20" alt="" height="20"></td>
						  		<td width="10">&nbsp;</td>
						  		<td align="left" style="padding-top:0px;padding-bottom:10px;padding-right:0;padding-left:0; text-align: left; font-size: 14px;"><strong style="color: #2d32aa; font-size: 16px;">Costo di attivazione: 0 &euro;</strong></td>
						  	</tr>
						  	<tr>
						  		
						  		<td colspan="3" align="left" style="padding-top:0px;padding-bottom:10px;padding-right:0;padding-left:0; text-align: left; font-size: 14px;">Sui pagamenti digitali vengono applicate le commissioni previste dal contratto del servizio sottoscritto con Nexi e associato al servizio Social Commerce. </td>
						  	</tr>
						  	
						  </table>
						</td>
					  </tr>
					 
					</table></td>
                </tr>
              </table>
            </div></td>
        </tr>
      </table>
      
      <!--[if (gte mso 9)|(IE)]>
                           </td>
                        </tr>
                     </table>
                     <![endif]--> 		
		
		
		
	    <!--[if (gte mso 9)|(IE)]>
							<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
							<tr>
							<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
							<![endif]-->

	<table class="outer" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px;">
		<tr>
          <td class="white" style="padding-top:0px;padding-bottom:0px;padding-right:30px;padding-left:30px;text-align:center;font-size:0; background-color: #fff;" ><div class="column" style="width:100%;display:inline-block;vertical-align:top;" >
              <table width="100%" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif; background-color: #fff;" >
                <tr>
                  <td><table class="contents" border="0" cellpadding="0" cellspacing="0" style="font-family: Arial, Helvetica, sans-serif;width:100%;font-size:14px;line-height: 18px;text-align:left;" >
                      
					  <tr>
					  <td colspan="2">
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="padding: 0px;">
							  <tr>
						  <td align="left" style="padding-top:0;padding-bottom:10px;padding-right:0;padding-left:0;" colspan="3"><p style="margin:0;font-size:14px;color:#000000;"><strong style="font-size:22px;color:#2d32aa; line-height: 26px;">Supporto</strong></p></td>
                      </tr>
						  	
						  </table>
						</td>
					  </tr>
					 
					</table></td>
                </tr>
              </table>
            </div></td>
        </tr>
      </table>
      
      <!--[if (gte mso 9)|(IE)]>
                           </td>
                        </tr>
                     </table>
                     <![endif]--> 		
		
			
		
		
		
		

 
		
 


      <!--[if (gte mso 9)|(IE)]>
							<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
							<tr>
							<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;background-color:#fff;" >
							<![endif]-->
      
      <table class="outer" align="left" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif; margin:0 auto; width:100%; max-width:600px; background-color: #fff;">
        <tr>
          <td style="padding-top:0px;padding-bottom:30px;padding-right:30px;padding-left:30px;background-color:#fff;">
          	<table style="width:100%;" border="0" cellpadding="0" cellspacing="0">
              <tbody>
                <tr>
                  <td style="border-collapse:collapse; font-size:14px; line-height:18px;color: #fff; border: 0px none transparent;">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tbody>
                        <tr>
						  <td>
						  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="left">
							  <tr>
							  <td style="text-align: left; padding-right: 10px;" align="left" width="42" valign="top"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/8856ddf6-0a2e-4f20-9033-9cf700c68084.png" alt="icon" width="40" height="40"/></td>							  
							  <td align="left" valign="top"><p style="margin:0;font-size:14px; line-height:18px; color:#000;padding-bottom: 20px;">Consulta le <strong>guide utente</strong>, i <strong>video tutorial</strong> e il <strong>materiale informativo</strong> nella sezione “Come funziona” della piattaforma.</p></td>
							  </tr>
							  <tr>
							  <td style="text-align: left; padding-right: 10px;" align="left" width="42" valign="top"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/739bf9bb-193b-4b88-bd28-96bffd18be94.png" alt="icon" width="42" height="42"/></td>
							  <td align="left" valign="top"><p style="margin:0;font-size:14px; line-height:18px; color:#000; padding-top: 0px;">Per <strong>assistenza su configurazione e utilizzo del servizio</strong> contatta DSGN, il partner tecnologico di Nexi, ai seguenti riferimenti:
								  <ul>
									  <li style="margin:0;font-size:14px; line-height:18px; color:#000;">compilando una richiesta direttamente nella sezione &ldquo;Aiuto&rdquo; della piattaforma</li>
									  <li style="margin:0;font-size:14px; line-height:18px; color:#000;">inviando una email a <a href="mailto:supporto@easy-delivery.it" target="_blank" style="color: #2d32aa;"><strong> supporto@easy-delivery.it</strong></a></li>
									  <li style="margin:0;font-size:14px; line-height:18px; color:#000;">scrivendo su Whatsapp al numero <a href="https://api.whatsapp.com/send?phone=+393270136948" target="_blank" style="color: #2d32aa;"><strong><span style="white-space: nowrap">393 270136948</span></strong></a>, accessibile lun-ven 8-20</li>
								  </ul></p></td>
							  </tr>
							  
						  
							  <tr>
							  <td style="text-align: left; padding-right: 10px;" align="left" width="42" valign="top"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/7fb778e0-5021-43f5-bdd6-6efa2a837aea.png" alt="icon" width="40" height="40"/></td>
							  
							  <td align="left" valign="top"><p style="margin:0;font-size:14px; line-height:18px; color:#000; padding-bottom: 20px;">Per <strong>assistenza amministrativa</strong> e per temi legati alla fatturazione, contatta Nexi al numero riportato sul tuo estratto conto o nella sezione &ldquo;Assistenza e Contatti&rdquo; di Nexi Business.</p></td>
							  </tr>
						  
						 	 
							</table>
						   </td>
              </tr>
                      </tbody>
                    </table></td>
                </tr>
              </tbody>
            </table></td>
        </tr>
      </table>
      <!--[if (gte mso 9)|(IE)]>
							</td>
							</tr>
							</table>
							<![endif]-->




      

      <!--[if (gte mso 9)|(IE)]>
							<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
							<tr>
							<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
							<![endif]-->
      
      <table class="outer" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px;">
        <tr>
          <td height="78" align="center" valign="middle" class="blu-nexi" style="padding-right:10px;padding-left:10px;background-color: #2d32aa;"><a href="https://www.nexi.it/" target="_blank" style="margin:0; padding:0;">
            <center>
              <img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/95349353-41a9-42d1-96f6-dba9a57ebe40.png" width="92" height="41" alt="nexi" class="img-auto" />
            </center>
            </a></td>
        </tr>
      </table>
      <!--[if (gte mso 9)|(IE)]>
							</td>
							</tr>
							</table>
							<![endif]--> 
  
		
		
				
			
			   
      <!--[if (gte mso 9)|(IE)]>
							<table width="600" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;" >
							<tr>
							<td style="padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;" >
							<![endif]-->
      <table class="outer" align="center" border="0" cellpadding="0" cellspacing="0" style="border-spacing:0;font-family: Arial, Helvetica, sans-serif;margin:0 auto;width:100%;max-width:600px; background-color: #e0e1dd;" >
       
		
		  
		  <tr>
          <td height="30" align="center" style="padding-top:20px;padding-bottom:30px;padding-right:30px;padding-left:30px;text-align:center;font-size:0;"><table style="width:100%;" border="0" cellspacing="0" cellpadding="0">
            <tbody>
              <tr>
                <td width="30" valign="middle"><img src="http://image.message.nexi.it/lib/fe4311717564047c741771/m/1/463f3ec1-e72f-485a-999b-63fbcf161f9d.png" alt="" width="30" height="24"></td>
                <td style="font-size:11px; line-height:14px; text-align: left;color: #000000;"  valign="middle" align="left">Nexi si preoccupa della tua sicurezza: non richiediamo l&rsquo;inserimento di credenziali o di dati sensibili al di fuori del sito Nexi o di quello della tua Banca.</td>
              </tr>
            </tbody>
          </table></td>
        </tr>
		 
      </table>
      <!--[if (gte mso 9)|(IE)]>
							</td>
							</tr>
							</table>
							<![endif]--> 
		
		
    </div>
  </center>
  <!----> 
</div>
<!--[if (gte mso 9)|(IE)]>
                            </td>
                            </tr>
                            </table>
                            <![endif]--> 
<!---->
</body>
</html>


<?php
	$email_body = ob_get_contents();
	ob_end_clean();
	
	$subject    = 'La tua piattaforma è attiva! Scopri Easy Delivery'; 
	$from_email = get_option('admin_email');

	$headers = array('Content-Type: text/html; charset=UTF-8', 'From: Nexi <'.$from_email.'>');
		
	$email_ack = wp_mail($to_email, $subject, $email_body, $headers);
	remove_filter( 'wp_mail_content_type','new_blog_email_content_type' );	
}

/*
* Function to set email type as HTML
*/
function new_blog_email_content_type(){
    return "text/html";
}