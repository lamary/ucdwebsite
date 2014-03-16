<div class="collaborators view">
<h2><?php  echo __('Collaborator'); ?></h2>
	<dl>
		<dt><?php echo __('Id'); ?></dt>
		<dd>
			<?php echo h($collaborator['Collaborator']['id']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($collaborator['Collaborator']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Surname'); ?></dt>
		<dd>
			<?php echo h($collaborator['Collaborator']['surname']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Link'); ?></dt>
		<dd>
			<?php echo h($collaborator['Collaborator']['link']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Slug'); ?></dt>
		<dd>
			<?php echo h($collaborator['Collaborator']['slug']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Collaborator'), array('action' => 'edit', $collaborator['Collaborator']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Collaborator'), array('action' => 'delete', $collaborator['Collaborator']['id']), null, __('Are you sure you want to delete # %s?', $collaborator['Collaborator']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Collaborators'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Collaborator'), array('action' => 'add')); ?> </li>
	</ul>
</div>
