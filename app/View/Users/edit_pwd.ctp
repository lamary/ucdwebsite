<div class="users form add_object">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend style="margin-bottom:7%;">
			<?php echo $this->Html->image("chgPswd.png", array("alt" => "Change your Password","style"=>array('margin-right:10px'))).__('Change your Password'); ?>
		</legend>
		<?php
		echo $this->Form->input('pass1',array('label'=>'Password','type'=>'password'));
		echo $this->Form->input('pass2',array('type'=>'password','label'=>'Confirm Password'));
		?>
	</fieldset>
	<?php echo $this->Form->submit('Submit', array('class' => 'btn btn-primary'));
		  echo $this->Form->end(); ?>
</div>
