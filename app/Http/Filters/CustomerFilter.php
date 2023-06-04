<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class CustomerFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const GROUP = 'group';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::GROUP => [$this, 'group'],
            self::SORT => [$this, 'sort'],
        ];
    }

    protected function keyword(Builder $builder, $value)
    {

        $builder->where(function ($query) use ($value) {
            $query->where('name', 'LIKE', "%$value%")
                ->orWhere('phone_number', 'LIKE', "%$value%")
                ->orWhere('email', 'LIKE', "%$value%")
                ->orWhere('info', 'LIKE', "%$value%")
                ->orWhereHas('cars', function ($query) use ($value) {
                    $query->where('vin_code', 'LIKE', "%$value%")
                        ->orWhere('year', 'LIKE', "%$value%")
                        ->orWhere('modification', 'LIKE', "%$value%")
                        ->orWhereHas('carModel', function ($query) use ($value) {
                            $query->where('name', 'LIKE', "%$value%")
                                ->orWhereHas('brand', function ($query) use ($value) {
                                    $query->where('name', 'LIKE', "%$value%");
                                });
                        });
                });
        });
    }

    protected function group(Builder $builder, $value)
    {
        $builder->where('group_id', $value);
    }

    protected function sort(Builder $builder, $value)
    {
        switch ($value) {
            case 'name_asc':
                $builder->orderBy('name');
                break;
            case 'name_desc':
                $builder->orderBy('name', 'desc');
                break;
            default:
                $builder->orderBy('id', 'desc');
                break;
        }
    }
}
