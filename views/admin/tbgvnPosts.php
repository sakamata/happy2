<form class="form-inline" action="<?php echo $this->escape($base_url); ?>/admin/tbgvnPosts" method="post" accept-charset="utf-8">
	<input type="hidden" name="_token" value="<?php echo $this->escape($_token); ?>">
		<label>tbgvnPosts:</label>
	<div class="form-group" class="col-xs-1">
		<label>usNo:<input type="number" name="no1" class="form-control"  style="width: 100px;" value="1"></label>
	</div>
	<div class="form-group" class="col-xs-1">
		<label>seUs:<input type="number" name="no2" class="form-control"  style="width: 100px;" value="2"></label>
	</div>
	<div class="form-group" class="col-xs-1">
		<label>seClk:<input type="number" name="clk" class="form-control"  style="width: 100px;" value="3"></label>
	</div>
	<div class="form-group" class="col-xs-1">
		<label><button type="submit" name="PtDefault" class="btn btn-primary">  値を指定してtbgvnにPOST</button></label>
	</div>
</form>
