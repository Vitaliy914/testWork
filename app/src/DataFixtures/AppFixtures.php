<?php

namespace App\DataFixtures;

use App\Entity\Products;
use App\Entity\ProductsCategories;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $categories = new ProductsCategories();
        $categories->setCategoryName('Food');
        $manager->persist($categories);
        $manager->flush();

        for($i=0; $i < 100; $i++) {
            $product = new Products();
            $product->setPrice(mt_rand(5, 5000));
            $product->setProductName('Product # '. $i);
            $product->setProductsCategories($categories);
            $manager->persist($product);
        }
        $manager->flush();

        $categories = new ProductsCategories();
        $categories->setCategoryName('Drinks');
        $manager->persist($categories);
        $manager->flush();
        for($i=0;$i < 100; $i++) {
            $product = new Products();
            $product->setPrice(rand(5, 5000));
            $product->setProductName('Drink # '. $i);
            $product->setProductsCategories($categories);
            $manager->persist($product);
        }
        $manager->flush();
    }
}
