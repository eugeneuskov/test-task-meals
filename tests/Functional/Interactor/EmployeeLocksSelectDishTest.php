<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Interactor;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use Exception;
use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\Exception\PollHasNoDishException;
use Meals\Application\Component\Validator\Exception\PollIsNotActiveException;
use Meals\Application\Component\Validator\Exception\SelectionLockNotAvailableException;
use Meals\Application\Feature\Poll\UseCase\EmployeeSetsPollResult\Interactor;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\SelectionLock\AvailableDay\AvailableDay;
use Meals\Domain\SelectionLock\AvailableDay\AvailableDayList;
use Meals\Domain\SelectionLock\AvailableTime\AvailableTimeStruct;
use Meals\Domain\SelectionLock\SelectionLock;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use tests\Meals\Functional\Fake\Provider\FakeDishProvider;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\Fake\Provider\FakeSelectionLockProvider;
use tests\Meals\Functional\FunctionalTestCase;

class EmployeeLocksSelectDishTest extends FunctionalTestCase
{
    /**
     * @throws Exception
     */
    public function testSuccess()
    {
        $pollRequest = $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getSelectionLock(),
            new DateTimeImmutable(
                (new DateTime("next monday"))->add(
                    new DateInterval('PT10H')
                )->format("Y-m-d H:i:s")
            )
        );
        verify($pollRequest)->equals($pollRequest);
    }

    public function testUserHasNotPermissionsFail()
    {
        $this->expectException(AccessDeniedException::class);

        $selectionLock = $this->performTestMethod(
            $this->getEmployeeWithNoPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getSelectionLock()
        );
        verify($selectionLock)->equals($selectionLock);
    }

    public function testPollNotActiveFail()
    {
        $this->expectException(PollIsNotActiveException::class);

        $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(false),
            $this->getDish(),
            $this->getSelectionLock()
        );
    }

    public function testPollHasNoDishFail()
    {
        $this->expectException(PollHasNoDishException::class);

        $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getEmptyMenuPoll(),
            $this->getDish(),
            $this->getSelectionLock()
        );
    }

    public function testSelectionLockNotAvailableByDay()
    {
        $this->expectException(SelectionLockNotAvailableException::class);

        $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getNotAvailableByDaySelectionLock(),
            new DateTimeImmutable("next friday")
        );
    }

    public function testSelectionLockNotAvailableByTime()
    {
        $this->expectException(SelectionLockNotAvailableException::class);

        $this->performTestMethod(
            $this->getEmployeeWithPermissions(),
            $this->getPoll(true),
            $this->getDish(),
            $this->getNotAvailableByTimeSelectionLock(),
            new DateTimeImmutable("next monday")
        );
    }

    private function performTestMethod(
        Employee $employee,
        Poll $poll,
        Dish $dish,
        SelectionLock $selectionLock,
        ?DateTimeImmutable $dateTime = null
    ): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakeDishProvider::class)->setDish($dish);
        $this->getContainer()->get(FakeSelectionLockProvider::class)->setLock($selectionLock);

        return $this->getContainer()->get(Interactor::class)->lockSelection(
            $employee->getId(),
            $poll->getId(),
            $dish->getId(),
            $selectionLock->getId(),
            $dateTime
        );
    }

    private function getEmployeeWithPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::PARTICIPATION_IN_POLLS),
                ]
            ),
        );
    }

    private function getEmployeeWithNoPermissions(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithNoPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithNoPermissions(): User
    {
        return new User(
            1,
            new PermissionList([]),
        );
    }

    private function getEmptyMenuPoll(): Poll
    {
        return new Poll(
            1,
            true,
            new Menu(
                1,
                'title',
                new DishList([]),
            )
        );
    }

    private function getPoll(bool $active): Poll
    {
        return new Poll(
            1,
            $active,
            new Menu(
                1,
                'title',
                new DishList([$this->getDish()]),
            )
        );
    }

    private function getDish(): Dish
    {
        return new Dish(
            1,
            'dish title',
            'dish description'
        );
    }

    private function getNotAvailableByDaySelectionLock(): SelectionLock
    {
        return new SelectionLock(
            1,
            new AvailableDayList([]),
            new AvailableTimeStruct(6, 22)
        );
    }

    private function getNotAvailableByTimeSelectionLock(): SelectionLock
    {
        return new SelectionLock(
            1,
            new AvailableDayList(
                [
                    new AvailableDay(AvailableDay::MONDAY),
                ]
            ),
            new AvailableTimeStruct(-1, -1)
        );
    }

    private function getSelectionLock(): SelectionLock
    {
        return new SelectionLock(
            1,
            new AvailableDayList(
                [
                    new AvailableDay(AvailableDay::MONDAY),
                ]
            ),
            new AvailableTimeStruct(6, 22)
        );
    }
}