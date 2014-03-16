<div class="homepages index">
	<div class="container">
		<h2>
			<?php echo $this->Html->image('home.png', array('alt' => 'Home','style'=>'padding-bottom:11px;')) ." ".__('Home'); ?>
		</h2>
		<div id="msg"></div>
		<?php 
		/********************************************* if logged ********************************************/
		//All person who is logged can add project
		if($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.admin') === true):
		echo $this->Html->script('wysiwyg/ckeditor/ckeditor');
		echo $this->Html->script('wysiwyg/ckeditor/adapters/jquery');
		echo $this->Html->script('homepage');?>
		<div id="div_add_object_btn">
			<a class="variousframe fancybox.iframe"
				style="text-decoration: none;" href="homepages/add">
				<button class="btn">
					<i class="icon-plus"></i> Add Widget
				</button>
			</a>
			<?php if(!empty($homepages)){?>
			<button class="btn" style="margin-left: 15px;" id="addrow">
				<i class="icon-plus"></i> Add Row
			</button>
			<?php }?>
		</div>
		<br />
		<?php 
		endif;
		$nbreCol = array();
		$nbreRow = array();
		$nbre = array();
		foreach ($homepages as $nbreColumn){
			$nbreCol[]	 = $nbreColumn['Homepage']['column'];
			$nbreRow[]	 = $nbreColumn['Homepage']['row'];
			$nbre['column'][]	=$nbreColumn['Homepage']['column'];
			$nbre['row'][]		=$nbreColumn['Homepage']['row'];
		}
		if(!empty($nbre['column']) && !empty($nbre['row'])){
		$colunique = array_unique($nbre['column']);
		$rowunique = array_unique($nbre['row']);
		//sort table
		sort($colunique);sort($rowunique);
		?>
		<div id="containhome" class="row-fluid">
			<?php 
			//count row
			foreach($rowunique as $numRow):?>
			<div class="homeRow" id="<?php echo $numRow; ?>">
				<?php 
				//count column
				foreach($homepages as $homepage): ?>
				<?php if($homepage['Homepage']['row'] == $numRow):?>
				<?php foreach ($colunique as $numCol):?>
				<?php if(($homepage['Homepage']['column']) == $numCol):?>
				<div id="row<?php echo $numRow.'col_'.$numCol; ?>"
					class="homeColonne">
					<div class="portlet"
						id="ref_<?php echo $homepage['Homepage']['id']?>">
						<div class="portlet-header"></div>
						<div class="portlet-content">
							<?php echo html_entity_decode(h($homepage['Homepage']['content'])); ?>
						</div>
					</div>
				</div>
				<?php endif; 
				endforeach;
				 endif; endforeach; unset($nbreCol);//end count column ?>
			</div>
			<div style="clear: both;" class="clear<?php echo $numRow?>"></div>
			<?php endforeach;unset($nbreRow);//end count row ?>
		</div>
		<?php }?>
	</div>
</div>
