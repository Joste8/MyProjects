<?php

namespace App\Controller\Admin;

use App\Entity\ProductAttributeValue;
use App\Entity\StockLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class PurchaseController extends AbstractController
{
    #[Route('/admin/purchase', name: 'admin_purchase')]
    public function index(EntityManagerInterface $em): Response
    {
        $variants = $em->getRepository(ProductAttributeValue::class)->findAll();
        $history = $em->getRepository(StockLog::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('admin/purchase/index.html.twig', [
            'variants' => $variants,
            'history' => $history,
        ]);
    }

    #[Route('/admin/purchase/process', name: 'admin_purchase_process', methods: ['POST'])]
    public function processPurchase(Request $request, EntityManagerInterface $em): Response
    {
        $variantId = $request->request->get('variant_id');
        $quantity = (int) $request->request->get('quantity');
        $customerName = $request->request->get('customer_name');

        $variant = $em->getRepository(ProductAttributeValue::class)->find($variantId);

        if (!$variant) {
            $this->addFlash('danger', 'Product not found!');
            return $this->redirectToRoute('admin_purchase');
        }

        $currentStock = $variant->getStock() ?? 0;
        $variant->setStock($currentStock - $quantity);

        $log = new StockLog();
        $log->setCustomerName($customerName);
        $log->setProduct($variant->getProduct());
        $log->setVariant($variant);
        $log->setQuantity($quantity);
        $log->setPreviousStock($currentStock); 
        $log->setCreatedAt(new \DateTimeImmutable());

        $em->persist($log);
        $em->flush();

        $this->addFlash('success', 'Purchase completed!');
        return $this->redirectToRoute('admin_purchase');
    }

    #[Route('/admin/purchase/receipt/{id}', name: 'admin_purchase_receipt')]
    public function generateReceipt(int $id, EntityManagerInterface $em): Response
    {
        $log = $em->getRepository(StockLog::class)->find($id);

        if (!$log) {
            throw $this->createNotFoundException('Receipt not found');
        }

        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        $html = $this->renderView('admin/purchase/receipt_pdf.html.twig', ['log' => $log]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'portrait'); 
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="receipt.pdf"'
        ]);
    }
}