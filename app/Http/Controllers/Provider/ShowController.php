<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Http\Resources\Provider\ProviderResource;
use App\Models\Provider;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function __invoke(Provider $provider)
    {
        return new ProviderResource($provider);
    }
}
