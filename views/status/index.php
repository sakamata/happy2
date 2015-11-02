<?php $this->setLayoutVar('title', 'ホーム') ?>

<h2>ホーム</h2>

<form action="<?php echo $base_url; ?>/status/post" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>

	<textarea name="body" rows="2" cols="60"><?php echo $this->escape($body); ?></textarea>

	<p>
		<input type="submit" value="発言">
	</p>
</form>

<div id="ws_test">


<script>
jQuery(function($) {
	var socket;
	// if ( $.browser.mozilla ){
	// 	socket = new MozWebSocket('ws://127.0.0.1:80/echo');
	// }else{
	// 	socket = new WebSocket('ws://127.0.0.1:80/echo');
	// }

		socket = new WebSocket('ws://127.0.0.1:80/echo');
		// socket = new WebSocket('ws://192.168.11.5/echo');


	socket.onopen = function(msg){
		$('#status').text('online');
	};
	socket.onmessage = function(msg){
		$('#res').text( $('#res').text() + msg.data );
		// $('#res').text( msg.data );
	};
	socket.onclose = function(msg){
		$('#status').text('offline');
	};
	$('#button').click(function(){
		socket.send($('#mes').val());
	});
});

</script>

<div id="status"></div>
<input type="text" id="mes">
<input type="button" id="button" value="send">
<div id="res"></div>



</div>

<div id="statuses">
	<?php foreach ($statuses as $status): ?>
	<?php echo $this->render('status/status', array('status' => $status)); ?>
	<?php endforeach; ?>
</div>