<?php

declare(strict_types=1);

namespace Meals\Application\Component\Provider;

interface GenerateIdProviderInterface
{
    public function generateId(): int;
}