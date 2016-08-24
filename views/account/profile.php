<?php $this->setLayoutVar('title', 'プロフィール編集') ?>

<h2>プロフィール編集</h2>


<form action="<?php echo $base_url; ?>/account/register" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>

	<table>
		<tbody>
			<tr>
				<th>ID</th>
				<td>
					<?php echo $this->escape($user['usId']); ?>
				</td>
			</tr>
			<tr>
				<th>名前</th>
				<td>
					<input type="text" name="usName" value="<?php echo $this->escape($user['usName']); ?>">
				</td>
			</tr>
			<tr>
				<th>画像</th>
				<td>
					<img src="<?php echo $base_url .'/../user/img/'. $user['usImg']; ?>" alt="user_photo">
				</td>
			</tr>
			<tr>
				<th>画像変更</th>
				<td>
					<input type='file' name='usimg' >
					<input type="hidden" name="usimg" value="<?php echo $this->escape($user['usPs']); ?>">
					<p>5MBまでのJPEG,GIF,PNGのみです</p>
				</td>
			</tr>
		</tbody>
	</table>


	<p>
		<input type="submit" value="変更">
	</p>
</form>
