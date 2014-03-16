<?php
/**
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

$cakeDescription = __d('cake_dev', 'UCD intranet');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $this->Html->charset(); ?>
<title><?php echo $cakeDescription ?>: <?php echo $title_for_layout; ?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="img/iconDub.ico" type="image/x-icon" />
<?php
echo $this->Html->css('bootstrap.min');
echo $this->Html->css('bootstrap-responsive.min');
echo $this->Html->css('jquery.fancybox');
echo $this->Html->css('smoothness/jquery-ui-1.10.3.custom.min');
echo $this->Html->css('style');
echo $this->Html->script('jquery-1.9.1.min');
echo $this->Html->script('jquery-ui-1.10.3.custom.min');
// echo $this->fetch('meta');
// echo $this->fetch('css');

?>
</head>
<body>
	<?php foreach ($properties as $property){
		if($property['Property']['type'] == 'navbarbck_img1')$navbarlinear1 = $property['Property']['attribut'];
		if($property['Property']['type'] == 'navbarbck_img2')$navbarlinear2 = $property['Property']['attribut'];
		if($property['Property']['type'] == 'navbar_bordercolor')$navbarborder = $property['Property']['attribut'];
		if($property['Property']['type'] == 'navbar_bck_default')$navbarbckdefault = $property['Property']['attribut'];
}?>
	<div class="navbar navbar-inverse navbar-static-top">
		<div class="navbar-inner" style="background-color:<?php echo $navbarbckdefault; ?>;border-color:<?php echo $navbarborder;?>;background-image:linear-gradient(to bottom, <?php echo $navbarlinear1;?>, <?php echo $navbarlinear2;?>);">
			<a class="brand" id="homeWebsite" href="/"></a>
			<!-- NAVBAR -->
			<?php echo $this->element('menu'); ?>
			<!--/.nav-collapse -->
		</div>
	</div>

	<div class="container">
		<div id="content">
			<noscript>
				<h4 class="alert" style="text-align: center;">Your javascript is not
					enabled, for a better navigation please, activate it on your tools
					broswer</h4>
			</noscript>
			<?php  
			echo $this->Session->flash();  ?>
			<div id="msg"></div>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer"></div>
	</div>
	<?php 
	// 			echo $this->element('sql_dump');
	// 	if(Configure::read('debug') >=2){
// 	debug($this->Session->read());
// }

// 	echo $this->Html->script('tinymce/tinymce.min');

	if(isset($params) && $params== 'medias'){
echo $this->Html->script('plupload/plupload');
echo $this->Html->script('plupload/plupload.flash');
echo $this->Html->script('plupload/plupload.html5');
}
echo $this->Html->script('jquery.fancybox.pack');
echo $this->Html->script('bootstrap.min');
echo $this->Html->script('main');

// 	echo $this->fetch('script');
?>
</body>
</html>
