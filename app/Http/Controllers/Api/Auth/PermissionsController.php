<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Validator;

class PermissionsController extends Controller
{
    use JsonResponseTrait;

    public function index()
    {

        $permisos = Permission::all();

        return $this->jsonResponse($permisos, 200, []);
    }

    public function store(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:75',
        ]);

        if ($validate->fails()) {
            return $this->jsonResponse([], 422, $validate->errors());
        }

        $permission = Permission::create([
            'name' => $request->name
        ]);

        return $this->jsonResponse([
            'message' => 'El permiso de ' . $permission->name . ' se creó correctamente'
        ], 200, []);
    }

    public function update(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required|string|max:75',
        ]);

        if ($validate->fails()) {
            return $this->jsonResponse([], 422, $validate->errors());
        }

        $permiso = Permission::find($request->id);

        if (!$permiso) {
            return $this->jsonResponse([], 404, ['message' => 'No se encontró el recurso']);
        }

        $permiso->update([
            'name' => $request->name,
        ]);

        return $this->jsonResponse([
            'message' => 'El permiso de ' . $permiso->name . ' se actualizó correctamente'
        ], 200, []);
    }

    public function delete($id)
    {

        $permiso = Permission::find($id);

        if (!$permiso) {
            return $this->jsonResponse([], 404, ['message' => 'No se encontró el recurso']);
        }

        $permiso->delete();

        return $this->jsonResponse([
            'message' => 'El permiso de ' . $permiso->name . ' se eliminó correctamente'
        ], 200, []);
    }
}
