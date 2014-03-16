<p> <strong>Hi <?php echo $username; ?></strong>
</p>

<p>To activate your account, please follow this link: </p>
<p><?php echo $this->Html->link('Activate my account',$this->Html->url($link,true)); ?></p>