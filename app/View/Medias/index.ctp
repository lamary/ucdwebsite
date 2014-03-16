<div class="medias index">
	<h2>
		<?php echo $this->Html->image('medias.gif', array('alt' => 'Publications','style'=>'padding-bottom:11px;')) ." ".__('Publications'); ?>
	</h2>
	<div id="msg"></div>
	<?php if($this->Session->read('Auth.User.id')){ ?>
	<div class="div_add_object_btn">
		<a class="various fancybox.ajax" href="/medias/add"><button
				class="btn ">
				<i class="icon-plus"></i> Add Publication
			</button> </a>
	</div>
	<?php  
	echo '<div id="divextract" style="float:right;margin-bottom:10px;">';
	if(!empty($medias)){
		echo $this->Html->link($this->Html->image("extract.png", array("alt" => "Extract values"))." Extract Publications ",
				array('controller' => 'medias', 'action' => 'extract'),
				array('escape'=>false,'id'=>'extracts'));

	}
	echo '</div><div style="clear:both;"></div>';
}
$yearMedia = array();
//foreach by project
foreach ($medias as $dateMedia){
				$yearMedia[]	  = $dateMedia['Media']['date'];
			}
			$yearMediaUnique = array_unique($yearMedia);
			//sort table
			arsort($yearMediaUnique);
			?>
	<div class="TitleProject">
		<div class="accordionMedia">
			<!-- Foreach by Year/ Media-->

			<?php foreach ($yearMediaUnique as $key=>$dates):?>
			<h4>
				<b><?php  echo $dates; ?> </b>
			</h4>
			<div>
				<table id="tab_group_<?php echo $dates; ?>"
					class="table table-striped table-hover table-bordered tabMedia">
					<thead>
						<tr>
							<th>References</th>
							<th>Files</th>
							<th>Project</th>
							<?php if($this->Session->read('Auth.User.id')){ 
								echo '<th class="actions">'.__('Actions').'</th>';
							}?>
						</tr>
					</thead>
					<tbody>
						<?php 
						foreach($medias as $media){
								if($media['Media']['date'] == $dates){
						?>
						<tr id="<?php echo $media['Media']['id'];?>">

							<?php
							$EmptyDescription = ($media['Media']['reference'] != "")? "<td class='truncate'>".h($media['Media']['reference']).'</td>' :
							"<td>".$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg')).'</td>';
									echo $EmptyDescription.'</td>'; ?>
							<td><span class="tab_group_list alignImg"> <?php 
							$typeImg = "";
							$typeMedia = $media['Media']['type'];
							if($typeMedia != '.txt'){
								//if pdf
								if($typeMedia == '.pdf'){
									echo '<a class="various_img" "data-fancybox-type="iframe"" href="'.$media['Media']['link'].'"
											title="'.$media['Project']['name'].' - '. $media['Media']['name'].'">';
									echo $this->Html->image("/img/pdf.png").'</a>';
								}
								//if any document
								elseif($typeMedia == '.doc' || $typeMedia == '.docx' || $typeMedia == '.odt' ){
									echo '<a class="various_img"  href="'.$media['Media']['link'].'"
											title="'.$media['Project']['name'].' - '. $media['Media']['name'].'">';
									echo $this->Html->image("/img/doc.png").'</a>';
								}
								//if any picture
								elseif($typeMedia == '.jpg' || $typeMedia == '.jpeg' || $typeMedia == '.bmp' || $typeMedia == '.png' || $typeMedia == '.gif'){
									echo '<a class="various_img"  href="'.$media['Media']['link'].'"
										title="'.$media['Project']['name'].' - '. $media['Media']['name'].'">';
									echo $this->Html->image("/".$media['Media']['link']).'</a>';
								}
								//if any video
								elseif($typeMedia == '.flv' || $typeMedia == '.mov' || $typeMedia == '.mp4' || $typeMedia == '.avi' || $typeMedia == '.mkv' || $typeMedia == '.wmv'){
									echo '<a class="various_img"  href="'.$media['Media']['link'].'"
										title="'.$media['Project']['name'].' - '. $media['Media']['name'].'">';
									echo $this->Html->image("/img/video.png").'</a>';
								}
								//if any music
								elseif($typeMedia == '.mp3'){
									echo '<a class="various_img"  href="'.$media['Media']['link'].'"
										title="'.$media['Project']['name'].' - '. $media['Media']['name'].'">';
									echo $this->Html->image("/img/mp3.png").'</a>';
								}
								//if no picture
								elseif($typeMedia == '' && $media['Media']['linkref'] == ''){
									echo $this->Html->image("/img/nopicture.png");
								}
								elseif($typeMedia == '' && $media['Media']['linkref'] != ''){
									echo $this->Html->link("See Publication",$media['Media']['linkref'],
											array('escape'=>false, 'target'=>'_blank'));
								}
							}//end if txt
							else{
									$mediaslink= $this->Html->image("/img/doc.png");

									echo $this->Html->link($this->Html->image("/img/doc.png", array("alt" => "Edit")),
											array('controller' => 'medias', 'action' => 'downloadtxt',$media['Media']['name']),
											array('escape'=>false));

								}
									
								?>
							</span>
							</td>
							<td><?php
							$EmptyProject = ($media['Project']['id'] != NULL)? $this->Html->link($media['Project']['name'], array('controller' => 'projects', 'action' => 'view', $media['Project']['id']),array('style'=>array('color:#0088CC;'))) :
							$this->Html->image("no_entry.png", array("alt" => "No Entry",'class'=>'alignImg'));
							echo $EmptyProject; ?></td>
							<?php if($this->Session->read('Auth.User.id')){ 
								echo '<td class="actions">';
								echo $this->Html->link($this->Html->image("edit.png", array("alt" => "Edit")),
										array('controller' => 'medias', 'action' => 'edit',$media['Media']['id']),
										array('escape'=>false, 'class'=>'various fancybox.ajax'));
								echo $this->Html->link($this->Html->image("delete.png", array("alt" => "Delete")),array('controller' => 'medias'),
								array('escape'=>false,'class'=>"del"));
								echo '</td>';
							}
							?>
						</tr>
						<?php } //end if
						}// end foreach ($medias as $media)?>
					</tbody>
				</table>
			</div>
			<?php endforeach; unset($yearMedia); //Foreach year by media ?>
		</div>
	</div>

</div>


