<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $this->Html->charset(); ?>
</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php
echo $this->Html->meta('iconDub.ico','img/iconDub.ico', array('type' => 'icon'));

echo $this->Html->css('bootstrap.min');
echo $this->Html->css('bootstrap-responsive.min');
echo $this->Html->css('jquery.fancybox');
echo $this->Html->css('smoothness/jquery-ui-1.10.3.custom.min');
echo $this->Html->css('style');
echo $this->Html->script('jquery-1.9.1.min');

// echo $this->fetch('meta');
// echo $this->fetch('css');

?>
</head>
<body>
	<div class="container">
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
			
		</div>
	</div>
	<?php 
	
	echo $this->Html->script('jquery-ui-1.10.3.custom.min');
	echo $this->Html->script('jquery.fancybox.pack');
	echo $this->Html->script('bootstrap.min');
	echo $this->Html->script('main');
	echo $this->fetch('script');
	?>
</body>
</html>
