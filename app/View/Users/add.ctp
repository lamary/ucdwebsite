<div class="users form add_object">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend>New Member </legend>
		<div style="margin-left: 18px;">
			<?php
			echo '<div class="block ">'.$this->Form->input('type_users_id',array('label'=>'Users Type'));
			echo $this->Form->input('name');
			echo $this->Form->input('surname').'</div><div style="clear:both;"></div>';
			echo '<div class="block">'.$this->Form->input('mail',array('label'=>'Email'));
			echo $this->Form->input('pass1',array('label'=>'Password','type'=>'password'));
			echo $this->Form->input('pass2',array('type'=>'password','label'=>'Confirm password')).'</div></div><div style="clear:both;"></div>';
			echo '<div class="block" >'.$this->Form->input('admin',array('div'=>array('style'=>'padding-left:15%;padding-right:12%;'))).
				$this->Form->input('link',array('type'=>'text','label'=>'Link of your Personal Webpage'));
			echo $this->Form->input('description').'<div style="clear:both;"></div>';
			?>
		</div>
	</fieldset>
	<?php echo $this->Form->submit('Send', array('class' => 'btn btn-primary'));
		  echo $this->Form->end(); ?>
</div>
