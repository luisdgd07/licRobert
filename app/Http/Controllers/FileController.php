<?php

namespace App\Http\Controllers;

use Storage;
use App\Share;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileController extends Controller
{
    protected $ubicacion;
    protected $tipos_permitidos;

    function __construct()
    {
        $this->tipos_permitidos = Config::get('fm.tipos_permitidos');
        $this->ubicacion = Config::get('fm.files_dir');
        $this->middleware('admin', ['except' => ['download']]);
    }

    /**
     * Upload an image/file and (for images) create thumbnail
     *
     * @param UploadRequest $request
     * @return string
     */
    public function upload()
    {
        Log::info('entrando');
        if ( ! Input::hasFile('file_to_upload'))
        {
            Log::info('sin archivo');
            return "Sin archivo seleccionado";
            exit;
        }

        $file = Input::file("file_to_upload");
            Log::info('iterando');
            $working_dir = Input::get('working_dir');
            $destinationPath = $this->ubicacion;

            $extension = $file->getClientOriginalExtension();

            if(!empty($this->tipos_permitidos["Files"]) && !in_array($extension, $this->tipos_permitidos["Files"]))
            {
                return "El tipo de archivos no se permite!";
                exit;
            }

            if (strlen($working_dir) > 1)
            {
                $destinationPath .= $working_dir . "/";
            }

            $filename = $file->getClientOriginalName();

            $new_filename = Str::slug(str_replace($extension, '', $filename)) . "." . $extension;

            if (File::exists($destinationPath . $new_filename))
            {
                return "Un archivo con este nombre ya existe!";
                exit;
            }

            $file->move($destinationPath, $new_filename);

        return "OK";
    }

     /**
     * Descarga un archivo
     *
     * @return mixed
     */
    public function download()
    {
        $file_to_download = Input::get('file');
        $dir = Input::get('dir');
        return Response::download($this->ubicacion
            .  $dir
            . "/"
            . $file_to_download);
    }


     /**
     * Borra los archivos
     *
     * @return mixed
     */
    public function getDelete()
    {
        $to_delete = Input::get('items');
        $base = Input::get("base");
        if ($base != "/")
        {
            $name = Share::where('route','LIKE', $to_delete.'%')->get();
            foreach ($name as $key => $encontrado) {
                $encontrado->users()->detach();
            }
            $name = Share::where('route','LIKE', $to_delete.'%')->delete();
            if (File::isDirectory($this->ubicacion . $base . "/" . $to_delete))
            {
                // se cerciora que el directorio se encuentre vacio
                if (sizeof(File::files($this->ubicacion . $base . "/" . $to_delete)) == 0)
                {
                    Share::where('route',$this->ubicacion . $base . "/" . $to_delete)->delete();
                    File::deleteDirectory($this->ubicacion . $base . "/" . $to_delete);
                    return "OK";
                } else
                {
                     Share::where('route',$this->ubicacion . $base . "/" . $to_delete)->delete();
                    File::deleteDirectory($this->ubicacion . $base . "/" . $to_delete);
                    return "OK";
                }
            } else
            {
                if (File::exists($this->ubicacion . $base . "/" . $to_delete))
                {
                    File::delete($this->ubicacion . $base . "/" . $to_delete);
                    return "OK";
                } else {
                    return $this->ubicacion . $base . "/" . $to_delete
                        . " no encontrado";
                }
            }
        } else
        {
            $file_name = $this->ubicacion . $to_delete;
            $name = Share::where('route','LIKE', $file_name.'%')->get();
            foreach ($name as $key => $encontrado) {
                $encontrado->users()->detach();
            }
            $name = Share::where('route','LIKE', $file_name.'%')->delete();
            if (File::isDirectory($file_name))
            {
                // cerciorarse que el directorio se encuentra vacio
                if (sizeof(File::files($file_name)) == 0)
                {
                    Share::where('route',$file_name)->delete();
                    File::deleteDirectory($file_name);
                    return "OK";
                } else
                {
                    Share::where('route',$file_name)->delete();
                    File::deleteDirectory($file_name);
                    return "OK";
                }
            } else
            {
                if (File::exists($file_name))
                {
                    File::delete($file_name);
                    return "OK";
                } else {
                    return $file_name . " no encontrado";
                }
            }
        }
    }
}