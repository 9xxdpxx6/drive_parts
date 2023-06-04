<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Http\Requests\Label\UpdateRequest;
use App\Models\Label;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Label $label)
    {
        $data = $request->validated();
        $label->update($data);

        return response([]);
    }
}
