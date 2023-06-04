<?php

namespace App\Http\Controllers\Provider ;

use App\Http\Controllers\Controller;
use App\Models\Provider ;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Provider $provider)
    {
        return view('provider.edit', compact('provider'));
    }
}
