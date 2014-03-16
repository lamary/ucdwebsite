<div class="typeUsers index">
	<h2>
		<?php echo __('Member\'s Types'); ?>
	</h2>
	<?php 
	echo $this->Form->create('TypeUser',array('id'=>'TypeUserAddForm','action'=>'/add')); 
		echo $this->Form->input('name',array('type'=>'text','label'=>false,'style'=>array('margin:0;'),'div'=>(array('style'=>array('margin:0 0 0 28px;display:inline-block;')))));?>
		<button id="addTypeUser" class="btn" style="display: inline-block;">
				<i class="icon-plus"></i> Add Member's Types
			</button> 
	 	<?php echo $this->Form->end(); ?>
	<ul id="sortable">
		<?php foreach ($typeUsers as $typeUser): ?>
		<li class="ui-state-default" value="<?php echo $typeUser['TypeUser']['order']; ?>" id="<?php echo $typeUser['TypeUser']['id']; ?>"><span
			class="ui-icon ui-icon-arrowthick-2-n-s"></span> 
				<input id="inputTypeUSer_<?php echo $typeUser['TypeUser']['id'];?>" type="text" style="background-color:transparent;border:none;margin-top:-6px;" 
					disabled="disabled" value="<?php echo h($typeUser['TypeUser']['name']);?>" />
			<?php echo $this->Form->postLink(
					$this->Html->image("delete.png", array("alt" => "Delete")), array('action' => 'delete',$typeUser['TypeUser']['id']),array('escape'=>false),
					__('Are you sure you want to delete # %s?', $typeUser['TypeUser']['name']));
				
			echo $this->Html->link(
					$this->Html->image("edit.png", array("alt" => "Edit")),
					array('controller' => 'typeusers', 'action' => 'edit',$typeUser['TypeUser']['id']),
					array('escape'=>false,'style'=>'margin-right:20px;','class'=>'editTypeUser'));
?></li>
		<?php endforeach; ?>
	</ul>
</div>
 	