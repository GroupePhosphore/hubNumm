<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\External\Salesforce\SalesforceClient;

use App\Stats\InvoiceBasedStatistics\CAClientRivalis;
use App\Stats\InvoiceBasedStatistics\CACMCIC;
use App\Stats\InvoiceBasedStatistics\CAComnat;
use App\Stats\InvoiceBasedStatistics\CALicenceDirecte;
use App\Stats\InvoiceBasedStatistics\CAProgrammeCroissance;
use App\Stats\InvoiceBasedStatistics\CARedevance;
use App\Stats\InvoiceBasedStatistics\CARenouvellement;
use App\Stats\InvoiceBasedStatistics\CARivashop;
use App\Stats\InvoiceBasedStatistics\CATotalReseau;
use App\Stats\InvoiceBasedStatistics\CATotalTpe;
use App\Stats\InvoiceBasedStatistics\CARivacentrale;

use App\Stats\InvoiceBasedStatistics\CAClientRivalisWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CACMCICWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CAComnatWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CALicenceDirecteWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CAProgrammeCroissanceWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CARedevanceWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CARenouvellementWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CARivashopWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CATotalReseauWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CATotalTpeWithoutIdentifiedThirdParty;
use App\Stats\InvoiceBasedStatistics\CARivacentraleWithoutIdentifiedThirdParty;

#[Route('/api', name: 'api_')]
class StatsController extends AbstractController
{
    #[Route('/stats/kerry', name: 'stats')]
    public function index(Request $request, SalesforceClient $salesforceClient): JsonResponse
    {
        $caTotal = new CATotalReseau($salesforceClient);
        $caTotalTPE = new CATotalTpe($salesforceClient);
        $caRivashop = new CARivashop($salesforceClient);
        $caRenouvellement = new CARenouvellement($salesforceClient);
        $caRedevance = new CARedevance($salesforceClient);
        $caProgrammeCroissance = new CAProgrammeCroissance($salesforceClient);
        $caLicenceDirecte = new CALicenceDirecte($salesforceClient);
        $caComnat = new CAComnat($salesforceClient);
        $caCmCic = new CACMCIC($salesforceClient);
        $caClientRivalis = new CAClientRivalis($salesforceClient);
		$caRivacentrale = new CARivacentrale($salesforceClient);
        
        $start = $end = null;
        if (!empty($request->query->get("start")) && !empty($request->query->get("end"))) {
            $oneMonthDateInterval = new \DateInterval('P1M');
            
            $start = new \DateTime($request->query->get("start"));
            $end = new \DateTime($request->query->get("end"));
            $end->modify('last day of this month 23:59:59.999999');

            $caTotal->setPeriod($start, $end);
            $caTotalTPE->setPeriod($start, $end);
            $caRivashop->setPeriod($start, $end);
            $caRenouvellement->setPeriod($start, $end);
            $caProgrammeCroissance->setPeriod($start, $end);
            $caLicenceDirecte->setPeriod($start, $end);
            $caComnat->setPeriod($start, $end);
            $caCmCic->setPeriod($start, $end);
            $caClientRivalis->setPeriod($start, $end);
            $caRivacentrale->setPeriod($start, $end);
            // Les redevances doivent être prise en compte un mois plus tard
            // Elles sont facturées et prises en compte
            // un mois après la période d'activité
            $startRedevance = new \DateTime($request->query->get("start"));
            $endRedevance = new \DateTime($request->query->get("end"));
            $startRedevance->add($oneMonthDateInterval);
            $endRedevance->add($oneMonthDateInterval);
            $endRedevance->modify('last day of this month 23:59:59.999999');
            $caRedevance->setPeriod($startRedevance, $endRedevance);
        }

        $formattedStats[$caTotal->getSlug()] = $caTotal->getResult();
        $formattedStats[$caTotalTPE->getSlug()] = $caTotalTPE->getResult();
        $formattedStats[$caRivashop->getSlug()] = $caRivashop->getResult();
        $formattedStats[$caRenouvellement->getSlug()] = $caRenouvellement->getResult();
        $formattedStats[$caRedevance->getSlug()] = $caRedevance->getResult();
        $formattedStats[$caProgrammeCroissance->getSlug()] = $caProgrammeCroissance->getResult();
        $formattedStats[$caLicenceDirecte->getSlug()] = $caLicenceDirecte->getResult();
        $formattedStats[$caComnat->getSlug()] = $caComnat->getResult();
        $formattedStats[$caCmCic->getSlug()] = $caCmCic->getResult();
        $formattedStats[$caClientRivalis->getSlug()] = $caClientRivalis->getResult();
        $formattedStats[$caRivacentrale->getSlug()] = $caRivacentrale->getResult();

        return $this->json([
            'message' => 'Success',
            'data' => $formattedStats
        ]);
    }


    #[Route('/stats/kerry-withoutid', name: 'stats-without-id')]
    public function statsWithoutId(Request $request, SalesforceClient $salesforceClient): JsonResponse
    {
        $caRivashop = new CARivashopWithoutIdentifiedThirdParty($salesforceClient);
        $caRenouvellement = new CARenouvellementWithoutIdentifiedThirdParty($salesforceClient);
        $caRedevance = new CARedevanceWithoutIdentifiedThirdParty($salesforceClient);
        $caProgrammeCroissance = new CAProgrammeCroissanceWithoutIdentifiedThirdParty($salesforceClient);
        $caLicenceDirecte = new CALicenceDirecteWithoutIdentifiedThirdParty($salesforceClient);
        $caComnat = new CAComnatWithoutIdentifiedThirdParty($salesforceClient);
        $caCmCic = new CACMCICWithoutIdentifiedThirdParty($salesforceClient);
        $caClientRivalis = new CAClientRivalisWithoutIdentifiedThirdParty($salesforceClient);
		$caRivacentrale = new CARivacentraleWithoutIdentifiedThirdParty($salesforceClient);
        
        $start = $end = null;
        if (!empty($request->query->get("start")) && !empty($request->query->get("end"))) {
            $oneMonthDateInterval = new \DateInterval('P1M');
            
            $start = new \DateTime($request->query->get("start"));
            $end = new \DateTime($request->query->get("end"));
            $end->modify('last day of this month 23:59:59.999999');

            $caRivashop->setPeriod($start, $end);
            $caRenouvellement->setPeriod($start, $end);
            $caProgrammeCroissance->setPeriod($start, $end);
            $caLicenceDirecte->setPeriod($start, $end);
            $caComnat->setPeriod($start, $end);
            $caCmCic->setPeriod($start, $end);
            $caClientRivalis->setPeriod($start, $end);
            $caRivacentrale->setPeriod($start, $end);
            // Les redevances doivent être prise en compte un mois plus tard
            // Elles sont facturées et prises en compte
            // un mois après la période d'activité
            $startRedevance = new \DateTime($request->query->get("start"));
            $endRedevance = new \DateTime($request->query->get("end"));
            $startRedevance->add($oneMonthDateInterval);
            $endRedevance->add($oneMonthDateInterval);
            $endRedevance->modify('last day of this month 23:59:59.999999');
            $caRedevance->setPeriod($startRedevance, $endRedevance);
        }

        $formattedStats[$caRivashop->getSlug()] = $caRivashop->getResult();
        $formattedStats[$caRenouvellement->getSlug()] = $caRenouvellement->getResult();
        $formattedStats[$caRedevance->getSlug()] = $caRedevance->getResult();
        $formattedStats[$caProgrammeCroissance->getSlug()] = $caProgrammeCroissance->getResult();
        $formattedStats[$caLicenceDirecte->getSlug()] = $caLicenceDirecte->getResult();
        $formattedStats[$caComnat->getSlug()] = $caComnat->getResult();
        $formattedStats[$caCmCic->getSlug()] = $caCmCic->getResult();
        $formattedStats[$caClientRivalis->getSlug()] = $caClientRivalis->getResult();
        $formattedStats[$caRivacentrale->getSlug()] = $caRivacentrale->getResult();

        $lastStats = [];
        foreach($formattedStats as $statName => $statByConseiller) {
            foreach($statByConseiller as $conseiller => $value) {
                $lastStats[$conseiller][$statName] = $value;
            }
        }

        return $this->json([
            'message' => 'Success',
            'data' => $lastStats
        ]);
    }
}
