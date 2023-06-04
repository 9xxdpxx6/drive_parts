<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Role $role)
    {
        return view('role.edit', compact('role'));
    }
}
