<?php

namespace App\Controller;

use Stripe\Price;
use Stripe\Stripe;
use Stripe\Product;
use Stripe\Checkout\Session;
use App\Repository\CartItemRepository;
use App\Repository\CategoryRepository;
use App\Repository\ShoppingSessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CheckoutController extends AbstractController
{
    /**
     * @Route("/payment", name="payment")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $user = $this->getUser();
        
        return $this->render('main/checkout/payment.html.twig', [
            'controller_name' => 'CheckoutController',
            'categories' => $categories,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout($stripeSK, ShoppingSessionRepository $shoppingSessionRepository, CartItemRepository $cartItemRepository): Response
    {
        $currency = 'eur';

        $user = $this->getUser();

        $shoppingSession = $shoppingSessionRepository->findOneBy([
            'user' => $user,
        ]);
        $cartItems = $cartItemRepository->findBy([
            'shoppingSession' => $shoppingSession,
        ]);
        

        Stripe::setApiKey($stripeSK);

        foreach ($cartItems as $cartItem) {
            $stripeProduct = Product::create([
                'name' => $cartItem->getProduct()->getName(),
            ]);

            $stripePrice = Price::create([
                'product' => $stripeProduct->id,
                'currency' => $currency,
                'unit_amount' => ($cartItem->getProduct()->getPrice())*100,
            ]);
            $lineItems[] = ['price' => $stripePrice->id, 'quantity' => $cartItem->getQuantity(),];
        }

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);

          return $this->redirect($session->url, 303);
    }

    /**
     * @Route("/success-url", name="success_url")
     */
    public function successUrl(): Response
    {

        $user = $this->getUser();


        return $this->redirectToRoute('command_show', ['slug' => $user->getSlug()], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/cancel-url", name="cancel_url")
     */
    public function cancelUrl(): Response
    {
        $user = $this->getUser();
        return $this->redirectToRoute('cart_index', ['slug' => $user->getSlug()], Response::HTTP_SEE_OTHER);
    }
}
