
/*****************************************HOMEPAGE**************************************************************************/
function homepageoptions(){

	$('.portlet-header').css('cursor','move');
	$( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
	.find( ".portlet-header" )
	.addClass( "ui-widget-header ui-corner-all" )
	.append("<span style='float:left;cursor:pointer;' class='ui-icon ui-icon-pencil'></span><span style='float:right;cursor:pointer;' class='ui-icon ui-icon-closethick'></span>");
	$( '.homeRow').each(function(){
		idRow 		= $(this).attr('id');
		sizecolumn  = $(this).children('.homeColonne').length;
		if(sizecolumn==2 || sizecolumn == 1 && $('#'+idRow+'>.addcolumn').length == 0){
			if(sizecolumn == 2){
				buttonplus='<li id="center"><a href="#"><i class="icon-align-center"></i> Center</a></li><li class="divider"></li>';
			}else{buttonplus='';}
			button = '<div class="btn-group" style="float:right;"><button class="btn addcolumn dropdown-toggle" data-toggle="dropdown" >'+
			'<i class="icon-plus"></i> Add Column <span class="caret"></span></button>'+
			' <ul class="dropdown-menu addcolumnmenu"><li id="left"><a href="#"><i class="icon-align-left"></i> Left</a></li><li class="divider"></li>'+buttonplus+
			'<li id="right"><a href="#"><i class="icon-align-right"></i> Right</a></li></ul></div>';
			$('#'+idRow).append(button);
		}
		if(sizecolumn==3 && $('#'+idRow+'>.addcolumn').length > 0){
			$('#'+idRow+'>.addcolumn').remove();
		}
	});
	/******************** Add column **********************************/

	$('.addcolumnmenu >li').click(function(e){
		e.preventDefault();

		var idrow		= $(this).parent().parent().parent().attr('id');
		var idlink 		= $(this).attr('id');
		//check if there are other empty widget
		$('.homeRow').each(function(){
			if($('#colmayfly').length >0){
				$('#colmayfly').remove();
				if($(this).find('.homeColonne').length == 1){
					$(this).find('.homeColonne').removeClass('span6');
				}
				if($(this).find('.homeColonne').length == 2){
					$(this).find('.homeColonne').removeClass('span4');
					$(this).find('.homeColonne').addClass('span6');
				}
			}
		});
		var linkcolumn  = $(this).parent().parent().parent().find('.homeColonne');
		if(linkcolumn.length >2){
			alert('Three columns maximum');
		}else{
			if(linkcolumn.length == 2){
				linkcolumn.removeClass('span6');
				linkcolumn.addClass('span4');
				idcolumn = linkcolumn.attr('id');
				txt = '<div id="colmayfly" class="empty homeColonne ui-sortable span4"></div>';

				if(idlink == 'left')$(txt).insertBefore('#row'+idrow+'col_1');
				if(idlink == 'center')$(txt).insertAfter('#row'+idrow+'col_1');
				if(idlink == 'right')$(txt).insertAfter('#row'+idrow+'col_2');
				columnsortable();
			}
			if(linkcolumn.length == 1){
				linkcolumn.addClass('span6');
				txt = '<div id="colmayfly" class="empty homeColonne ui-sortable span6"></div>';
				if(idlink == 'left')$(txt).insertBefore('#row'+idrow+'col_1');
				if(idlink == 'center')$(txt).insertAfter('#row'+idrow+'col_1');
				if(idlink == 'right')$(txt).insertAfter('#row'+idrow+'col_1');
				columnsortable();
			}
		}
	});
	/******************** end column **********************************/
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
}
function columnsortable(){

	//Sortable
	$('.homeColonne').sortable({
		connectWith: '.empty',
		handle:'.portlet-header',
		cursor: "move",
		cancel : "a, input, button, [contenteditable]",
		placeholder: 'widget-placeholder',
		forcePlaceholderSize: true,
		receive: function( event, ui ){
			nbre = 0;
			//number of row not empty
			$nbrrow=0;
			$('.homeRow').each(function( indexRow ) {
				if($(this).children('.homeColonne').text() != 0){
					$nbrrow++;
				}
			});
			$('.homeRow').each(function( indexRow ) {				
				var Tabid = [];var Tabcolumn = [];	
				//if no widget not column in the table
				nbrecolumn = 0;
				$(this).children('.homeColonne').each(function( indexColumn ) {
					var column = $(this).attr('id');
//					var columnsplit = column.split('_');
//					var idcolumn = columnsplit[1];
					$( '#'+column).children().each(function( index ) {
						id = $(this).attr('id');
						cutref = id.split('_');
						id=cutref[1];
						Tabid.push(id);
					});
					if($('#'+column).children().length >0)Tabcolumn.push(column);

				});
				var idRow = $(this).attr('id');
				if(Tabcolumn != ""){
					nbre++;
					req = {'idrow':idRow,'idwidget[]':Tabid, 'idcolumn[]':Tabcolumn};
					$.ajax({
						url: "/homepages/editOrder",
						type: "POST", 
						data: req, 
						dataType: 'json',
						success: function(data) {
							if(data.statut == "success"){
								$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
								if(nbre == $nbrrow){
									$('#containhome').load('homepages #containhome>div', function(){
										$('div.homeRow').each(function( index ) {
											id = $(this).attr('id');
											if($('#'+id+'>.homeColonne').length == 2) $('#'+id+'>.homeColonne').addClass('span6');
											if($('#'+id+'>.homeColonne').length == 3) $('#'+id+'>.homeColonne').addClass('span4');
										});
										homepageoptions();
//										columnsortable();
//										addrow();
									});
								}

							} else{
								$('#msg').html("<span class='alert alert-error'>"+data.message+"</span>");
							}
						},
						error:function(XMLHttpRequest,textStatus, errorThrown) {
							alert(textStatus);
						}
					});

				}else{
					$('#'+idRow+'>.addcolumn').remove();
					$('#'+idRow).remove();
					$('.clear'+idRow).remove();
				}

			});
			return false;
		},
//		deactivate: function( event, ui ){

//		}
	});

	$( ".homeColonne" ).disableSelection();	

}
function addrow(){
	var Tabidrow = [];
	$('#addrow').click(function(){
		$('.homeRow').each(function( index ) {
			id = $(this).attr('id');
			Tabidrow.push(id);
		});
		var max=Math.max.apply(null, Tabidrow);
		numRow = max+1;
		if($('#'+max).children('.homeColonne').children().length >0){
			$('<div id="'+numRow+'" class="homeRow"><div id="row'+numRow+'col_1" class="empty homeColonne ui-sortable span4"></div></div><div style="clear: both;" class="clear'+numRow+'"></div>').insertAfter($('.clear'+max));
			columnsortable();
//			homepageoptions();
		}else{
			alert('There is already an empty row');
		}
	});
}
/*****************************************END HOMEPAGE**************************************************************************/

$(document).ready(function() {
	if($('#containhome').length > 0) {
		homepageoptions();
		columnsortable();
		addrow();
	}
	
});