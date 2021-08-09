<?php

namespace App\Controller\Main;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
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
    public function home(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $user = $this->getUser();

        return $this->render('main/home.html.twig', [
            'controller_name' => 'HomeController',
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

        return $this->render('main/index.html.twig', [
            'products' => $category->getProducts(),
            'categories' => $categories,
            'user' => $user,
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
        $categories = $product->getCategories();
        $randomProducts = $categories[0]->getProducts();

        return $this->render('main/show.html.twig', [
            'product' => $product,
            'caracteristics' => $caracteristics,
            'reviews' => $reviews,
            'randomProducts' => $randomProducts,
            'user' => $user,
            'categories' => $categories,
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
        return $this->render('main/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
            'user' => $user,
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

}
