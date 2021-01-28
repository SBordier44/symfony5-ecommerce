<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $products = $this->getDoctrine()->getRepository(Product::class)->findBy([], ['createdAt' => 'DESC'], 8);
        return $this->render(
            'home/index.html.twig',
            [
                'pageTitle' => 'Accueil',
                'products' => $products
            ]
        );
    }
}
