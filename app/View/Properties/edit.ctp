<div id="msg"></div><?php 
echo $this->Html->css('farbtastic');
echo $this->Html->script('farbtastic');
echo $this->Html->script('properties');

foreach ($datapropreties as $property){
	echo '<div style="display:inline-block;margin:10px;">
			<b>'.$property['Property']['name'].'</b>
			<form>
			<input type="text" style="background-color:'.$property['Property']['attribut'].';" id="'.$property['Property']['type'].'" name="color" value="'.$property['Property']['attribut'].'" />
			</form>
			<div style="display: none;" id="color_'.$property['Property']['type'].'" class="colorpicker"></div>
			</div>';
}
?>
<center><button style="margin-top:15px;" id="edtproperties" class="btn btn-large btn-primary" >Submit</button></center>


