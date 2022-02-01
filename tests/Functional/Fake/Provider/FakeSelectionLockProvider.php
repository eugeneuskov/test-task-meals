<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\SelectionLockProviderInterface;
use Meals\Domain\SelectionLock\SelectionLock;

class FakeSelectionLockProvider implements SelectionLockProviderInterface
{
    private SelectionLock $selectionLock;

    public function getLock(int $selectionLockId): SelectionLock
    {
        return $this->selectionLock;
    }

    public function setLock(SelectionLock $selectionLock)
    {
        $this->selectionLock = $selectionLock;
    }
}