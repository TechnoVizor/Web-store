<?php

namespace App\Support;

use Illuminate\Support\Str;

class Search
{
    public static function pattern(string $value): string
    {
        return '%'.Str::lower(trim($value)).'%';
    }

    public static function whereLike($query, string $column, string $value)
    {
        return $query->whereRaw("LOWER({$column}) LIKE ?", [self::pattern($value)]);
    }

    public static function orWhereLike($query, string $column, string $value)
    {
        return $query->orWhereRaw("LOWER({$column}) LIKE ?", [self::pattern($value)]);
    }

    public static function orWhereIntegerLike($query, string $column, string $value)
    {
        return $query->orWhereRaw("CAST({$column} AS TEXT) LIKE ?", ['%'.trim($value).'%']);
    }
}
