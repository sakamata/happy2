<div class="form-group">
	<label class="col-sm-2 control-label">ユーザーID</label>
	<div class="col-sm-4">
		<input type="text" name="usId" class="form-control" id="InputText" placeholder="半角英数字20文字まで" value="<?php echo $this->escape($usId); ?>">
	</div>
</div>
<div class="form-group">
	<label class="col-sm-2 control-label" for="InputPassword">パスワード</label>
	<div class="col-sm-4">
		<input type="password" name="usPs" class="form-control"  id="InputPassword" placeholder="半角英数字" value="<?php echo $this->escape($usPs); ?>">
	</div>
</div>
