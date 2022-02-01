<?php

declare(strict_types=1);

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\PollHasNoDishException;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Poll\Poll;

class PollHasDishValidator
{
    public function validate(Poll $poll, Dish $dish)
    {
        if (!$poll->getMenu()->getDishes()->hasDish($dish)) {
            throw new PollHasNoDishException();
        }
    }
}