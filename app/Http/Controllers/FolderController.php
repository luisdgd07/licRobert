<?php

namespace App\Http\Controllers;

use Storage;
use Auth;
use App\Share;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Http\Requests\FolderRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FolderController extends Controller
{
    protected $ubicacion;

    function __construct()
    {
        $this->ubicacion = Config::get('fm.files_dir');
        $this->middleware('admin', ['except' => ['listar','getFolders']]);
    }
    /**
     * Se lista todos los archivos de una carpeta 
     *
     * @param  Request $request 
     * @return Response
     */
    public function listar()
    {

        if (Input::has('base'))
        {
            $working_dir = Input::get('base');
            $base = $this->ubicacion . Input::get('base') . "/";
            $this->ubicacion = $base;
        } else
        {
            $working_dir = "/";
            $base = $this->ubicacion;
        }

        $user = User::lists('name','id')->except(Auth::id());
        $user->prepend('Ningun Usuario','0');
            	Log::info($base);

        return view('home')
        ->with('users', $user)
        ->with('base', $base)
        ->with('working_dir', $working_dir);
    }

    /**
     * Get list of folders as json to populate treeview
     *
     * @return mixed
     */
    public function getFolders(Request $request)
    {
    	Log::info(Input::has('base'));
        if (Input::has('base'))
        {
            $working_dir = Input::get('base');
        } else{
            $working_dir = '/';
        }
	Log::info('entra para leer los archivo');
        $user = $request->user();
        //se busca si es o no administrador
        if ($user->isAdmin())
        {
        	Log::info('entra para leer los archivo como admin');
            $archivos = Storage::Files($working_dir);
            $directorios = Storage::Directories($working_dir);
        }else{
            $directorios = $user->shares()->get()->pluck('route')->toArray();
            $permitidoVer = 0;
            foreach ($directorios as $key => $directorio) {
                if(substr($working_dir, 0, strlen($directorio)) === $directorio){
                    $permitidoVer = 1;
                }
            }
            if($permitidoVer == 1){
                $archivos = Storage::Files($working_dir);
                $directorios = Storage::Directories($working_dir);
            } else {
                $archivos = [];
                $directorios = $user->shares()->get()->pluck('route')->toArray();
            }

        }
        if(Input::has('orden')){
            $archivosConj=[];
            foreach ($archivos as $key => $archivo) {
                array_push($archivosConj,[$archivo,date('d / m / Y', Storage::lastModified($archivo))]);
            }
            if(Input::get('orden')=='nombre' && Input::get('direccion')=='asc'){
                arsort($directorios);
                arsort($archivos);
            }elseif(Input::get('orden')=='nombre' && Input::get('direccion')=='desc'){
                asort($directorios);
                asort($archivos);
            }elseif(Input::get('orden')=='fecha' && Input::get('direccion')=='asc'){
                usort($archivosConj, function($a, $b) {
                    return $b[1] - $a[1];
                });
                $archivos = array_column($archivosConj, 0);
            }elseif(Input::get('orden')=='fecha' && Input::get('direccion')=='desc'){
                usort($archivosConj, function($a, $b) {
                    return $a[1] - $b[1];
                });
                $archivos = array_column($archivosConj, 0);
            }
        }
        return view("tree")
        ->with('archivos', $archivos)
        ->with('directorios', $directorios);        
    }

    /**
     * Get list of folders as json to populate treeview
     *
     * @return mixed
     */
    public function shareFolder(Request $request)
    {
        $nombre_carpeta = $request->input('share_folder');
        if($request->input('user')=="0"){
            // si es que se solicita borrar toda relacion a una carpeta
            if (File::exists($this->ubicacion . $nombre_carpeta))
            {
                $name = Share::where('route','LIKE', $nombre_carpeta.'%')->get();
                foreach ($name as $key => $encontrado) {
                    $encontrado->users()->detach();
                }
                $name = Share::where('route','LIKE', $nombre_carpeta.'%')->delete();
                return "OK";
                exit;
            } else
            {
                return "Este archivo no puede compartirse";
                exit;
            }
        }else{
            //si es que se solicita crear una relación para una carpeta
            if (File::exists($this->ubicacion . $nombre_carpeta))
            {
                $share = Share::create($request->all());
                $share->users()->attach($request->input('user'));
                $share->route = $nombre_carpeta;
                $share->save();
                return "OK";
                exit;
            } else
            {
                return "Este archivo no puede compartirse";
                exit;
            }
        }
    }



	 /**
     * Se crea una nueva carpeta dentro del sistema de archivo seleccionado.
     *
     * @param  FolderRequest $request 
     * @return Response
     */

    public function createFolder(Request $request)
    {
        $base = $this->ubicacion;
        $working_dir = $request->input('working_dir');
        $nombre_carpeta = Str::slug($request->input('nombre'));
        if (!File::exists($base .$working_dir. "/" . $nombre_carpeta))
        {
            File::makeDirectory($base . $working_dir . "/" . $nombre_carpeta, $mode = 0777, true, true);
            return "OK";
            exit;
        } else
        {
            return "Una carpeta con este nombre ya existe";
            exit;
        }
    }
}