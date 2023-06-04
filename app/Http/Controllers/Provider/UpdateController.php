<?php

namespace App\Http\Controllers\Provider ;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\UpdateRequest;
use App\Models\Provider;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, Provider $provider)
    {
        $data = $request->validated();

        unset($data['provider_id']);

        if ($provider->code == $data['code']) {
            unset($data['code']);
        }

        $provider->update($data);

        return response([]);
    }
}
