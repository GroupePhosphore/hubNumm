<?php

namespace App\Controller;

use App\External\Salesforce\SalesforceClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class InvoiceManagementController extends AbstractController
{
    #[Route('/invoice/management', name: 'app_invoice_management')]
    public function updateNumberFromCSVFile(Request $request, SalesforceClient $salesforceClient): JsonResponse
    {
        $journal = $request->get('journal');
        $entity = $request->get('entity');
        $establishment = $request->get('establishment');
        $file = $request->files->get('file');

        if ($file->getMimeType() !== 'text/csv') {
            throw new \Exception('File type not allowed');
        }

        $destination = $this->getParameter('kernel.project_dir').'/public/uploads';

        $savedFile = $file->move($destination);

        $rows = [];
        if (($fp = fopen($savedFile->getPathname(), 'r')) !== false) {
            while (($row = fgetcsv($fp)) !== false) {
                $rows[] = $row;
            }
        }

        unlink($savedFile->getPathname());

        if ($rows[0] !== ['DocumentId', 'Numéro', 'Nouveau numéro', 'DateEffet', 'DateCreation']) {
            throw new \Exception('File columns error');
        }

        array_shift($rows);

        $updatedRows = [];
        $errorRows = [];
       foreach($rows as $row) {
            $date = date_create_from_format('d/m/Y H:i:s.u P', $row[3]);
            try {
                $updated = $salesforceClient->fetch->updateInvoiceBaseLine(
                    $row[1],
                    $row[2],
                    $journal,
                    $entity,
                    $establishment,
                    $date
                );
                $updatedRows[] = ['row' => $row];
            } catch (\Exception $e) {
                $errorRows[] = [
                    'row' => $row,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $this->json([
            'message' => 'Finish',
            'error' => $errorRows,
            'success' => $updatedRows
        ]);
    }
}
