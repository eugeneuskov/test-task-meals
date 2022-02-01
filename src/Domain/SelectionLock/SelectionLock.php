<?php

declare(strict_types=1);

namespace Meals\Domain\SelectionLock;

use Meals\Domain\Poll\Poll;
use Meals\Domain\SelectionLock\AvailableDay\AvailableDayList;
use Meals\Domain\SelectionLock\AvailableTime\AvailableTimeStruct;

class SelectionLock
{
    public function __construct(
        private int $id,
        private AvailableDayList $availableDays,
        private AvailableTimeStruct $availableTimeStruct
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function getAvailableDays(): AvailableDayList
    {
        return $this->availableDays;
    }

    public function getAvailableTimeStruct(): AvailableTimeStruct
    {
        return $this->availableTimeStruct;
    }
}