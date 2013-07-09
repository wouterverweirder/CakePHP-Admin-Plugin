<div class="users form">
<?php echo $this->ExtendedForm->create('User', array('class' => 'form-horizontal'));?>
	<fieldset>
		<legend><?php echo __('Edit User'); ?></legend>
	<?php
		echo $this->ExtendedForm->input('id', array('label' => __('Id')));
		echo $this->ExtendedForm->input('email', array('label' => __('Email')));
		echo $this->ExtendedForm->input('new_password', array('type' => 'password', 'label' => __('New Password')));
		echo $this->ExtendedForm->input('confirm_password', array('type' => 'password', 'label' => __('Confirm Password')));
	?>
	</fieldset>
<?php echo $this->ExtendedForm->end(array('label' => __('Save User'), 'class' => 'btn btn-primary', 'div' => false, 'before' => '<div class="control-group"><div class="controls">', 'after' => "\n" . $this->Html->link(__('Cancel'), $cancelUrl, array('class' => 'btn')) . '</div></div>'));?>
</div>