
<div class="col-md-10 col-md-offset-2">
    <ul class="lista-archivo list-group">
        <li class="list-group-item titulo-arbol clearfix"><div class="col-xs-4">Nombre</div> <div class="col-xs-4 text-right">Ultima Modificación</div></li>
        <li class="list-group-item clearfix tipo-retroceso"><a onclick="clickFolder()"><span class="glyphicon glyphicon-arrow-left"></span> &nbsp;Subir Nivel</a></li>
    @foreach ($directorios as $key => $directorio)
        <li class="list-group-item clearfix tipo-directorio"><a id="folder_{{ $key }}" data-id="{{ $directorio }}" onclick="clickFolder('folder_{{ $key }}', 0)" class="col-xs-4 pointer"><span class="glyphicon glyphicon-folder-open">&nbsp;</span>{!! basename($directorio) !!}</a><span class="col-xs-4"></span><span class="col-xs-4">
            @if(Auth::user()->isAdmin())
            <a href="javascript:trash('{{ basename($directorio) }}')">
                <i class="glyphicon glyphicon-trash"></i>
            </a>&nbsp;<a data-toggle="modal"  data-target="#shareModal" onclick="clickShareFolder('{{ $directorio}}')" href="#">
                <i class="glyphicon glyphicon-eye-open"></i>
            </a>
            @endif
        </span></li>
    @endforeach
    @foreach ($archivos as $archivo)
        <li class="list-group-item clearfix tipo-archivo"><div class="col-xs-4" onclick="descargar('{{ basename($archivo) }}')"><span class="glyphicon glyphicon-file">&nbsp;</span>{{ basename($archivo) }}</div><span class="col-xs-4 text-right">{{
         date('d / m / Y', Storage::lastModified($archivo)) }}</span><span class="col-xs-4">
            @if(Auth::user()->isAdmin())
            <a href="javascript:trash('{{ basename($archivo) }}')">
                <i class="glyphicon glyphicon-trash"></i>
            </a></span> </li>
            @endif
    @endforeach
    
    </ul>
</div>