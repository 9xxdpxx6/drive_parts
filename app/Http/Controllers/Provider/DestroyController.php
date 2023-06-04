<?php

namespace App\Http\Controllers\Provider ;

use App\Http\Controllers\Controller;
use App\Models\Provider ;
use Illuminate\Http\Request;

class DestroyController extends Controller
{
    public function __invoke(Provider $provider)
    {
        $provider->delete();

        return response([]);
    }
}
