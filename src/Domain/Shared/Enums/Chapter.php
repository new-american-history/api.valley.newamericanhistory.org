<?php

namespace Domain\Shared\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self aftermath()
 * @method static self eve()
 * @method static self war()
 */
class Chapter extends Enum
{
    protected static function labels(): array
    {
        return [
            'aftermath' => 'The Aftermath',
            'eve' => 'The Eve of War',
            'war' => 'The War Years',
        ];
    }
}
