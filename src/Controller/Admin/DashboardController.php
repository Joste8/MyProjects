<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Entity\Customer;
use App\Entity\Purchase;
use App\Entity\Notification;
use App\Repository\ProductRepository;
use App\Repository\NotificationRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;


class DashboardController extends AbstractDashboardController
{
    private EntityManagerInterface $entityManager;
    private ProductRepository $productRepo;
    private NotificationRepository $notifRepo;
    private CustomerRepository $customerRepo;

    public function __construct(
        EntityManagerInterface $entityManager, 
        ProductRepository $productRepo, 
        NotificationRepository $notifRepo,
        CustomerRepository $customerRepo
    ) {
        $this->entityManager = $entityManager;
        $this->productRepo = $productRepo;
        $this->notifRepo = $notifRepo;
        $this->customerRepo = $customerRepo;
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

        $totalCustomers = $this->customerRepo->count([]);
        $allCustomers = $this->customerRepo->findAll();
        $totalSales = 0;
        foreach ($allCustomers as $customer) {
            $totalSales += $customer->getGrandTotal();
        }

        return $this->render('admin/dashboard.html.twig', [
            'notifications' => $this->notifRepo->findBy([], ['createdAt' => 'DESC'], 5),
            'lowStock' => $lowStockProducts,
            'products' => $this->productRepo->findAll(), 
            'totalProducts' => $this->productRepo->count([]),
            'totalCustomers' => $totalCustomers,
            'totalSales' => $totalSales,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<b>COFSO</b> Admin')
            ->renderContentMaximized();
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addHtmlContentToHead('
                <style>
                    .table {
                        border-collapse: separate !important;
                        border-spacing: 0 !important;
                        border: 1px solid #444 !important;
                        border-radius: 12px !important;
                        overflow: hidden !important;
                        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5) !important;
                        margin-top: 20px !important;
                    }
                    .table thead th {
                        background-color: #2c2f33 !important;
                        color: #5865f2 !important; 
                        font-weight: 700 !important;
                        padding: 16px !important;
                        border-bottom: 2px solid #444 !important;
                        border-right: 1px solid #3d3d3d !important;
                    }
                    .table tbody td {
                        padding: 14px !important;
                        border-right: 1px solid #333 !important;
                        border-bottom: 1px solid #333 !important;
                        font-size: 0.95rem !important;
                    }
                    .table td:nth-last-child(2) {
                        font-weight: bold !important;
                        color: #2ecc71 !important; 
                        font-size: 1.1rem !important;
                        text-align: right !important;
                        background-color: rgba(46, 204, 113, 0.05) !important;
                    }
                    .table td:nth-child(2) {
                        font-weight: 600 !important;
                        color: #ffffff !important;
                    }
                    .table tbody tr:hover {
                        background-color: #247ec2 !important;
                        transition: 0.3s ease;
                    }
                </style>
            ');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Inventory');
        yield MenuItem::linkToCrud('Products', 'fa fa-box-open', Product::class);
        yield MenuItem::section('Sales');
        yield MenuItem::linkToCrud('Purchase', 'fas fa-shopping-cart', Purchase::class);
        yield MenuItem::section('Categories');
        yield MenuItem::linkToRoute('Category', 'fas fa-tags', 'app_category_index');
        yield MenuItem::linkToRoute('SubCategory', 'fas fa-tag', 'app_subcategory_index');
        yield MenuItem::section('CRM');
        yield MenuItem::linkToCrud('Customers', 'fas fa-users', Customer::class);
    }
}