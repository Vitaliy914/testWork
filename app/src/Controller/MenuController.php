<?php

namespace App\Controller;

use App\Entity\Products;
use App\Entity\ProductsCategories;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MenuController extends AbstractController
{
    #[Route('/', name: 'app_menu')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $productCategories = $entityManager->getRepository(ProductsCategories::class)->findAll();
        $products = $entityManager->getRepository(Products::class)->findAll();
        $productArray = [];

        if($products){
            foreach ($products as $product){
                $productArray[] = $product;
            }
        }
        return $this->render('menu/index.html.twig', [
            'productCategories' => $productCategories,
            'products'=>$productArray
        ]);
    }
}
