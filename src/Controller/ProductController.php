<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/{categorySlug}/{slug}', name: 'product_show', priority: -1)]
    public function index(
        Product $product,
        Request $request,
        ManagerRegistry $managerRegistry
    ): Response {
        return $this->render(
            'product/index.html.twig',
            [
                'product' => $product,
                'categoryRequest' => $managerRegistry->getRepository(Category::class)->findOneBy(
                    ['slug' => $request->get('categorySlug')]
                )
            ]
        );
    }
}
