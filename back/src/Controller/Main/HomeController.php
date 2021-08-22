<?php

namespace App\Controller\Main;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(CategoryRepository $categoryRepository, UserRepository $userRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $user = $this->getUser();
        $userAdmin = $userRepository->find(1690);
        $products = $userAdmin->getFavorite();
        

        return $this->render('main/home.html.twig', [
            'controller_name' => 'HomeController',
            'products' => $products,
            'categories' => $categories,
            'user' => $user,
            
        ]);
    }

    /**
     * @Route("/all", name="all_product", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();
        
        return $this->render('main/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categories' => $categories,
            'user' => $user,
        ]);
    }

    /**
     * @Route("/category/{slug}", name="cat_product", methods={"GET"})
     */
    public function SearchByCat(Category $category, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();
        $products = $category->getProducts();

        $brandUniqueList = [];
        foreach ($products as $a) {
            $brand = $a->getBrand();
            if (in_array($brand, $brandUniqueList) == false) {
                $brandUniqueList[] = $a->getBrand();
            }
        }

        return $this->render('main/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'user' => $user,
            'category' => $category,
            'brands' => $brandUniqueList,
        ]);
    }

    /**
     * @Route("/product/{slug}", name="product_show", methods={"GET"})
     */
    public function show(Product $product, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();
        $caracteristics = $product->getCaracteristic();
        $reviews = $product->getReviews();
        $categoriesbc = $product->getCategories();
        $randomProducts = $categories[0]->getProducts();

        return $this->render('main/show.html.twig', [
            'product' => $product,
            'caracteristics' => $caracteristics,
            'reviews' => $reviews,
            'randomProducts' => $randomProducts,
            'user' => $user,
            'categories' => $categories,
            'categoriesbc' => $categoriesbc,
        ]);
    }

    /**
     * @Route("/new", name="new_product", methods={"GET"})
     */
    public function SearchByNew(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();

        
        $products = $productRepository->findBy(
            ['new' => 'Yes']
        );
        
        $brandUniqueList = [];
        foreach ($products as $a) {
            $brands[] = $a->getBrand();
            if (in_array($brands, $brandUniqueList) == false) {
                $brandUniqueList[] = $a->getBrand();
            }
        }

        return $this->render('main/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'user' => $user,
            'brands' => $brandUniqueList,
        ]);
    }

    /**
     * @Route("/favorite", name="favorite", methods={"GET", "POST"})
     */
    public function Favorite(Request $request, ProductRepository $productRepository, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $product = $productRepository->findOneBy(['id' => $request->get('productId')]);

        if ($product->likedByUser($user)) {

            $product->removeUser($user);
            $manager->persist($product);
            $manager->flush();

            return $this->json(['message' => 'il aime plus.']);
        } 

            $product->addUser($user);
            $manager->persist($product);
            $manager->flush();

            return $this->json(['message' => 'il aime maintenant.']);
     

    }

    /**
     * @Route("/About", name="about")
     */
    public function About(CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $categories = $categoryRepository->findAll();

        return $this->render('main/about.html.twig', [
            'categories' => $categories,
            'user' => $user,
        ]);
     

    }
}
