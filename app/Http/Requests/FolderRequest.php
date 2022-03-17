<?php
namespace App\Http\Requests;

use Auth;
use Carbon\Carbon;
use App\Http\Requests\Request;

class FolderRequest extends Request
{
    /**
     * Determina si el usuario está autorizado a realizar esta petición.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Obtiene las reglas de validación que se van a aplicar a la petición.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nombre' => 'required|max:200'
        ];
    }
}