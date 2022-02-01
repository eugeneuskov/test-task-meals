<?php

namespace Meals\Application\Feature\Poll\UseCase\EmployeeSetsPollResult;

use DateTimeImmutable;
use Meals\Application\Component\Provider\DishProviderInterface;
use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\GenerateIdProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Provider\SelectionLockProviderInterface;
use Meals\Application\Component\Validator\PollHasDishValidator;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\SelectionDayIsAvailableValidator;
use Meals\Application\Component\Validator\SelectionTimeIsAvailableValidator;
use Meals\Application\Component\Validator\UserHasAccessToSelectionOfDishValidator;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    public function __construct(
        private EmployeeProviderInterface $employeeProvider,
        private PollProviderInterface $pollProvider,
        private UserHasAccessToSelectionOfDishValidator $userHasAccessToSelectionOfDishValidator,
        private PollIsActiveValidator $pollIsActiveValidator,
        private DishProviderInterface $dishProvider,
        private PollHasDishValidator $pollHasDishValidator,
        private SelectionLockProviderInterface $selectionLockProvider,
        private SelectionDayIsAvailableValidator $dayIsAvailableValidator,
        private SelectionTimeIsAvailableValidator $timeIsAvailableValidator,
        private GenerateIdProviderInterface $generateIdProvider
    ) {}

    public function lockSelection(
        int $employeeId,
        int $pollId,
        int $dishId,
        int $selectionLockId,
        ?DateTimeImmutable $dateTime = null
    ): PollResult
    {
        $employee = $this->employeeProvider->getEmployee($employeeId);
        $this->userHasAccessToSelectionOfDishValidator->validate($employee->getUser());

        $poll = $this->pollProvider->getPoll($pollId);
        $this->pollIsActiveValidator->validate($poll);

        $dish = $this->dishProvider->getDish($dishId);
        $this->pollHasDishValidator->validate($poll, $dish);

        $selectionLock = $this->selectionLockProvider->getLock($selectionLockId);
        $this->dayIsAvailableValidator->validate($selectionLock, $dateTime);
        $this->timeIsAvailableValidator->validate($selectionLock, $dateTime);

        return new PollResult(
            $this->generateIdProvider->generateId(),
            $poll,
            $employee,
            $dish,
            $employee->getFloor()
        );
    }
}