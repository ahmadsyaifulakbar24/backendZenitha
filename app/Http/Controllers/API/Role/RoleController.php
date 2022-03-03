<?php

namespace App\Http\Controllers\APi\Role;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\Role\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function get()
    {
        $role = Role::all();
        return ResponseFormatter::success(RoleResource::collection($role), 'success get role data');
    }
}
