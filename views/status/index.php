<?php $this->setLayoutVar('title', 'ホーム') ?>

<h2>ホーム</h2>

<div id="wsStatus"></div>
<input type="text" id="mes" placeholder="WebSocket Test">
<input type="button" id="wsButton" value="send">
<div id="res"></div>

<hr>

<form class="form-inline">
	<div class="form-group">
		<input type="text" class="form-control input-lg" id="InputText">
	</div>

		<button type="submit" class="btn btn-warning btn-lg">send</button>

	<div class="form-group">
		<lavel for="InputSelect">並び替え</lavel>

		<select class="form-control input-lg" id="InputSelect">
			<option>選択肢1</option>
			<option>選択肢2</option>
			<option>選択肢3</option>
		</select>
	</div>
</form>


<form action="<?php echo $base_url; ?>/status/post" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
</form>

<div id="statuses">
	<?php foreach ($statuses as $status): ?>
	<?php echo $this->render('status/status', array('status' => $status)); ?>
	<?php endforeach; ?>
</div>
