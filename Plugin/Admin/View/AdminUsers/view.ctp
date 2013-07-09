<h2><?php  echo __('User') . ': ' . $user['User']['toString'];?></h2>
<ul class="nav nav-tabs">
    <li class="active"><a href="#tabs-1" data-toggle="tab"><?php  echo __('User Details');?></a></li>
</ul>
<div class="tab-content">
    <div id="tabs-1" class="tab-pane active">
        <div class="users view container-fluid">
            <div class="row-fluid">
                <div class="span9">
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
                <div class="actions span2">
                    <div class="btn-group">
                        <a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
                            Actions
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                				<li><?php echo $this->Html->link(__('Edit User'), array('action' => 'edit', $user['User']['id'])); ?> </li>
				<li><?php echo $this->Form->postLink(__('Delete User'), array('action' => 'delete', $user['User']['id']), null, __('Are you sure you want to delete # %s?', $user['User']['id'])); ?> </li>
				<li><?php echo $this->Html->link(__('List Users'), array('action' => 'index')); ?> </li>
				<li><?php echo $this->Html->link(__('New User'), array('action' => 'add')); ?> </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>