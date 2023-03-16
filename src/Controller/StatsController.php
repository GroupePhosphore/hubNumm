<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\External\Salesforce\SalesforceClient;
use App\External\Salesforce\Utils\SalesforceStatsParser;

#[Route('/api', name: 'api_')]
class StatsController extends AbstractController
{
    #[Route('/stats', name: 'stats')]
    public function index(Request $request, SalesforceClient $salesforceClient): JsonResponse
    {
         $start = $end = null;
        if (!empty($request->query->get("start")) && !empty($request->query->get("end"))) {
            $oneMonthDateInterval = new \DateInterval('P1M');
            
            $start = new \DateTime($request->query->get("start"));
            $end = new \DateTime($request->query->get("end"));
            $end->modify('last day of this month 23:59:59.999999');
            // Les redevances doivent être prise en compte un mois plus tard
            // Elles sont facturées et prises en compte
            // un mois après la période d'activité
            $startRedevance = new \DateTime($request->query->get("start"));
            $endRedevance = new \DateTime($request->query->get("end"));
            $startRedevance->add($oneMonthDateInterval);
            $endRedevance->add($oneMonthDateInterval);
            $endRedevance->modify('last day of this month 23:59:59.999999');
        }

        $parser = new SalesforceStatsParser();
        $parser->setStatsToFetch([
            $parser::STAT_CA_TOTAL_RESEAU,
            $parser::STAT_CA_TOTAL_TPE,
            $parser::STAT_CA_COMNAT,
            $parser::STAT_CA_RENOUVELLEMENT,
            $parser::STAT_CA_RIVASHOP,
            $parser::STAT_CA_LICENCE_DIRECTE,
            $parser::STAT_CA_PROGRAMME_CROISSANCE,
            $parser::STAT_CA_CLIENT_RIVALIS,
            $parser::STAT_CA_CM_CIC,
        ]);
        $stats = $salesforceClient->getInvoices(
            $start,
            $end,
            null,
            $parser->getSelectedAccounts()
        );

        $records = $stats->records;

        if (isset($stats->nextRecordsUrl) && $stats->done !== true) {
            do {
                $stats = $salesforceClient->getEagerResult($stats->nextRecordsUrl);
                $records = array_merge($records, $stats->records);
            } while (isset($stats->nextRecordsUrl) && $stats->done !== true);
        }

        $parserRedevance = new SalesforceStatsParser();
        $parserRedevance->setStatsToFetch([
            $parser::STAT_CA_REDEVANCE,
        ]);
        $stats = $salesforceClient->getInvoices(
            $startRedevance,
            $endRedevance,
            null,
            $parserRedevance->getSelectedAccounts()
        );

        $records = array_merge($records, $stats->records);

        if (isset($stats->nextRecordsUrl) && $stats->done !== true) {
            do {
                $stats = $salesforceClient->getEagerResult($stats->nextRecordsUrl);
                $records = array_merge($records, $stats->records);
            } while (isset($stats->nextRecordsUrl) && $stats->done !== true);
        }

        $parser->setStatsToFetch($parser::STAT_ALL);
        $formattedStats = $parser->formatStatsFromInvoices($records);

        return $this->json([
            'message' => 'Success',
            'data' => $formattedStats
        ]);
    }
}
