<?php

namespace App\Controller;

use App\Entity\CartItem;
use App\Entity\Product;
use App\Entity\ShoppingSession;
use App\Repository\CartItemRepository;
use App\Repository\ShoppingSessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(): Response
    {
        return $this->render('main/cart/index.html.twig', [
            'controller_name' => 'CartController',
        ]);
    }

    /**
     * @Route("/cart/add/{slug}", name="cart_add")
     */
    public function add(Product $product, EntityManagerInterface $manager, ShoppingSessionRepository $shoppingSessionRepository, CartItemRepository $cartItemRepository)
    {
        $user = $this->getUser();
        $shoppingSession = $shoppingSessionRepository->findOneBy([
            'user' => $user,
        ]);
        $cartItem = $cartItemRepository->findOneBy([
            'shoppingSession' => $shoppingSession,
            'product' => $product,
        ]);

        if ($shoppingSession == false) {
            $shoppingSession = new ShoppingSession;
            $shoppingSession->setUser($user);
        }

        if ($cartItem) {
            $quantity = ($cartItem->getQuantity()) + 1;
            $cartItem->setQuantity($quantity);
        } else {

            $cartItem = new CartItem;
            $cartItem->setShoppingSession($shoppingSession);
            $cartItem->setProduct($product);
            $cartItem->setQuantity(1);
        }

        $total = (($product->getPrice())*($cartItem->getQuantity()) + ($shoppingSession->getTotal()) );

        $shoppingSession->setTotal($total);
        $manager->persist($cartItem);
        $manager->persist($shoppingSession);
        $manager->flush();

    }
}
