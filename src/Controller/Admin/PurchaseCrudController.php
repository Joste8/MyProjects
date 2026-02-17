<?php

namespace App\Controller\Admin;

use App\Entity\Purchase;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField; 
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Response;
use Dompdf\Dompdf;
use Dompdf\Options;

class PurchaseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Purchase::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $exportExcel = Action::new('exportExcel', 'Export to Excel', 'fa fa-file-excel')
            ->linkToCrudAction('exportExcel')
            ->createAsGlobalAction()
            ->setCssClass('btn btn-success');

        $exportPdf = Action::new('exportPdf', 'Export to PDF', 'fa fa-file-pdf')
            ->linkToCrudAction('exportToPdf')
            ->createAsGlobalAction()
            ->setCssClass('btn btn-danger');

        return $actions
            ->add(Crud::PAGE_INDEX, $exportExcel)
            ->add(Crud::PAGE_INDEX, $exportPdf);
    }

    
    public function exportExcel()
    {
        $purchases = $this->container->get('doctrine')->getRepository(Purchase::class)->findAll();
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        $sheet->setCellValue('A1', 'Product Name');
        $sheet->setCellValue('B1', 'Customer');
        $sheet->setCellValue('C1', 'Unit Price (INR)');
        $sheet->setCellValue('D1', 'Qty');
        $sheet->setCellValue('E1', 'Total Amount');
        $sheet->setCellValue('F1', 'Status'); 

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        foreach ($purchases as $purchase) {
            $sheet->setCellValue('A' . $row, $purchase->getItemName());
            $sheet->setCellValue('B' . $row, $purchase->getCustomer() ? $purchase->getCustomer()->getName() : 'N/A');
            $sheet->setCellValue('C' . $row, $purchase->getPrice());
            $sheet->setCellValue('D' . $row, $purchase->getQuantity());
            $sheet->setCellValue('E' . $row, $purchase->getTotalPrice());
            $sheet->setCellValue('F' . $row, ucfirst($purchase->getStatus() ?? 'Pending'));
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="purchase_report.xlsx"');
        
        return $response;
    }

    public function exportToPdf(AdminContext $context): Response
{
    
    ini_set('memory_limit', '256M');

    $purchases = $this->container->get('doctrine')->getRepository(Purchase::class)->findAll();

    $pdfOptions = new Options();
    
    $pdfOptions->set('defaultFont', 'Helvetica');
    $pdfOptions->set('isHtml5ParserEnabled', true);
    $pdfOptions->set('isRemoteEnabled', true); 

    $dompdf = new Dompdf($pdfOptions);

    $html = $this->renderView('admin/pdf_template.html.twig', [
        'purchases' => $purchases,
    ]);

    try {
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return new Response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="purchase_report.pdf"',
        ]);
    } catch (\Exception $e) {
        
        return new Response("PDF Generation Error: " . $e->getMessage());
    }
}

    public function configureFields(string $pageName): iterable
    {
        $quantityField = IntegerField::new('quantity', 'Qty');
        if ($pageName === 'index') {
            $quantityField->formatValue(function ($value) {
                return sprintf('<span class="badge badge-info shadow-sm" style="padding: 5px 10px;">%s</span>', $value);
            });
        }

        return [
            TextField::new('itemName', 'Product Name')->setCssClass('fw-bold text-primary'),
            AssociationField::new('customer', 'Customer'),
            MoneyField::new('price', 'Unit Price')->setCurrency('INR')->setStoredAsCents(false),
            $quantityField,
            MoneyField::new('totalPrice', 'Total Amount')->setCurrency('INR')->setStoredAsCents(false)->setCssClass('text-success fw-bold'),
            ChoiceField::new('status', 'Payment Status')
                ->setChoices(['Paid' => 'paid', 'Pending' => 'pending', 'Failed' => 'failed'])
                ->renderAsBadges(['paid' => 'success', 'pending' => 'warning', 'failed' => 'danger']),
        ];
    }
}