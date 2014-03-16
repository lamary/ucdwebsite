
/*****************************************HOMEPAGE**************************************************************************/
function homepageoptions(){

	$('.portlet-header').css('cursor','move');
	/******************** Add column **********************************/
	if($('.homeColonne').length < 3 && $('.portlet').length > 0){
		if($('#addcolumn').length == 0){
			$('#div_add_object_btn').append('<button class="btn" id="addcolumn" style="float:right;"><i class="icon-plus"></i> Add Column</button>');
		}
	}else{
		if($('#addcolumn').length > 0){
			$('#addcolumn').remove();
			}
	}
	
	$('#addcolumn').click(function(){
		if($('.homeColonne').length == 3){
			alert('Three columns maximum');
		}else{
			var Tabid = ['1','2','3'];
			var diff = '';
			var Tabidtwo = [];
			$('.homeColonne').each(function( index ) {
				id = $(this).attr('id');
				Tabidtwo.push(id);
			});
			if($('.homeColonne').length == 2){
				$('.homeColonne').removeClass('span6');
				$('.homeColonne').addClass('span4');
				var diff = $(Tabid).not(Tabidtwo).get();
				txt = '<div id="'+diff+'" class="homeColonne ui-sortable span4"></div>';
				if(diff == '1') $(txt).insertBefore('#2');
				if(diff == '2') $(txt).insertAfter('#1');
				if(diff == '3') $(txt).insertAfter('#2');
				sortable();
			}
			if($('.homeColonne').length == 1){
				$('.homeColonne').addClass('span6');
				var diff = $(Tabid).not(Tabidtwo).get();
				var load = true;	
				$.each(diff, function(index, value) {
					txt = '<div id="'+value+'" class="homeColonne ui-sortable span6"></div>';
					if(value == '1') {$(txt).insertBefore('#2');sortable();load = false;}
					if(value == '2') {$(txt).insertAfter('#1');sortable();load = false;}
					if(value == '3') {$(txt).insertAfter('#2');sortable();load = false;}
					if(load === false)return false;
				});		
			}
		}
	});
	/******************** end column **********************************/

}
function sortable(){

	//Sortable
	$( ".homeColonne" ).sortable({
		connectWith: ".homeColonne",
		handle:'.portlet-header',
		cursor: "move",
		cancel : "a, input, button, [contenteditable]",
		placeholder: 'widget-placeholder',
		forcePlaceholderSize: true,
		receive: function( event, ui ){
			// order est un array
			var order = $(this).sortable("toArray", {attribute: 'value'});
			var id = $(this).sortable("toArray", {attribute: 'id'});
			var TabMenu = [];var Tabid = [];

			var column = $(this).attr('id');

			$( '#'+column).children().each(function( index ) {
				id = $(this).attr('id');
				cutref = id.split('_');
				id=cutref[1];
				Tabid.push(id);
			});
			req = {'id[]':Tabid, 'column':column};
			$.ajax({
				url: "/homepages/editOrder",
				type: "POST", 
				data: req, 
				dataType: 'json',
				success: function(data) {
					if(data.statut != 'error'){
						i=0;
						$('.homeColonne').each(function( index ) {
							if($(this).children().length == 0) {
								$(this).remove();
								i++;
							}	
						});
						
						//rest 1 or 2column = check
						if(i == 1){
							j=0;
							$('.homeColonne').each(function( index ) {
								if($(this).children().length > 0) {
									j++;
								}
							});
							//if rest 1column
							if(j == '1'){
								$('.homeColonne').removeClass('span6');
							}
							//if rest 2column
							if(j == '2'){
								$('.homeColonne').removeClass('span4');
								$('.homeColonne').addClass('span6');
							}
						}
						//rest 1column
						if(i=='2'){
							$('.homeColonne').removeClass('span4');
						}
//						homepageoptions();
						$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
					} else{
						$('#msg').html("<span class='alert alert-error'>"+data.message+"</span>");
					}
				},
				error:function(XMLHttpRequest,textStatus, errorThrown) {
					alert(textStatus);
				}
			});return false;
		}
	});

//	if edit
	$( ".portlet-header .ui-icon-pencil" ).click(function() {
		//if editable open
		if($("#editable").length > 0 ){
			//confirm leave edition
			if(confirm('If you leave this edition, you lost your update')){
				//if content not empty
				if(CKEDITOR.instances.editable.getData() != ""){
					//if it's editor in progress
					if($(this).parent().parent().find('#editable').length == "1"){
						CKEDITOR.instances.editable.destroy();
						$('#btnsubmit').remove();
						$('#editable').unwrap();
						$('#editable').removeAttr("id");
						
					}else{
						//if it's other block for editing
						CKEDITOR.instances.editable.destroy();
						$('#btnsubmit').remove();
						$('#editable').unwrap();
						$('#editable').removeAttr("id");
						
						$(this).parent().parent().find(".portlet-content").attr({id:'editable',contenteditable:"true"});
						$("#editable").ckeditor();
						id = $(this).parent().parent().attr('id');
						cutref = id.split('_');
						id=cutref[1];
						$('#editable').wrap('<form id="HomepageEditForm" accept-charset="utf-8" method="post" action="/homepages/edit/'+id+'" />');
						$('#HomepageEditForm').append('<div id="btnsubmit" style="text-align:center;"><input type="submit" class="btn btn-primary" value="Submit" style="width:100%;" /></div>');
						$("#HomepageEditForm").submit(function(){
							return manage_homepage('#HomepageEditForm');
						});	
					}

				}else{
					alert('Fields Content must be completed');return false;
				}
			}else{return false;}		
		}else{
			//if it's first click
			$(this).parent().parent().find(".portlet-content").attr({id:'editable',contenteditable:"true"});
			$("#editable").ckeditor();
			id = $(this).parent().parent().attr('id');
			cutref = id.split('_');
			id=cutref[1];
			$('#editable').wrap('<form id="HomepageEditForm" accept-charset="utf-8" method="post" action="/homepages/edit/'+id+'" />');
			$('#HomepageEditForm').append('<div id="btnsubmit" style="text-align:center;"><input type="submit" class="btn btn-primary" value="Submit" style="width:100%;" /></div>');
			$("#HomepageEditForm").submit(function(){
				return manage_homepage('#HomepageEditForm');
			});	
		}
		if($("#editable").length > 0){

		}else{

		}

	});
	//if delete
	$( ".portlet-header .ui-icon-closethick" ).click(function() {
		if(confirm('Are you sure to delete this widget?')){
			id = $(this).parent().parent().attr('id');
			cutref = id.split('_');
			id=cutref[1];
			$.ajax({
				url: "/homepages/delete/"+id,
				type: "POST", 
				dataType: 'json',
				success: function(data) {
					if(data.statut == "success"){
						location.reload();
					}
				},
				error:function(XMLHttpRequest,textStatus, errorThrown) {
					alert(textStatus);
				}
			});
			return false;
		}	
	});
//	$( ".portlet-header .ui-icon" ).click(function() {
//	$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
//	$( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
//	});
	$( ".homeColonne" ).disableSelection();	
}
/*****************************************END HOMEPAGE**************************************************************************/

$(document).ready(function() {

	$( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
	.find( ".portlet-header" )
	.addClass( "ui-widget-header ui-corner-all" )
	.append("<span style='float:left;cursor:pointer;' class='ui-icon ui-icon-pencil'></span><span style='float:right;cursor:pointer;' class='ui-icon ui-icon-closethick'></span>");
	if($('#containhome').length > 0) {homepageoptions(); sortable();}


});