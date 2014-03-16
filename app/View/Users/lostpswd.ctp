<?php echo $this->Session->flash(); ?>
<div class="add_object">
	<h2>
		<?php echo $this->Html->image("chgPswd.png", array("alt" => "Lost password","style"=>array('margin-right:10px'))).__('Lost Password ? '); ?>
	</h2>
	<br />
	<p id="msgLostPwd"></p><br />
	<label for="UserMailPswdLost">Please, enter you mail </label>
	<input id="UserMailPswdLost" type="text" value="" name="mailPswdLost" /><br />
	<input type="button" class="btn btn-primary" value="Send" id="submitLostPwd" >

</div>
