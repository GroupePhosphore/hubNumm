<?php

namespace App\Controller;

use App\External\Salesforce\SalesforceClient;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class InvoiceManagementController extends AbstractController
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    # A utiliser pour modifier les numeros de facture en cas de probleme avec les imports des factures depuis Henrri
    #[Route('/invoice/management', name: 'app_invoice_management')]
    public function updateNumberFromCSVFile(Request $request, SalesforceClient $salesforceClient): JsonResponse
    {
        $journal = $request->get('journal');
        $entity = $request->get('entity');
        $establishment = $request->get('establishment');
        $file = $request->files->get('file');

        try {
            $savedFile = $this->saveFile($file);
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

            $this->logger->info('Numero de factures - Synchro finie: ');
            $this->logger->info('Erreurs : ' . json_encode($errorRows));
            $this->logger->info('Succes : ' . json_encode($updatedRows));

            return $this->json([
                'message' => 'Finish',
                'error' => $errorRows,
                'success' => $updatedRows
            ]);
        } catch (\Exception $e) {
            $this->logger->error('Error : ' . $e->getMessage());
            return $this->json([
                'message' => 'Error',
                'details' => $e->getMessage()
            ], 400);
        }
    }


    #[Route('/invoice/switchEntity', name: 'app_invoice_switchEntity')]
    public function switchBetweenEntities(Request $request, SalesforceClient $client)
    {
        $rows = [];
        $updatedRows = [];
        $errorRows = [];

        $file = $request->files->get('file');
        $destinationEntity = $request->get('destinationEntity');
        $destinationEstablishment = $request->get('destinationEstablishment');
        $destinationJournal = $request->get('destinationJournal');

        try {
            $savedFile = $this->saveFile($file);

            if (!($fp = fopen($savedFile->getPathname(), 'r'))) {
                throw new \Exception("Could not read file", 1);
            }

            while (($row = fgetcsv($fp, null, ';')) !== false) {
                try {
                    $rows[] = $this->parseSingleRow($row, $destinationEntity, $destinationJournal, $destinationEstablishment);
                } catch (\Exception $e) {
                    $this->logger->error('Import error - IMPINV2 <br> ----------------------------------<br>' .$e->getMessage() . '<br> ----------------------------------<br>' .json_encode($row));
                }
            }

            fclose($fp);
            unlink($savedFile->getPathname());

            array_shift($rows);

            foreach ($rows as $row) {
                if (!$updatedRow = $client->fetch->findLineId($row)) {
                    $errorRows[] = $row;
                    continue;
                }
                $updatedRows[] = $updatedRow;
            }

            return $this->json([
                'error' => $errorRows,
                'success' => $updatedRow
            ]);

        } catch (\Exception $e) {
            $this->logger->error('Import error - IMPINV1<br> ----------------------------------<br>' .$e->getMessage() . '<br> ----------------------------------<br>');
            return $this->json([
                'error' => $e->getMessage()
                ], Response::HTTP_BAD_REQUEST);
        }
    }

    private function parseSingleRow(array $row, string $destinationEntity, string $destinationJournal, string $destinationEstablishment)
    {
        return [
            'entity' => $row[0],
            'establishment' => $row[1],
            'journal' => $row[2],
            'account' => $row[4],
            'thirdParty' => $row[5],
            'date' => $row[6],
            'label' => $row[10],
            'piece' => $row[9],
            'reference'=> $row[11],
            'amount_debit'=> $row[13],
            'amount_credit'=> $row[14],
            'destinationJournal' => $destinationJournal,
            'destinationEntity' => $destinationEntity,
            'destinationEstablishment' => $destinationEstablishment,
        ];
    }

    private function saveFile($file)
    {
        $fileIsCSV = $file->getMimeType() === 'text/csv' || ($file->getMimeType() === 'text/plain' && substr($file->getClientOriginalName(), -4, 4) === '.csv');
        if (!$fileIsCSV) {
            throw new \Exception('File type not allowed');
        }

        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $savedFile = $file->move($destination);
        return $savedFile;
    }
}
