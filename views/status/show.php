<?php $this->setLayoutVar('title', $user['user_name']) ?>

<?php echo $this->render('status/status', array('status' => $status)); ?>