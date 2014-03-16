<?php if(!empty($params)){?>
<input type="hidden" value="<?php echo $params;?>" id="currentPage" />
<?php if(isset($paramsfull)):?>
<input type="hidden" value="<?php echo $paramsfull;?>" id="fullPage" />
<?php endif; 
}
foreach ($properties as $property){
		if($property['Property']['type'] == 'navbar_link')$navbarlink = $property['Property']['attribut'];
		if($property['Property']['type'] == 'navbar_link_hover')$navbarlinkhover = $property['Property']['attribut'];
		if($property['Property']['type'] == 'navbar_link_hover_bck')$navbarlinkhoverbck = $property['Property']['attribut'];
		if($property['Property']['type'] == 'navbarbck_img1')$navbarlinear1 = $property['Property']['attribut'];
		if($property['Property']['type'] == 'navbarbck_img2')$navbarlinear2 = $property['Property']['attribut'];
}
?>
<input type="hidden" value="<?php echo $navbarlink;?>" id="navbarlink" />
<input type="hidden" value="<?php echo $navbarlinkhover;?>" id="navbarlinkhover" />
<input type="hidden" value="<?php echo $navbarlinkhoverbck;?>" id="navbarlinkhoverbck" />
<input type="hidden" value="<?php echo $navbarlinear1;?>" id="navbarbck_img1" />
<input type="hidden" value="<?php echo $navbarlinear2;?>" id="navbarbck_img2" />

<ul class="nav" id="menuNav">
	<li class="divider-vertical"></li>
	<?php if($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.admin') === true){ ?>
	<li class="dropdown" id="adminMember"><?php echo $this->Html->link("Members <span class='caretNav'></span>", array("controller"=>'members'),array('data-target'=>'#', 'escape'=>false,'class'=>'dropdown-toggle','data-toggle'=>'dropdown')); ?>
		<ul class="dropdown-menu">
			<li><?php echo $this->Html->link($this->Html->image("iconuser.png", array("alt" => "Manage Members","style"=>array('margin-right:5px')))." Manage Members",
					array('controller' => 'users', 'action' => 'index'),
					array('escape'=>false));?>
			</li>
			<li class="divider"></li>
			<li><?php echo $this->Html->link($this->Html->image("typeuser.png", array("alt" => "Manage Member's types","style"=>array('margin-right:5px')))." Manage Member's types",
					array('controller' => 'type_users', 'action' => 'index'),
					array('escape'=>false));?>
			</li>
			<li class="divider"></li>
			<li><?php echo $this->Html->link($this->Html->image("activation.png", array("alt" => "Manage Member's activation","style"=>array('margin-right:5px')))." Manage Member's activation",
					array('controller' => 'users', 'action' => 'activationpage'),
					array('escape'=>false));?>
			</li>
		</ul>
	</li>
	<?php }else{?>
	<li><?php echo $this->Html->link('Members', array('controller' => 'users', 'action' => 'index'),array('id'=>'members')); ?>
	</li>
	<?php } ?>
	<li class="divider-vertical"></li>
	<li><?php echo $this->Html->link('Projects', array('controller' => 'projects', 'action' => 'index'),array('id'=>'projects')); ?>
	</li>
	<li class="divider-vertical"></li>
	<li><?php echo $this->Html->link('Publications', array('controller' => 'medias', 'action' => 'index'),array('id'=>'medias')); ?>
	</li>
	<li class="divider-vertical"></li>
	<li><?php echo $this->Html->link('Collaborators', array('controller' => 'collaborators', 'action' => 'index'),array('id'=>'collaborators')); ?>
	</li>
</ul>

<ul class="nav pull-right" id="linksign">
	<?php  if(AuthComponent::user('id')) {
		$name 	 = $this->Session->read('Auth.User.name');
		$surname = $this->Session->read('Auth.User.surname');
		$idusers = $this->Session->read('Auth.User.id'); ?>
	<li class="dropdown" style="margin-right: 15px;"><?php echo $this->Html->link("Hello ".$name." ".$surname."<span class='caretNav'></span>", array("controller"=>'members'),array('data-target'=>'#', 'escape'=>false,'class'=>'dropdown-toggle','data-toggle'=>'dropdown')); ?>
		<ul class="dropdown-menu">
			<li><?php echo $this->Html->link($this->Html->image("manage.png", array("alt" => "Manage your account","style"=>array('margin-right:5px')))." Manage your account",
					array('controller' => 'users', 'action' => 'edit',$idusers),
					array('escape'=>false, 'class'=>'variousframe fancybox.iframe'));?>
			</li>
			<li class="divider"></li>
			<li><?php echo $this->Html->link($this->Html->image("password.png", array("alt" => "Change your password","style"=>array('margin-right:5px')))." Change your Password",
					array('controller' => 'users', 'action' => 'editPwd',$idusers),
					array('escape'=>false, 'class'=>'variousframe fancybox.iframe'));?>
			</li>
			<?php if($this->Session->read('Auth.User.id') && $this->Session->read('Auth.User.admin') === true){ ?>
			<li class="divider"></li>
			<li><?php echo $this->Html->link($this->Html->image("properties.png", array("alt" => "Manage website properties","style"=>array('margin-right:5px')))." Manage website properties",
					array('controller' => 'properties', 'action' => 'edit'),
					array('escape'=>false, 'class'=>'variousframe fancybox.iframe'));?>
			</li>
			<?php }?>
		</ul>
	</li>
	<li><?php echo $this->Html->link('Logout', array('controller' => 'users', 'action' => 'logout')); ?>
	</li>
	<?php 
	}else{ ?>
	<li class="dropdown signin"><?php echo $this->Html->link('Sign in <span class="caretNav"></span>', array('controller'=>'users','action'=>'login'),array('data-target'=>'#','escape' => false,"class"=> "dropdown-toggle","data-toggle"=>"dropdown")); ?>
		<ul id="signin-dropdown" class="dropdown-menu dropdown-form">
			<li><?php echo $this->element('log'); ?></li>
		</ul>
	</li>
	<?php } ?>
</ul>
