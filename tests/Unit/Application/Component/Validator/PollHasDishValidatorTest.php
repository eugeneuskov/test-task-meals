<?php

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Domain\Dish\Dish;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use PHPUnit\Framework\TestCase;

class PollHasDishValidatorTest extends TestCase
{
    private const EXIST_DISH = 4;
    private const NOT_EXIST_DISH = 2;

    public function testSuccessful()
    {
        verify(
            $this->getActivePoll()
                ->getMenu()
                ->getDishes()
                ->hasDish(
                    $this->getNeedleDish(static::EXIST_DISH)
                )
        )->true();
    }

    public function testFail()
    {
        verify(
            $this->getActivePoll()
                ->getMenu()
                ->getDishes()
                ->hasDish(
                    $this->getNeedleDish(static::NOT_EXIST_DISH)
                )
        )->false();
    }

    private function getNeedleDish(int $dishId): Dish
    {
        return new Dish(
            $dishId,
            'title',
            'description'
        );
    }

    private function getActivePoll(): Poll
    {
        return new Poll(
            1,
            true,
            new Menu(
                1,
                'menu',
                new DishList(
                    [
                        new Dish(1, 'title', 'description'),
                        new Dish(4, 'name', 'dish description'),
                        new Dish(8, 'dish', '')
                    ]
                )
            )
        );
    }
}