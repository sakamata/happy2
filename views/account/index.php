<script>
<?php if($session->isAuthenticated()): ?>
	location.replace('http://' + '<?php echo $permitDomain; ?>' + '/happy2/web/');
<?php endif; ?>
</script>
