<?php echo $this->Session->flash();?>
<center>
	<h2>Login</h2>
<?php  
			echo $this->Form->create(null,array(
					'url' => array('controller' => 'users', 'action' => 'login'),
					'id'=>'UserLoginForm'));
// 			echo $this->fetch('log'); 
			echo $this->Form->input('mail', array('placeholder'=>'Your Mail...'));
			echo $this->Form->input('password', array('placeholder'=>'Your Password...'));
			echo $this->Html->link('Lost password?',  array('controller' => 'users', 'action' => 'lostpswd'));
// 			echo $this->Form->input('remember', array('label'=>'Remember Me','type'=>'checkbox'));
			echo $this->Form->submit('Send', array('class' => 'btn btn-primary'));
			echo $this->Form->end(); ?>
</center>