<?php

namespace App\Controller\Admin;

use App\Entity\Purchase;
use App\Entity\ProductVariant;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    #[Route('/admin/purchase', name: 'admin_purchase')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $history = $entityManager->getRepository(Purchase::class)->findAll();
        $variants = $entityManager->getRepository(ProductVariant::class)->findAll();

        return $this->render('admin/purchase/index.html.twig', [
            'history'  => $history,
            'variants' => $variants,
        ]);
    }

    #[Route('/admin/purchase/new', name: 'admin_purchase_process', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $variantId = $request->request->get('variant_id');
        $customerName = $request->request->get('customer_name');
        $quantity = (int) $request->request->get('quantity');

        $variant = $entityManager->getRepository(ProductVariant::class)->find($variantId);

        if (!$variant) {
            $this->addFlash('danger', 'Product not found!');
            return $this->redirectToRoute('admin_purchase');
        }

        if ($variant->getStock() < $quantity) {
            $this->addFlash('danger', 'Insufficient stock!');
            return $this->redirectToRoute('admin_purchase');
        }

        $sale = new Purchase();
        $sale->setItemName($variant->getProduct()->getName());
        $sale->setCustomerName($customerName);
        $sale->setQuantity($quantity);
        $sale->setPrice($variant->getProduct()->getPrice());
        $sale->setProductVariant($variant);
        $sale->setPurchasedAt(new \DateTimeImmutable());


        $variant->setStock($variant->getStock() - $quantity);

        $entityManager->persist($sale);
        $entityManager->persist($variant);
        $entityManager->flush();

        $this->addFlash('success', 'Purchase recorded successfully!');
        return $this->redirectToRoute('admin_purchase');
    }

    #[Route('/admin/purchase/receipt/{id}', name: 'admin_purchase_receipt')]
    public function receipt(Purchase $purchase): Response
    {
        return $this->render('admin/purchase/receipt.html.twig', [
            'purchase' => $purchase,
        ]);
    }
}