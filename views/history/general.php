<script>
// change not ssl protocol
if (document.location.protocol==="https:")
{location.replace('http://'+window.location.host+window.location.pathname);}
</script>

<?php $this->setLayoutVar('title', '全体履歴') ?>
<div class="container">
	<div class="row">
		<div id="pageTitle">
			<h2>全体履歴</h2>
		</div><!-- pageTitle -->
	</div><!-- row -->
</div><!-- container -->

<div class="container">
	<div class="row">
		<div id="orderInfoArea">
<?php
echo $this->render('status/pager', array('page' => $page, 'limit' => $limit, 'tableCount' => $tableCount, 'order' => $order, 'usersArray' => $usersArray, 'action' => $_SERVER['REQUEST_URI'], 'method' => 'get'));

if ($order !== null) :
	echo $this->render('status/order_changer', array('order' => $order, 'usersArray' => $usersArray, 'method' => 'get'));
endif;
?>
		</div><!-- orderInfoArea -->
	</div><!-- row -->
</div><!-- container -->

<div class="container">
	<div class="row">
		<div class="table_wrapper">
			<table class='history_table table-condensed table-bordered table-striped table-hover'>
				<thead>
					<tr>
						<th class='hidden-xs hidden-sm'>No</th>
						<th>From Happy</th>
						<th>To Happy</th>
						<th class='hidden-xs hidden-sm'>Click</th>
						<th class='hidden-md hidden-lg'>Clk</th>
						<th>Point</th>
						<th>DateTime</th>
					</tr>
				</thead>
				<tbody>
<?php
foreach ($result as $tableData):
	if ($tableData['fromId'] === $tableData['toUserId']) {
		$myHappy ="class='table_myHappy'";
	} else {
		$myHappy ='';
	}

	if ($tableData['getPt'] == 'undecided') {
		$tableData['getPt'] = '未定';
	}

	$mdTime = strtotime($tableData['dTm']);
	$Y = date('Y', $mdTime);
	$mdHis = date('m-d H:i:s', $mdTime);

	echo "<tr ". $myHappy .">\n";
	echo "<td class='hidden-xs hidden-sm'><div class='right'>".$tableData['gvnNo']."</div></td>\n";

	echo "<td class='break-all'><img class='history_img' src=".$href_base.'/user/img/'.$tableData['fromImg']. " alt='user_photo'><div class='history_id'>".$tableData['fromId'].'<br><b>'. $tableData['fromName']."</b></div><div class='clearBoth'> </div></td>\n";

	echo "<td class='break-all'><img class='history_img' src=".$href_base.'/user/img/'.$tableData['toUserImg']. " alt='user_photo'><div class='history_id'>".$tableData['toUserId'].'<br><b>'. $tableData['toUserName']."</b></div><div class='clearBoth'> </div></td>\n";

	echo "<td><div class='right'>". $tableData['formClickCount'] ."</div></td>";
	echo "<td class='hidden-xs hidden-sm'><div class='right'>". $tableData['getPt'] ."</div></td>\n";

	echo "<td class='hidden-md hidden-lg'><div class='right'>". $tableData['roundPt'] ."</div></td>\n";

	echo "<td><div class='right table_date'><span class='hidden-xs hidden-sm'>".$Y."</span><wbr> ".$mdHis."</div></td>\n";
	echo "</tr>\n";
endforeach;
?>
				</tbody>
			</table>
		</div><!-- table_wrapper -->
	</div><!-- row -->
</div><!-- container -->

<div class="container">
	<div class="row">
<?php
if ($page * $limit < $tableCount ) :
	echo $this->render('status/pager_footer', array('page' => $page, 'limit' => $limit, 'tableCount' => $tableCount, 'order' => $order, 'usersArray' => $usersArray, 'action' => $_SERVER['REQUEST_URI'], 'method' => 'get'));
endif;
?>
	</div><!-- row -->
</div><!-- container -->
