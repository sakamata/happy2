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
$tbNo = 0;
foreach ($tables as $table){
	$tableName = $this->escape($tableNames[$tbNo]);
	$cntNo = $tableNames[$tbNo];
	$tableCount = $this->escape($tbCounts[$cntNo]);

	echo "<h3>" .$tableName. "(" .$tableCount. ")";
	echo	'<form action="' .$base_url. '/admin/tableCommand" method="post" accept-charset="utf-8">　table操作	:';

	foreach ($command as $cmd) {
?>
		<!-- <input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>"> -->
		<input type="hidden" name="<?php echo $cmd; ?>" value="<?php echo $cmd; ?>">
		<input type="submit" name="<?php echo $tableName; ?>" value="<?php echo $cmd; ?>">

<?php
	}
?>
		</form>
	</h3>

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
	<hr>

<?php
	$tbNo++;
}
