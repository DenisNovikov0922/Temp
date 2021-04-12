<?php
$path = preg_replace('/wp-content.*$/','',__DIR__);
include($path.'wp-load.php');
?>
<!DOCTYPE HTML>
<html>
    <head>
    	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    	<title><?php _e('Instagram Authentication','registrationmagic-gold'); ?></title>
    	<pre class="rm-pre-wrapper-for-script-tags"><script type="text/javascript">
    		//using php to look for the error parameter in the URL
    		if(<?php echo intval(isset($_GET['error'])); ?>) {
				self.close();
			}
    	</script></pre>
    </head>
    <body>
    </body>
</html>