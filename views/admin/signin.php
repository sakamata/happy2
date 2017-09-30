<?php
require_once '../basicAuth/functions.php';
$username = require_basic_auth();
header('Content-Type: text/html; charset=UTF-8');
?>

<script>
	// change ssl protocol
	if (document.location.protocol==="http:")
	{location.replace('https://'+window.location.host+window.location.pathname);}
</script>

<?php $this->setLayoutVar('title', 'adminログイン') ?>

<h2>admin_signin</h2>


<form action="<?php echo $base_url; ?>/admin/authenticate" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>

	<?php echo $this->render('account/inputs', array('usId' => $usId, 'usPs' => $usPs)); ?>

	<p>
		<input type="submit" value="ログイン">
	</p>
</form>
