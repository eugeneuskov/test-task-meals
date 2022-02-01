<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\AccessDeniedException;
use Meals\Application\Component\Validator\UserHasAccessToSelectionOfDishValidator;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class UserHasAccessToSelectionOfDishValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testSuccessful()
    {
        $permissionList = $this->prophesize(PermissionList::class);
        $permissionList->hasPermission(Permission::PARTICIPATION_IN_POLLS)->willReturn(true);

        $user = $this->prophesize(User::class);
        $user->getPermissions()->willReturn($permissionList->reveal());

        $validator = new UserHasAccessToSelectionOfDishValidator();
        verify($validator->validate($user->reveal()))->null();
    }

    public function testFail()
    {
        $this->expectException(AccessDeniedException::class);

        $permissionList = $this->prophesize(PermissionList::class);
        $permissionList->hasPermission(Permission::PARTICIPATION_IN_POLLS)->willReturn(false);

        $user = $this->prophesize(User::class);
        $user->getPermissions()->willReturn($permissionList->reveal());

        $validator = new UserHasAccessToSelectionOfDishValidator();
        $validator->validate($user->reveal());
    }
}