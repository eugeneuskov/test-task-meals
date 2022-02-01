<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use Exception;
use Meals\Application\Component\Validator\Exception\SelectionLockNotAvailableException;
use Meals\Application\Component\Validator\SelectionTimeIsAvailableValidator;
use Meals\Domain\SelectionLock\AvailableTime\AvailableTimeStruct;
use Meals\Domain\SelectionLock\SelectionLock;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class SelectionTimeIsAvailableTest extends TestCase
{
    use ProphecyTrait;

    private const CORRECT_TIME = 12;
    private const INCORRECT_TIME = 0;

    /**
     * @throws Exception
     */
    public function testSuccessful()
    {
        $availableTimeStruct = $this->prophesize(AvailableTimeStruct::class);
        $availableTimeStruct->timeIsAvailable(static::CORRECT_TIME)->willReturn(true);

        $selectionLock = $this->prophesize(SelectionLock::class);
        $selectionLock->getAvailableTimeStruct()->willReturn($availableTimeStruct->reveal());

        $validator = new SelectionTimeIsAvailableValidator();
        verify($validator->validate(
            $selectionLock->reveal(),
            new DateTimeImmutable(
                (new DateTime("next monday"))->add(
                    new DateInterval('PT12H')
                )->format("Y-m-d H:i:s")
            )
        ))->null();
    }

    public function testFail()
    {
        $this->expectException(SelectionLockNotAvailableException::class);

        $availableTimeStruct = $this->prophesize(AvailableTimeStruct::class);
        $availableTimeStruct->timeIsAvailable(static::INCORRECT_TIME)->willReturn(false);

        $selectionLock = $this->prophesize(SelectionLock::class);
        $selectionLock->getAvailableTimeStruct()->willReturn($availableTimeStruct->reveal());

        $validator = new SelectionTimeIsAvailableValidator();
        verify($validator->validate($selectionLock->reveal(), new DateTimeImmutable("next monday")))->null();
    }
}