<?php
$this->setLayoutVar('title', 'このユーザーの履歴');
echo $this->render('status/js/index_js_header', array(
	'myStatus' => $myStatus,
	'headerUser' => $headerUser,
	'statuses' => null,
	'href_base' => $href_base,
	'clickStatus' => $clickStatus,
	'user' => $user,
));

?>

<div class="container">
<div class="row">
<form class="indexFrom" action="/happy2/web/history/userHistory"  method="get">
	<input type='hidden' name='order' value='<?php echo $this->escape($order); ?>'>
	<input type='hidden' name='viewUser' value='<?php echo $this->escape($viewUser); ?>'>
	<div class="form-inline">
		<div class="form-group col-xs-5 col-sm-4 col-md-4 col-lg-4">
			<input type="text" class="form-control input-sm" disabled="disabled" id="InputText" placeholder="検索(未実装)">
		</div>
		<div class="form-group col-xs-2 col-sm-2 col-md-2 col-lg-2">
			<button type="submit" class="btn btn-warning btn-sm"  disabled="disabled">send</button>
		</div>
		<div class="form-group hidden-xs col-sm-3 col-md-3 col-lg-3 form_lavel_r">
			<lavel for="InputSelect">並び替え</lavel>
		</div>
		<div class="form-group col-xs-5 col-sm-3 col-md-3 col-lg-3">
			<select class="form-control input-sm" id="InputSelect" name="usersArray" onChange="this.form.submit()">
			<option value="receiveFromHistory" <?php echo $selected['receiveFromHistory']; ?>>もらった履歴</option>
			<option value="toSendHistory" <?php echo $selected['toSendHistory']; ?>>あげた履歴</option>
			</select>
		</div>
	</div>
</form>
</div><!-- row -->
</div><!-- container -->

<div id="dummyIndexForm"></div>

<div class="container">
	<div class="row">
		<div class="col-xs-5 col-sm-5 col-md-5 col-lg-5">
			<div id="pageTitle">
				<h2><?php echo $this->escape($headerUser['usName']); ?>さん　<?php echo $usersArrayMessage; ?></h2>
			</div><!-- pageTitle -->
		</div><!--  -->
		<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7">
			<div id="calcStatusArea">
				<p>集計<b><?php echo $calcCount; ?></b>回　<span id="wsStatus"></span></p>
			</div>
		</div>
	</div><!-- row -->
</div><!-- container -->

<div id="main_user">
<?php
if ($headerUser['usNo'] != $myStatus['usNo'] ) :
	echo $this->render('status/users', array(
		'myStatus' => $myStatus,
		'user' => $user,
		'status' => $headerUser,
		'follow_token'=> $follow_token,
		'click_token'=> $click_token,
		'thisTimeAllClkSum' => $headerUser['thisTimeAllClkSum'],
	));
else:
	echo $this->render('status/main_user', array(
		'myStatus' => $myStatus,
		'user' => $user,
		'headerUser' => $headerUser,
		'follow_token'=> $follow_token,
		'click_token'=> $click_token,
		'thisTimeAllClkSum' => $headerUser['thisTimeAllClkSum'],
	));
endif;
?>
</div>

<div class="container">
	<div class="row">
		<div id="orderInfoArea">
			<div class="col-xs-8" class="col-sm-8" class="col-md-8" class="col-lg-8">
				<div class="pagerArea">
<?php
echo $this->render('status/pager', array(
	'page' => $page,
	'limit' => $limit,
	'tableCount' => $tableCount,
	'order' => $order,
	'viewUser' => $viewUser,
	'usersArray' => $usersArray,
	'searchWord' => $searchWord,
	'action' => $_SERVER['REQUEST_URI'],
	'method' => 'get',
	'name_add' => null,
));
?>
				</div><!-- pager -->
			</div>
<?php
if ($order !== null) :
	echo $this->render('status/order_changer', array(
		'page' => $page,
		'order' => $order,
		'viewUser' => $viewUser,
		'limit' => $limit,
		'usersArray' => $usersArray,
		'searchWord' => $searchWord,
		'action' => $_SERVER['REQUEST_URI'],
		'method' => 'get',
	));
endif;
?>
		</div><!-- orderInfoArea -->
	</div><!-- row -->
</div><!-- container -->

<div class="container">
	<div class="row">
<?php
if(!$result){
	echo $this->render('status/users_null', array('usersNullMessage' => $usersNullMessage));
} else {
?>

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

	echo "<td class='break-all'>
	<a href='/happy2/web/history/userHistory?viewUser=".$tableData['fromNo']."'>
	<img class='history_img' src=".$href_base.'/user/img/'.$tableData['fromImg']. " alt='user_photo'><div class='history_id'>".$tableData['fromId'].'<br><b>'. $tableData['fromName']."</b></div>
	</a><div class='clearBoth'> </div></td>\n";

	echo "<td class='break-all'>
	<a href='/happy2/web/history/userHistory?viewUser=".$tableData['toUserNo']."'>
	<img class='history_img' src=".$href_base.'/user/img/'.$tableData['toUserImg']. " alt='user_photo'><div class='history_id'>".$tableData['toUserId'].'<br><b>'. $tableData['toUserName']."</b></div></a><div class='clearBoth'> </div></td>\n";

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
<?php
}
?>

	</div><!-- row -->
</div><!-- container -->
<div class="container">
	<div class="row">
		<div class="footer_wrapper col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<div class="pagerArea_footer">
<?php

if ($page * $limit < $tableCount ) :
	echo $this->render('status/pager', array(
		'page' => $page,
		'limit' => $limit,
		'tableCount' => $tableCount,
		'order' => $order,
		'viewUser' => $viewUser,
		'usersArray' => $usersArray,
		'searchWord' => $searchWord,
		'action' => $_SERVER['REQUEST_URI'],
		'method' => 'get',
		'name_add' => '_footer',
	));
endif;

?>
			</div><!-- pager -->
			<p class="lead text-center">
				<a href="http://happy-project.org" target="_blank">Happy-Project.org</a>
			</p>
		</div>
	</div><!-- row -->
</div><!-- container -->

<?php
	echo $this->render('status/js/index_js', array(
		'hostName'=> $hostName,
		'wsProtocol'=> $wsProtocol,
		'wsPort'=> $wsPort,
		'status' => $headerUser,
		'follow_token'=> $follow_token,
		'click_token'=> $click_token,
		'postSecond'=> $postSecond,
		'clickStatus'=> $clickStatus,
		'headerUser' => $headerUser,
		'user' => $myStatus,
	));
