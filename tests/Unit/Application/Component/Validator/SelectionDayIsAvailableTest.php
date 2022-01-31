<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\SelectionLockNotAvailableException;
use Meals\Application\Component\Validator\SelectionDayIsAvailable;
use Meals\Domain\SelectionLock\AvailableDay\AvailableDay;
use Meals\Domain\SelectionLock\AvailableDay\AvailableDayList;
use Meals\Domain\SelectionLock\SelectionLock;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class SelectionDayIsAvailableTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $availableDaysList = $this->prophesize(AvailableDayList::class);
        $availableDaysList->dayIsAvailable(AvailableDay::MONDAY)->willReturn(true);

        $selectionLock = $this->prophesize(SelectionLock::class);
        $selectionLock->getAvailableDays()->willReturn($availableDaysList->reveal());

        $validator = new SelectionDayIsAvailable();
        verify($validator->validate($selectionLock->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(SelectionLockNotAvailableException::class);

        $availableDaysList = $this->prophesize(AvailableDayList::class);
        $availableDaysList->dayIsAvailable(AvailableDay::MONDAY)->willReturn(false);

        $selectionLock = $this->prophesize(SelectionLock::class);
        $selectionLock->getAvailableDays()->willReturn($availableDaysList->reveal());

        $validator = new SelectionDayIsAvailable();
        $validator->validate($selectionLock->reveal());
    }
}