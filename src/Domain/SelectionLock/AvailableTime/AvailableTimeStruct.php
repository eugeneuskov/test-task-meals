<?php

declare(strict_types=1);

namespace Meals\Domain\SelectionLock\AvailableTime;

use JetBrains\PhpStorm\Pure;

class AvailableTimeStruct
{
    public function __construct(private int $start, private int $end)
    {
    }

    public function getStart(): int
    {
        return $this->start;
    }

    public function getEnd(): int
    {
        return $this->end;
    }

    public function timeIsAvailable(int $needle): bool
    {
        return $needle >= $this->getStart() && $needle <= $this->getEnd();
    }
}