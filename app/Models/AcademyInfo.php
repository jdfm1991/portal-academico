<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AcademyInfo extends Model
{
    protected $table = 'academy_info';

    protected $fillable = ['key', 'value'];

    /**
     * Obtiene toda la información como un array asociativo.
     */
    public static function allAsArray(): array
    {
        return Cache::rememberForever('academy_info', function () {
            return self::pluck('value', 'key')->toArray();
        });
    }

    /**
     * Obtiene un valor específico.
     */
    public static function getValue(string $key, $default = null)
    {
        return self::allAsArray()[$key] ?? $default;
    }

    /**
     * Limpia la caché cuando se actualiza la información.
     */
    public static function clearCache(): void
    {
        Cache::forget('academy_info');
    }
}