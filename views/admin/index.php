<?php $this->setLayoutVar('title', 'admin　index画面') ?>

<h2>admin index!</h2>

<ul>
	<li>
		<a href="<?php echo $base_url ?>">ホーム</a>
	</li>
	<li>
		<a href="<?php echo $base_url; ?>/admin/signout">管理画面ログアウト</a>
	</li>
</ul>

<h3>tbus ( <?php echo $this->escape($tbCounts['tbus']); ?> )</h3>
<div id="ad_tbus">
	<table class='table table-striped table-bordered table-hover table-condensed'>
		<thead>
			<tr>
				<th>usNo</th>
				<th>usId</th>
				<th>usName</th>
				<th>usImg</th>
				<th>nowPt</th>
				<th>ip</th>
				<th>host</th>
				<th>regDate</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($ad_tbuses as $ad_tbus): ?>
			<?php echo $this->render('admin/ad_tbuses', array('ad_tbus' => $ad_tbus)); ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>



<!-- ***ToDo*** table名はtableArrayで表示させる -->
<h3>tbgvn (	<?php echo $this->escape($tbCounts['tbgvn']); ?> )</h3>


<?php
// ***ToDo tableArrayをControllerから送りkeyに使う***
foreach ($tables['tbgvn'] as $table){
echo <<<EOT
<div id="ad_tbgvn">
	<table class='table table-striped table-bordered table-hover table-condensed'>
		<thead>
			<tr>
EOT;

	$field_no = 0;

	foreach ($table as $noUseValue){
		$fieldNames = array_keys($table);
		$fieldName = $fieldNames[$field_no];

		echo "<th>". $this->escape($fieldName) ."</th>";
		$field_no++;
	}

?>

				<!-- <th>gvnNo</th>
				<th>usNo</th>
				<th>seUs</th>
				<th>seClk</th>
				<th>dTm</th> -->
			</tr>
		</thead>
		<tbody>
			<?php foreach ($tables['tbgvn'] as $table): ?>
			<?php echo $this->render('admin/table', array('table' => $table)); ?>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php
}
?>
