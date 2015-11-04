<?php $this->setLayoutVar('title', 'アカウント登録') ?>

<h2>アカウント登録</h2>

<p>
	<a href="<?php echo $base_url; ?>/account/signin">ログイン</a>
</p>


<form action="<?php echo $base_url; ?>/account/register" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

	<?php if (isset($errors) && count($errors) > 0): ?>
	<?php echo $this->render('errors', array('errors' => $errors)); ?>
	<?php endif; ?>

	<table>
		<tbody>
			<tr>
				<th>名前</th>
				<td>
					<input type="text" name="usName" value="<?php echo $this->escape($usName); ?>">
				</td>
			</tr>
		</tbody>
	</table>


	<?php echo $this->render('account/inputs', array('usName' => $usName, 'usId' => $usId, 'usPs' => $usPs,)); ?>

	<p>
		<input type="submit" value="登録">
	</p>
</form>
