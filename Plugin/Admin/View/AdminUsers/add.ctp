<div class="users form">
<?php echo $this->ExtendedForm->create('User');?>
	<fieldset>
		<legend><?php echo __('Add User'); ?></legend>
	<?php
		echo $this->ExtendedForm->input('email', array('label' => __('Email')));
		echo $this->ExtendedForm->input('password', array('type' => 'password', 'label' => __('Password')));
		echo $this->ExtendedForm->input('confirm_password', array('type' => 'password', 'label' => __('Confirm Password')));
	?>
	</fieldset>
<?php echo $this->ExtendedForm->end(__('Save'));?>
</div>