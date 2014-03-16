$(document).ready(function() {
	$('input[name=color]').click(function(){
		id = $(this).parent().next().attr('id');
		$('#'+id).toggle();
		idcolor = $(this).attr('id');	
		$('#'+id).farbtastic('#'+idcolor);
	});

	serial = "";
	nbre = 0;
	$('#edtproperties').click(function(){
		$('.colorpicker').each(function(){
			nbre++;
			idcolor = $(this).prev().find('input[name=color]').attr('id');	
			valuecolor = $(this).prev().find('input[name=color]').attr('value');
			if(nbre < $('.colorpicker').length){and = '&';}else{and='';}
			serial += idcolor+'='+valuecolor+and;
		});
		$.ajax({
			url: "/properties/edit",
			type: 'POST', 
			data: serial, 
			dataType: 'json',
			success: function(data) {
				if(data.statut == 'success'){
					parent.$.fancybox.close();
					parent.location.reload();
					parent.$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
				} else{
					$('#msg').html("<span class='alert alert-error'>"+data.message+"</span>");
				}
			},
			error:function(XMLHttpRequest,textStatus, errorThrown) {
				alert(textStatus);
			}
		});
	});
  });