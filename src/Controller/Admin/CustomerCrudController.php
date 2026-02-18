<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField; 
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

class CustomerCrudController extends AbstractCrudController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public static function getEntityFqcn(): string
    {
        return Customer::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        // PDF ഡൗൺലോഡ് ചെയ്യാനുള്ള പുതിയ Action
        $downloadPdf = Action::new('downloadPdf', 'Download PDF', 'fa fa-file-pdf')
            ->linkToRoute('customer_pdf_download', function (Customer $customer) {
                return ['id' => $customer->getId()];
            })
            ->setCssClass('btn btn-danger');

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->add(Crud::PAGE_DETAIL, $downloadPdf);
    }

    #[Route('/admin/customer/{id}/pdf', name: 'customer_pdf_download')]
    public function downloadPdf(Customer $customer): Response
    {
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($pdfOptions);

        // Twig ടെംപ്ലേറ്റ് വഴി HTML റെൻഡർ ചെയ്യുന്നു
        $html = $this->renderView('admin/customer/pdf_export.html.twig', [
            'customer' => $customer,
        ]);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="Customer_Details.pdf"'
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Customer Name');
        yield EmailField::new('email', 'Email Address');
        yield TelephoneField::new('phone', 'Phone Number');
        yield TextareaField::new('address', 'Address');

        if ($pageName === Crud::PAGE_DETAIL) {
            yield FormField::addPanel('Insights & History');

            $allProducts = $this->entityManager->getRepository(Product::class)->findAll();
            
            yield IdField::new('id', 'Smart Recommendations')
                ->onlyOnDetail()
                ->setTemplatePath('admin/customer/recommendations.html.twig')
                ->setCustomOptions([
                    'allProducts' => $allProducts,
                ]);

            yield CollectionField::new('purchases', 'Purchase History')
                ->onlyOnDetail()
                ->setTemplatePath('admin/purchase/history.html.twig');

            yield MoneyField::new('grandTotal', 'Overall Spent')
                ->setCurrency('INR')
                ->setStoredAsCents(false)
                ->onlyOnDetail();
        }
    }
}