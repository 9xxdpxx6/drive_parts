<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    use HasFactory;

    const ACCESS_CREATE = 1;
    const ACCESS_READ = 2;
    const ACCESS_UPDATE = 3;
    const ACCESS_DELETE = 4;

    protected $table = 'permission_role';
    protected $guarded = false;

    static function getAccesses()
    {
        return [
            self::ACCESS_CREATE => 'Просмотр',
            self::ACCESS_READ => 'Добавление',
            self::ACCESS_UPDATE => 'Редактирование',
            self::ACCESS_DELETE => 'Удаление',
        ];
    }

    public function getAccessTitleAttribute()
    {
        return self::getAccesses()[$this->type];
    }
}
