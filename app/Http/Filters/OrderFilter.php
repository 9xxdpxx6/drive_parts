<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    const KEYWORD = 'keyword';
    const STATUS = 'status';
    const LABELS = 'labels';
    const DATES = 'dates';
    const SORT = 'sort';

    protected function getCallbacks(): array
    {
        return [
            self::KEYWORD => [$this, 'keyword'],
            self::STATUS => [$this, 'status'],
            self::LABELS => [$this, 'labels'],
            self::DATES => [$this, 'dates'],
            self::SORT => [$this, 'sort'],
        ];
    }

    protected function keyword(Builder $builder, $value)
    {
        $builder->where(function ($query) use ($value) {
            $query->whereHas('user', function ($query) use ($value) {
                $query->where('name', 'LIKE', "%$value%");
            })
                ->orWhereHas('car', function ($query) use ($value) {
                    $query->where('vin_code', 'LIKE', "%$value%")
                        ->orWhere('year', 'LIKE', "%$value%")
                        ->orWhere('modification', 'LIKE', "%$value%")
                        ->orWhereHas('carModel', function ($query) use ($value) {
                            $query->where('name', 'LIKE', "%$value%")
                                ->orWhereHas('brand', function ($query) use ($value) {
                                    $query->where('name', 'LIKE', "%$value%");
                                });
                        });
                })
                ->orWhereHas('purchases.product', function ($query) use ($value) {
                    $query->where('name', 'LIKE', "%$value%")
                        ->orWhere('part_number', 'LIKE', "%$value%");
                })
                ->orWhere('comment', 'LIKE', "%$value%")
                ->orWhere('note', 'LIKE', "%$value%")
                ->orWhere('address', 'LIKE', "%$value%");
        });
    }

    protected function status(Builder $builder, $value)
    {
        $builder->where('status_id', $value);
    }

    protected function labels(Builder $builder, $value)
    {
        $builder->whereHas('labels', function ($b) use ($value) {
            $b->whereIn('label_id', $value);
        });
    }

    protected function dates(Builder $builder, $value)
    {
        $dates = explode('_', $value);

        if (count($dates) === 2) {
            $startDate = $dates[0];
            $endDate = $dates[1];

            $startDate = date('Y-m-d 00:00:00', strtotime($startDate));
            $endDate = date('Y-m-d 23:59:59', strtotime($endDate));

            $builder->whereBetween('created_at', [$startDate, $endDate]);
        }
    }


    protected function sort(Builder $builder, $value)
    {
        switch ($value) {
            case 'date_asc':
                $builder->orderBy('created_at');
                break;
            case 'date_desc':
                $builder->orderBy('created_at', 'desc');
                break;
            default:
                $builder->orderBy('id', 'desc');
                break;
        }
    }
}
