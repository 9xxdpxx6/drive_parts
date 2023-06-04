<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ProductFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const CATEGORIES = 'categories';
    const BRANDS = 'brands';
    const PRICES = 'prices';
    const ACTIVE = 'active';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::CATEGORIES => [$this, 'categories'],
            self::BRANDS => [$this, 'brands'],
            self::PRICES => [$this, 'prices'],
            self::ACTIVE => [$this, 'active'],
            self::SORT => [$this, 'sort'],
        ];
    }

    protected function keyword(Builder $builder, $value)
    {
        $builder->where(function ($query) use ($value) {
            $query->where('name', 'LIKE', "%$value%")
                ->orWhere('part_number', 'LIKE', "%$value%");
        });
    }

    protected function categories(Builder $builder, $value)
    {
        $builder->whereIn('category_id', $value);
    }

    protected function brands(Builder $builder, $value)
    {
        $builder->whereIn('brand_id', $value);
    }

    protected function prices(Builder $builder, $value)
    {
        $builder->whereBetween('price', $value);
    }

    protected function active(Builder $builder, $value)
    {
        $builder->whereIn('is_active', $value);
    }

    protected function sort(Builder $builder, $value)
    {
        switch ($value) {
            case 'price_asc':
                $builder->orderBy('price');
                break;
            case 'price_desc':
                $builder->orderBy('price', 'desc');
                break;
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
