<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener
{
    public function __construct(protected SluggerInterface $slugger)
    {
    }

    public function prePersist(Product $product, LifecycleEventArgs $args): void
    {
        if ($product->getSlug() === null) {
            $product->setSlug(strtolower($this->slugger->slug($product->getName())->toString()));
        }
    }
}
