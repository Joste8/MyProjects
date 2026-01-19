<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductStockController extends AbstractController
{
    #[Route('/admin/product/stock-update', name: 'admin_product_stock_update', methods: ['POST'])]
    public function updateStock(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $productId = $request->request->get('product_id');
        $qty = (int) $request->request->get('quantity');

        if (!$productId || $qty <= 0) {
            $this->addFlash('danger', 'Invalid input');
            return $this->redirectToRoute('admin', [
                'crudControllerFqcn' => ProductCrudController::class
            ]);
        }

        $product = $em->getRepository(Product::class)->find($productId);

        if (!$product) {
            $this->addFlash('danger', 'Product not found');
            return $this->redirectToRoute('admin', [
                'crudControllerFqcn' => ProductCrudController::class
            ]);
        }

        // 🔥 Example: simple stock field
        $product->setStock(
            $product->getStock() - $qty
        );

        $em->flush();

        $this->addFlash('success', 'Stock updated successfully');

        return $this->redirectToRoute('admin', [
            'crudControllerFqcn' => ProductCrudController::class
        ]);
    }
}
