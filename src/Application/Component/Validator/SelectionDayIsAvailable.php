<?php

declare(strict_types=1);

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\SelectionLockNotAvailableException;
use Meals\Domain\SelectionLock\SelectionLock;

class SelectionDayIsAvailable
{
    public function validate(SelectionLock $selectionLock)
    {
        if (!$selectionLock->getAvailableDays()->dayIsAvailable(getdate()['wday'])) {
            throw new SelectionLockNotAvailableException();
        }
    }
}