<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\SelectionTimeIsAvailable;
use Meals\Domain\SelectionLock\AvailableTime\AvailableTimeStruct;
use Meals\Domain\SelectionLock\SelectionLock;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class SelectionTimeIsAvailableTest extends TestCase
{
    use ProphecyTrait;

    private const CORRECT_TIME = 12;
    private const INCORRECT_TIME = 0;

    public function testSuccessful()
    {
        $availableTimeStruct = $this->prophesize(AvailableTimeStruct::class);
        $availableTimeStruct->timeIsAvailable(static::CORRECT_TIME)->willReturn(true);

        $selectionLock = $this->prophesize(SelectionLock::class);
        $selectionLock->getAvailableTimeStruct()->willReturn($availableTimeStruct->reveal());

        $validator = new SelectionTimeIsAvailable();
        verify($validator->validate($selectionLock->reveal()))->null();
    }
}