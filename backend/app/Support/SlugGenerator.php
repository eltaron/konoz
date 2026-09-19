<?php

namespace App\Support;

use Illuminate\Support\Str;

class SlugGenerator
{
    public static function make(?string $value, string $prefix): string
    {
        $value = trim($value ?? '');

        $slug = Str::slug($value);

        return $slug !== '' ? $slug : $prefix . '-' . substr(md5($value), 0, 8);
    }
}