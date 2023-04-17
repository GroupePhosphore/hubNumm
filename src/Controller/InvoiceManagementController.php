<?php

namespace App\Controller;

use App\External\Salesforce\SalesforceClient;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class InvoiceManagementController extends AbstractController
{
    #[Route('/invoice/management', name: 'app_invoice_management')]
    public function updateNumberFromCSVFile(Request $request, SalesforceClient $salesforceClient, LoggerInterface $logger): JsonResponse
    {
        $journal = $request->get('journal');
        $entity = $request->get('entity');
        $establishment = $request->get('establishment');
        $file = $request->files->get('file');

        try {
            if ($file->getMimeType() !== 'text/csv') {
                throw new \Exception('File type not allowed');
            }
        

            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';

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
            foreach ($rows as $row) {
                $date = new \DateTime($row[3]);
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

            $logger->info('Numero de factures - Synchro finie: ');
            $logger->info('Erreurs : ' . json_encode($errorRows));
            $logger->info('Succes : ' . json_encode($updatedRows));

            return $this->json([
                'message' => 'Finish',
                'error' => $errorRows,
                'success' => $updatedRows
            ]);
        } catch (\Exception $e) {
            $logger('Error : ' . $e->getMessage());
            return $this->json([
                'message' => 'Error',
                'details' => $e->getMessage()
            ], 400);
        }
    }
}
