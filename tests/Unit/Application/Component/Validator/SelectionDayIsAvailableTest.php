<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use DateTimeImmutable;
use Meals\Application\Component\Validator\Exception\SelectionLockNotAvailableException;
use Meals\Application\Component\Validator\SelectionDayIsAvailableValidator;
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
        $availableDaysList->dayIsAvailable(1)->willReturn(true);

        $selectionLock = $this->prophesize(SelectionLock::class);
        $selectionLock->getAvailableDays()->willReturn($availableDaysList->reveal());

        $validator = new SelectionDayIsAvailableValidator();
        verify($validator->validate($selectionLock->reveal(), new DateTimeImmutable("next monday")))->null();
    }

    public function testFail()
    {
        $this->expectException(SelectionLockNotAvailableException::class);

        $availableDaysList = $this->prophesize(AvailableDayList::class);
        $availableDaysList->dayIsAvailable(5)->willReturn(false);

        $selectionLock = $this->prophesize(SelectionLock::class);
        $selectionLock->getAvailableDays()->willReturn($availableDaysList->reveal());

        $validator = new SelectionDayIsAvailableValidator();
        $validator->validate($selectionLock->reveal(), new DateTimeImmutable("next friday"));
    }
}