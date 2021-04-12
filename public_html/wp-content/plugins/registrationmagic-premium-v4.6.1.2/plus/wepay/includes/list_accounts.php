<?php
/**
 * This PHP script helps you find your account_id
 *
 */



/**
 * Put your API credentials here:
 * Get these from your API app details screen
 * https://stage.wepay.com/app
 */
$client_id = "PUT YOUR CLIENT_ID HERE";
$client_secret = "PUT YOUR CLIENT_SECRET HERE";
$access_token = "PUT YOUR ACCESS TOKEN HERE";

/** 
 * Initialize the WePay SDK object 
 */
require '../wepay.php';
Wepay::useStaging($client_id, $client_secret);
$wepay = new WePay($access_token);

/**
 * Make the API request to get a list of all accounts this user owns
 * 
 */
try {
	$accounts = $wepay->request('/account/find');
} catch (WePayException $e) { // if the API call returns an error, get the error message for display later
	$error = $e->getMessage();
}

?>

<html>
	<head>
	</head>
	
	<body>
		
		<h1><?php _e('List all accounts', 'registrationmagic-gold'); ?>:</h1>
		
		<p><?php _e('The following is a list of all accounts that this user owns', 'registrationmagic-gold'); ?></p>
		
		<?php if (isset($error)): ?>
			<h2 style="color:red"><?php _e('ERROR', 'registrationmagic-gold'); ?>: <?php echo htmlspecialchars($error); ?></h2>
		<?php elseif (empty($accounts)) : ?>
			<h2><?php printf(__('You do not have any accounts. Go to <a href="%s">https://stage.wepay.com</a> to open an account.',"registrationmagic-gold"),'https://stage.wepay.com.com'); ?></h2>
		<?php else: ?>
			<table border="1">
				<thead>
					<tr>
						<td><?php _e('Account ID', 'registrationmagic-gold'); ?></td>
						<td><?php _e('Account Name', 'registrationmagic-gold'); ?></td>
						<td><?php _e('Account Description', 'registrationmagic-gold'); ?></td>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($accounts as $a): ?>
					<tr>
						<td><?php echo htmlspecialchars($a->account_id); ?></td>
						<td><?php echo htmlspecialchars($a->name); ?></td>
						<td><?php echo htmlspecialchars($a->description); ?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		<?php endif; ?>
	
	</body>
	
</html>