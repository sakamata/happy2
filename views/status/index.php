<?php $this->setLayoutVar('title', 'ホーム') ?>

<h2>ホーム</h2>

<div id="ws_test">
	<div id="wsStatus"></div>
	<input type="text" id="mes">
	<input type="button" id="button" value="send">
	<div id="res"></div>
</div>



<form action="<?php echo $base_url; ?>/status/post" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
</form>

<div id="statuses">
	<?php foreach ($statuses as $status): ?>
	<?php echo $this->render('status/status', array('status' => $status)); ?>
	<?php endforeach; ?>
</div>
