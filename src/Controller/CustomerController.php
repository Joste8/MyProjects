<?php

namespace App\Controller;

use App\Entity\Customer;
use App\Entity\Purchase;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use App\Repository\ProductVariantRepository;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/customer')]
final class CustomerController extends AbstractController
{
    #[Route(name: 'app_customer_index', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository): Response
    {
        return $this->render('customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/history', name: 'app_customer_history')]
    public function history(
        Customer $customer, 
        ProductVariantRepository $variantRepository, 
        PurchaseRepository $purchaseRepository
    ): Response {
        return $this->render('customer/history.html.twig', [
            'customer' => $customer,
            'variants' => $variantRepository->findAll(),
            'purchases' => $purchaseRepository->findBy(['customer' => $customer]), 
        ]);
    }

    #[Route('/{id}/purchase/new', name: 'app_customer_purchase_new', methods: ['POST'])]
    public function addPurchase(
        Request $request, 
        Customer $customer, 
        ProductVariantRepository $variantRepository, 
        EntityManagerInterface $entityManager
    ): Response {
        $variantId = $request->request->get('variant_id');
        $quantity = (int) $request->request->get('quantity');
        $variant = $variantRepository->find($variantId);

        if ($variant && $quantity > 0) {
            $purchase = new Purchase();
            
            $purchase->setCustomer($customer);
            $purchase->setProductVariant((string) $variant); 
            $purchase->setQuantity($quantity);
          
            $product = $variant->getProduct();
            $purchase->setItemName($product->getName()); 
            $purchase->setPrice((float) $product->getPrice());
            
            $total = (float) $product->getPrice() * $quantity;
            $purchase->setTotalPrice($total);

            $purchase->setPurchasedAt(new \DateTimeImmutable());

            $entityManager->persist($purchase);
            $entityManager->flush();
            
            $this->addFlash('success', 'Purchase added successfully!');
        } else {
            $this->addFlash('error', 'Invalid product or quantity!');
        }

        return $this->redirectToRoute('app_customer_history', ['id' => $customer->getId()]);
    }

    #[Route('/purchase/{id}/delete', name: 'app_purchase_delete', methods: ['POST'])]
    public function deletePurchase(Purchase $purchase, EntityManagerInterface $entityManager): Response
    {
        $customerId = $purchase->getCustomer()->getId();
        $entityManager->remove($purchase);
        $entityManager->flush();

        $this->addFlash('success', 'Purchase deleted!');
        return $this->redirectToRoute('app_customer_history', ['id' => $customerId]);
    }

   
    #[Route('/{id}/edit', name: 'app_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Customer updated successfully!');

            return $this->redirectToRoute('app_customer_history', ['id' => $customer->getId()]);
        }

        return $this->render('customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_customer_show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}