jQuery(document).ready( function() {
    rm_anet_add_signup_link();
});


function rm_anet_add_signup_link() {
    var signup_html = '<div id="id_anet_signup_link"><a href="http://reseller.authorize.net/application/100412/" target="_blank">Sign Up</a></div>';
    jQuery("input[name='payment_gateway[]'][value='anet']").parent().append(signup_html);
}