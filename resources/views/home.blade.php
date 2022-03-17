@extends('app')

@section('content')
<script type="text/javascript">

	$(document).ready(function () {
		//cargar carpeta
        $.ajax({
            type: "GET",
            dataType: "text",
            url: "folders",
            data: "base={{ $working_dir }}",
            cache: false
        }).done(function (data) {
            $("#tree1").html(data);
        });

        ////////////////////////////////////////
        // Subida de archivos a carpeta actual
        $("#fileuploader").uploadFile({
			url:"/archivo/upload",
			fileName:"file_to_upload[]",
			dragDrop: true,
			uploadStr: "Cargar",
			multiple: true,
			formData: {working_dir: function(){
				return $(".working_dir").val();
			}},
			afterUploadAll:function(files,data,xhr,pd)
			{
				$("#uploadModal").modal('hide');
				loadFiles();
				var notificaciones = "";
				files.responses.forEach(function(currentValue){
					notificaciones = notificaciones + "<br>" + currentValue
				})
				notify(notificaciones);
			},
		});
        // $("#upload-btn").click(function () {
		// 	var options = {
		// 		beforeSubmit:  showRequest,
		// 		success:       showResponse
		// 	};
		// 	function showRequest(formData, jqForm, options) {
		// 		$("#upload-btn").html('<i class="glyphicon glyphicon-refresh glyphicon-spin"></i> Cargando');
		// 		return true;
		// 	}
		// 	function showResponse(responseText, statusText, xhr, $form)  {
		// 		$("#uploadModal").modal('hide');
		// 		$("#upload-btn").html('Subir Archivo');
		// 		if (responseText != "OK"){
		// 			notify(responseText);
		// 		}
		// 		$("#file_to_upload").val('');
		// 		loadFiles();
		// 	}
		// 	$("#uploadForm").ajaxSubmit(options);
		// 	return false;
		// });
        // Subida de archivos a carpeta actual
		////////////////////////////////////////

		////////////////////////////////////////
        // Creacion de carpeta en carpeta actual
		$("#create-btn").click(function () {
			var options = {
				beforeSubmit:  showRequest,
				success:       showResponse
			};
			function showRequest(formData, jqForm, options) {
				$("#create-btn").html('<i class="glyphicon glyphicon-refresh glyphicon-spin"></i> Cargando');
				return true;
			}
			function showResponse(responseText, statusText, xhr, $form)  {
				$("#createFolderModal").modal('hide');
				$("#create-btn").html('Crear Carpeta');
				if (responseText != "OK"){
					notify(responseText);
				}
				$("#nombre-folder").val('');
				loadFiles();
			}
			$("#createFolderForm").ajaxSubmit(options);
			return false;
		});
	    // Creacion de carpeta en carpeta actual	
		////////////////////////////////////////

		////////////////////////////////////////
	    // Compartir la carpeta seleccionada
	    $("#compartir-btn").click(function () {
			var options = {
				beforeSubmit:  showRequest,
				success:       showResponse
			};
			function showRequest(formData, jqForm, options) {
				$("#create-btn").html('<i class="glyphicon glyphicon-refresh glyphicon-spin"></i> Cargando');
				return true;
			}
			function showResponse(responseText, statusText, xhr, $form)  {
				$("#shareModal").modal('hide');
				$("#share-btn").html('Compartir');
				if (responseText != "OK"){
					notify(responseText);
				}
				$("#nombre-folder").val('');
				loadFiles();
			}
			$("#shareForm").ajaxSubmit(options);
			return false;
		});
	    // Compartir la carpeta seleccionada
	    ////////////////////////////////////////

    	////////////////////////////////////////
	    // Ordenar la carpeta por nombre
	    $(".orden-nombre").click(function () {
	    	$('.orden-nombre').find('i').toggleClass('glyphicon-chevron-up').toggleClass('glyphicon-chevron-down');
	    	loadFiles('nombre', $('.orden-nombre').data('orden'));
	    	if($('.orden-nombre').data('orden')=='asc'){
	    		$('.orden-nombre').data('orden','desc')
	    	}else{
	    		$('.orden-nombre').data('orden','asc')
	    	}
		});
	    // Ordenar la carpeta por nombre
	    ////////////////////////////////////////


	    ////////////////////////////////////////
	    // Ordenar la carpeta por fecha
	    $(".orden-fecha").click(function () {
	    	$('.orden-fecha').find('i').toggleClass('glyphicon-chevron-up').toggleClass('glyphicon-chevron-down');
	    	loadFiles('fecha', $('.orden-fecha').data('orden'));
	    	if($('.orden-fecha').data('orden')=='asc'){
	    		$('.orden-fecha').data('orden','desc')
	    	}else{
	    		$('.orden-fecha').data('orden','asc')
	    	}
		});
	    // Ordenar la carpeta por fecha
	    ////////////////////////////////////////


    	$(window).keydown(function(event){
	    if(event.keyCode == 13) {
	      event.preventDefault();
	      return false;
	    }
  		});
	});

	function loadFiles(x,y) {
	    $.ajax({
	        type: "GET",
	        dataType: "html",
	        url: "folders",
	        data: {
	            base: $(".working_dir").val(),
	            orden: x,
	            direccion: y
	        },
	        cache: false
	    }).done(function (data) {
	        $("#tree1").html(data);
	    });
	}

	function resetOrden(){
		resetOrdenNombre();
		resetOrdenFecha();
		return 0;
	}
	function resetOrdenNombre(){
		if($('.orden-nombre').find('i').hasClass('glyphicon-chevron-up')){
			$('.orden-nombre').find('i').toggleClass('glyphicon-chevron-up').toggleClass('glyphicon-chevron-down');
			$('.orden-nombre').data('orden','asc')
		}
		return 0;
	}

	function resetOrdenFecha(){
		if($('.orden-fecha').find('i').hasClass('glyphicon-chevron-up')){
			$('.orden-fecha').find('i').toggleClass('glyphicon-chevron-up').toggleClass('glyphicon-chevron-down');
			$('.orden-fecha').data('orden','asc')
		}
		return 0;
	}

	function trash(x) {
        bootbox.confirm("¿Esta seguro de querer eliminar el archivo o carpeta?", function (result) {
            if (result == true) {
                $.ajax({
                    type: "GET",
                    dataType: "text",
                    url: "delete",
                    data: {
                        base: $(".working_dir").val(),
                        items: x
                    },
                    cache: false
                }).done(function (data) {
                    if (data != "OK") {
                        notify(data);
                    } else {
                        loadFiles();
                    }
                });
            }
        });
    }


	function clickRoot() {
        $(".working_dir").val('/');
        loadFiles();
    }

    function clickBack() {
        $(".working_dir").val('{{$working_dir.".."}}');
        loadFiles();
    }

	function clickFolder(x, y) {
		$(".working_dir").val($('#' + x).data('id'));
		resetOrden();
		loadFiles();
    }

    function clickShareFolder(x){
    	$(".share_folder").val(x);
    }

	function notify(x) {
		bootbox.alert(x);
	}


    function descargar(x) {
        location.href = "download?"
            + "dir="
            + $(".working_dir").val()
            + "&file="
            + x;
    }

</script>
	<div class="row">
		<div class="col-md-10 col-md-offset-2 nav-funciones">
			@if(Auth::user()->isAdmin())
			<div class="button-funciones">
				<p>&nbsp;</p>
				<a data-toggle="modal" data-target="#uploadModal" href="#" alt="Subir archivo"><img src="{{asset('img/icon/subir.png')}}"></a>
				<a data-toggle="modal"  data-target="#createFolderModal" href="#"><img src="{{asset('img/icon/nuevacarpeta.png')}}"></a>
			</div>
			@endif
			<div class="button-orden">
				<span class="orden-titulo">Ordenar por</span>
				{!! Form::open(array('url' => '/ordenar-nombre', 'role' => 'form', 'name' => 'ordenarNombreForm','id' => 'ordenarNombreForm', 'method' => 'post')) !!}
						{!! Form::hidden('working_dir', $working_dir, ['class' => 'working_dir']) !!}
				</form>
				{!! Form::open(array('url' => '/ordenar-fecha', 'role' => 'form', 'name' => 'ordenarFechaForm','id' => 'ordenarFechaForm', 'method' => 'post')) !!}
						{!! Form::hidden('working_dir', $working_dir, ['class' => 'working_dir']) !!}
				</form>
				<a href="#" class="orden-nombre" data-orden="asc"><i class="glyphicon glyphicon-chevron-down"></i><span>Nombre</span></a>
				<a href="#" class="orden-fecha" data-orden="asc"><i class="glyphicon glyphicon-chevron-down"></i><span >Fecha</span></a>
			</div>
		</div>
	</div>
	
	<div class="row">
		<!-- se dibuja el arbol de directorio -->
		<div id="tree1">
		</div>
		<!-- se dibuja el arbol de directorio -->
	</div>

		<!-- Modal de Creacion de carpeta -->
		<div class="modal fade" id="createFolderModal"
		tabindex="-1" role="dialog"
		aria-labelledby="createFolderLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" 
						data-dismiss="modal" 
						aria-label="Close">
						<span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" 
						id="favoritesModalLabel">Crea una carpeta</h4>
					</div>
					{!! Form::open(array('url' => '/create-folder', 'role' => 'form', 'name' => 'createFolderForm','id' => 'createFolderForm', 'method' => 'POST')) !!}
						<div class="modal-body">
							<p>
								Por favor, introduce el nombre de la
								<b><span id="fav-title">carpeta</span></b> 
								que desee crear.
							</p>
							<div class="row">
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<div class="form-group">
									<label class="col-md-4 control-label">Nombre</label>
									<div class="col-md-6">
										<input type="text" class="form-control" name="nombre" id="nombre-folder">
									</div>
									{!! Form::hidden('working_dir', $working_dir, ['class' => 'working_dir']) !!}
								</div>
							</div>
						</div>
					</form>
					<div class="modal-footer">
						<button type="button" 
						class="btn btn-default" 
						data-dismiss="modal">Cancelar</button>
						<span>
							<button type="submit" class="btn btn-primary" id="create-btn">
								Crear Carpeta
							</button>
						</span>
					</div>
				</div>
			</div>
	</div>
	<!-- Modal de Creacion de carpeta -->


	<!-- Modal de subida de archivos -->
	<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Sube un Archivo</h4>
					</div>
					<div class="modal-body">
						<div id="fileuploader">Cargar</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal de subida de archivos-->


	<!-- Modal de compartir archivos -->
	<div class="modal fade" id="shareModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="myModalLabel">Compartir</h4>
					</div>
					{!! Form::open(array('url' => '/share-folder', 'role' => 'form', 'name' => 'shareForm','id' => 'shareForm', 'method' => 'post')) !!}
					<div class="modal-body">
						<div class="form-group" id="attachment">
							{!! Form::label('file_to_share', 'Selecciona el usuario con quien compartir', array('class' => 'control-label')); !!}
							<div class="controls">
								<div class="form-group">
									<label for="category_id" class="control-label">Usuario</label>
									{!! Form::select('user', $users, null, array( 'class' => 'form-control')) !!}
								</div>
								<div class="form-group">
									<label for="share_folder" class="control-label">Carpeta a compartir</label><br>
									<div class="input-group">
									<span class="input-group-addon">/</span><input type="text" class="form-control share_folder" name="share_folder" /></div>
								</div>
							</div>
						</div>
						{!! Form::hidden('working_dir', $working_dir, ['class' => 'working_dir']) !!}
					</div>
					</form>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
						<button type="submit" class="btn btn-primary" id="compartir-btn">Compartir</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Modal de compartir archivos-->

@endsection
