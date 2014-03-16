<?php 
echo $this->Html->script('wysiwyg/ckeditor/ckeditor');
echo $this->Html->script('wysiwyg/ckeditor/adapters/jquery');
?>
<div class="projects form add_object">
	<div id="msg"></div>
	<?php echo $this->Form->create('Project'); ?>
	<fieldset>
		<div>
			<div class="blockColl">
				<h4>General information :</h4>
				<?php 
// 				$checkM= "";$checkO ="";
				foreach($project['User'] as $sizeUser) {
					if(sizeof($project['User'])== 1 && $sizeUser['id'] == $this->Session->read('Auth.User.id')){
						$checkM ='checked="checked"';
						$checkO ='';
					}else{
						$checkM ='';
						$checkO ='checked="checked"';
					}
				}
				echo $this->Form->input('name');
				echo $this->Form->input('stardate', array('label'=>'Start Date','class'=>'datepicker','type' => 'text','name' => 'data[Project][stardate]'));
				echo $this->Form->input('enddate',array('label'=>'End Date','class'=>'datepicker','type' => 'text','name'=>'data[Project][enddate]')).'</div>';

				echo '<hr><div class="block"><h4>Members :</h4>';
				//Project Manager
				if($this->Session->read('Auth.User.admin') === true){
// 					debug($project);
					echo '<div style="margin-right:6%;margin-left:5%;"><strong>Project Manager</strong><br /><br />
						<div class="input select required">
						<select id="ProjectManager" name="data[Project][manager]">
							<option value="">Online Member</option>';
							foreach ($project['User'] as $user){
								$checked=($user['id'] == $project['Project']['manager'])?"selected='selected'":"";
								if($this->Session->read('Auth.User.id') === $user['id']) echo "";
								else echo '<option '.$checked.' value="'.$user['id'].'">'.$user['name'].' '.$user['surname'].'</option>';
							}
					echo '</select>
						</div></div>';
							
// 							$this->Form->input('Project.User',array('label'=> false,'type'=>'select','id'=>'ProjectManager','name'=>'data[Project][manager]','empty'=>'Online Administrator')).'</div>';
				}else{
					echo '<div style="margin-right:15%"><strong>Project Manager : </strong><br /><br />'.$this->Session->read('Auth.User.name').' '.$this->Session->read('Auth.User.surname').'</div>';
				}
				echo   '<div><strong>Group Members</strong><br /><br />
						<div><label for="ProjectUserM">Me</label>
						<input '.$checkM.' id="ProjectUserMEdit" type="radio" required="required" value="M" name="data[Project][TypeUser]"></div>
							<div><label for="ProjectUserO">With Members</label>
							<input '.$checkO.' id="ProjectUserOEdit" type="radio" required="required" value="O" name="data[Project][TypeUser]"></div>'.
							'<div style="width:25%;">'.$this->Form->input('Project.User',array('div'=>array('id'=>'listDisplayEdit'),'label'=>'Select Member(s)','type'=>'select','multiple' => true)).'</div>
							</div></div>';

				echo '<div style="clear:both"></div>
					<hr><div><h4>Additional information :</h4>'.
					$this->Form->input('external',array('class'=>'ProjectExternalEdit','div'=>array('style'=>'display:inline-block'))).'<div class="input text" id="linkExtEdit">'
					.$this->Form->input('link').'</div>'.$this->Form->input('description');

				?>
			</div>
		</div>
	</fieldset>
	<?php echo $this->Form->submit('Submit', array('class' => 'btn btn-primary','div'=>array('style'=>'margin-top:20px;')));
		  echo $this->Form->end(); ?>
</div>
