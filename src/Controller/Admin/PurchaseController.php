<?php

namespace App\Controller\Admin;

use App\Entity\Purchase;
use App\Entity\ProductVariant;
use App\Entity\Customer; 
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
        $customers = $entityManager->getRepository(Customer::class)->findAll();

        return $this->render('admin/purchase/index.html.twig', [
            'history'   => $history,
            'variants'  => $variants,
            'customers' => $customers,
        ]);
    }

    #[Route('/admin/purchase/new', name: 'admin_purchase_process', methods: ['POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $variantId = $request->request->get('variant_id');
        $customerId = $request->request->get('customer_id'); 
        $quantity = (int) $request->request->get('quantity');

        $variant = $entityManager->getRepository(ProductVariant::class)->find($variantId);
        $customer = $entityManager->getRepository(Customer::class)->find($customerId);

        
        if (!$variant || !$customer) {
            $this->addFlash('danger', 'Product or Customer not found!');
            return $this->redirectToRoute('admin_purchase');
        }

        
        if ($variant->getStock() < $quantity) {
            $this->addFlash('danger', 'Insufficient stock!');
            return $this->redirectToRoute('admin_purchase');
        }

        
        $sale = new Purchase();
$sale->setItemName($variant->getProduct()->getName());
$sale->setCustomer($customer);
$sale->setQuantity($quantity);
$sale->setPrice($variant->getProduct()->getPrice());
$sale->setProductVariant($variant->getValue());
$sale->setPurchasedAt(new \DateTimeImmutable());
        
      $sale->setStockBalance($variant->getStock());

        $entityManager->persist($sale);
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