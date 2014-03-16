<div class="media view add_object">
<h2><?php  echo __('Media'); ?></h2>
	<dl>
		<dt><?php echo __('Projects'); ?></dt>
		<dd>
			<?php echo $this->Html->link($media['Project']['name'], array('controller' => 'projects', 'action' => 'view', $media['Project']['id'])); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd>
			<?php echo h($media['Media']['description']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Name Media'); ?></dt>
		<dd>
			<?php echo h($media['Media']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Slug'); ?></dt>
		<dd>
			<?php echo h($media['Media']['slug']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Media'), array('action' => 'edit', $media['Media']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Media'), array('action' => 'delete', $media['Media']['id']), null, __('Are you sure you want to delete # %s?', $media['Media']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Media'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Media'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Projects'), array('controller' => 'projects', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Projects'), array('controller' => 'projects', 'action' => 'add')); ?> </li>
	</ul>
</div>
