<div class="container">
	<h2>
		<?php echo $this->Html->image('home.png', array('alt' => 'Home','style'=>'padding-bottom:11px;')) ." ".__('Home'); ?>
	</h2>
	<div class="row">
		<div class="homeColonne">
			<h3>Latest News</h3>
		</div>
		<div class="homeColonne">
			<h3>Latest Groups</h3>

			<?php foreach ($typeUsers as $type):?>
			<p style="text-decoration: underline;font-weight: bold;">
				<?php echo $type['TypeUser']['name']; ?>
				</p>
			<ul>
				<?php foreach ($lastUser as $listUser): 
				if($listUser['TypeUser']['name'] == $type['TypeUser']['name']):?>

				<li style="text-align: center;"><?php  echo $this->Html->link($listUser['User']['name']." ".$listUser['User']['surname'],
					array('controller' => 'users', 'action' => 'view',$listUser['User']['id']),
					array('escape'=>false)); 
					?></li>
				<?php endif; endforeach;?>
			</ul>
			<?php endforeach; ?>

		</div>
		<div class="homeColonne">
			<h3>Latest Projects</h3>
			<ul>
				<?php foreach ($lastProject as $listProject):?>
				<li style="margin-left:40%;"><?php echo $this->Html->link($listProject['Project']['name'],
					array('controller' => 'projects', 'action' => 'view',$listProject['Project']['id']),
					array('escape'=>false)); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</div>
