<?php

namespace App\Controller\Admin;

use App\Entity\ProductAttributeValue;
use App\Entity\StockLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StockInController extends AbstractController
{
    #[Route('/admin/stock-in', name: 'admin_stock_in')]
    public function index(EntityManagerInterface $em): Response
    {
        $variants = $em->getRepository(ProductAttributeValue::class)->findAll();
        
        $history = $em->getRepository(StockLog::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/stock_in/index.html.twig', [
            'variants' => $variants,
            'history' => $history,
        ]);
    }

    #[Route('/admin/stock-in/process', name: 'admin_stock_in_process', methods: ['POST'])]
    public function processStockIn(Request $request, EntityManagerInterface $em): Response
    {
        $variantId = $request->request->get('variant_id');
        $quantity = (int) $request->request->get('quantity');
        $source = $request->request->get('source_name');

        $variant = $em->getRepository(ProductAttributeValue::class)->find($variantId);

        if ($variant) {
            $currentStock = $variant->getStock() ?? 0;
            
            $variant->setStock($currentStock + $quantity);

            $log = new StockLog();
            $log->setCustomerName('STOCK-IN: ' . $source);
            $log->setProduct($variant->getProduct());
            $log->setVariant($variant);
            $log->setQuantity($quantity); 
            $log->setPreviousStock($currentStock);
            $log->setCreatedAt(new \DateTimeImmutable());

            $em->persist($log);
            $em->flush();

            $this->addFlash('success', 'Stock updated successfully!');
        }

        return $this->redirectToRoute('admin_stock_in');
    }
}