<div class="users index">
	<h2>
		<?php echo $this->Html->image('useactiver.png', array('alt' => 'Member\'s activation','style'=>'padding-bottom:11px;')) ." ".__('Member\'s activation'); ?>
	</h2>
	<div id="bodytabUser">
		<div id="tabUser">
			<?php if($typeUsers == null){
				echo '<table id="tab_group" class="table table-striped table-hover table-bordered"></table>';
			}
		foreach ($typeUsers as $type):?>

			<h4>
				<b><?php  echo $type['TypeUser']['name']; ?> </b>
			</h4>
			<table id="tab_group"
				class="table table-striped table-hover table-bordered">
				<tr>
					<th width="50%"><?php echo $this->Paginator->sort('name'); ?></th>
					<th class="actions"><?php echo __('Activation'); ?></th>
				</tr>
				<?php
				foreach ($users as $user):
				if($user['TypeUser']['name'] == $type['TypeUser']['name']){?>
				<tr>
				<td><?php 
					$txtadmin = ($user['User']['admin']== 1)?'<b>(Admin)</b>':'';
					echo $txtadmin.' '.h($user['User']['nameSurname']); ?>&nbsp;</td>
					<td class="actions">
					<?php $checked = ($user['User']['activate'] === true)?'checked=checked':'';
					?>
						<input type="checkbox" class="checkboxactivation" id="<?php echo $user['User']['id'];?>" <?php echo $checked;?> />
					</td>
				</tr>
				<?php } endforeach; ?>
			</table>
			<?php endforeach; ?>
		</div>
	</div>
</div>
