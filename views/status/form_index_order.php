<form class="dis_inline" action="<?php echo $req_base; ?>"  method="post">
	<lavel  id="Lavel_Order" for="InputSelect">並び替え</lavel>
	<select id="Order_Form" class="input_form_style" name="usersArray" onChange="this.form.submit()">
		<option value="newUsers" <?php echo $selected['newUsers']; ?>>登録順</option>
		<option value="following" <?php echo $selected['following']; ?>>フォロー中</option>
		<option value="followers" <?php echo $selected['followers']; ?>>フォローされている</option>
<?php if ($selected['searchWord'] !== null) : ?>
		<option value="searchWord" <?php echo $selected['searchWord']; ?>>検索結果</option>
<?php endif; ?>
		<!-- <option value="test" <?php echo $selected['test']; ?>>テスト</option> -->
	</select>
</form>
