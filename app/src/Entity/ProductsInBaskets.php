<?php

namespace App\Entity;

use App\Repository\ProductsInBasketsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductsInBasketsRepository::class)]
class ProductsInBaskets
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

//    #[ORM\Column]
//    private ?int $product_id = null;

    #[ORM\ManyToOne(targetEntity: Products::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Products $product_id = null;


    #[ORM\ManyToOne(inversedBy: 'baskets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Baskets $basket_id = null;

    #[ORM\Column]
    private ?int $product_count = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): ?Products
    {
        return $this->product_id;
    }

    public function setProductId(?Products $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getBasketId(): ?Baskets
    {
        return $this->basket_id;
    }

    public function setBasketId(?Baskets $basket_id): self
    {
        $this->basket_id = $basket_id;

        return $this;
    }

    public function getProductCount(): ?int
    {
        return $this->product_count;
    }

    public function setProductCount(int $product_count): self
    {
        $this->product_count = $product_count;

        return $this;
    }
}
