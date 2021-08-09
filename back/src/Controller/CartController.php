<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\CartItem;
use App\Entity\ShoppingSession;
use App\Repository\CartItemRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ShoppingSessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(ShoppingSessionRepository $shoppingSessionRepository, CartItemRepository $cartItemRepository, CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();
        $user = $this->getUser();
        $shoppingSession = $shoppingSessionRepository->findOneBy([
            'user' => $user,
        ]);
        $cartItems = $cartItemRepository->findBy([
            'shoppingSession' => $shoppingSession,
        ]);



        return $this->render('main/cart/index.html.twig', [
            'controller_name' => 'CartController',
            'cartItems' => $cartItems,
            'categories' => $categories,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/cart/add/{slug}", name="cart_add")
     */
    public function add(Product $product, EntityManagerInterface $manager, ShoppingSessionRepository $shoppingSessionRepository, CartItemRepository $cartItemRepository): Response
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

        return $this->json(['message' => 'Produit ajouté']);

    }

    /**
     * @Route("/{slug}/cart/item/delete", name="cart_item_delete", methods={"GET", "POST"})
     */
    public function delete(User $user, Request $request, CartItemRepository $cartItemRepository): Response
    {

        $cartItem = $cartItemRepository->findOneBy(['id' => $request->get('id')]);

        if ($this->isCsrfTokenValid('delete'.$cartItem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cartItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cart_index', ['slug' => $user->getSlug()], Response::HTTP_SEE_OTHER);
    }
}
