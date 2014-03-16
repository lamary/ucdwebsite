 <?php echo $this->Html->link(__('<i class="icon-backward icon-white"></i> Previous page', true), $pageReturn,array('escape'=>false,'class'=>'btn btn-primary'));?>
 <div class="users view add_object">
	<h2>
		<?php  echo __('Member')." ".$user['User']['name']." ".$user['User']['surname']; ?>

	</h2>
	<?php 
	if($user['User']['link'] != ''){
		echo $this->Html->link(
				$this->Html->image("link.png", array("alt" => "See my profil"))." Personal Website",
				$user['User']['link'],array('escape'=>false,"target"=>"_blank"));
}?>
<br />
<p style="text-align: justify;margin:10px 0 15px 0;"><?php echo h($user['User']['description']); ?></p>

</div>
<div class="related">
	<h3>
		<img style="padding-bottom: 11px; padding-right: 5px;"
			alt="Related Projects" src="/img/project.png">
		<?php echo __('Related Projects'); ?>
	</h3>
	<?php if (!empty($user['Project'])): ?>
	<table class="table table-striped table-hover table-bordered">
		<tr>
			<th><?php echo __('Name Project'); ?></th>
			<th><?php echo __('Start Date Project'); ?></th>
			<th><?php echo __('End Date Project'); ?></th>
			<th><?php echo __('Description Project'); ?></th>
			<th><?php echo __('External Project')?></th>
		</tr>
		<?php
		foreach ($user['Project'] as $project): ?>
		<tr>
			<td><?php echo $this->Html->link($project['name'],array('controller' => 'projects', 'action' => 'view',$project['id'])); ?>
			</td>
			<td><?php echo $project['stardate']; ?></td>
			<td><?php echo $project['enddate']; ?></td>
			<td class="truncate"><?php echo $project['description']; ?>
			</td>
			<?php if($project['external'] === true){
				echo'<td><a target="_blank" class="alignImg" href="'.$project['link'].'">
				'.$this->Html->image("entry.png", array("alt" => "Entry")).'</a></td>';
			}else{
				echo'<td>'.$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg')).'</td>';
					}
					?>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
</div>
