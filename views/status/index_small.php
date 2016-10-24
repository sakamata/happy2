<?php
$this->setLayoutVar('title', 'ホーム');
echo $this->render('status/js/index_js_header', array(
	'headerUser' => $headerUser,
	'myStatus' => $myStatus,
	'statuses' => $statuses,
	'href_base' => $href_base,
	'clickStatus' => $clickStatus,
	'user' => $user,
));
?>

<div class="container">
<div class="row">
<form class="indexFrom" action="<?php echo $req_base; ?>"  method="post">
	<input type='hidden' name='order' value='<?php echo $this->escape($order); ?>'>
	<div class="form-group">
		<div class="form-inline">
			<div class="col-xs-5 col-sm-4 col-md-4 col-lg-4">
				<input type="text" class="form-control input-sm" disabled="disabled" id="InputText" placeholder="検索(未実装)">
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<button type="submit" class="btn btn-warning btn-sm"  disabled="disabled">send</button>
			</div>
			<div class="hidden-xs col-sm-2 col-md-2 col-lg-2">
				<lavel for="InputSelect">並び替え</lavel>
			</div>
			<div class="col-xs-5 col-sm-4 col-md-4 col-lg-4">
				<select class="form-control input-sm" id="InputSelect" name="usersArray" onChange="this.form.submit()">
				<option value="newUsers" <?php echo $selected['newUsers']; ?>>登録順</option>
				<option value="following" <?php echo $selected['following']; ?>>フォロー中</option>
				<option value="followers" <?php echo $selected['followers']; ?>>フォローされている</option>
				<!-- <option value="test" <?php echo $selected['test']; ?>>テスト</option> -->
				</select>
			</div>
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
				<h2>ホーム</h2>
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
	<?php echo $this->render('status/main_user_small', array('headerUser' => $headerUser, 'user' => $user,)); ?>
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
		'action' => $req_base,
		'method' => 'post',
		'footer' => null,
	));
?>
				</div><!-- pager -->
			</div>
<?php
	if ($order !== null) :
		echo $this->render('status/order_changer', array('order' => $order, 'usersArray' => $usersArray, 'action' => $req_base, 'method' => 'post'));
	endif;
?>
	<form action="<?php echo $href_base; ?>/status/post" method="post" accept-charset="utf-8">
		<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
	</form>
<?php
	if (count($statuses) !== 0) :
		echo '<p>'.$usersArrayMessage.'<b>'. count($statuses) . '</b>名を表示しています。</p>';
	endif;
?>
		</div><!-- orderInfoArea -->
	</div><!-- row -->
</div><!-- container-fluid -->

<div id="statuses">
	<div class="container">
		<div class="row">
			<ul class="viewUsers_small">
<?php
	if(!$statuses){
		echo $this->render('status/users_null', array('usersNullMessage' => $usersNullMessage));
	} else {
		foreach ($statuses as $status):
			echo $this->render('status/users_small', array(
				'status' => $status,
				'follow_token'=> $follow_token,
				'click_token'=> $click_token,
				'thisTimeAllClkSum' => $headerUser['thisTimeAllClkSum'],
				'user' => $user,
			));
		endforeach;
	}
?>
			<div class="clearBoth">	</div>
			</ul><!-- viewUsers -->
		</div><!-- row -->
	</div><!-- container -->
</div><!-- statuses -->

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
		'action' => $req_base,
		'method' => 'post',
		'footer' => '_footer',
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
		'status' => $status,
		'follow_token'=> $follow_token,
		'click_token'=> $click_token,
		'postSecond'=> $postSecond,
		'clickStatus'=> $clickStatus,
		'headerUser' => $headerUser,
		'user' => $user,
	));
