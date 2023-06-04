<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Filters\CustomerFilter;
use App\Http\Requests\Customer\IndexRequest;
use App\Http\Resources\User\IndexUserResource;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        if (!isset($data['sort'])) {
            $data['sort'] = 'qwe';
        }
        $filter = app()->make(CustomerFilter::class, ['queryParams' => array_filter($data)]);

        $customers = User::where('role_id', 1)->filter($filter)->paginate($data['per_page'], ['*'], 'page', $data['page']);

        return IndexUserResource::collection($customers);
    }
}
