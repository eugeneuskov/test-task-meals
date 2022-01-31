<?php

declare(strict_types=1);

namespace Meals\Application\Component\Validator;

use DateTimeImmutable;
use Meals\Application\Component\Validator\Exception\SelectionLockNotAvailableException;
use Meals\Domain\SelectionLock\SelectionLock;

class SelectionDayIsAvailableValidator
{
    public function validate(SelectionLock $selectionLock, ?DateTimeImmutable $dateTime = null)
    {
        if (!$selectionLock->getAvailableDays()->dayIsAvailable(getdate($dateTime?->getTimestamp())['wday'])) {
            throw new SelectionLockNotAvailableException();
        }
    }
}