<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\External\Salesforce\SalesforceClient;
use App\Stats\AccountBasedStatistics\ExternalExpensesStatistic;
use App\Stats\AccountBasedStatistics\OutsourcingStatistic;
use App\Stats\AccountBasedStatistics\SalesStatistic;
use App\Stats\AccountBasedStatistics\SocialExpensesStatistic;
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

use OpenApi\Annotations as OA;

#[Route('/api', name: 'api_')]
class StatsController extends AbstractCustomController
{
    /**
     * Returns a list of gross sales statistics for Kerry
     *
     * @param Request $request
     * @param SalesforceClient $salesforceClient
     * @return JsonResponse
     * @OA\Post(
     *  path="/api/stats/kerry",
     *  tags={"Statistiques"},
     *  security={ "bearer" },
     *  @OA\Parameter(
     *      name="start",
     *      in="query",
     *      required=true,
     *      description="Date de début des données au format YYYY-mm (le premier jour de la période est pris en compte)",
     *      example="2023-02"
     *  ),
     *  @OA\Parameter(
     *      name="end",
     *      in="query",
     *      required=true,
     *      description="Date de fin des données au format YYYY-mm (le dernier jour de la période est pris en compte)",
     *      example="2023-02"
     *  ),
     *  @OA\Response(
     *      response="200",
     *      description="Liste de CA triés par type et par conseillers",
     *      @OA\JsonContent(
     *          description="Réponse",
     *                  @OA\Property(
    *                       property="message",
    *                      type="string",
    *                      example="Success"
    *                 ),
    *               @OA\Property(
    *                   property="data",
     *                  type="object",
     *                  allOf={
     *
     *                      @OA\Schema(ref="#/components/schemas/StatisticClientRivalis"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticCMCIC"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticComnat"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticLeasis"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticLicenceDirecte"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticProgrammeCroissance"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticRedevance"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticRenouvellement"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticRivacentrale"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticRivashop"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticTotalReseau"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticTotalTPE"),
     *
     *                  }
     *              )
     *          )
     *      )
     *  )
     * )
     *
     */
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


    /**
     * Returns a list of gross sales statistics for Kerry
     *
     * @param Request $request
     * @param SalesforceClient $salesforceClient
     * @return JsonResponse
     * @OA\Post(
     *  path="/api/stats/kerry-withoutid",
     *  tags={"Statistiques"},
     *  security={ "bearer" },
     *  @OA\Parameter(
     *      name="start",
     *      in="query",
     *      required=true,
     *      description="Date de début des données au format YYYY-mm (le premier jour de la période est pris en compte)",
     *      example="2023-02"
     *  ),
     *  @OA\Parameter(
     *      name="end",
     *      in="query",
     *      required=true,
     *      description="Date de fin des données au format YYYY-mm (le dernier jour de la période est pris en compte)",
     *      example="2023-02"
     *  ),
     *  @OA\Response(
     *      response="200",
     *      description="Liste de CA triés par type et par conseillers dans le cas où le conseiller n'a pas d'ID Datalake renseigné dans Numm",
     *      @OA\JsonContent(
     *          description="Statistiques sans ID Datalake",
     *                  @OA\Property(
    *                       property="message",
    *                      type="string",
    *                      example="Success"
    *                 ),
    *               @OA\Property(
    *                   property="data",
     *                  allOf={
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDClientRivalis"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDCMCIC"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDComnat"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDLeasis"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDLicenceDirecte"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDProgrammeCroissance"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDRedevance"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDRenouvellement"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDRivacentrale"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDRivashop"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDTotalReseau"),
     *                      @OA\Schema(ref="#/components/schemas/StatisticWithoutIDTotalTPE"),
     *                  }
     *          )
     *      )
     *  )
     * )
     */
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




    /**
     * Returns a accounting data by analytics
     *
     * @param Request $request
     * @param SalesforceClient $salesforceClient
     * @return JsonResponse
     * @OA\Post(
     *  path="/api/stats/analyticCompta",
     *  tags={"Statistiques", "Analytique", "BU"},
     *  security={ "bearer" },
     *  @OA\Parameter(
     *      name="start",
     *      in="query",
     *      required=true,
     *      description="Date de début des données au format YYYY-mm (le premier jour de la période est pris en compte)",
     *      example="2023-02"
     *  ),
     *  @OA\Parameter(
     *      name="end",
     *      in="query",
     *      required=true,
     *      description="Date de fin des données au format YYYY-mm (le dernier jour de la période est pris en compte)",
     *      example="2023-02"
     *  ),
     *  @OA\Response(
     *      response="200",
     *      description="Liste de données analytiques par BU et par catégorie",
     *      @OA\JsonContent(
     *          description="Données de la comptabilité analytique, par BU et catégorie",
     *                  @OA\Property(
    *                       property="message",
    *                      type="string",
    *                      example="Success"
    *                 ),
    *               @OA\Property(
    *                   property="data",
     *                  allOf={
     *                      @OA\Schema(ref="#/components/schemas/ExternalExpenses"),
     *                      @OA\Schema(ref="#/components/schemas/Outsourcing"),
     *                      @OA\Schema(ref="#/components/schemas/Sales"),
     *                      @OA\Schema(ref="#/components/schemas/SocialExpenses"),

     *                  }
     *          )
     *      )
     *  )
     * )
     */
    #[Route('/stats/analyticCompta', name: 'stats-compta-analytique')]
    public function analyticStats(Request $request, SalesforceClient $salesforceClient)
    {
        $initialMemoryLimit = ini_get('memory_limit');
        ini_set('memory_limit', '2048M');
        $start = $end = null;
        $externalExpenses = new ExternalExpensesStatistic($salesforceClient);
        $socialExpenses = new SocialExpensesStatistic($salesforceClient);
        $outsourcings = new OutsourcingStatistic($salesforceClient);
        $sales = new SalesStatistic($salesforceClient);


        if (!empty($request->query->get("start")) && !empty($request->query->get("end"))) {
            $start = new \DateTime($request->query->get("start"));
            $end = new \DateTime($request->query->get("end"));
            $end->modify('last day of this month 23:59:59.999999');

            $externalExpenses->setPeriod($start, $end);
            $socialExpenses->setPeriod($start, $end);
            $outsourcings->setPeriod($start, $end);
            $sales->setPeriod($start, $end);
        }

        $formattedStats[$externalExpenses->getSlug()] = $externalExpenses->getResult();
        $formattedStats[$socialExpenses->getSlug()] = $socialExpenses->getResult();
        $formattedStats[$outsourcings->getSlug()] = $outsourcings->getResult();
        $formattedStats[$sales->getSlug()] = $sales->getResult();

        ini_set('memory_limit', $initialMemoryLimit);
        return $this->json([
            'message' => 'Success',
            'data' => $formattedStats
        ]);
    }


    #[Route('/stats/kerry/monthly_amount', name: 'monthly_stats')]
    public function statsMonthlyCumulations(Request $request, SalesforceClient $salesforceClient): JsonResponse
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

        $formattedStats[$caTotal->getSlug()] = $caTotal->getMonthlyCumulation();
        $formattedStats[$caTotalTPE->getSlug()] = $caTotalTPE->getMonthlyCumulation();
        $formattedStats[$caRivashop->getSlug()] = $caRivashop->getMonthlyCumulation();
        $formattedStats[$caRenouvellement->getSlug()] = $caRenouvellement->getMonthlyCumulation();
        $formattedStats[$caRedevance->getSlug()] = $caRedevance->getMonthlyCumulation();
        $formattedStats[$caProgrammeCroissance->getSlug()] = $caProgrammeCroissance->getMonthlyCumulation();
        $formattedStats[$caLicenceDirecte->getSlug()] = $caLicenceDirecte->getMonthlyCumulation();
        $formattedStats[$caComnat->getSlug()] = $caComnat->getMonthlyCumulation();
        $formattedStats[$caCmCic->getSlug()] = $caCmCic->getMonthlyCumulation();
        $formattedStats[$caClientRivalis->getSlug()] = $caClientRivalis->getMonthlyCumulation();
        $formattedStats[$caRivacentrale->getSlug()] = $caRivacentrale->getMonthlyCumulation();

        return $this->json([
            'message' => 'Success',
            'data' => $formattedStats
        ]);
    }
}
