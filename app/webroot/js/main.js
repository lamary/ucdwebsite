/*****************************************HOMEPAGE**************************************************************************/
function manage_homepage(name){
	var NameForm = $(name);
	if($('#HomepageEditForm').length >0){
		serial = 'content='+CKEDITOR.instances.editable.getData();
		value = CKEDITOR.instances.editable.getData();
	}else{
		serial = NameForm.serialize();
		value = $('#HomepageContent').val();
	}

	if( value == ''){
		alert('Fields Content must be completed');
	}else{

		$.ajax({
			url: NameForm.attr('action'),
			type: NameForm.attr('method'), 
			data: serial, 
			dataType: 'json',
			success: function(data) {
				if(data.statut == 'success'){
					parent.$.fancybox.close();
					parent.location.reload();
					parent.$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
				} 
			},
			error:function(XMLHttpRequest,textStatus, errorThrown) {
				alert(textStatus);
			}
		});
	}
	return false;
}
/***************************************** END HOMEPAGE**************************************************************************/
/*****************************************ADD COLLAB**************************************************************************/
function add_collab(name){
	var NameForm		= $(name);
	var CollabSurname 	= $('#CollaboratorSurname').val();
	var CollabName 		= $('#CollaboratorName').val();
	if(CollabSurname == '' || CollabName == '') {
		alert('Fields Name and Surname must be completed');
	} 
	else {
		$.ajax({
			url: NameForm.attr('action'),
			type: NameForm.attr('method'), 
			data: NameForm.serialize(), 
			dataType: 'json',
			success: function(data) {
				if(afterValidate(data) === true){
					$('#tab_group').load('collaborators #tab_group>tbody');
					$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
					$.fancybox.close();
				} 
			},
			error:function(XMLHttpRequest,textStatus, errorThrown) {
				alert(textStatus);
			}
		});
	}
	return false;

}
/*****************************************END ADD COLLAB**************************************************************************/
/*****************************************ADD MEMBER**************************************************************************/
function manage_member(RecupNameForm){
	var NameForm		= $('#'+RecupNameForm);
	var UserSurname 	= $('#UserSurname').val();
	var UserName 		= $('#UserName').val();
	if($('.control-group .error').length >0){
		$("div.input").removeClass("control-group error");
	}

	if(UserSurname == '' || UserName == '') {
		alert('Fields Name and Surname must be completed');
		return false;
	} 
	if($('#UserPass1').val()=="" || $('#UserPass1').val()==""){
		alert('Fields Passwords be completed');
		return false;
	}

	$.ajax({
		url: NameForm.attr('action'),
		type: NameForm.attr('method'), 
		data: NameForm.serialize(), 
		dataType: 'json',
		success: function(data) {
			if(afterValidate(data) === true){
				//if success change password
				if(RecupNameForm == 'UserEditPwdForm'){
					parent.$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
					parent.$.fancybox.close();
				}
				//if success edit user
				if(RecupNameForm == 'UserEditForm'){
					parent.$.fancybox.close();
					parent.$('#bodytabUser').load('users #tabUser');
					parent.$('#msg').html("<span class='alert alert-success'>"+data.message+" If you're updated name/surname, reload your page to see modifications</span>");
				}
				//if success add user
				if(RecupNameForm == 'UserAddForm'){
					parent.$('#tabUser').load('users #tabUser');
					parent.$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
					parent.$.fancybox.close();
				}
			}
		},
		error:function(XMLHttpRequest,textStatus, errorThrown) {
			alert(textStatus);
		}
	});
	return false;

}
/*****************************************END ADD MEMBER**************************************************************************/
/*****************************************ADD PROJECT**************************************************************************/
function manage_project(Name){
	var NameForm= $('#'+Name);
	$.ajax({
		url: NameForm.attr('action'),
		type: NameForm.attr('method'), 
		data: NameForm.serialize(), 
		dataType: 'json',
		success: function(data) {
			if(afterValidate(data) === true){
				//If edit project
				if(Name == "ProjectEditForm")
				{ 	parent.$('#msg').html("");
				parent.$.fancybox.close();
				parent.$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
				parent.$('#tab_group').load('projects #tab_group>tbody', function() {
					AddClassProjectExternal();
					
				});
				}
				//If add Project
				if(Name == "ProjectAddForm")
				{	
					$('#msg').html("");
					$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
					$('.alert-info, .help-inline').remove();
					$('div').removeClass('error');
					document.forms.ProjectAddForm.reset();
					$('#ProjectDescription').val('');
					$('#addProject').slideToggle('slow');
					$('#tab_group').load('projects #tab_group>tbody', function() {
						AddClassProjectExternal();
						$('td.truncate').jTruncate({length:100});
					});
				}
			} 
		},
		error:function(XMLHttpRequest,textStatus, errorThrown) {
			alert(textStatus);
		}
	});
	return false; 
}

/*****************************************END ADD PROJECT**************************************************************************/
/*****************************************EDIT MEDIA**************************************************************************/
function edit_media(name){
	var NameForm= $('#'+name);
	if($('#MediaReference').val() == ""){
		alert('Please, complete Reference field');
		return false;
	}
	$.ajax({
		url: NameForm.attr('action'),
		type: NameForm.attr('method'), 
		data: NameForm.serialize(), 
		dataType: 'Json',
		success: function(data) {
			if(afterValidate(data) === true){
				$('.TitleProject').load('medias/ .TitleProject>div', function() {
					accordionPublication();
					delpublication();
//					center img in table
					if($('.alignImg').length>0){
						$('.alignImg').each(function(){
							$(this).parent().css('text-align','center');
						});
					}
				});
				parent.$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
				$.fancybox.close();
			} else{
				$('#msgAddMedia').html("<span class='alert alert-error'>"+data.message+"</span>");
				return false;
			}
		},
		error:function(XMLHttpRequest,textStatus, errorThrown) {
			alert(textStatus);
		}
	});
	return false;
}

/*****************************************END EDIT MEDIA**************************************************************************/
/**************************************DEL PUBLICATION****************************************************************/
function delpublication(){
	$('.del').bind('click',function(e){
		e.preventDefault();
		if(confirm("Are you sure you want to delete this media ?")){
			$.ajax({
				url: "/medias/delete",
				type: "GET", 
				data: "action=delete&id="+$(this).parent().parent().attr('id')+"&idtable="+$(this).parent().parent().parent().parent().attr('id'), 
				dataType: 'json',
				success: function(data){
					parent.$('#msg').html("");
					if(afterValidate(data) === true){
						if($("table#"+data.idtable+" tbody").children().length-1 == 0){
							$('.TitleProject').load('medias/ .TitleProject>div', function() {
								accordionPublication();
								delpublication();
//								center img in table
								if($('.alignImg').length>0){
									$('.alignImg').each(function(){
										$(this).parent().css('text-align','center');
									});
								}
								$( ".accordionMedia" ).on( "accordioncreate", function( event, ui ) {
									if($('.accordionMedia').children().length == 0) {
										$('#divextract').html('');	
									}
									$('.TitleProject').load('medias/ .TitleProject>div', function() {
										accordionPublication();
										delpublication();
									});
								});
								parent.$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
								$.fancybox.close();
							});

						}else{
							$(this).parent().parent().fadeOut('slow');
							$('tr#'+data.id).remove();
						}
						$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
					}
				}
			});

		}
		return false;
	});
}

/************************************** FIN DEL PUBLICATION****************************************************************/
/*****************************************VALIDATE AJAX FORM**************************************************************************/
function afterValidate(data)  {

	$(".alert").remove();

	if (data.statut == 'error') {
		onError(data);
		return false;
	} else if (data.statut == 'success') {
		flashMessage(data.message);
		return true;
	}
}

function flashMessage(message) {
	$('<div>'+message+'</div>').insertAfter('legend').addClass('alert alert-info');
	var _insert = $(document.createElement('div')).css('display', 'none');
}

function onError(data) {
	$('.help-inline').remove();
	flashMessage(data.message);
	$.each(data.data, function(key1, value1){
		$.each(value1, function(key2, value2){
			var key2 = key2.substr(0,1).toUpperCase()+key2.substr(1,key2.length).toLowerCase();
			var element = $("#" + key1+key2	);
			element.parent().addClass('control-group error');
			$('<label>'+value2+'</label>').insertBefore(element).addClass('help-inline');
		});
	});
}

function camelize(string) {
	var a = string.split('_'), i;
	s = [];
	for (i=0; i<a.length; i++){
		s.push(a[i].charAt(0).toUpperCase() + a[i].substring(1));
	}
	s = s.join('');
	return s;
}
function AddClassProjectExternal(){
	var externalProject = $('.external');
	if(externalProject.html() == 1){
		return externalProject.addClass('trueExternal');
	}else{
		return externalProject.addClass('falseExternal');}
}
/**************************************PARAM PROJECT****************************************************************/
function paramProject(){
	$( ".datepicker" ).datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "dd-mm-yy"
	});

}
/**************************************PARAM PROJECT****************************************************************/
/************************************** Sortable Type User *************************************************************/
function sortTypeUser(){
	$( "#sortable" ).sortable({
		placeholder: "ui-state-highlight",
		update: function(){
			// order est un array
			var order = $(this).sortable("toArray", {attribute: 'value'});
			var id = $(this).sortable("toArray", {attribute: 'id'});
			var TabMenu = [];var Tabid = [];
			var comma = "";
			$( "#sortable>li" ).each(function( index ) {
				id = $(this).attr('id');
				order = $(this).attr('value');
				Tabid.push(id);
			});
			req = {'id[]':Tabid};
			$.ajax({
				url: "/type_users/editOrder",
				type: "POST", 
				data: req, 
				dataType: 'json',
				success: function(data) {
					if(data.statut != 'error'){
						$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
					} else{
						$('#msg').html("<span class='alert alert-error'>"+data.message+"</span>");
					}
				},
				error:function(XMLHttpRequest,textStatus, errorThrown) {
					alert(textStatus);
				}
			});
		}
	});
}
/******************************************end Sortable Type User **************************************************/
/******************************************** EDIT TYPEUSER******************************************************/
//submit edit
function edit_typeuser(id){
	val = $('#inputTypeUSer_'+id).val();
	$.ajax({
		url: '/type_users/edit',
		type: 'POST', 
		data: 'id='+id+'&val='+val, 
		dataType: 'Json',
		success: function(data) {
			if(afterValidate(data) === true){
				$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
				$('#inputTypeUSer_'+id).attr('disabled','disabled');
				$('#inputTypeUSer_'+id).css('background-color','transparent');
				$('#editspan').remove();
				$('a.editTypeUser').removeAttr('id');
//				$('#sortable').load('typeusers/ #sortable>li');
			} else{
				$('#msg').html("<span class='alert alert-error'>"+data.message+"</span>");
			}
		},
		error:function(XMLHttpRequest,textStatus, errorThrown) {
			alert(textStatus);
		}
	});
}
function pagin(){
	$('a[href*="/sort:"]').on('click', function(e){
		e.preventDefault();
		$('#content').load($(this).attr('href'),function(){
			pagin();
			if($('td.truncate').length) $('td.truncate').jTruncate({length: 100});
		});
		return false;
	});
	$('a[href*="/page:"]').on('click', function(e){
		e.preventDefault();
		$('#content').load($(this).attr('href'),function(){
			pagin();
		});
		return false;
	});
}
/*********************************************END EDIT TYPEUSER******************************************************/

function accordionPublication(){
	$('.accordionMedia').accordion({
		collapsible:true,
		header: "h4",
		heightStyle: "content",
		active: false,
		create :function( event, ui ){
//			delpublication();
			$('td.truncate').jTruncate({length:100});
		},
		beforeActivate: function(event, ui) {
			// The accordion believes a panel is being opened
			if (ui.newHeader[0]) {
				var currHeader  = ui.newHeader;
				var currContent = currHeader.next('.ui-accordion-content');
				// The accordion believes a panel is being closed
			} else {
				var currHeader  = ui.oldHeader;
				var currContent = currHeader.next('.ui-accordion-content');
			}
			// Since we've changed the default behavior, this detects the actual status
			var isPanelSelected = currHeader.attr('aria-selected') == 'true';

			// Toggle the panel's header
			currHeader.toggleClass('ui-corner-all',isPanelSelected).toggleClass('accordion-header-active ui-state-active ui-corner-top',!isPanelSelected).attr('aria-selected',((!isPanelSelected).toString()));

			// Toggle the panel's icon
			currHeader.children('.ui-icon').toggleClass('ui-icon-triangle-1-e',isPanelSelected).toggleClass('ui-icon-triangle-1-s',!isPanelSelected);

			// Toggle the panel's content
			currContent.toggleClass('accordion-content-active',!isPanelSelected);    
			if (isPanelSelected) { currContent.slideUp(); }  else { currContent.slideDown(); }
			return false; // Cancels the default action
		}		
	});
}
/*******************************************************************************************************************/
$(document).ready(function() {
	if($.browser.name == "msie"){
		$.ajaxSetup ({
			cache: false
		});
	}
	if($('#currentPage').length == 0){
		$('#homeWebsite').attr('title','Homepage');
	}else{ //if other page
		$('#homeWebsite').removeAttr('title');
		$( "#menuNav a" ).parent().removeClass('active');
		$( "#menuNav a" ).each(function( index ) {
			if($(this).attr('id') == $('#currentPage').val()){
				if($('#fullPage').val() == 'login'){
					$('ul#linksign').remove();
					$('#UserLoginForm').append('<input type="hidden" id="pagelog" name="pagelog" value="true" >');
				}
				$(this).parent().addClass('active');
			}
			if($('#adminMember').length >0){
				if($('#currentPage').val() == 'members' || $('#currentPage').val() == 'type_users'){
					$('#adminMember').addClass('active');
				}
			}
		});}
	/************************ color page of website **************************************/
	$('.navbar-inverse .divider-vertical').css({'border-left-color':$('#navbarbck_img2').val(),'border-right-color':$('#navbarbck_img1').val()});

	$('.nav > li > a').each(function(){
		if($(this).parent('.active').length > 0 || $('.dropdown-toggle').parent('.open').length > 0 ){
			$(this).css({'color':$('#navbarlinkhover').val(),'background-color':$('#navbarlinkhoverbck').val()});
		}else{
			$(this).css('color',$('#navbarlink').val());
		}
	});		

	nbreclick = 0;
	$('.dropdown>.dropdown-toggle').click(function(){
		if(nbreclick==0){
			nbreclick++;
		}
		else {nbreclick=0;}
//		alert(nbreclick);
		if(nbreclick == 1){
			$(this).focus();
			$(this).css({'color':$('#navbarlinkhover').val(),'background-color':$('#navbarlinkhoverbck').val()});
		}else{
			$(this).css({'color':$('#navbarlink').val(),'background-color':''});
		}
	});
	$('.dropdown-toggle').blur(function(){
		$(this).css({'color':$('#navbarlink').val(),'background-color':''});
		nbreclick = 0;
	});

	$('.navbar-inverse .nav > li > a').hover( function () {
		$(this).css('color',$('#navbarlinkhover').val());
		$('.open>.dropdown-toggle').css({'color':$('#navbarlinkhover').val(),'background-color':$('#navbarlinkhoverbck').val()});
	},
	function () {
		$('.nav > li > a').each(function(){
			if($(this).parent().hasClass('open') === false || $(this).parent().hasClass('active') === false){
				$(this).css('color',$('#navbarlink').val());
			}
			if($(this).parent().hasClass('active') === true || $(this).parent().hasClass('open') === true){
				$('.navbar-inverse .nav .active > a').css({'color':$('#navbarlinkhover').val(),'background-color':$('#navbarlinkhoverbck').val()});
				$('.navbar-inverse .nav .open > a').css({'color':$('#navbarlinkhover').val(),'background-color':$('#navbarlinkhoverbck').val()});
			}
		});	
//		}
	});


	/************************ end color page of website **************************************/
//	center img in table
	if($('.alignImg').length>0){
		$('.alignImg').each(function(){
			$(this).parent().css('text-align','center');
		});
	}
	//Sort table
	pagin();
	//Init
	$('#msg').html('');
	//if page publication
	if(($('.accordionMedia')).length>0){
		accordionPublication();
//		action delete media
		delpublication();
	}else{
//		truncate description in table
		$('td.truncate').jTruncate({length: 100});
	}

	//Activate account again
	if($('#activateaccount').length>0){
		$('#activateaccount').click(function(){
			mail = $('#UserMail').val();
			$.ajax({
				url: '/members/mailactivate',
				type: 'POST', 
				data: 'mail='+mail, 
				dataType: 'Json',
				success: function(data) {
					if(data.statut === 'success'){
						$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
						setTimeout(window.location = "/",30000);
					} else{
						$('#msg').html("<span class='alert alert-error'>"+data.message+"</span>");
					}
				},
				error:function(XMLHttpRequest,textStatus, errorThrown) {
					alert(textStatus);
				}
			});
		})
	}

//	Menu
//	if home
	/************************* HOME PAGE *********************************/
	if($('#containhome').length > 0){
		$('div.homeRow').each(function( index ) {
			id = $(this).attr('id');
			if($('#'+id+'>.homeColonne').length == 2) $('#'+id+'>.homeColonne').addClass('span6');
			if($('#'+id+'>.homeColonne').length == 3) $('#'+id+'>.homeColonne').addClass('span4');
		});
	}
	if($('#HomepageAddForm').length > 0){
		$('#HomepageContent').ckeditor();
		$("#HomepageAddForm").submit( function(){
			return manage_homepage('#HomepageAddForm');
		});
	}
	/************************* END HOME PAGE *********************************/	

	//add project
	if($('#ProjectDescription').length > 0){
		$( '#ProjectDescription' ).ckeditor();
	}

	//Activation members
	$('.checkboxactivation').click(function(){
		idmember = $(this).attr('id');
		valuechck = $(this).is(':checked');
		$.ajax({
			url: '/members/activationpage',
			type: 'POST', 
			data: 'id='+idmember+'&value='+valuechck, 
			dataType: 'Json',
			success: function(data) {
				if(data.statut === 'success'){
					$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
				} else{
					$('#msg').html("<span class='alert alert-error'>"+data.message+"</span>");
				}
			},
			error:function(XMLHttpRequest,textStatus, errorThrown) {
				alert(textStatus);
			}
		});
	});
	//end activation
	
	$(document).ajaxStart(function() {
		if($('#fancybox-loading').length == 0){
			$('.navbar').append('<div id="progress" ><img src="/img/loaderPage.gif" alt="" />Loading...</div>');
			$('#progress').slideDown('slow');
		}
	});
	$(document).ajaxStop(function() {
		$('#progress').slideUp('slow',function(){
			$('#progress').remove();
		});
		if($('td.truncate').length > 0 && $('.actions > a.variousframe').length > 0)
		{
			$('td.truncate').jTruncate({length: 100});	
		}
	});
	/****************************************** TYPE USER ******************************************/
	if($("#sortable" ).length >0){
		sortTypeUser();
	}
	if($('a.editTypeUser').length >0){
		//Edit
		$('a.editTypeUser').click(function(){
			id = $(this).parent().attr('id');
			input = $('#inputTypeUSer_'+id);

			if($('#active').length > 0){
				if($(this).attr('id') == 'active'){
					input.attr('disabled','disabled');
					input.css('background-color','transparent');
					$('#editspan').remove();
					$(this).removeAttr('id');
				}else{
					idverif = $('#active').parent().attr('id');
					inputverif = $('#inputTypeUSer_'+idverif);
					inputverif.attr('disabled','disabled');
					inputverif.css('background-color','transparent');
					$('#editspan').remove();
					$('a.editTypeUser').each(function(){
						if($(this).attr('id') === 'active'){
							$(this).removeAttr('id');	
						}

					});
					$(this).attr('id','active');
					input.removeAttr('disabled');
					input.css('background-color','');
					input.after('<span id="editspan" style="cursor:pointer;"><i class="icon-ok"></i></span>');
					$("#editspan").bind('click', function (){ edit_typeuser(id); });
				}
			}else{
				$(this).attr('id','active');
				input.removeAttr('disabled');
				input.css('background-color','');
				input.after('<span id="editspan" style="cursor:pointer;"><i class="icon-ok"></i></span>');
				$("#editspan").bind('click', function (){ edit_typeuser(id); });
			}
			return false;
		});
		//end edit
	}
	if($('#TypeUserAddForm').length > 0){
		$('#addTypeUser').bind('click',function(){
//			e.preventDefault();
			var NameForm= $('#TypeUserAddForm');
			if($('#TypeUserName').val() == "") {alert('Please fill out this field'); return false;}
			$.ajax({
				url: NameForm.attr('action'),
				type: NameForm.attr('method'), 
				data: NameForm.serialize(), 
				dataType: 'Json',
				success: function(data) {
					if(afterValidate(data) === true){
						$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
						if($('label.help-inline')) $('label.help-inline').remove();
						$('#TypeUserName').html('');
						$('#TypeUserName').parent().attr('class','input text required');
						$('#sortable').load('type_users/ #sortable>li');
					} else{
						$('#msg').html("<span class='alert alert-error'>"+data.message+"</span>");
					}
				},
				error:function(XMLHttpRequest,textStatus, errorThrown) {
					alert(textStatus);
				}
			});
			return false;
		});
	}
	/****************************************** END TYPE USER ******************************************/
//	Lost Pswd
	$('#submitLostPwd').click(function(){
		var UserMailPswdLost = $('#UserMailPswdLost').val();
		if (UserMailPswdLost == ""){
			$('#msgLostPwd').html("<span class='alert alert-error'>Please, write your mail.</span>");
		}else{
			$('#msgLostPwd').html('');
			$.ajax({
				url: "/members/lostpswd",
				type: "POST", 
				data: "UserMailPswdLost="+UserMailPswdLost, 
				dataType: 'json',
				success: function(data) {
					if(data.statut != 'error'){
						$('#msgLostPwd').html("<span class='alert alert-success'>"+data.message+"</span>");
					} else{
						$('#msgLostPwd').html("<span class='alert alert-error'>"+data.message+"</span>");
						$("#UserMailPswdLost").val('');
					}
				},
				error:function(XMLHttpRequest,textStatus, errorThrown) {
					alert(textStatus);
				}
			});

		}return false;
	});

//	View media
	$(".various_img").fancybox({
		openEffect	: 'elastic',
		closeEffect	: 'elastic',
		helpers : {
			title : {type : 'over'},
			overlay : { closeClick: false }

		}
	});
//	action edit media
	$('.edit').click(function(){
		//good span
		$span = $(this).parent().parent().find('span');
		$span.css('display','none');
		$(this).parent().parent().append('<input type="text" value="'+$span.text()+'" />');

	});

//	iframe
	$(".variousframe").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		width		: '100%',
		height		: '100%',
		autoSize	: false,
		helpers     : {
			overlay : { closeClick: false }
		}
	});
	/************************************* PROJECT *****************************************/
//	test add project
	$('#div_add_object_btn').click(function() {
		$('#addProject').slideToggle('slow');
	});
	if($('div.projects ').length >0){
		AddClassProjectExternal();
	}
	//onclick edit toggle if open
	$('.editProject').click(function() {
		if($('#addProject').css('display') == 'block'){
			$('#addProject').slideToggle('slow');
		}
	});

	if($("#ProjectAddForm").length > 0){
		paramProject();
		//Add Project add input if checkbox checked
		$('#ProjectExternal').change(function(){
			if($(this).is(':checked') === true){
				$('#linkExt').append('<label for="ProjectLink" class="extProject">Link</label>'
						+'<input id="ProjectLink" type="text" class="extProject" name="data[Project][link]">');	
			}else{
				$('.extProject').remove();
			}
		});
		//Add Project display select if radio checked
		if($('#ProjectUserO').is(':checked') === false){
			$('#listDisplay').hide();}
		$('input[type=radio]').click(function(){
			if($('#ProjectUserO').is(':checked') === true){
				$('#listDisplay').show();
			}else{
				$('#listDisplay').hide();
			}
		});
//		action add project
		$("#ProjectAddForm").submit( function(){
			return manage_project('ProjectAddForm');
		});
	}

//	action edit project
	if($("#ProjectEditForm").length > 0){
		paramProject();
		//Edit Project add input if checkbox checked
		//init
		if($('.ProjectExternalEdit').is(':checked') === true){
			$('#linkExtEdit').show();
		}else $('#linkExtEdit').hide();

		$('.ProjectExternalEdit').change(function(){
			if($(this).is(':checked') === true){
				$('#linkExtEdit').show();
			}else{
				$('#ProjectLink').val('');
				$('#linkExtEdit').hide();
			}
		});
		//Add Project display select if radio checked
		if($('#ProjectUserOEdit').is(':checked') === false){
			$('#listDisplayEdit').hide();}
		$('input[type=radio]').click(function(){
			if($('#ProjectUserOEdit').is(':checked') === true){
				$('#listDisplayEdit').show();
			}else{
				$('#listDisplayEdit').hide();
			}
		});
		$("#ProjectEditForm").submit( function(){
			return manage_project('ProjectEditForm');
		});
	}
	/***************************************** END PROJECT ***************************************************/
	/*****************************************  USER ***************************************************/
//	Edit Password
	if($("#UserEditPwdForm").length > 0){
		$("#UserEditPwdForm").submit(function(){
			return manage_member('UserEditPwdForm');
		});
	}
//	action edit members
//	action add group
	if($("#UserEditForm").length > 0){
		$("#UserEditForm").submit(function(){
			return manage_member('UserEditForm');
		});
	}
//	action add members
	if($("#UserAddForm").length > 0){
		$("#UserAddForm").submit(function(){
			return manage_member('UserAddForm');
		});
	}
//	action add members admin
	if($("#UserAddForm").length > 0){
		$("#UserAddForm").submit(function(){
			return manage_member('UserAddForm');
		});
	}
	/*****************************************  END USER ***************************************************/
//	add project/medias/user
	$(".various").fancybox({
//		minWidth	: 650,
//		maxHeight	: 700,
		fitToView	: false,
		width		: 'auto',
		height		: 'auto',
//		width		: '70%',
//		height		: '70%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none',
		helpers     : {
			overlay : { closeClick: false }
		},
		afterShow: function() {
			/***********************PLUPLOAD PUBLICATIONS**********************************/
			if($("#MediaAddForm").length > 0 || $("#MediaEditForm").length > 0){
				var uploader =  new plupload.Uploader({
					runtimes : 'html5,flash',
					container : 'containerPlup',
					browse_button : 'browse',
//					drop_element : 'droparea',
					max_file_size : '200Mb',
					unique_names : true,
					multi_selection: false,
					resize : {width : 800, height : 600, quality : 90},
					filters : [
					           {title : "All Video Files", extensions : "avi,flv,mpg,mov,wmv,avchd,3gp,asf,m4v,mpeg,mpeg4,mpg4,mp4"},
					           {title : "All picture files", extensions : "jpg,jpeg,bmp,png,gif"},
					           {title : "All document files", extensions : "doc,docx,odt,pdf,txt"},
					           {title : "mp3", extensions : "mp3"}
					           ],
					           flash_swf_url : 'js/plupload/plupload.flash.swf'
////					        	   multipart : true
				});

				uploader.bind('FilesAdded',function(up,files){
					var filelist = $('#filelist');
					for(var i in files){
						var file = files[i];
						filelist.prepend('<div id="'+file.id+'" class = "file">'+file.name+' ('+plupload.formatSize(file.size)+')'
								+'<div class="progress progress-striped active"><div class="bar"></div></div></div>')
					}

				});//end FilesAdded
				
				//action add media
				$("#sendsubmitFormPub").click(function(event) {
					idNameForm = $(this).parent().parent().attr('id');
					NameForm = $("#"+idNameForm);
					event.preventDefault();
					if($('#filelist').html() == '' || $('.file').attr('id') === undefined) {
						if($('#MediaReference').val() != ''){
							if($('#filelist').html() == ''){
								datainfoplus = '&listfield=empty';
							}else datainfoplus='';
							$.ajax({
								url: NameForm.attr('action'),
								type: NameForm.attr('method'),
								data: NameForm.serialize()+''+datainfoplus, 
								dataType: 'json',
								success: function(data) {
									if(afterValidate(data) === true){
										$('.TitleProject').load('medias/ .TitleProject>div', function() {
											accordionPublication();
											delpublication();
//											center img in table
											if($('.alignImg').length > 0){
												$('.alignImg').each(function(){
													$(this).parent().css('text-align','center');
												});
											}
										});
										$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
										$.fancybox.close();
									} 
								},
								error:function(XMLHttpRequest,textStatus, errorThrown) {
									alert(textStatus);
								}
							});
						}else{
							alert('Please, complete Reference field');
						}
					}else{
						if($('#MediaReference').val() != ''){
							var MediaReference		= $('#MediaReference').val();
							var linkRef 			= $('#MediaLinkref').val();
							var MediaDateYear 		= $('#MediaDateYear option:selected').val();
							var MediaProjectId 		= $('#MediaProjectId').val(); 	
							var project_id 			= $("#MediaProjectId option:selected").val();
							
							if(idNameForm == 'MediaAddForm'){
								page = NameForm.attr('action');
							}else{
								page = NameForm.attr('action');
							}
							uploader.settings.url = page+'?project_id='+project_id+'&reference='+MediaReference+'&linkref='+linkRef+'&date='+MediaDateYear;
							uploader.start();
						}else{
							alert('Please, complete Reference field');
						}
					}return false;
				});
				uploader.init();
				$('#browse').click(function(e) {
					uploader.splice();
					if($('.file').length > 0) {	
						$('.file').remove();
						uploader.splice();
					}
				});

				$('.delone').bind('click',function(e){
					e.preventDefault();
					if(confirm('Do you want to delete this publication?')){
						$(this).parent().parent().slideUp('slow', function() {
							$('#filelist').html('');
						});
						
					}return false;
					});

				uploader.bind('UploadProgress',function(up, file){
					$('#'+file.id).find('.bar').css('width',file.percent+'%');
				});

				uploader.bind('Error', function(up, err) {
					alert(err.message);
//					$('#droparea').removeClass('hover');
					uploader.refresh();
				});


				uploader.bind('FileUploaded', function(up, file, response) {
					var json = $.parseJSON(response.response);
					$('.alert').remove();

					if(json.statut === true){
						$('.TitleProject').load('medias/ .TitleProject>div', function() {
							accordionPublication();
							delpublication();
							if($('.alignImg').length > 0){
								$('.alignImg').each(function(){
									$(this).parent().css('text-align','center');
								});
							}
						});
						if($('div#divextract').children().length == 0){
							$('#divextract').append('<a id="extracts" href="/medias/extract">'+
							'<img alt="Extract values" src="/img/extract.png"></a>');
						}

						$('#'+file.id).remove();
						parent.$('#msg').html("<span class='alert alert-success'>"+json.message+"</span>");
						$.fancybox.close();
					}else{
						onError(json);
						$('#msgAddMedia').html("<span class='alert alert-success'>"+json.message+"</span>");
						return false;
					}
				});//end FileUploaded 

				/***********************END PLUPLOAD PUBLICATIONS**********************************/

			}//AddMembers Form

			//edit Collab
			if($("#CollaboratorEditForm").length > 0){
				$("#CollaboratorEditForm").submit(function(){
					return add_collab('#CollaboratorEditForm');
				});
			}
			//add collaborators
			if($("#CollaboratorAddForm").length > 0){
				$("#CollaboratorAddForm").submit(function(){
					return add_collab('#CollaboratorAddForm');
				});	
			}
			//edit Media
			if($('#MediaEditForm').length > 0) {
				$("#sendEditPub").click(function(){
					return edit_media('MediaEditForm');
				});	
			}
		}//end AfterShow

	});	
});
