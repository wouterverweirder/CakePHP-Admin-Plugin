<div class="users form">
<?php echo "<?php echo \$this->Session->flash('auth'); ?>\n"; ?>
<?php echo "<?php echo \$this->Form->create('User');?>\n"; ?>
    <fieldset>
        <legend><?php echo "<?php echo __('Please enter your email and password'); ?>";?></legend>
    <?php echo "<?php\n"; ?>
        <?php echo "echo \$this->Form->input('email');\n"; ?>
        <?php echo "echo \$this->Form->input('password');\n"; ?>
        <?php echo "?>\n"; ?>
    </fieldset>
<?php echo "<?php echo \$this->Form->end(__('Login'));?>\n"; ?>
</div>