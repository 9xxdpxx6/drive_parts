<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Group;

class FilterListController extends Controller
{
    public function __invoke()
    {
        $groups = Group::all();

        $result = [
            'groups' => $groups,
        ];

        return response()->json($result);
    }
}
