<?php

namespace App\Controller\Admin;

use App\Entity\Purchase;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PurchaseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Purchase::class;
    }

    
    public function configureActions(Actions $actions): Actions
    {
        $exportAction = Action::new('exportExcel', 'Export to Excel')
            ->linkToCrudAction('exportExcel')
            ->createAsGlobalAction()
            ->setIcon('fa fa-file-excel')
            ->setCssClass('btn btn-success');

        return $actions->add(Crud::PAGE_INDEX, $exportAction);
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

        
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $row = 2;
        foreach ($purchases as $purchase) {
            $sheet->setCellValue('A' . $row, $purchase->getItemName());
            $sheet->setCellValue('B' . $row, $purchase->getCustomer() ? $purchase->getCustomer()->getName() : 'N/A');
            $sheet->setCellValue('C' . $row, $purchase->getPrice());
            $sheet->setCellValue('D' . $row, $purchase->getQuantity());
            $sheet->setCellValue('E' . $row, $purchase->getTotalPrice());
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="purchase_report.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
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
            TextField::new('itemName', 'Product Name')
                ->setCssClass('fw-bold text-primary'),

            AssociationField::new('customer', 'Customer'),

            MoneyField::new('price', 'Unit Price')
                ->setCurrency('INR')
                ->setStoredAsCents(false),

            $quantityField,
            
            MoneyField::new('totalPrice', 'Total Amount')
                ->setCurrency('INR')
                ->setStoredAsCents(false)
                ->setCssClass('text-success fw-bold'),
        ];
    }
}