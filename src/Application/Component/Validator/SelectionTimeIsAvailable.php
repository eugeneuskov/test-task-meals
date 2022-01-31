<?php

declare(strict_types=1);

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\SelectionLockNotAvailableException;
use Meals\Domain\SelectionLock\SelectionLock;

class SelectionTimeIsAvailable
{
    public function validate(SelectionLock $selectionLock)
    {
        if (!$selectionLock->getAvailableTimeStruct()->timeIsAvailable(getdate()['hours'])) {
            throw new SelectionLockNotAvailableException();
        }
    }
}