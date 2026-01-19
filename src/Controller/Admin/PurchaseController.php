<?php

namespace App\Controller\Admin;

use App\Entity\ProductAttributeValue;
use App\Entity\StockLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
    'ea' => null, 
]);
    }

    #[Route('/admin/purchase/process', name: 'admin_purchase_process', methods: ['POST'])]
   // PurchaseController.php ഉള്ളിൽ

public function processPurchase(Request $request, EntityManagerInterface $em): Response
{
    $variantId = $request->request->get('variant_id');
    $quantity = (int) $request->request->get('quantity');
    $customerName = $request->request->get('customer_name');

    $variant = $em->getRepository(ProductAttributeValue::class)->find($variantId);

    if (!$variant) {
        $this->addFlash('danger', 'Product variant not found!');
        return $this->redirectToRoute('admin_purchase');
    }

    // പർച്ചേസ് ചെയ്യാനുള്ള ക്വാണ്ടിറ്റി സ്റ്റോക്കിനേക്കാൾ കൂടുതലാണോ എന്ന് പരിശോധിക്കുന്നു
    if ($quantity > $variant->getStock()) {
        $this->addFlash('danger', sprintf(
            'Insufficient stock! Available: %d, but you tried to purchase: %d',
            $variant->getStock(),
            $quantity
        ));
        return $this->redirectToRoute('admin_purchase');
    }

    // സ്റ്റോക്ക് കുറയ്ക്കുന്നു
    $variant->setStock($variant->getStock() - $quantity);

    // പർച്ചേസ് ലോഗ് സേവ് ചെയ്യുന്നു
    $log = new StockLog();
    $log->setCustomerName($customerName);
    $log->setProduct($variant->getProduct());
    $log->setVariant($variant);
    $log->setQuantity($quantity);
    $log->setCreatedAt(new \DateTimeImmutable());

    $em->persist($log);
    $em->flush();

    $this->addFlash('success', 'Purchase completed successfully!');
    return $this->redirectToRoute('admin_purchase');
}
}