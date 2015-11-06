<?php $this->setLayoutVar('title', 'アカウント') ?>

<h2>アカウント</h2>

<p>
	ユーザID:<a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($user['usName']); ?>">
	<strong><?php echo $this->escape($user['usName']); ?></strong>
	</a>
</p>

<ul>
	<li>
		<a href="<?php echo $base_url ?>">ホーム</a>
	</li>
	<li>
		<a href="<?php echo $base_url; ?>/account/signout">ログアウト</a>
	</li>
</ul>
<h3>フォロー中</h3>

<?php if (count($followings) > 0): ?>
<ul>
	<?php foreach ($followings as $following): ?>
	<li>
		<a href="<?php echo $base_url; ?>/user/<?php echo $this->escape($following['usName']); ?>">
		<?php echo $this->escape($following['usName']); ?>
		</a>
	</li>
	<?php endforeach; ?>
</ul>
<?php endif; ?>
