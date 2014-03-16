<?php 
echo $this->Html->script('wysiwyg/ckeditor/ckeditor');
?>
<div class="homepages index">
	<div class="container">
		<h2>
			<?php echo $this->Html->image('home.png', array('alt' => 'Home','style'=>'padding-bottom:11px;')) ." ".__('Home'); ?>
		</h2>
		<div id="msg"></div>
		<?php 
		/********************************************* if logged ********************************************/
		//All person who is logged can add project
		if($this->Session->read('Auth.User.id')):
		echo $this->Html->script('homepage');?>
		<div id="div_add_object_btn">
			<a class="variousframe fancybox.iframe" href="homepages/add">
				<button class="btn">
					<i class="icon-plus"></i> Add Widget
				</button>
			</a>
		</div>
		<br />
		<?php 
		endif;
		$nbre = array();
		foreach ($homepages as $nbreColumn){
			$nbre[]	 = $nbreColumn['Homepage']['column'];
		}
		$columunique = array_unique($nbre);
		//sort table
		sort($columunique);
		?>
		<div id="containhome" class="row-fluid">
			<?php 
			foreach($columunique as $numCol): ?>
			<div class="homeColonne" id="<?php echo $numCol; ?>">
				<?php foreach ($homepages as $homepage): 
				if($homepage['Homepage']['column'] == $numCol ):?>
				<div class="portlet"  id="ref_<?php echo $homepage['Homepage']['id']?>" >
					<div class="portlet-header"></div>
					<div class="portlet-content" style="display: block;">
						<?php echo html_entity_decode(h($homepage['Homepage']['content'])); ?>
					</div>
				</div>
				<?php endif; 
				endforeach;?>
			</div>
			<?php endforeach; unset($nbre);?>
		</div>
	</div>
</div>
