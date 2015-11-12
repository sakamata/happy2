<?php
echo "<tr>";
$field_no = 0;
foreach ($tableData as $noUseValue){
	$fieldNames = array_keys($tableData);
	$fieldName = $fieldNames[$field_no];
	echo "<td>". $this->escape($tableData[$fieldName]) ."</td>";
	$field_no++;
}
echo "</tr>";
