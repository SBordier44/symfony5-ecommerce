<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/{categorySlug}/{slug}', name: 'product_show', priority: - 1)]
    public function index(
        Product $product,
        Request $request
    ): Response {
        return $this->render(
            'product/index.html.twig',
            [
                'product' => $product,
                'categoryRequest' => $this->getDoctrine()->getRepository(Category::class)->findOneBy(
                    ['slug' => $request->get('categorySlug')]
                )
            ]
        );
    }
}
