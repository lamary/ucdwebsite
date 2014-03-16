<div class="collaborators form add_object">
<?php echo $this->Form->create('Collaborator'); ?>
	<fieldset>
		<legend><?php echo __('Edit Collaborator'); ?></legend>
	<?php
		echo '<div class="blockColl">'.$this->Form->input('name');
		echo $this->Form->input('surname').'</div>';
		echo '<div class="blockColl">'.$this->Form->input('organization');
		echo $this->Form->input('link').'</div>';
	?>
	</fieldset>
<?php echo $this->Form->submit('Send', array('class' => 'btn btn-primary'));
		  echo $this->Form->end(); ?>
</div>

