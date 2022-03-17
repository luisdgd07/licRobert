<?php

namespace App\Http\Controllers;

use Storage;
use App\Share;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class UserController extends Controller
{

    function __construct()
    {
        $this->middleware('admin');
    }
    /**
     * Se lista todos los usuarios del sistema
     *
     * @param  Request $request 
     * @return Response
     */
    public function listar()
    {
        $users = User::where('role', '!=', '7')->get();
        return view('user.tree')->with('users', $users);
    }

    /**
     * Se lista todos los usuarios del sistema
     *
     * @param  Request $request 
     * @return Response
     */
    public function show()
    {
        return view('user.show');
    }


     /**
     * Borra el usuario
     *
     * @return mixed
     */
    public function getDelete()
    {
        $id = Input::get('id');
        $user = User::find($id);
        $user->shares()->detach();    
        $user->delete();
        if($user===null){
            return "Usuario no encontrado";
        }else{
            return "OK";    
        }
    }


}