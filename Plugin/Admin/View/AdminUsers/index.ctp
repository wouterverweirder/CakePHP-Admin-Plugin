<h2><?php echo __('Users');?></h2>


        <div class="users index table">
            <?php echo $this->element('../AdminUsers/table');?>
        </div>

        <div class="actions">

            <h3><?php echo __('Actions'); ?></h3>
            <?php echo $this->Html->link(__('New User'), array('action' => 'add'), array('class' => 'btn btn-primary')); ?>        </div>