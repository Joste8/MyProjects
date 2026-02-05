<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Notification;
use App\Repository\ProductRepository;
use App\Repository\NotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Customer;

class DashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepo;
    private NotificationRepository $notifRepo;

    public function __construct(
        EntityManagerInterface $entityManager, 
        ProductRepository $productRepo, 
        NotificationRepository $notifRepo
    ) {
        $this->entityManager = $entityManager;
        $this->productRepo = $productRepo;
        $this->notifRepo = $notifRepo;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        
        $lowStockProducts = $this->productRepo->createQueryBuilder('p')
            ->where('p.stock < :limit')
            ->setParameter('limit', 5)
            ->getQuery()
            ->getResult();

        foreach ($lowStockProducts as $product) {
            
            $msg = 'Low stock alert: ' . $product->getName() . ' (Only ' . $product->getStock() . ' left!)';
            
            $exists = $this->notifRepo->findOneBy(['message' => $msg]);

            if (!$exists) {
                $notification = new Notification();
                $notification->setMessage($msg);
                $notification->setCreatedAt(new \DateTimeImmutable());
                $notification->setIsRead(false);
                $this->entityManager->persist($notification);
            }
        }
        $this->entityManager->flush();

        
        return $this->render('admin/dashboard.html.twig', [
            'notifications' => $this->notifRepo->findBy([], ['createdAt' => 'DESC'], 5),
            'lowStock' => $lowStockProducts,
            'products' => $this->productRepo->findAll(), 
            'totalProducts' => $this->productRepo->count([]),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()->setTitle('COFSO Admin');
    }

 public function configureMenuItems(): iterable
{
    yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
    yield MenuItem::linkToCrud('Products (CRUD)', 'fa fa-box', Product::class);
    yield MenuItem::linkToCrud('Customers', 'fas fa-users', Customer::class);

    yield MenuItem::linkToRoute('Purchase Module', 'fa fa-shopping-cart', 'admin_purchase');
    yield MenuItem::linkToRoute('Category Module', 'fas fa-tags', 'app_category_index');
    yield MenuItem::linkToRoute('SubCategory Module', 'fas fa-tags', 'app_subcategory_index');
}
}