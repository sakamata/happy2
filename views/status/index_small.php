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
<div class="row indexFrom">

<?php
echo $this->render('status/form_search', array(
	'req_base' => $req_base,
	'order' => $order,
	'selected' => $selected,
	'searchWord' => $searchWord,
));
echo $this->render('status/form_index_order', array(
	'req_base' => $req_base,
	'selected' => $selected,
));
?>

</div>
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
<?php
	echo $this->render('status/main_user_small', array(
		'headerUser' => $headerUser,
		'user' => $user,
	));
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
		'action' => $req_base,
		'method' => 'post',
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
			'usersArray' => $usersArray,
			'searchWord' => $searchWord,
			'action' => $req_base,
			'method' => 'post',
		));
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
		'searchWord' => $searchWord,
		'action' => $req_base,
		'method' => 'post',
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
		'status' => $status,
		'follow_token'=> $follow_token,
		'click_token'=> $click_token,
		'postSecond'=> $postSecond,
		'clickStatus'=> $clickStatus,
		'headerUser' => $headerUser,
		'user' => $user,
	));
