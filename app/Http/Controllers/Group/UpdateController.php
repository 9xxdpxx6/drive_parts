<?php

namespace App\Http\Controllers\Group ;

use App\Http\Controllers\Controller;
use App\Http\Requests\Group\UpdateRequest;
use App\Models\Group;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Group $group)
    {
        $data = $request->validated();

        unset($data['group_id']);

        if ($group->alias == $data['alias']) {
            unset($data['alias']);
        }

        $group->update($data);

        return response([]);
    }
}
