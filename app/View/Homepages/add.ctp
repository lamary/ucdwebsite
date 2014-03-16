<?php 
echo $this->Html->script('wysiwyg/ckeditor/ckeditor');
echo $this->Html->script('wysiwyg/ckeditor/adapters/jquery');
?>
<div class="homepages form add_object" id="addHomepage">
<?php echo $this->Form->create('Homepage'); ?>
	<fieldset>
		<legend>New Widget</legend>
			<?php
			//echo $this->Form->input('column',array('type'=>'select','options' => array('First Column', 'Second Column', 'Third Column')));
			echo $this->Form->input('content');
			?>
	</fieldset>
	<?php echo $this->Form->submit('Send', array('class' => 'btn btn-primary','div'=>array('style'=>'margin-top:20px;')));
		  echo $this->Form->end(); ?>

</div>
