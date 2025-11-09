<?php

namespace App\Enums;

enum TaskPriority : string
{
    case HIGH = 'HIGH';
    case MEDIUM = 'MEDIUM';
    case LOW = 'LOW';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(function (self $priority): string {
            return $priority->value;
        }, self::cases());
    }
}
