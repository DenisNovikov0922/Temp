<?php

function my_admin_menu() {

add_menu_page(

__( 'Assistenza', 'my-textdomain' ),

__( 'Assistenza', 'my-textdomain' ),

'manage_options',

'assistenza',

'my_admin_page_contents',

'dashicons-sos',

13

);

}



add_action( 'admin_menu', 'my_admin_menu' );



function my_admin_page_contents() {

?>
<div class="wrap">
<h1 class="wp-heading-inline">

<i class="fa fa-life-ring" aria-hidden="true"></i> <?php esc_html_e( 'Assistenza Easy Delivery', 'my-plugin-textdomain' ); ?>

</h1>
<hr class="wp-header-end">
In questa sezione potrai consultare la nostra documentazione, leggere le FAQ oppure richiedere assistenza diretta tramite i nostri canali: whatsapp e ticket.<br>Ti ricordiamo che per le emergenze, inclusi i problemi tecnici, è disponibile il numero verde.<br>
<h2>Documentazione</h2>
Segui questo <a href="https://www.easy-delivery.it/blog/documentation" target="_blank">collegamento</a> per accedere alla documentazione di Easy Delivery: potrai trovare manuali, procedure operative e tutorials.<p>&nbsp;</p>
<hr>
<h2>
<i class="fa fa-whatsapp" aria-hidden="true"></i> Whatsapp</h2>Invia una richiesta di informazioni sul nostro canale whatsapp, un operatore prenderà in carico la tua richiesta<br><i>Servizio attivo lun-ven 8-20</i>
<p class="submit"><a class="button button-primary" href="https://api.whatsapp.com/send?phone=+393270136948" target="_blank">Apri Chat</a></p><br>
<hr>
<h2>
<i class="fa fa-ticket" aria-hidden="true"></i> Ticket di Supporto</h2>Apri un ticket di supporto per problematiche relative alla piattaforma o al software di stampa ordini<br><i>Riceverai una risposta entro 2 ore dal lun al ven dalle 8 alle 20</i>
<p class="submit"><a class="button button-primary" href="https://easy-delivery.it/ticket/">Apri un Ticket di Supporto</a>
<hr>
<h2 style="color: red !important;">
<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Emergenza</h2>Chiamaci al numero verde per emergenze come: blocco improvviso della web-app, intrusione, furto di dati, truffe e manomissioni<br><i>Servizio attivo dal lun al ven dalle 8 alle 20, è sempre consigliabile <b><u>creare un ticket</u></b> prima di telefonare</i><br>
<img src="https://easy-delivery.it/wp-content/uploads/2020/07/numero-verde.png" width="220px">
<hr>
<h2>
<i class="fa fa-times" aria-hidden="true"></i> Disattivazione</h2>Se vuoi inviare una richiesta di disdetta, scrivici tramite l'apposito pulsante specificando, nel corpo del messaggio, il tuo <strong>indirizzo e-mail</strong> e <strong>l'indirizzo della piattaforma</strong>, indicando come oggetto <strong>Richiesta Disattivazione</strong>
<p class="submit"><a class="button button-primary" href="mailto:supporto@easy-delivery.it">Invia Richiesta Disattivazione</a>
</div>
<?php

}
