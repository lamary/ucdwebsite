<div class="users index">
	<h2>
		<?php echo $this->Html->image('user.png', array('alt' => 'Members','style'=>'padding-bottom:11px;')) ." ".__('Members'); ?>
	</h2>
	<?php
	if($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.admin') === true){ ?>
	<div class="div_add_object_btn">

		<a class="variousframe fancybox.iframe" href="users/add"><button
				class="btn">
				<i class="icon-plus"></i> Add Members
			</button> </a>
	</div>
	<?php } ?>
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
					<th><?php echo $this->Paginator->sort('name'); ?></th>
					<th>Description</th>
					<th>Link</th>
					<th class="actions"><?php echo __('Actions'); ?></th>
				</tr>
				<?php
				foreach ($users as $user):
	if($user['TypeUser']['name'] == $type['TypeUser']['name']){?>
				<tr>
					<td><?php echo h($user['User']['nameSurname']); ?>&nbsp;</td>
					<td class="truncate"><?php echo h($user['User']['description']); ?>&nbsp;</td>
					<td><?php 
					if($user['User']['link'] != ''){
echo $this->Html->link(
					$this->Html->image("link.png", array("alt" => "See my profil"))." Personal Website",
					$user['User']['link'],array('escape'=>false,"target"=>"_blank"));
}?></td>
					<td class="actions">
					<?php 
					echo $this->Html->link(
					$this->Html->image("view.png", array("alt" => "View")),
					array('controller'=>'users','action' => 'view','slug'=>$user['User']['slug'], 'id'=>$user['User']['id']),
					array('escape'=>false));
			if(($this->Session->read('Auth.User.id') == $user['User']['id']) || ($this->Session->read('Auth.User.admin') === true)){
				echo $this->Html->link(
					$this->Html->image("edit.png", array("alt" => "Edit")),
					array('controller' => 'users', 'action' => 'edit',$user['User']['id']),
					array('escape'=>false, 'class'=>'variousframe fancybox.iframe'));
			}
			if($this->Session->read('Auth.User.admin') === true && $this->Session->read('Auth.User.id') != $user['User']['id']){
			echo $this->Form->postLink(
		$this->Html->image("delete.png", array("alt" => "Delete")), array('action' => 'delete',$user['User']['id']),array('escape'=>false),
		__('Are you sure you want to delete # %s?', $user['User']['name']));
}
?>

				</td>
				</tr>
				<?php } endforeach; ?>
			</table>
			<?php endforeach; ?>
		</div>
	</div>

	<!-- 	it's page with several table, so i think, it's not necessary to have this pagination -->
	<!-- 	<p> -->
	<?php
	// 		echo $this->Paginator->counter(array(
	// 	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	// 	));
	?>
	<!-- 	</p> -->

	<!-- 	<div class="paging"> -->
	<?php
	// 		echo $this->Paginator->prev('< ' . __('previous '), array(), null, array('class' => 'prev disabled'));
	// 		echo $this->Paginator->numbers(array('separator' => ' '));
	// 		echo $this->Paginator->next(__(' next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	<!-- 	</div> -->
</div>
