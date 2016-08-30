<?php $this->setLayoutVar('title', 'プロフィール編集') ?>

<h2>プロフィール編集</h2>

<form action="<?php echo $base_url; ?>/account/profileConfirm" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
					<span>16文字まで</span>

				</td>
			</tr>
			<tr>
				<th>画像</th>
				<td>
					<img src="<?php echo $base_url .'/../user/img/'. $user['usImg']; ?>" alt="user_photo" width="100" height="100">
				</td>
			</tr>
			<tr>
				<th>画像変更</th>
				<td>
					<input type='file' name='imageFile'>
					<input type="hidden" name="imageName" value="<?php echo $this->escape($user['usImg']); ?>">
					<sapn>5MBまで 画像形式: JPEG,GIF,PNG</sapn>
				</td>
			</tr>
		</tbody>
	</table>
	<input type="submit" value="変更">
</form>
