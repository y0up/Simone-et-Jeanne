<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\CartItem;
use App\Entity\ShoppingSession;
use App\Repository\CartItemRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ShoppingSessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }
    
    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(ShoppingSessionRepository $shoppingSessionRepository, CartItemRepository $cartItemRepository, CategoryRepository $categoryRepository): Response
    {

        $categories = $categoryRepository->findAll();
        $user = $this->getUser();

        if ($user) {
            $shoppingSession = $shoppingSessionRepository->findOneBy([
                'user' => $user,
            ]);
            $cartItems = $cartItemRepository->findBy([
                'shoppingSession' => $shoppingSession,
            ]);
        } else {
            $shoppingSession = $shoppingSessionRepository->findOneBy([
                'id' => $this->requestStack->getSession()->get('shoppingSession'),
            ]);
            $cartItems = $cartItemRepository->findBy([
                'shoppingSession' => $shoppingSession,
            ]);
        }

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
        $session = $this->requestStack->getSession();

        if ($user) {
            $shoppingSession = $shoppingSessionRepository->findOneBy([
                'user' => $user,
            ]);
            $cartItem = $cartItemRepository->findOneBy([
                'shoppingSession' => $shoppingSession,
                'product' => $product,
            ]);
        } else {
            $shoppingSession = $shoppingSessionRepository->findOneBy([
                'id' => $session->get('shoppingSession'),
            ]);
            $cartItem = $cartItemRepository->findOneBy([
                'shoppingSession' => $shoppingSession,
                'product' => $product,
            ]);
        }

        if ($shoppingSession == NULL && $user != NULL) {
            $shoppingSession = new ShoppingSession;
            $shoppingSession->setUser($user);
        } elseif ($shoppingSession == NULL) {
            $shoppingSession = new ShoppingSession;
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
        $session->set('shoppingSession', $shoppingSession->getId());
        
        return $this->json(['message' => 'Produit ajouté']);

    }

    /**
     * @Route("/cart/item/delete", name="cart_item_delete", methods={"GET", "POST"})
     */
    public function delete(Request $request, CartItemRepository $cartItemRepository): Response
    {
        $cartItem = $cartItemRepository->findOneBy(['id' => $request->get('id')]);

        if ($this->isCsrfTokenValid('delete'.$cartItem->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cartItem);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cart_index', [], Response::HTTP_SEE_OTHER);
    }
}
