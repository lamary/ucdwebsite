<div class="collaborators index">
	<h2>	<?php echo $this->Html->image('shakeHand.gif', array('alt' => 'Collaborators','style'=>'padding-bottom:11px;')) ." ".__('Collaborators'); ?>
	</h2>
<?php if($this->Session->read('Auth.User.id')){ ?>
	<div class="div_add_object_btn">
		<a class="various fancybox.ajax" href="collaborators/add"><button class="btn" />
			<i class="icon-plus"></i> Add Collaborator
			</button> </a>
	</div>
	<?php }
	if($collaborators != null){
	?>
		<table id="tab_group" class="table table-striped table-hover table-bordered">
	<tr>
			<th><?php echo $this->Paginator->sort('organization'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('surname'); ?></th>
			<th><?php echo $this->Paginator->sort('link'); ?></th>
			<?php if($this->Session->read('Auth.User.id')){ ?>
				<th class="actions"><?php echo __('Actions'); ?></th>
				<?php } ?>
	</tr>
	<?php foreach ($collaborators as $collaborator): 
	$orga = ($collaborator['Collaborator']['organization'] == "")? ''.$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg')).'': ''.h($collaborator['Collaborator']['organization']).'';
	$link = ($collaborator['Collaborator']['link'] != "")? '<a target="_blank" class="alignImg" href="'.$collaborator['Collaborator']['link'].'">'.$this->Html->image("entry.png", array("alt" => "Entry")).'</a>':''.$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg')).'';
	
	?>
	<tr>
		<td><?php echo $orga; ?>&nbsp;</td>
		<td><?php echo h($collaborator['Collaborator']['name']); ?>&nbsp;</td>
		<td><?php echo h($collaborator['Collaborator']['surname']); ?>&nbsp;</td>
		<td><?php echo $link; ?>&nbsp;</td>
		<?php if($this->Session->read('Auth.User.id')){ ?>
		<td class="actions"><?php 
			echo $this->Html->link(
					$this->Html->image("edit.png", array("alt" => "Edit")),
					array('action' => 'edit',$collaborator['Collaborator']['id']),
					array('escape'=>false, 'class'=>'various fancybox.ajax'));
			echo $this->Form->postLink(
		$this->Html->image("delete.png", array("alt" => "Delete")), array('action' => 'delete',$collaborator['Collaborator']['id']),array('escape'=>false),
		__('Are you sure you want to delete # %s?', $collaborator['Collaborator']['name']));

		?></td><?php } ?>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total')
	));
	?>	</p>
	<div class="paging">
		<?php
		echo $this->Paginator->prev('< ' . __('previous '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' '));
		echo $this->Paginator->next(__(' next') . ' >', array(), null, array('class' => 'next disabled'));
		?>
	</div>
</div>
<?php }
else {
echo '<table id="tab_group" class="table table-striped table-hover table-bordered"></table>';
}?>