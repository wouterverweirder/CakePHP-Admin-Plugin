<h2><?php  echo __('User') . ': ' . $user['User']['toString'];?></h2>
<div id="tabs">
    <ul>
        <li><a href="#tabs-1"><?php  echo __('User Details');?></a></li>
    </ul>

    <div id="tabs-1">
        <div class="users view">
            <dl>
            				<dt><?php echo __('Id'); ?></dt>
				<dd>
			<?php echo h($user['User']['id']); ?>
			&nbsp;
		</dd>
				<dt><?php echo __('Created'); ?></dt>
				<dd>
			<?php echo h($user['User']['created']); ?>
			&nbsp;
		</dd>
				<dt><?php echo __('Modified'); ?></dt>
				<dd>
			<?php echo h($user['User']['modified']); ?>
			&nbsp;
		</dd>
				<dt><?php echo __('Email'); ?></dt>
				<dd>
			<?php echo h($user['User']['email']); ?>
			&nbsp;
		</dd>
				<dt><?php echo __('Password'); ?></dt>
				<dd>
			<?php echo h($user['User']['password']); ?>
			&nbsp;
		</dd>
            	</dl>
        </div>
        <div class="actions">
            <h3><?php echo __('Actions'); ?></h3>
            <ul>
        				<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
				<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
				<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
            </ul>
        </div>
    </div>

</div>
<script type="text/javascript">
    $(function() {
        $( "#tabs" ).tabs();
    });
</script>