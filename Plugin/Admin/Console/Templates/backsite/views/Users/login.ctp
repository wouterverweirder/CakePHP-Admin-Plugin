<div class="users form">
<?php echo "<?php echo \$this->Session->flash('auth'); ?>\n"; ?>
<?php echo "<?php echo \$this->Form->create('User');?>\n"; ?>
    <fieldset>
        <legend><?php echo "<?php echo __d('{$backendPluginNameUnderscored}', 'Please enter your email and password'); ?>";?></legend>
    <?php echo "<?php\n"; ?>
        <?php echo "echo \$this->Form->input('email');\n"; ?>
        <?php echo "echo \$this->Form->input('password');\n"; ?>
        <?php echo "?>\n"; ?>
    </fieldset>
<?php echo "<?php echo \$this->Form->end(array('label' => __d('{$backendPluginNameUnderscored}', 'Login'), 'class' => 'btn btn-primary'));?>\n"; ?>
</div>