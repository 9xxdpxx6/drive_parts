<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UpdateController extends Controller
{
    public function __invoke(UpdateRequest $request, User $customer)
    {
        $data = $request->validated();

        $cars = $data['cars'];
        unset($data['cars'], $data['customer_id']);

        $customer->update($data);

        return response([]);
    }
}
