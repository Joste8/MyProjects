<?php

namespace App\Controller\Admin;

use App\Entity\Task;
use App\Entity\Product;
use App\Entity\User;
use App\Entity\Course;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MyProjects');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Tasks', 'fa fa-list', Task::class);
        yield MenuItem::linkToCrud('Product', 'fa fa-list', Product::class);
        yield MenuItem::linkToCrud('User', 'fa fa-list', User::class);
        yield MenuItem::linkToCrud('Course', 'fa fa-book', Course::class);
    }
}
