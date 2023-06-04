<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();

        $cars = $data['cars'];
        unset($data['cars']);

        $data['password'] = Hash::make($data['password']);

        User::firstOrCreate([
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
        ], $data);

        return response([]);
    }
}
