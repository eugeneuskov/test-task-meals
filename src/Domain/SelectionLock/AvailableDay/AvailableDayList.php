<?php

declare(strict_types=1);

namespace Meals\Domain\SelectionLock\AvailableDay;

use Assert\Assertion;

class AvailableDayList
{
    /**
     * @param AvailableDay[] $availableDays
     */
    public function __construct(private array $availableDays)
    {
        Assertion::allIsInstanceOf($availableDays, AvailableDay::class);
    }

    /**
     * @return AvailableDay[]
     */
    public function getAvailableDays(): array
    {
        return $this->availableDays;
    }

    public function dayIsAvailable(int $needle): bool
    {
        foreach ($this->getAvailableDays() as $availableDay) {
            if ($availableDay->getValue() === $needle) {
                return true;
            }
        }
        return false;
    }
}