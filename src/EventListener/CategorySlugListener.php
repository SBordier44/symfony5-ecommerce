<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugListener
{
    public function __construct(protected SluggerInterface $slugger)
    {
    }

    public function prePersist(Category $category, LifecycleEventArgs $args): void
    {
        if ($category->getSlug() === null) {
            $category->setSlug(strtolower($this->slugger->slug($category->getName())->toString()));
        }
    }
}
