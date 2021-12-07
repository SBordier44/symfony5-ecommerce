<?php

declare(strict_types=1);

namespace App\DataFixtures\Providers;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class HelpersProvider
{
    public function __construct(private ContainerBagInterface $params)
    {
    }

    public function hasFeaturedProduct(int $currentCount): bool
    {
        $numberOfFeaturedProducts = $this->params->get(
            'app.fixture.datafixtures.providers.helpers.number_featured_products'
        );

        return $currentCount <= $numberOfFeaturedProducts;
    }
}
