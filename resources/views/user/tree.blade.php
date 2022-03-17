	<div class="col-md-10 col-md-offset-2">
	    <ul class="lista-archivo list-group">
	        <li class="list-group-item titulo-arbol clearfix"><div class="col-xs-4">Nombre</div></li>
		    @foreach ($users as $key => $user)
		        <li class="list-group-item clearfix tipo-directorio"><a  data-id="{{ $user->id }}" class="col-xs-4 pointer"><span class="glyphicon glyphicon-user">&nbsp;</span>{!! $user->name !!}</a><span class="col-xs-4"></span><span class="col-xs-4">
		            <a href="javascript:trashuser('{{ $user->id }}','{{ $user->name }}')">
		                <i class="glyphicon glyphicon-trash"></i>
		            </a>
		        	</span>
		        </li>
		    @endforeach
	    </ul>
	</div>