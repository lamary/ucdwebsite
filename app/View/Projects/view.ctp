<?php 
echo $this->Html->link(__('<i class="icon-backward icon-white"></i> Previous page', true), $pageReturn,array('escape'=>false,'class'=>'btn btn-primary'));

echo $this->Html->script('wysiwyg/ckeditor/ckeditor');
?>
<div class="projects view add_object">
	<h2>
		<?php  echo __('Project')." ".$project['Project']['name']; ?>
	</h2>
	<p style="text-align: justify;">
		<?php echo html_entity_decode(h($project['Project']['description'])); ?>
	</p>
	<br />
	<div class="row container">
		<div class=" span3 offset1 homeColonne projectColonne">
			<h4>
				<?php echo $this->Html->image('user.png', array('alt' => 'Members Project')) ." ".__('Members'); ?>
			</h4>
			<ul>
				<?php 
				foreach($project['User'] as $user){
					$icon=($user['id'] == $project['Project']['manager'])? $this->Html->image("manager.png", array("alt" =>"project manager")):"";
					echo ' <li>'.$this->Html->link($user['nameSurname'], array('controller' => 'users', 'action' => 'view',$user['id'])).' '.$icon.'</li> ';
				}?>
			</ul>
		</div>
		<div class=" span3 homeColonne projectColonne">
			<h4>
				<?php echo $this->Html->image('dates.png', array('alt' => 'Dates Project')) ." ".__('Dates'); ?>
			</h4>
			<ul>
				<?php $endDate = ($project['Project']['enddate'] != null)? h($project['Project']['enddate']):''.$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg')).'';?>
				<li><b>Start Date Project : </b> <?php echo h($project['Project']['stardate']); ?>
				</li>
				<li><b>End Date Project : </b> <?php echo $endDate; ?>
				</li>
			</ul>
		</div>
		<div class=" span3 homeColonne projectColonne">
			<h4>
				<?php echo $this->Html->image('project.png', array('alt' => 'Type Project')) ." ".__('Type Project'); ?>
			</h4>
			<ul>
				<?php $classExternal = ($project['Project']['external'] == 1)? '<a target="_blank" class="alignImg" href="'.$project['Project']['link'].'">'.$this->Html->image("entry.png", array("alt" => "Entry")).'</a>':''.$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg')).'';
				?>
				<li><b>External Project : </b> <?php echo $classExternal; ?>
				</li>
			</ul>
		</div>
	</div>
	<div class="mediasProject">
		<h4>
			<?php echo $this->Html->image('medias.gif', array('alt' => 'Publications Project')) ." ".__('Publications'); ?>
		</h4>
		<div class="accordionMedia">
			<?php 
			//Group publication by year
			foreach ($typeMedia as $key=>$type):
			?>
			<h4>
				<b><?php  echo $type['Media']['date']; ?> </b>
			</h4>
			<div>
				<table id="tab_group_<?php echo $type['Media']['date']; ?>"
					class="table table-striped table-hover table-bordered tabMedia">
					<thead>
						<tr>
							<th>Descriptions</th>
							<th>Files</th>
						</tr>
					</thead>
					<tbody>
				<?php foreach($project['Media'] as $mediaData){
						if($mediaData['date'] == $type['Media']['date']) {?>
						<tr id="<?php echo $mediaData['id'];?>">

							<?php
							$EmptyDescription = ($mediaData['reference'] != "")? "<td class='truncate' style='width:60%;'>".h($mediaData['reference']).'</td>' :
							"<td>".$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg')).'</td>';
									echo $EmptyDescription.'</td>'; ?>
							<td><span class="tab_group_list alignImg"> <?php 
							$typeImg = "";
							$typeMedia = $mediaData['type'];
							if($typeMedia != '.txt'){
								//if pdf
								if($typeMedia == '.pdf'){
									echo '<a class="various_img" "data-fancybox-type="iframe"" href="/'.$mediaData['link'].'"
											title="'.$media['Project']['name'].' - '. $mediaData['name'].'">';
									echo $this->Html->image("/img/pdf.png").'</a>';
								}
								//if any document
								elseif($typeMedia == '.doc' || $typeMedia == '.docx' || $typeMedia == '.odt' ){
									echo '<a class="various_img"  href="/'.$mediaData['link'].'"
											title="'.$media['Project']['name'].' - '. $mediaData['name'].'">';
									echo $this->Html->image("/img/doc.png").'</a>';
								}
								//if any picture
								elseif($typeMedia == '.jpg' || $typeMedia == '.jpeg' || $typeMedia == '.bmp' || $typeMedia == '.png' || $typeMedia == '.gif'){
									echo '<a class="various_img"  href="/'.$mediaData['link'].'"
										title="'.$project['Project']['name'].' - '. $mediaData['name'].'">';
									echo $this->Html->image("/".$mediaData['link']).'</a>';
								}
								//if any video
								elseif($typeMedia == '.flv' || $typeMedia == '.mov' || $typeMedia == '.mp4' || $typeMedia == '.avi' || $typeMedia == '.mkv' || $typeMedia == '.wmv'){
									echo '<a class="various_img"  href="/'.$mediaData['link'].'"
										title="'.$media['Project']['name'].' - '. $mediaData['name'].'">';
									echo $this->Html->image("/img/video.png").'</a>';
								}
								//if any music
								elseif($typeMedia == '.mp3'){
									echo '<a class="various_img"  href="/'.$mediaData['link'].'"
										title="'.$media['Project']['name'].' - '. $mediaData['name'].'">';
									echo $this->Html->image("/img/mp3.png").'</a>';
								}
								//if no picture
								elseif($typeMedia == '' && $mediaData['linkref'] == ''){
									echo $this->Html->image("/img/nopicture.png");
								}
								elseif($typeMedia == '' && $mediaData['linkref'] != ''){
									echo $this->Html->link("See Publication",$mediaData['linkref'],
											array('escape'=>false, 'target'=>'_blank'));
								}
							}//end if txt
							else{
								$mediaslink= $this->Html->image("/img/doc.png");

								echo $this->Html->link($this->Html->image("/img/doc.png", array("alt" => "Edit")),
										array('controller' => 'medias', 'action' => 'downloadtxt',$mediaData['name']),
										array('escape'=>false));

							 }?>
							</span></td>
						</tr>
				<?php } 
					}?>

			</tbody>
				</table>
			</div>
			<?php endforeach;?>
		</div>
	</div>
	</div>