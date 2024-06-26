<?php

declare(strict_types=1);

namespace App\Service\User;

enum Sex: string
{
    case Man = 'Man';
    case Woman = 'Woman';


    /**
     * @return string       Random Sex-case value
     */
    public static function randomCaseAsString(): string
    {
        $key = random_int(0, count(self::valuesAsArray()) - 1);

        return self::cases()[$key]->value;
    }

    /**
     * @return array<string, int>   Array of all values of cases
     */
    public static function valuesAsArray(): array
    {
        return array_map(function($case) {
            return $case->value;
        }, self::cases());
    }
}
