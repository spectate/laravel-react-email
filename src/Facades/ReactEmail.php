<?php

namespace Spectate\ReactEmail\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Spectate\ReactEmail\ReactEmail
 */
class ReactEmail extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Spectate\ReactEmail\ReactEmail::class;
    }
}
