<?php

namespace App\Traits;
use Illuminate\Support\Str;

trait HasRandomId
{
    public static function bootHasRandomId()
    {
        static::creating(function ($model) {
            if (! $model->getKey()) {
                $model->{$model->getKeyName()} = self::generateRandomId();
            }
        });
    }

    public static function generateRandomId(int $length = 15): string
    {
        return Str::random($length);
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }
}
