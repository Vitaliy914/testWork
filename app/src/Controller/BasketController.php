<?php

namespace App\Controller;

use App\Entity\Baskets;
use App\Entity\Products;
use App\Entity\ProductsInBaskets;
use Cassandra\Date;
use DateTimeImmutable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Routing\Annotation\Route;

class BasketController extends AbstractController
{
    /**
     * @param Request $request
     * @param ManagerRegistry $doctrine
     * @param string $action
     * @return Response
     */
    #[Route('/basket/{action}/{subAction}', name: 'app_basket')]
    public function index(Request $request, ManagerRegistry $doctrine, ?string $action = 'index', ?string $subAction = ''): Response
    {
        $entityManager = $doctrine->getManager();
        $basketId = $request->cookies->get('basket_id') ?? $this->createBasket($entityManager);
        $this->setCoockie('basket_id', $basketId);
        switch ($action) {
            case 'add':
                return $this->addToBasket($request, $basketId, $doctrine);
            case 'remove':
                return $this->removeFromBasket($request, $basketId, $doctrine);
            case 'recount':
                return $this->recountBasket($request, $basketId, $doctrine, $subAction);
            case 'order':
                return $this->orderBasket($request, $basketId, $doctrine);
            default:
                return $this->showBasket($request, $basketId, $doctrine);
        }
    }

    private function showBasket(Request $request, int $basketId, ManagerRegistry $doctrine)
    {
        $productsInBasket = $doctrine->getRepository(ProductsInBaskets::class)->findBy(['basket_id' => $basketId]);
        return $this->render('basket/index.html.twig', [
            'productsInBasket' => $productsInBasket,
        ]);
    }

    private function orderBasket(Request $request, int $basketId, ManagerRegistry $doctrine)
    {
        $basket = $doctrine->getRepository(Baskets::class)->find($basketId);
        if (!$basket)
            throw $this->createNotFoundException();
        $basket->setStatus(1);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($basket);
        $entityManager->flush();


        $response = new RedirectResponse($this->generateUrl('app_menu'));
        $response->headers->clearCookie('basket_id');

        return $response;
    }

    private function addToBasket(Request $request, int $basketId, ManagerRegistry $doctrine): Response
    {
        $productId = $request->get('product_id');
        $product = $doctrine->getRepository(Products::class)->find($productId);
        if (!$product)
            throw $this->createNotFoundException();
        $basket = $doctrine->getRepository(Baskets::class)->find($basketId);
        if (!$basket)
            throw $this->createNotFoundException();
        $productsInBaskets = $doctrine->getRepository(ProductsInBaskets::class)->findOneBy(['basket_id' => $basketId, 'product_id' => $productId]);
        $em = $doctrine->getManager();
        if ($productsInBaskets) {
            $productsInBaskets->setProductCount($productsInBaskets->getProductCount() + 1);
        } else {
            $productsInBaskets = new ProductsInBaskets();
            $productsInBaskets->setBasketId($basket);
            $productsInBaskets->setProductId($product);
            $productsInBaskets->setProductCount(1);
        }
        $em->persist($productsInBaskets);
        $em->flush();

        $route = $request->server->get('HTTP_REFERER');
        return $this->redirect($route);
    }

    private function removeFromBasket(Request $request, int $basketId, ManagerRegistry $doctrine): Response
    {
        $productId = $request->get('product_id');
        $product = $doctrine->getRepository(Products::class)->find($productId);
        if (!$product)
            throw $this->createNotFoundException();
        $productsInBaskets = $doctrine->getRepository(ProductsInBaskets::class)->findOneBy(['basket_id' => $basketId, 'product_id' => $productId]);
        if (!$productsInBaskets)
            throw $this->createNotFoundException();
        $entityManager = $doctrine->getManager();
        $entityManager->remove($productsInBaskets);
        $entityManager->flush();
        $route = $request->server->get('HTTP_REFERER');
        return $this->redirect($route);
    }

    private function recountBasket(Request $request, int $basketId, ManagerRegistry $doctrine, $subAction): Response
    {
        $productId = $request->get('product_id');
        $product = $doctrine->getRepository(Products::class)->find($productId);

        if (!$product || $subAction === '')
            throw $this->createNotFoundException();
        $productsInBaskets = $doctrine->getRepository(ProductsInBaskets::class)->findOneBy(['basket_id' => $basketId, 'product_id' => $productId]);
        $entityManager = $doctrine->getManager();
        if (!$productsInBaskets)
            throw $this->createNotFoundException();

        switch ($subAction) {
            case 'increase':
                $count = $productsInBaskets->getProductCount() + 1;
                break;
            case 'reduce':
                $count = $productsInBaskets->getProductCount() - 1;
                break;
        }
        if ($count <= 0) {
            $entityManager->remove($productsInBaskets);
        } else {
            $productsInBaskets->setProductCount($count);
            $entityManager->persist($productsInBaskets);
        }
        $entityManager->flush();
        $route = $request->server->get('HTTP_REFERER');
        return $this->redirect($route);
    }

    private function createBasket($em): int
    {
        $basket = new Baskets();
        $date = new DateTimeImmutable();
        $basket->setCreatedAt($date);
        $em->persist($basket);
        $em->flush();
        return $basket->getId();
    }

    private function setCoockie(string $cookieName, int $cookieValue): void
    {
        $expires = time() + (365 * 24 * 60 * 60);
        $cookie = Cookie::create($cookieName, $cookieValue, $expires);
        $response = new Response();
        $response->headers->setCookie($cookie);
        $response->sendHeaders();
    }

}
