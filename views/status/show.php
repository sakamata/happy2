<?php $this->setLayoutVar('title', $user['usName']) ?>

<?php echo $this->render('status/status', array('status' => $status)); ?>