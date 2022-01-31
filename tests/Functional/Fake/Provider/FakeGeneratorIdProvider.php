<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\GenerateIdProviderInterface;

class FakeGeneratorIdProvider implements GenerateIdProviderInterface
{
    public function generateId(): int
    {
        return mt_rand();
    }
}