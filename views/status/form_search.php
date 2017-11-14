<form class="dis_inline" action="<?php echo $req_base; ?>"  method="post">
	<input type='hidden' name='order' value='<?php echo $this->escape($order); ?>'>

	<input type="text" name='searchWord' id="Search_Form" class="input_form_style" placeholder="No,ID,名前" <?php if ($selected['searchWord'] =='selected') { echo "value='" .$searchWord. "'" ;}; ?>>
	<button type="submit" id="Search_Button" class="btn btn-sm btn-warning">検索</button>
</form>
