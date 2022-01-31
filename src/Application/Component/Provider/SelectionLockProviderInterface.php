<?php

declare(strict_types=1);

namespace Meals\Application\Component\Provider;

use Meals\Domain\SelectionLock\SelectionLock;

interface SelectionLockProviderInterface
{
    public function getLock(int $selectionLockId): SelectionLock;
}