<?php

namespace App\Http\Controllers\Label;

use App\Http\Controllers\Controller;
use App\Models\Label;
use Illuminate\Http\Request;

class EditController extends Controller
{
    public function __invoke(Label $label)
    {
        return view('label.edit', compact('label'));
    }
}
