<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use App\Repository\ProductAttributeValueRepository;
use App\Repository\StockLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductManageController extends AbstractController
{
    #[Route('/admin/product-manage', name: 'admin_product_manage')]
    public function index(
        ProductRepository $productRepository,
        ProductAttributeValueRepository $variantRepository,
        StockLogRepository $stockLogRepository
    ): Response {
        return $this->render('admin/product/index.html.twig', [
            'products'  => $productRepository->findAll(),
            'variants'  => $variantRepository->findAll(),
            'stockLogs' => $stockLogRepository->findBy(
                [],
                ['createdAt' => 'DESC'],
                10
            ),
        ]);
    }
}
