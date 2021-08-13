<?php

namespace App\Controller;

use Stripe\Price;
use Stripe\Stripe;
use Stripe\Product;
use DateTimeImmutable;
use App\Entity\OrderItem;
use App\Entity\OrderAdress;
use App\Entity\OrderDetail;
use Stripe\Checkout\Session;
use App\Repository\CartItemRepository;
use App\Repository\CategoryRepository;
use App\Repository\ShippingRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ShoppingSessionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;

class CheckoutController extends AbstractController
{

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

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
        

        Stripe::setApiKey($stripeSK);

        foreach ($cartItems as $cartItem) {
            $cartItemImage = $cartItem->getProduct()->getimages()[0]->getName();
            $stripeProduct = Product::create([
                'name' => $cartItem->getProduct()->getName(),
                'images' => [$cartItemImage],
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
            'success_url' => $this->generateUrl('success_url', ['shoppingSessionId' => $shoppingSession->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('cancel_url', [], UrlGeneratorInterface::ABSOLUTE_URL),
          ]);

          return $this->redirect($session->url, 303);
    }

    
    /**
     * @Route("/livraion/mondialrelay", name="mondialrelay")
     */
    public function mondialrealy(ShoppingSessionRepository $shoppingSessionRepository, ShippingRepository $shippingRepository, EntityManagerInterface $manager, Request $request): Response
    {
        $user = $this->getUser();
        $datas = json_decode($request->getContent());
        
        $session = $this->requestStack->getSession();
        $session->set('data', $datas);
        
        if ($user) {
            $shoppingSession = $shoppingSessionRepository->findOneBy([
                'user' => $user,
            ]);
            
            $shipping = $shippingRepository->findOneBy([
                'title' => 'mondialrelay',
            ]);
        } else {
            $shoppingSession = $shoppingSessionRepository->findOneBy([
                'id' => $this->requestStack->getSession()->get('shoppingSession'),
            ]);
            
            $shipping = $shippingRepository->findOneBy([
                'title' => 'mondialrelay',
            ]);
        }

        $shoppingSession = $shoppingSessionRepository->findOneBy([
            'user' => $user,
        ]);
        
        $shipping = $shippingRepository->findOneBy([
            'title' => 'mondialrelay',
        ]);
        
        $shoppingSession->setShipping($shipping);
        
        $manager->persist($shoppingSession);
        $manager->flush();


        return $this->json(['message' => 'vous avez choisit la livraison en point relay.']);
    }
    
    /**
     * @Route("/livraion/workshop", name="workshop")
     */
    public function workshop(ShoppingSessionRepository $shoppingSessionRepository, ShippingRepository $shippingRepository, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        
        if ($user) {
            $shoppingSession = $shoppingSessionRepository->findOneBy([
                'user' => $user,
            ]);
            
            $shipping = $shippingRepository->findOneBy([
                'title' => 'atelier',
            ]);
        } else {
            $shoppingSession = $shoppingSessionRepository->findOneBy([
                'id' => $this->requestStack->getSession()->get('shoppingSession'),
            ]);
            
            $shipping = $shippingRepository->findOneBy([
                'title' => 'atelier',
            ]);
        }

        
        $shoppingSession->setShipping($shipping);
        
        $manager->persist($shoppingSession);
        $manager->flush();
        
        return $this->json(['message' => 'vous avez choisit latelier.']);
    }

    /**
     * @Route("/success-url", name="success_url", methods={"GET", "POST"})
     */
    public function successUrl(ShoppingSessionRepository $shoppingSessionRepository, Request $request, CartItemRepository $cartItemRepository, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $session = $this->requestStack->getSession();
        $dataAdress = $session->get('data');
        
        
        $shoppingSession = $shoppingSessionRepository->findOneBy([
            'id' => $request->query->get('shoppingSessionId'),
        ]);

        $cartItems = $cartItemRepository->findBy([
            'shoppingSession' => $shoppingSession,
        ]);
        
        $orderDetail = new OrderDetail();
        $orderDetail->setTotal($shoppingSession->getTotal());
        $orderDetail->setShippingPrice(($shoppingSession->getShipping())->getPrice());
        $orderDetail->setStatus(0);
        $orderDetail->setCommandNumber(date("Ymd")."00".($shoppingSession->getId()));
        $orderDetail->setUser($user);
        $orderDetail->setShippingChoice(($shoppingSession->getShipping())->gettitle());

        $orderAdress = new OrderAdress();
        $orderAdress->setOrderDetail($orderDetail);
        if ($orderDetail->getShippingChoice() == 'atelier') {
            $orderAdress->setName('SIMONEETJEANNE');
            $orderAdress->setLine1('15 rue Descartes');
            $orderAdress->setCity('BORDEAUX');
            $orderAdress->setPostalCode('33000');
            $orderAdress->setCountry('France');
            $orderAdress->setStatus('adresse de livraison');
            $date = new DateTimeImmutable();
            $orderAdress->setCreatedAt($date);
        } elseif ($orderDetail->getShippingChoice() == 'mondialrelay') {
            $orderAdress->setName($dataAdress->Nom);
            $orderAdress->setLine1($dataAdress->Adresse1);
            $orderAdress->setCity($dataAdress->Ville);
            $orderAdress->setPostalCode($dataAdress->CP);
            $orderAdress->setCountry($dataAdress->Pays);
            $orderAdress->setStatus('adresse de livraison');
            $date = new DateTimeImmutable();
            $orderAdress->setCreatedAt($date);
        }

        foreach ($cartItems as $cartItem) {
            $orderItem = new OrderItem();

            $orderItem->setOrderDetail($orderDetail);
            $orderItem->setProduct($cartItem->getProduct());
            $orderItem->setQuantity($cartItem->getQuantity());
            $manager->persist($orderItem);
            $inventory = $orderItem->getProduct()->getInventory();
            $newInventoryQuantity = ($inventory->getQuantity())+($orderItem->getQuantity()); 
            $inventory->setQuantity($newInventoryQuantity);
            $manager->persist($inventory);

        }

        $manager->persist($orderAdress);
        $manager->persist($orderDetail);
        $manager->remove($shoppingSession);
        $manager->flush();

        $session->remove('data');
        $session->remove('shoppingSession');



        if ($user == true) {
            return $this->redirectToRoute('command_show', ['slug' => $user->getSlug(), 'commandNumber' => $orderDetail->getCommandNumber()], Response::HTTP_SEE_OTHER);
            
        }
        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);

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



