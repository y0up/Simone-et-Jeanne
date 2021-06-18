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
use App\Entity\Inventory;
use App\Entity\OrderItem;
use App\Entity\OrderDetail;
use App\Entity\Caracteristic;
use App\Entity\PaymentDetail;
use App\Entity\ShoppingSession;
use App\Entity\CaracteristicDetail;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create();
        $productList = [];
        $userList = [];
        $categoryList = [];
        $caracteristicList = [];
        
        $total = 0;
        $randomNumber = $faker->numberBetween($min = 1, $max = 3);

        for ($i=0; $i < 5; $i++) { 
            $category = new Category();

            $category->setName($faker->word);
            $category->setDescription($faker->text);

            $manager->persist($category);
            $categoryList[] = $category;
        } 

        for ($i=0; $i < 5; $i++) { 
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
            $inventory = new Inventory();

            $inventory->setQuantity($faker->numberBetween($min = 0, $max = 1000));

            $manager->persist($inventory);

            $product = new Product();


            $product->setName($faker->word);
            $product->setDescription($faker->text);
            $product->setPrice($faker->randomFloat($nbMaxDecimals = 2, $min = 15, $max = 100));
            $product->setInventory($inventory);
            $product->addCategory($faker->randomElement($categoryList));
            $product->addCaracteristic($faker->randomElement($caracteristicList));
            $productList[] = $product;
            
            $manager->persist($product);
        }

        $randomProductObject = $faker->randomElement($productList);

        for ($i=0; $i < 30; $i++) { 
            $user = new User();
            $shoppingSession = new ShoppingSession();
            
            $user->setEmail($faker->freeEmail);
            $user->setUsername($faker->userName);
            $user->setRoles(['ROLE_USER']);
            $user->setPassword($this->encoder->encodePassword($user, 'user'));
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setphoneNumber($faker->tollFreePhoneNumber);

            $manager->persist($user);
            $userList[] = $user;

            for ($j=0; $j < $randomNumber; $j++) { 
                $adress = new Adress();
            
                $adress->setLine1($faker->streetAddress);
                $adress->setCity($faker->city);
                $adress->setPostalCode($faker->randomNumber($nbDigits = 5, $strict = true));
                $adress->setCountry($faker->country);
                $adress->setUser($user);
                $adress->setTelephone($faker->tollFreePhoneNumber);
            
                $manager->persist($adress);
            }

            $shoppingSession->setUser($user);
            $cartItemProduct = [];
            
            for ($j=0; $j < 5; $j++) { 
                $randomProductObject = $faker->randomElement($productList);
                if (in_array($randomProductObject, $cartItemProduct) == false) {
                
                    $cartItem = new CartItem();
                    
                    
                    $cartItem->setProduct($randomProductObject);
                    $cartItem->setQuantity($faker->numberBetween($min = 1, $max = 5));
                    $cartItem->setShoppingSession($shoppingSession);
                    $total = $total + ($randomProductObject->getPrice() * $cartItem->getQuantity());
                    
                    $manager->persist($cartItem);
                    $cartItemProduct[] = $randomProductObject;
                }
            }
            
            $shoppingSession->setTotal($total);
            
            for ($j=0; $j < $randomNumber; $j++) { 
                $payment = new Payment();
                
                $payment->setType($faker->creditCardType());
                $payment->setAccountNumber($faker->numberBetween($min = 700000, $max = 100000000));
                $payment->setExpiry($faker->creditCardExpirationDate());
                $payment->setProvider($faker->word);
                $payment->setUser($user);
                
                $manager->persist($payment);
                
            }
            
            $manager->persist($shoppingSession);
            
        }
        
        for ($i=0; $i < 100; $i++) { 
            $review = new Review();

            $review->setContent($faker->text);
            $review->setRate($faker->numberBetween($min = 0, $max = 5));
            $review->setUser($faker->randomElement($userList));
            $review->setProduct($faker->randomElement($productList));

            $manager->persist($review);
        }

        $manager->flush();
    }
}
