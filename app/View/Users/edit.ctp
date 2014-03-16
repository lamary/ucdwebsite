<div class="users form add_object">
	<?php echo $this->Form->create('User'); ?>
	<fieldset>
		<legend>
			<?php echo __('Edit Members'); ?>
		</legend>
			<?php
			if($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.admin') === true){
				if($this->Session->read('Auth.User.id') == $iduser){
					$admin= "";
				}else{
					$admin=$this->Form->input('admin',array('div'=>array('style'=>'padding-left:15%;padding-right:12%;display:inline-block')));
				}
				$usertype = $this->Form->input('type_users_id');

			}else {
				$admin= "";
				foreach($typeUsersnoadmin as $type){
					if($userinfo['User']['type_users_id'] == $type['TypeUser']['id']){
						$usertype = '<div><label>User Type</label><input type="text" readonly="readonly" value="'.$type['TypeUser']['name'].'"/></div>';
					}
				}
			}
			echo $admin;
			echo '<div class="block ">'.$usertype;
			echo $this->Form->input('name');
			echo $this->Form->input('surname').'</div>';
			echo '<div class="block">'.$this->Form->input('mail',array('label'=>'Email'));
			echo $this->Form->input('link',array('type'=>'text','label'=>'Link of your Personal Webpage'));
			echo $this->Form->input('description').'</div><div style="clear:both;"></div>';
			?>
	</fieldset>
<?php echo $this->Form->submit('Submit', array('class' => 'btn btn-primary'));
		  echo $this->Form->end(); ?>
</div>