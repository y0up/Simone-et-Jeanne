<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Adress;
use App\Entity\Review;
use App\Entity\Payment;
use App\Entity\Product;
use App\Entity\CartItem;
use App\Entity\Category;
use App\Entity\Shipping;
use App\Entity\OrderItem;
use App\Entity\OrderAdress;
use App\Entity\OrderDetail;
use App\Entity\Caracteristic;
use App\Entity\PaymentDetail;
use App\Entity\ShoppingSession;
use App\Entity\CaracteristicDetail;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    protected $slugger;
    private $encoder;

    public function __construct(UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger)
    {
        $this->passwordHasher = $passwordHasher;
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $productList = [];
        $userList = [];
        $categoryList = [];
        $caracteristicList = [];
        $cartItemList = [];
        $shipping = [];
        $adressStatus = array("adresse de livraison", "adresse de facturation");
        $orderStatusList = array("prepared", "shipped", "treated");
  
        //create shipping possibilities
        $shipping = new Shipping();
        $shipping->setTitle("atelier");
        $shipping->setPrice(0);
        $manager->persist($shipping);
        $shippingList[] = $shipping;
        
        $shipping = new Shipping();
        $shipping->setTitle("mondialrelay");
        $shipping->setPrice(10);
        $manager->persist($shipping);
        $shippingList[] = $shipping;

        for ($i=0; $i < 5; $i++) { 
            $category = new Category();

            $category->setName($faker->word);
            $category->setDescription($faker->text);
            $category->setSlug(strtolower($this->slugger->slug($category->getName())));

            $manager->persist($category);
            $categoryList[] = $category;
        } 

        for ($i=0; $i < 5; $i++) { 
            $randomNumber = $faker->numberBetween($min = 1, $max = 3);
            $caracteristic = new Caracteristic();

            $caracteristic->setName($faker->word);
            
            for ($j=0; $j < $randomNumber; $j++) { 
                $caracteristicDetail = new CaracteristicDetail();
    
                $caracteristicDetail->setName($faker->word);
                $caracteristicDetail->setInfo($faker->text);
                $caracteristicDetail->setCaracteristic($caracteristic);
    
                $manager->persist($caracteristicDetail);
            }

            $manager->persist($caracteristic);
            $caracteristicList[] = $caracteristic;
        }

        for ($i=0; $i < 20; $i++) { 
            $product = new Product();
            $product->setName($faker->word);
            $product->setBrand($faker->company);
            $product->setDescription($faker->text);
            $product->setPrice($faker->randomFloat($nbMaxDecimals = 2, $min = 15, $max = 100));
            $product->setQuantity($faker->numberBetween($min = 0, $max = 1000));
            $product->addCategory($faker->randomElement($categoryList));
            $product->addCaracteristic($faker->randomElement($caracteristicList));
            $product->setNew($faker->boolean($chanceOfGettingTrue = 25));
            $product->setSlug(strtolower($this->slugger->slug($product->getName())));
            $productList[] = $product;

            for ($j=0; $j < 3; $j++) { 
                $image = new Image();
                $image->setName($faker->imageUrl($width = 640, $height = 480));
                $image->setProduct($product);
                $manager->persist($image);
            }
            
            $manager->persist($product);
        }

        $randomProductObject = $faker->randomElement($productList);


        // Create admin user
        $user = new User();            
        $user->setEmail('simoneetjeanne@gmail.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'GirlBossSJ33'));
        $user->setFirstName('Alexia');
        $user->setLastName('Deschamps');
        $user->setSlug(strtolower($this->slugger->slug($user->getfirstName(), '-', $user->getLastName())));
        $user->setDateOfBirth(new \DateTime('20-01-1992'));
        $manager->persist($user);

        for ($i=0; $i < 100; $i++) { 
            $randomNumber = $faker->numberBetween($min = 1, $max = 3);
            $user = new User();
            $shoppingSession = new ShoppingSession();
            
            $user->setEmail($faker->freeEmail);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'sasuuke'));
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setphoneNumber($faker->tollFreePhoneNumber);
            $user->setSlug(strtolower($this->slugger->slug($user->getfirstName(), '-', $user->getLastName())));
            for ($j=0; $j < $faker->numberBetween($min = 0, $max = 3); $j++) { 
                $user->addFavorite($faker->randomElement($productList));
            }
            $user->setDateOfBirth(new \DateTime($faker->date($format = 'Y-m-d', $max = 'now')));

            for ($j=0; $j < $randomNumber; $j++) { 
                $adress = new Adress();
                
                $adress->setFirstName($faker->firstName);
                $adress->setLastName($faker->lastName);
                $adress->setLine1($faker->streetAddress);
                $adress->setCity($faker->city);
                $adress->setPostalCode($faker->randomNumber($nbDigits = 5, $strict = true));
                $adress->setCountry($faker->country);
                $adress->setUser($user);
                $adress->setTelephone($faker->tollFreePhoneNumber);
                $adress->setStatus("adresse de livraison");
                
                $manager->persist($adress);
                $user->addAdress($adress);
            }    
            
            
            for ($j=0; $j < $randomNumber; $j++) { 
                $payment = new Payment();
                
                $payment->setType($faker->creditCardType());
                $payment->setName($faker->firstName.' '.$faker->lastName);
                $payment->setAccountNumber($faker->numberBetween($min = 700000, $max = 100000000));
                $payment->setExpiry($faker->creditCardExpirationDate());
                $payment->setProvider($faker->word);
                $payment->setUser($user);
                
                $manager->persist($payment);
                $user->addPayment($payment);
                
            }            
            $manager->persist($user);
            $userList[] = $user;
        }

        //visitor
        $l=0;

        for ($i=0; $i < 20; $i++) { 
            $total = 0;
            $cartItemProductList = [];
            $cartItemList = [];
            $shoppingSession = new ShoppingSession();
            
            // creation of the cart
            for ($j=0; $j < 5; $j++) { 
                $randomProductObject = $faker->randomElement($productList);
                if (in_array($randomProductObject, $cartItemProductList) == false) {
                
                    $cartItem = new CartItem();
                    
                    $cartItem->setProduct($randomProductObject);
                    $cartItem->setQuantity($faker->numberBetween($min = 1, $max = 5));
                    $cartItem->setShoppingSession($shoppingSession);

                    $total = $total + ($randomProductObject->getPrice() * $cartItem->getQuantity());
                    
                    $manager->persist($cartItem);
                    $cartItemProductList[] = $randomProductObject;
                    $cartItemList[] = $cartItem;
                }
            }
            $shoppingSession->setShipping($faker->randomElement($shippingList));
            $shippingPrice = $shoppingSession->getShipping()->getPrice();
            $total = $total + ($shippingPrice);
            $shoppingSession->setTotal($total);
            $manager->persist($shoppingSession);

            // creation of the order

            $orderDetail = new OrderDetail();
            $l = $l++;
            $commandNumber = date('d').date('m').date('y').'0000'.$l;
            $orderDetail->setTotal($total);
            $orderDetail->setShippingPrice($shippingPrice);
            $orderDetail->setShippingChoice($shoppingSession->getShipping()->getTitle());
            $orderDetail->setStatus($faker->randomElement($orderStatusList));
            $orderDetail->setCommandNumber($commandNumber);

            $paymentDetail = new PaymentDetail();

            $paymentDetail->setAmount($total);
            $paymentDetail->setProvider("crédit Agricole");
            $paymentDetail->setStatus("payed");
            $manager->persist($paymentDetail);

            $orderDetail->setPaymentDetail($paymentDetail);

            $manager->persist($orderDetail);
        
            $orderAdress = new OrderAdress();
            $orderAdress->setLine1($faker->streetAddress);
            $orderAdress->setCity($faker->city);
            $orderAdress->setPostalCode($faker->randomNumber($nbDigits = 5, $strict = true));
            $orderAdress->setCountry($faker->country);
            $orderAdress->setFirstName($faker->firstName);
            $orderAdress->setLastName($faker->lastName);
            $orderAdress->setPhoneNumber($faker->tollFreePhoneNumber);
            $orderAdress->setStatus("adresse de livraison");
            $orderAdress->setOrderDetail($orderDetail);
            $orderAdress->setCreatedAt(new \DateTimeImmutable($faker->date($format = 'Y-m-d', $max = 'now')));
            
            $manager->persist($orderAdress);

            foreach ($cartItemList as $a) {
                $orderItem = new OrderItem();
                $orderItem->setProduct($a->getProduct());
                $orderItem->setQuantity($a->getQuantity());
                $orderItem->setOrderDetail($orderDetail);

                $manager->persist($orderItem);
            }

            
        }

        //connected
        $l=0;
        $userConnectedList = [];
        for ($i=0; $i < 10; $i++) { 
            $cartItemProductList = [];
            $cartItemList = [];
            $userConnected = $faker->randomElement($userList);
            if (in_array($userConnected, $userConnectedList) == false) {


                    $shoppingSession = new ShoppingSession();
                    // creation of the cart
                    for ($j=0; $j < 5; $j++) { 
                        $randomProductObject = $faker->randomElement($productList);
                        if (in_array($randomProductObject, $cartItemProductList) == false) {
                            
                            $cartItem = new CartItem();
                            
                            $cartItem->setProduct($randomProductObject);
                            $cartItem->setQuantity($faker->numberBetween($min = 1, $max = 5));
                            $cartItem->setShoppingSession($shoppingSession);
                            
                            $total = $total + ($randomProductObject->getPrice() * $cartItem->getQuantity());
                            
                            $manager->persist($cartItem);
                            
                            for ($k=0; $k < $randomNumber; $k++) { 
                                
                                $review = new Review();
                                
                                $review->setTitle($faker->sentence($nbWords = 6, $variableNbWords = true));
                                $review->setContent($faker->text);
                                $review->setRate($faker->numberBetween($min = 0, $max = 5));
                                $review->setUser($userConnected);
                                $review->setCreatedAt(new \DateTime($faker->date($format = 'Y-m-d', $max = 'now')));
                                
                                $manager->persist($review);
                                
                                $randomProductObject->addReview($review);
                                
                                $manager->persist($randomProductObject);
                            }
                            
                            $cartItemProductList[] = $randomProductObject;
                            $cartItemList[] = $cartItem;
                        }
                    }
                    
                    $shoppingSession->setUser($userConnected);
                    $shoppingSession->setShipping($faker->randomElement($shippingList));
                    $shippingPrice = $shoppingSession->getShipping()->getPrice();
                    $total = $total + ($shippingPrice);
                    $shoppingSession->setTotal($total);
                    $manager->persist($shoppingSession);
                    
                    // creation of the order
                    
                    $orderDetail = new OrderDetail();
                    
                    $orderDetail->setUser($userConnected);
                    $commandNumber = date('d').date('m').date('y').'0000'.$l;
                    $orderDetail->setTotal($total);
                    $orderDetail->setShippingPrice($shippingPrice);
                    $orderDetail->setShippingChoice($shoppingSession->getShipping()->getTitle());
                    $orderDetail->setStatus($faker->randomElement($orderStatusList));
                    $orderDetail->setCommandNumber($commandNumber);
                    
                    $userPayment = $faker->randomElement($userConnected->getPayments());
                    
                    $paymentDetail = new PaymentDetail();
                    
                    $paymentDetail->setAmount($total);
                    $paymentDetail->setProvider($userPayment->getProvider());
                    $paymentDetail->setStatus("payed");
                    $manager->persist($paymentDetail);
                    
                    $orderDetail->setPaymentDetail($paymentDetail);
                    
                    $manager->persist($orderDetail);
                    
                    $manager->persist($userConnected);
                    
                    $userAdress = $faker->randomElement($userConnected->getAdresses());
                    
                    $orderAdress = new OrderAdress();
                    $orderAdress->setLine1($userAdress->getLine1());
                    $orderAdress->setCity($userAdress->getCity());
                    $orderAdress->setPostalCode($userAdress->getPostalCode());
                    $orderAdress->setCountry($userAdress->getCountry());
                    $orderAdress->setFirstName($userConnected->getFirstName());
                    $orderAdress->setLastName($userConnected->getLastName());
                    $orderAdress->setPhoneNumber($userAdress->getTelephone());
                    $orderAdress->setStatus("adresse de livraison");
                    $orderAdress->setOrderDetail($orderDetail);
                    $orderAdress->setCreatedAt(new \DateTimeImmutable($faker->date($format = 'Y-m-d', $max = 'now')));
                    
                    $manager->persist($orderAdress);
                    
                    foreach ($cartItemList as $a) {
                        $orderItem = new OrderItem();
                        $orderItem->setProduct($a->getProduct());
                        $orderItem->setQuantity($a->getQuantity());
                        $orderItem->setOrderDetail($orderDetail);
                        
                        $manager->persist($orderItem);
                        
                    }
                    
                    $randomNumber = $faker->numberBetween($min = 0, $max = 3);
                    $userConnectedList[] = $userConnected;
                }
                $l = $l++;
            }


        $manager->flush();
    }
}
