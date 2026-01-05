<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Attribute;
use App\Entity\AttributeValue;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect(
            $adminUrlGenerator
                ->setController(ProductCrudController::class)
                ->generateUrl()
        );
    }

    public function configureMenuItems(): iterable
{
    yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
    yield MenuItem::section('Catalog');

    yield MenuItem::linkToCrud('Products', 'fas fa-box', Product::class);

    // താഴെ പറയുന്നവയ്ക്ക് CRUD Controller ഉണ്ടെങ്കിൽ മാത്രം കമന്റ് മാറ്റുക
    // yield MenuItem::linkToCrud('Attributes', 'fas fa-tags', Attribute::class);
    // yield MenuItem::linkToCrud('Attribute Values', 'fas fa-list', AttributeValue::class);
}
}