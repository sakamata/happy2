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

<?php
$tableName = 0;
foreach ($tables as $table){

	echo "<h3>";
	echo $this->escape($tableNames[$tableName]) . "(";
	$tNo = $tableNames[$tableName];
	$tableName++;
	echo $this->escape($tbCounts[$tNo]);
	echo ")</h3>";
?>
	<div>
		<table class='table table-striped table-bordered table-hover table-condensed'>
			<thead>
				<tr>
<?php
	$field_no = 0;
	foreach ($table[0] as $noUseValue){
		$fieldNames = array_keys($table[0]);
		$fieldName = $fieldNames[$field_no];

		echo "<th>". $this->escape($fieldName) ."</th>";
		$field_no++;
	}
?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($table as $tableData): ?>
				<?php echo $this->render('admin/table', array('tableData' => $tableData)); ?>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>

<?php
}
