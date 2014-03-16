<div class="media form add_object">
	<?php echo $this->Form->create('Media'); ?>
	<fieldset>
		<legend>
			<?php echo __('Edit Publication'); ?>
		</legend>
		<?php
		
		
		echo "<div class='block'>".$this->Form->input('reference');
		echo $this->Form->input('linkref',array("label"=>'External Link', "type"=>'text'))."</div>";
		echo $this->Form->input('Media.date',array('type'=>'date', 'selected'=>'Media.date','dateFormat'=>'Y', 'minYear' => date('Y') - 50,'maxYear' => date('Y')));
		echo $this->Form->input('project_id',array('empty'=>'No Project'));
		echo'<div id="containerPlup">
				<a id="browse" class="btn btn-primary" href="#">Select
				files</a>
				<div id="filelist">';
		if($publication['Media']['name']){
			echo'<div class="file">
				<img src="'.$publication['Media']['link'].'" />
				'.$publication['Media']['name'].'
				<div class="actions">
				<a href="'.$publication['Media']['link'].'" class="delone"><img style="width:24px;height:24px;" src="/img/delete.png" /></a>
				</div></div>
				';
		}
		echo '</div></div>';
		?>
	</fieldset>
	<?php echo $this->Form->submit('Send', array('class' => 'btn btn-primary','id'=>'sendsubmitFormPub'));
		  echo $this->Form->end(); ?>
</div>
