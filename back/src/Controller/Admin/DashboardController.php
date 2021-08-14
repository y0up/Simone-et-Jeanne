<?php

namespace App\Controller\Admin;

use App\Entity\Caracteristic;
use App\Entity\CaracteristicDetail;
use App\Entity\Category;
use App\Entity\OrderDetail;
use App\Entity\Product;
use App\Entity\Review;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Simone & Jeanne');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Produits', 'fas fa-archive', Product::class);
        yield MenuItem::linkToCrud('Catégories', 'fab fa-buffer', Category::class);
        yield MenuItem::linkToCrud('Caractéristique', 'fas fa-cog', Caracteristic::class);
        yield MenuItem::linkToCrud('Détail des Caraqutéristiques', 'fas fa-cogs', CaracteristicDetail::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-clipboard', OrderDetail::class);
        yield MenuItem::linkToCrud('Avis', 'fas fa-comment', Review::class);
    }
}
