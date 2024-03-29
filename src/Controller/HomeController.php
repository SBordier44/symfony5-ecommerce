<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Product;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $products = $managerRegistry->getRepository(Product::class)
            ->findBy(['featured' => true], ['createdAt' => 'DESC']);
        return $this->render(
            'home/index.html.twig',
            [
                'pageTitle' => 'Accueil',
                'products' => $products
            ]
        );
    }
}
