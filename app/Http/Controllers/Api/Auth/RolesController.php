<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    use JsonResponseTrait;

    public function index(){

        $roles = Role::with(['permissions'])->get();

        return $this->jsonResponse($roles, 200, []);
    }

    public function store(Request $request){

        $validate = Validator::make($request->all(),[
            'name' => 'required|string|max:75',
            'permisos' => 'required|array'
        ]);

        if ($validate->fails()) {
            return $this->jsonResponse([], 422, $validate->errors());
        }

        $role = Role::create([
            'name' => $request->name
        ]);

        if (count($request->permisos) > 0) {
            $role->syncPermissions($request->permisos);
        }

        return $this->jsonResponse([
            'message' => 'El rol de ' . $role->name . ' se creó correctamente'
        ], 200, []);
    }

    public function update(Request $request){

        $validate = Validator::make($request->all(),[
            'rol_id' => 'required',
            'name' => 'required|string|max:75',
            'permisos' => 'array'
        ]);

        if ($validate->fails()) {
            return $this->jsonResponse([], 422, $validate->errors());
        }

        $role = Role::find($request->rol_id);

        if (!$role) {
            return $this->jsonResponse([], 404, ['message' => 'No se encontró el recurso']);
        }

        $role->update([
            'name' => $request->name,
        ]);

        if (count($request->permisos) > 0) {
            $role->syncPermissions($request->permisos);
        }

        return $this->jsonResponse([
            'message' => 'El rol de ' . $role->name . ' se actualizó correctamente'
        ], 200, []);
    }

    public function delete($id){

        $role = Role::find($id);

        if (!$role) {
            return $this->jsonResponse([], 404, ['message' => 'No se encontró el recurso']);
        }

        $role->delete();

        return $this->jsonResponse([
            'message' => 'El rol de ' . $role->name . ' se eliminó correctamente'
        ], 200, []);
    }
}
