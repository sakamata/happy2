<?php
echo "<tr>";

$field_no = 0;
foreach ($table as $noUseValue){
	$fieldNames = array_keys($table);
	$fieldName = $fieldNames[$field_no];

	echo "<td>". $this->escape($table[$fieldName]) ."</td>";
	$field_no++;
}

echo "</tr>";
