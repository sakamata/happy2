<?php $this->setLayoutVar('title', 'admin　index画面') ?>

<h2>admin index!</h2>

<?php
echo $this->render('admin/header_menu', array());
?>

<?php echo "全ユーザーのPt合計(tbus):　<b>" .$allUsersPtsSum. " Pt</b><br>"; ?>
<?php echo "Pt合計(tbset):　<b>" .$allUsersPtsSum_tbset. " Pt</b><br>"; ?>
<?php echo "ユーザー数(tbusCount):　<b>" .$tbCounts['tbus']. "</b>"; ?>

	<h3>
		<form action="<?php echo $this->escape($base_url); ?>/admin/calc" method="post" accept-charset="utf-8">
			<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
				calcAction: <input type="submit" name="calc" value="集計する">
		</form>
		<form action="<?php echo $this->escape($base_url); ?>/admin/PtDefault" method="post" accept-charset="utf-8">
			<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">

			<p style="text-align:right;">PtDefaultAction: <input type="submit" name="PtDefault" value="Ptを初期値に"><p>
		</form>

</h3>
<hr>

<?php
$tbNo = 0;
foreach ($tables as $table){
	$tableName = $this->escape($tableNames[$tbNo]);
	$cntNo = $tableNames[$tbNo];
	$tableCount = $this->escape($tbCounts[$cntNo]);

	echo "<h3 id='anchor_" .$tableName. "'>" .$tableName. "(" .$tableCount. ")";
	echo	'<form action="' .$base_url. '/admin/tableCommand" method="post" accept-charset="utf-8">　table操作	:';

	foreach ($commands as $cmd) {
?>
		<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
		<input type="hidden" name="tableName" value="<?php echo $tableName; ?>">
		<input type="submit" name="command" value="<?php echo $cmd; ?>">

<?php
	}
?>
		</form>
<?php
		if ($tableName == 'tbgvn') {
			echo $this->render('admin/tbgvnPosts', array('_token' => $_token));
		}
?>
	</h3>

	<div>
		<table class='table table-striped table-bordered table-hover table-condensed'>
			<thead>
				<tr>
<?php
	$field_no = 0;
	if ($table) {
		foreach ($table[0] as $noUseValue){
			$fieldNames = array_keys($table[0]);
			$fieldName = $fieldNames[$field_no];

			echo "<th>". $this->escape($fieldName) ."</th>";
			$field_no++;
		}
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

<?php if ($prevpages[$tableName] != -1 ): ?>
	<a href="?<?php echo 'table=' .$tableName. '&page=' .$prevpages[$tableName]. '&#anchor_' .$tableName; ?>" name="prev">&lt;&lt;前のページ</a>
<?php endif; ?>

<?php if ($offsets[$tableName] + $limit < $tableCount ): ?>
	<a href="?<?php echo 'table=' .$tableName. '&page=' .$nextpages[$tableName]. '&#anchor_' .$tableName; ?>" name="next">次のページ&gt;&gt;</a>
<?php endif; ?>

	<hr>

<?php
	$tbNo++;
}
