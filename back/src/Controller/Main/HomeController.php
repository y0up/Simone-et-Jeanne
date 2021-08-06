<?php

namespace App\Controller\Main;

use App\Entity\Product;
use App\Entity\Category;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
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

        return $this->render('main/home.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $categories,
            
        ]);
    }

    /**
     * @Route("/all", name="all_product", methods={"GET"})
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        
        return $this->render('main/index.html.twig', [
            'products' => $productRepository->findAll(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{slug}", name="cat_product", methods={"GET"})
     */
    public function SearchByCat(Category $category, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('main/index.html.twig', [
            'products' => $category->getProducts(),
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/product/{slug}", name="product_show", methods={"GET"})
     */
    public function show(Product $product, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $caracteristics = $product->getCaracteristic();
        $reviews = $product->getReviews();
        $categories = $product->getCategories();
        $randomProducts = $categories[0]->getProducts();

        return $this->render('main/show.html.twig', [
            'product' => $product,
            'caracteristics' => $caracteristics,
            'reviews' => $reviews,
            'randomProducts' => $randomProducts,
        ]);
    }

    /**
     * @Route("/new", name="new_product", methods={"GET"})
     */
    public function SearchByNew(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        $products = $productRepository->findBy(
            ['new' => 'Yes']
        );
        return $this->render('main/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }

}
