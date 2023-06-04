<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Http\Resources\Provider\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke()
    {
        $providers = Provider::all();
        return ProviderResource::collection($providers);
    }
}
