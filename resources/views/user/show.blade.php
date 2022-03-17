@extends('app')

@section('content')
<script type="text/javascript">

	$(document).ready(function () {
		//cargar usuarios
        $.ajax({
            type: "GET",
            dataType: "text",
            url: "list-user",
            cache: false
        }).done(function (data) {
            $("#usertree").html(data);
        });

});

	function loadUsers(){
		$.ajax({
            type: "GET",
            dataType: "text",
            url: "list-user",
            cache: false
        }).done(function (data) {
            $("#usertree").html(data);
        });		
	}

function trashuser(x,y) {
        bootbox.confirm("¿Esta seguro de querer eliminar al usuario "+y+" ?", function (result) {
            if (result == true) {
                $.ajax({
                    type: "GET",
                    dataType: "text",
                    url: "delete-user",
                    data: {
                        id: x,
                    },
                    cache: false
                }).done(function (data) {
                    if (data != "OK") {
                        notify(data);
                    } else {
                        loadUsers();
                    }
                });
            }
        });
    }
</script>
	<div class="row">
	</div>
	
	<div class="row">
		<!-- se dibuja el arbol de directorio -->
		@if(Auth::user()->isAdmin())

		<div id="usertree">
		
		</div>
		@endif
		<!-- se dibuja el arbol de directorio -->
	</div>


	

@endsection
