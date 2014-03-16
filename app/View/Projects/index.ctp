<?php 
echo $this->Html->script('wysiwyg/ckeditor/ckeditor');
echo $this->Html->script('wysiwyg/ckeditor/adapters/jquery');
?>
<div class="projects index">
	<h2>
		<?php echo $this->Html->image('project.png', array('alt' => 'Projects','style'=>'padding-bottom:11px;')) ." ".__('Projects'); ?>
	</h2>
	<!-- If not connected, can't add -->
	<?php 
	/********************************************* if logged ********************************************/
	//All person who is logged can add project
	if($this->Session->read('Auth.User.id')){ ?>
	<div id="div_add_object_btn">
		<button class="btn">
			<i class="icon-plus"></i> Add Project
		</button>
	</div>
	<br />
	<div class="projects form" id="addProject" style="display: none;">
		<?php echo $this->Form->create('Project',array('id'=>'ProjectAddForm','action'=>'/add')); ?>
		<fieldset>
			<legend>
				<?php echo __('Add Project'); ?>
			</legend>
			<div style="margin-left: 10%; margin-right: 10%; text-align: center;">
				<div class="blockColl">
					<h4>General information :</h4>
					<?php 	
					echo $this->Form->input('name');
					echo $this->Form->input('stardate', array('label'=>'Start Date','class'=>'datepicker','type' => 'text','name' => 'data[Project][stardate]'));
					echo $this->Form->input('enddate',array('label'=>'End Date','class'=>'datepicker','type' => 'text','name'=>'data[Project][enddate]')).'</div>';
					echo '<hr><div class="block"><h4>Members :</h4>';
					//Project Manager
					if($this->Session->read('Auth.User.admin') === true){
						echo '<div style="margin-right:6%;margin-left:10%;"><strong>Project Manager</strong><br /><br />'.
								$this->Form->input('Project.User',array('label'=> false,'type'=>'select','id'=>'ProjectManager','name'=>'data[Project][manager]','empty'=>'Online Member')).'</div>';
					}else{
						echo '<div style="margin-right:15%"><strong>Project Manager : </strong><br /><br />'.$this->Session->read('Auth.User.name').' '.$this->Session->read('Auth.User.surname').'</div>';
					}
						
					echo '<div><strong>Group Members</strong><br /><br />
							<div><label for="ProjectUserM">Online Member</label>
							<input checked="checked" id="ProjectUserM" type="radio" required="required" value="M" name="data[Project][TypeUser]"></div>';
					
					if($users){//display if there are other members
						echo '	<div><label for="ProjectUserO">With Other Members</label>
								<input id="ProjectUserO" type="radio" required="required" value="O" name="data[Project][TypeUser]"></div>'.
								'<div style="width:25%;">'.$this->Form->input('Project.User',array('div'=>array('id'=>'listDisplay'),'label'=>'Select Member(s)','type'=>'select','multiple' => true)).'</div>';
					}
					
					echo '</div></div><div style="clear:both"></div>
										<hr><div><h4>Additional information :</h4>'.
					$this->Form->input('external',array('div'=>array('style'=>'display:inline-block'))).'<div style="display:inline-block" class="input text" id="linkExt" ></div>'.
					$this->Form->input('description');
					?>
				</div>
			</div>
		</fieldset>
		<?php echo $this->Form->submit('Send', array('class' => 'btn btn-primary','div'=>array('style'=>'margin-top:20px;')));
		  echo $this->Form->end(); ?>
	</div>
	<?php } 
	/********************************************************* endif logged *******************************************************/
	if($projects != null){
	?>

	<table id="tab_group" class="table table-striped table-hover table-bordered">
		<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th>Members</th>
			<th>Description</th>
			<th><?php echo $this->Paginator->sort('stardate','Start Date'); ?>
			</th>
			<th><?php echo $this->Paginator->sort('enddate','End Date'); ?>
			</th>
			<th>External</th>
			<th class="actions"><?php echo __('Actions'); ?>
			</th>
		</tr>
		<?php 
		foreach($projects as $project):
		$classExternal = ($project['Project']['external'] == 1)? '<a target="_blank" class="alignImg" href="'.$project['Project']['link'].'">'.$this->Html->image("entry.png", array("alt" => "Entry")).'</a>':''.$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg')).'';
		$enddate = ($project['Project']['enddate'] == 1)? h($project['Project']['enddate']):''.$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg')).'';
		?>
		<tr>
			<td><?php echo h($project['Project']['name']); ?>&nbsp;</td>
			<td><ul>
					<?php 
					foreach($project['User'] as $user){
						$icon=($user['id'] == $project['Project']['manager'])? $this->Html->image("manager.png", array("alt" =>"project manager")):"";
				echo ' <li>'.$this->Html->link($user['nameSurname'], array('controller' => 'users', 'action' => 'view',$user['id'])).' '.$icon.'</li> ';
			}
			?>
				</ul>
			</td>
			<td class="truncate"><?php echo html_entity_decode(h($project['Project']['description'])); ?>&nbsp;</td>
			<td><?php echo h($project['Project']['stardate']); ?>&nbsp;</td>
			<td><?php echo $enddate; ?>&nbsp;</td>
			<td><?php echo $classExternal; ?>
			</td>
			<td class="actions"><?php 
			echo $this->Html->link(
					$this->Html->image("view.png", array("alt" => "View")),
					array('controller'=>'projects','action' => 'view','slug'=>$project['Project']['slug'], 'id'=>$project['Project']['id']),
					array('escape'=>false, 'class'=>'various1'));
			//if session user != manager or creator or not admin = can't edit or delete!
 			if(($this->Session->read('Auth.User.id') == $project['Project']['creator']) || ($this->Session->read('Auth.User.id') == $project['Project']['manager']) || ($this->Session->read('Auth.User.admin') === true)){
				echo $this->Html->link(
						$this->Html->image("edit.png", array("alt" => "Edit")),
						array('controller' => 'projects', 'action' => 'edit',$project['Project']['id']),
						array('escape'=>false, 'class'=>'variousframe fancybox.iframe editProject'))."
				" .$this->Form->postLink(
				$this->Html->image("delete.png", array("alt" => "Delete")), array('action' => 'delete',$project['Project']['id']),array('escape'=>false),
				__('Are you sure you want to delete # %s?\n Publications attached will be without project', $project['Project']['name']));
			}
?></td>
		</tr>
		<?php endforeach; ?>
	</table>
	<p>
		<?php
		echo $this->Paginator->counter(array(
		'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total')
	));
	?>
	</p>
	<div class="paging">
		<?php
		echo $this->Paginator->prev('< ' . __('previous '), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' | '));
		echo $this->Paginator->next(__(' next') . ' >', array(), null, array('class' => 'next disabled'));
		?>
	</div>
</div>
<?php } else {
echo '<table id="tab_group" class="table table-striped table-hover table-bordered"></table>';
}?>