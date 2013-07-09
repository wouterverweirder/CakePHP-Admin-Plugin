<?php
$this->Paginator->options(array(
    'url' => $usersTableURL,
    'update' => '.users.table',
    'evalScripts' => true
));?>
<table class="table table-striped">
<tr>
	<th><?php echo $this->Paginator->sort('email', null, array('model' => 'User'));?></th>
	<th><?php echo $this->Paginator->sort('modified', null, array('model' => 'User'));?></th>
    <th class="actions"><?php echo __('Actions');?></th>
</tr>
<?php
foreach ($users as $user): ?>
	<tr>
		<td><?php echo h($user['User']['email']); ?>&nbsp;</td>
		<td><?php echo (empty($user['User']['modified']) || '0000-00-00 00:00:00' == $user['User']['modified'] || '1970-01-01 01:00:00' == $user['User']['modified']) ? '' : $this->Time->format('d/m/Y', $user['User']['modified']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('plugin' => 'admin', 'controller' => 'admin_users', 'action' => 'view', $user['User']['id']), array('class' => 'btn btn-info btn-mini')); ?>
			<?php echo $this->Html->link(__('Edit'), array('plugin' => 'admin', 'controller' => 'admin_users', 'action' => 'edit', $user['User']['id']), array('class' => 'btn btn-mini')); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('plugin' => 'admin', 'controller' => 'admin_users', 'action' => 'delete', $user['User']['id']), array('class' => 'btn btn-danger btn-mini'), __('Are you sure you want to delete # %s?', $user['User']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
</table>
<p>
<?php
echo $this->Paginator->counter(array(
'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}'),
'model' => 'User'
));
?></p>

<div class="pagination">
    <ul>
<?php
		echo '<li>' . $this->Paginator->prev('Prev', array('model' => 'User'), null, array('class' => 'prev disabled')) . '</li>';
		echo $this->Paginator->numbers(array('separator' => '', 'tag' => 'li', 'currentTag' => 'span', 'model' => 'User'));
		echo '<li>' . $this->Paginator->next('Next', array('model' => 'User'), null, array('class' => 'next disabled')) . '</li>';
	?>
    </ul>
</div>

<?php
      echo $this->Js->writeBuffer();