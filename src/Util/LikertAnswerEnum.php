<?php

namespace App\Util;

use InvalidArgumentException;

enum LikertAnswerEnum: string
{
    case YES = 'yes';
    case MAYBE = 'maybe';
    case NO = 'no';

    public function score(): int
    {
        return match ($this) {
            self::YES => 2,
            self::MAYBE => 1,
            self::NO => 0.5,
        };
    }

    public static function fromString(?string $s): self
    {
        return match (strtolower((string) $s)) {
            'yes' => self::YES,
            'maybe' => self::MAYBE,
            'no' => self::NO,
            default => throw new InvalidArgumentException('Invalid Likert answer'),
        };
    }
}
