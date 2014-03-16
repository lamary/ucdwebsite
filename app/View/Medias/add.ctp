<div class="media form add_object">
	<?php 
	echo $this->Form->create('Media'); ?>
	<fieldset>
		<legend>
			<?php 
			echo __('Add Publication'); ?>
		</legend>
		<div id="msgAddMedia" style="margin: 5px 5px 25px;"></div>
		<?php
		echo "<div class='block'>".$this->Form->input('reference');
		echo $this->Form->input('linkref',array("label"=>'External Link', "type"=>'text'))."</div>";
		echo $this->Form->input('date',array('type'=>'date', 'dateFormat'=>'Y', 'minYear' => date('Y') - 50,'maxYear' => date('Y')));
		echo $this->Form->input('project_id',array('empty'=>'No Project'));
		//echo  $this->Form->input('name_media');
		?>
		<div id="containerPlup">
				<a id="browse" class="btn btn-primary" href="#">Select
					files</a>
				<div id="filelist"></div>
			</div>
	
	</fieldset>
	<?php 
	echo $this->Form->submit('Send', array('class' => 'btn btn-primary','id'=>'sendsubmitFormPub'));
		  echo $this->Form->end(); ?>
</div>

<!-- <div class="media form add_object"> -->
<?php //echo $this->Form->create('Media', array('type' => 'file')); ?>
<!-- 	<fieldset> -->
<!-- 		<legend> -->
<?php //echo __('Add Publication'); ?>
<!--  		</legend> 
		<div id="msgAddMedia" style="margin: 5px 5px 25px;"></div> -->
<?php
// 		echo "<div style='float:left;'>".$this->Form->input('description')."</div>";
// 		echo "<div style='float:right;margin-left:15px;' >".$this->Form->input('name', array('label'=>'Name (if empty, it will be name per default)'))."</div>";
// 		echo "<div>
// 				<label>Choose a file</label>
// 			".$this->Form->file('submittedfile',array('multiple'=>'multiple')).'</div><br />';

// 		echo $this->Form->input('project_id',array('empty'=>'No Project'));
?>
<!-- 	</fieldset> -->
<?php 
// 		echo $this->Form->submit('Send', array('class' => 'btn','id'=>'sendMedia'));
// 		  echo $this->Form->end(); ?>
<!-- </div> -->
