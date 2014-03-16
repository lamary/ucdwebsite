
/*****************************************ADD COLLAB**************************************************************************/
function add_collab(){
	var NameForm		= $('#CollaboratorAddForm');
	var CollabSurname 	= $('#CollaboratorSurname').val();
	var CollabName 		= $('#CollaboratorName').val();
	var CollabLinkProfil= $('#CollaboratorLink').val();

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
				if(afterValidate(data, data.statut) === true){
					$('#tabCollab').load('collaborators/index #tabCollab>tbody');
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
function add_member(){
	var NameForm		= $('#UserAddForm');
	var UserSurname 	= $('#UserSurname').val();
	var UserName 		= $('#UserName').val();
	var UserDescription = $('#UserDescription').val();
	var UserLinkProfil 	= $('#UserLinkProfil').val();

	if(UserSurname == '' || UserName == '') {
		alert('Fields Name and Surname must be completed');
	} 
	else {
		$.ajax({
			url: NameForm.attr('action'),
			type: NameForm.attr('method'), 
			data: NameForm.serialize(), 
			dataType: 'json',
			success: function(data) {
				if(afterValidate(data, data.statut) === true){
					$('#tabUser').load('users/index #tabUser>tbody');
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
/*****************************************END ADD MEMBER**************************************************************************/
/*****************************************ADD PROJECT**************************************************************************/
function manage_project(Name){
	var NameForm				= $('#'+Name);
	var ProjectGroupsId			= $('#ProjectGroupsId').val();
	var ProjectNameProject 		= $('#ProjectNameProject').val();
	var ProjectStartDateProject = $('#ProjectStartDateProject').val();
	var ProjectEndDateProject 	= $('#ProjectEndDateProject').val();
	var ProjectDescriptionProject= $('#ProjectDescriptionProject').val();
	$.ajax({
		url: NameForm.attr('action'),
		type: NameForm.attr('method'), 
		data: NameForm.serialize(), 
		dataType: 'json',
		success: function(data) {
			if(afterValidate(data, data.statut) === true){
//				window.location = "http://localhost/ucd_intranet2/projects";

				//If edit project
				if(Name == "ProjectEditForm")
				{ 	parent.$.fancybox.close();	
				parent.$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
				parent.$('#tab_group').load('projects #tab_group>tbody', function() {
					$('.truncate').jTruncate({length: 50});
					AddClassProjectExternal();
				});
				}

				//If add Project
				if(Name == "ProjectAddForm")
				{	
					$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
					$('.alert-info, .help-inline').remove();
					$('div').removeClass('error');
					document.forms.ProjectAddForm.reset();
					$('#addProject').slideToggle('slow');
					$('#tab_group').load('projects #tab_group>tbody', function() {
						$('.truncate').jTruncate({length: 50});
						AddClassProjectExternal();
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
/*****************************************ADD MEDIA**************************************************************************/
function manage_media(Name){
	var NameForm			= $('#'+Name);
	var MediaDescription	= $('#MediaDescription').val();
	var MediaName 			= $('#MediaName').val();
	var MediaSubmittedfile  = $('#MediaSubmittedfile').val();
	var MediaProjectId 		= $('#MediaProjectId').val();
	if(MediaSubmittedfile == ""){
		alert('Please,Select a publication.')
	}else{
		$.ajax({
			url: NameForm.attr('action'),
			type: NameForm.attr('method'), 
			data: NameForm.serialize(), 
			dataType: 'json',
			success: function(data) {
				if(afterValidate(data, data.statut) === true){
					//If edit project
//					if(Name == "ProjectEditForm")
//					{ 	parent.$.fancybox.close();	
//					parent.$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
//					parent.$('#tab_group').load('projects #tab_group>tbody', function() {
//					$('.truncate').jTruncate({length: 50});
//					AddClassProjectExternal();
//					});
//					}

					//If add Project
					if(Name == "MediaAddForm")
					{	
						$('#msg').html("<span class='alert alert-success'>"+data.message+"</span>");
						$.fancybox.close();
						$('.TitleProject').load('medias/ .TitleProject', function() {
							$( ".accordionMedia" ).accordion({
								collapsible: true,
								header: "h4",
								heightStyle: "content",
								active: false
							});	
						});
					}


				} 
			},
			error:function(XMLHttpRequest,textStatus, errorThrown) {
				alert(textStatus);
			}
		});
	}
	return false; 
}

/*****************************************END ADD MEDIA**************************************************************************/
/*****************************************VALIDATE AJAX FORM**************************************************************************/
function afterValidate(data, statut)  {

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
/*******************************************************************************************************************/
$(document).ready(function() {


	//Menu
	$( ".nav a" ).parent().removeClass('active');
	$( ".nav a" ).each(function( index ) {
		if($(this).attr('id') == $('#currentPage').val()){
			if($('#fullPage').val() == 'login'){
				$('ul#linksign').remove();
			}
			$(this).parent().addClass('active');
		}
	});
	//test add project
	$('#div_add_object_btn').click(function() {
		$('#addProject').slideToggle('slow');
	});

	AddClassProjectExternal();
	//View media
	$(".various_img").fancybox({
		openEffect	: 'elastic',
		closeEffect	: 'elastic',
		helpers : {
			title : {type : 'over'},
			overlay : { closeClick: false }

		}
	});
	//action edit media
	$('.edit').click(function(){
		//good span
		$span = $(this).parent().parent().find('span');
		$span.css('display','none');
		$(this).parent().parent().append('<input type="text" value="'+$span.text()+'" />');

	});
	//delete media
	//action delete media
	$('.del').bind('click',function(e){
		e.preventDefault();
		if(confirm("Are you sure you want to delete this media ?")){
			$.get('medias/delete',{action: 'delete',id:$(this).attr('id')});
			$(this).parent().parent().fadeOut('slow');
		}
		return false;
	});
	//truncate description in table
	$('.truncate').jTruncate({length: 50});
	//accordion view publication
	$( ".accordionMedia" ).accordion({
		collapsible: true,
		header: "h4",
		heightStyle: "content",
		active: false
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

	//iframe
	$(".variousframe").fancybox({
		maxWidth	: 800,
		maxHeight	: 600,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		helpers     : {
			overlay : { closeClick: false }
		}
	});

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
//	end action edit project
	//addpublication
//	if($("#MediaAddForm").length > 0){
////	$("#MediaAddForm").submit( function(){
////	return manage_media('MediaAddForm');
////	});
//	formdata = false;

//	if (window.FormData) {
//	formdata = new FormData();
////	document.getElementById("btn").style.display = "none";
//	}

//	$("#MediaAddForm").submit( function(){
//	var i = 0, len =  $( '#MediaSubmittedfile' ).length, img, reader, file;

////	for ( ; i < len; i++ ) {
////	file = $( '#MediaSubmittedfile' )[i];
////	if (formdata) {
////	formdata.append($('input[name^="submittedfile"]'), file);
////	}
////	}

////	file = $( '#MediaSubmittedfile' );
//	var fileInput = document.querySelector('#MediaSubmittedfile');
////	formdata.append("name", file.name);
////	formdata.append("size", file.size);
////	formdata.append("file", );
//	formdata.append('file', fileInput.files[0]);
////	jQuery.each($('#MediaSubmittedfile')[0].files, function(i, file) {
////	formdata.append( 'file', $( '#MediaSubmittedfile' )[0].files[0] );
////	});
//	if (formdata) {

//	var NameForm = $("#MediaAddForm");
//	var MediaDescription	= $('#MediaDescription').val();
//	var MediaName 			= $('#MediaName').val();
//	var MediaProjectId 		= $('#MediaProjectId').val(); 					

//	$.ajax({
//	url: NameForm.attr('action'),
//	type: "POST",
//	data: NameForm.serialize()+formdata,
////	dataType: 'json',
//	processData: false,
//	contentType: false,
//	success: function (res) {
//	//document.getElementById("response").innerHTML = res;
//	alert("succes = "+res);
//	}
//	});
//	}
//	});
//	}
	//add project/medias/user
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
//		type:'ajax',
		afterShow: function() {
			/***********************PLUPLOAD**********************************/
			if($("#MediaAddForm").length > 0){
//				if (window.FormData) {
//				formdata = new FormData();
//				}
//				$("#MediaAddForm").submit( function(){
//				$("#sendMedia").click( function(){

//				var file = $('#MediaSubmittedfile').get(0).files[0];

//				formdata.append("name", file.name);
//				formdata.append("size", file.size);
//				formdata.append("file", fileData);

//				var NameForm 			= $("#MediaAddForm");
//				var MediaDescription	= $('#MediaDescription').val();
//				var MediaName 			= $('#MediaName').val();
//				var MediaProjectId 		= $('#MediaProjectId').val(); 
//				alert(NameForm.serialize());

//				$.ajax({
//				url: NameForm.attr('action'),
//				data: NameForm.serialize(),
//				secureuri: false,
//				fileElementId: 'MediaSubmittedfile',
//				dataType: 'json',
//				processData: false,
//				contentType: false,
//				success: function (data,statut) {
				//document.getElementById("response").innerHTML = res;
//				alert("succes = "+data.statut);
//				},
//				error:function(XMLHttpRequest,textStatus, errorThrown) {
//				alert(textStatus);
//				return false;
//				}
//				});
//				});
				var uploader =  new plupload.Uploader({
					runtimes : 'html5,flash',
					container : 'containerPlup',
					browse_button : 'browse',
//					drop_element : 'droparea',
					max_file_size : '400Mb',
					unique_names : true,
					multi_selection: false,
					//resize : {width : 320, height : 240, quality : 90},
////					filters : [
////					{title : "All Video Files", extensions : "avi,flv,mpg,mov,wmv,avchd,3gp,asf,m4v,mpeg,mpeg4,mpg4,mp4"},
////					],
//					flash_swf_url : 'js/plupload/plupload.flash.swf',
////					multipart : true
				});

//				detect drag and drop browser
//				uploader.bind('Init', function(up, params){
//				if(params.runtime != 'html5'){
//				$('#droparea').css('border','none').css('height','auto').find('p,span').remove();
//				}
//				});
				//action add media
				$("#MediaAddForm").bind("submit", function(event) {
					event.preventDefault();
//					if($('#filelist').html() == '') {
//					alert('Add publications');
//					}else{
					var NameForm = $("#MediaAddForm");
//					var MediaDescription	= $('#MediaDescription').val();
//					var MediaName 			= $('#MediaName').val();
//					var MediaProjectId 		= $('#MediaProjectId').val(); 					
//					url: NameForm.attr('action'),

//					data: NameForm.serialize()+formdata,
//					id_project = $("#MediaProjectId option:selected").val();
//					uploader.settings.url = 'medias/add?id_project='+id_project;
					uploader.settings.url = 'medias/add?'+NameForm.serialize();
					uploader.start();
//					}
				});
				uploader.init();

				$('#browse').click(function(e) {
					if($('.file').length > 0) {	
						$('.file').remove();
						uploader.splice()
					}
				});

				uploader.bind('FilesAdded',function(up,files){
					var filelist = $('#filelist');
					for(var i in files){
						var file = files[i];
						filelist.prepend('<div id="'+file.id+'" class = "file">'+file.name+' ('+plupload.formatSize(file.size)+')'
								+'<div class="progress progress-striped active"><div class="bar"></div></div></div>')
					}
//					filelist.prepend('<div id="'+file.id+'" class = "file">'+file.name+' ('+plupload.formatSize(file.size)+')'
//					+'<div class="progress progress-striped active"><div class="bar"></div></div>'+
//					'<div class="delMedia'+file.id+' ui-icon ui-icon-closethick"></div></div>')
//					}

					//on click del image
//					$('.delMedia').click(function() {
//					$('#'+file.id).remove();
//					});
//					$('#droparea').removeClass('hover');
//					uploader.start();
//					up.refresh();
				});//end FilesAdded

				uploader.bind('UploadProgress',function(up, file){
					$('#'+file.id).find('.bar').css('width',file.percent+'%');
				});

				uploader.bind('Error', function(up, err) {
					alert(err.message);
//					$('#droparea').removeClass('hover');
					uploader.refresh();
				});

//				uplaod finish
//				var total_upload_files = 0;
//				var statutFalse  = 0;
//				var msgTrue		 = new Array();
//				var msgFalse	 = new Array();
//				var statutrue 	 = 0;
				uploader.bind('FileUploaded', function(up, file, response) {
					alert(response.response);
					var json = $.parseJSON(response.response);
					$('.alert').remove();
					//del publication in popup
//					$('.delMedia'+file.id).click(function() {
//					$('#'+file.id).remove();
//					});
					//if tab true ++
//					if(json.statut === true){
//					statutrue++;
//					msgTrue.push(json.message);
//					$('#'+file.id).remove();
//					}
					//if tab false ++
//					else{
//					statutFalse++;
//					msgFalse.push(json.message);
//					}

//					total_upload_files--;
//					alert(total_upload_files);
//					if(total_upload_files == 0){
					//if statut True > 0
//					if(statutrue > 0 && statutFalse == 0){
//					for (var i = 0; i < msgTrue.length; i++) {
//					parent.$('#msg').append("<div class='alert alert-success'>"+msgTrue[i]+"</div>");
//					}
//					$.fancybox.close();
//					$('.TitleProject').load('medias/ .TitleProject', function() {
//					$( ".accordionMedia" ).accordion({
//					collapsible: true,
//					header: "h4",
//					heightStyle: "content",
//					active: false
//					});	
//					});
					//remove msg after 3s
//					setTimeout(function(){$('.alert').remove();}, 3000); 
//					}
					//if there are true AND false
//					if(statutrue > 0 && statutFalse > 0){
//					for (var i = 0; i < msgFalse.length; i++) {
//					$('#msgAddMedia').append("<div class='alert alert-success'>"+msgTrue[i]+"</div>");
//					$('#msgAddMedia').append("<div class='alert alert-error'>"+msgFalse[i]+"</div>");
//					setTimeout(function(){$('.file').remove();}, 5000); 
//					}
//					}
					//if statut False > 0
//					if(statutFalse > 0 && statutrue == 0){
//					for (var i = 0; i < msgFalse.length; i++) {
//					$('#msgAddMedia').append("<div class='alert alert-error'>"+msgFalse[i]+"</div>");
//					setTimeout(function(){$('.file').remove();}, 5000); 
//					}
//					}
//					}

//					//Init
//					$('#msgAddMedia>span').remove();
//					$('.alert-info').remove();

////					if(json.statut === true){
////					$('.TitleProject').load('medias/ .TitleProject', function() {
////					$( ".accordionMedia" ).accordion({
////					collapsible: true,
////					header: "h4",
////					heightStyle: "content",
////					active: false
////					});	
////					});
////					$('#'+file.id).remove();
////					$('#msgAddMedia').html("<span class='alert alert-success'>"+json.message+"</span>");
////					$.fancybox.close();
////					}else{
////					onError(json);
////					return false;
////					}
				});//end FileUploaded 
//				uploader.bind('StateChanged',function(up, file, response){
//				total_upload_files = uploader.files.length;
//				});
				/***********************END PLUPLOAD**********************************/
//				$('#droparea').bind({
//				dragover:function(e){
//				$(this).addClass('hover');
//				},
//				dragleave:function(e){
//				$(this).removeClass('hover');
//				}
//				});	
			}//AddMembers Form
			$('.truncate').jTruncate({length: 200});
			//action add group
			$("#UserAddForm").submit(function(){
				return add_member();
			});
			//add collaborators
			$("#CollaboratorAddForm").submit(function(){
				return add_collab();
			});	

		}//end AfterShow

	});


	//project view
	$( "#accordion" ).accordion({
		collapsible: true,
		header: "h4",
		heightStyle: "content",
		active: 2,
		//Init padding
		beforeActivate: function( event, ui ) {
			$("div.caption").each(function(){
				var imgChil = $(this).children('img');
				imgChil.css('padding-top', "");
			});	
		},
		//put padding for small picture
		activate:function( event, ui ) {
			if($('div.caption').css('display') == 'block'){
				$("div.caption").each(function(){
					var imgChil = $(this).children('img');
					if(imgChil.css('height') < '94px'){
						imgChil.css('padding-top', (94-imgChil.height())/2 + "px");
					}
				});	
				for(var i=0; i<$('.divAcco').length;i++){
					var dateImg = $("#mainImg"+i).val();
					$(".popupImg"+i).fancybox({
						prevEffect	: 'none',
						nextEffect	: 'none',
						helpers	: {
							title	: {
								type: 'over'
							},
							thumbs	: {
								width	: 50,
								height	: 50
							}
						}
					});
				}

			}
		}
	});
//	$(".mainImg").each(function(){



//	alert(dateImg);

//	});


	//ZOOM
//	$("div.zoomHover").click(function(){
//	$(this).children("img.mediaLarge").stop().fadeTo(400, 1);
////	$(this).children('img.mediaLarge').css('display','block');
//	},function(){
//	$(this).find('img.mediaLarge').css('display','none');
//	});

	//Caption
	$("div.caption").hover(function(){
		$(this).children('span').css("width", $(this).width()- 20 + "px");
		// Fade in the child "span"
		$(this).children("span").stop().fadeTo(400, 1);
		//Zomm

	}, function(){
		// Once you mouse off, fade it out
		$(this).children("span").stop().delay(300).fadeOut(400);
	});		
});
