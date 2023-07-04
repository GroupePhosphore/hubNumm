<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDTotalReseau",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_TOTAL_RESEAU",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CATotalReseauWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_TOTAL_RESEAU';
    protected array $accounts = [
        706009,
        706010,
        706011,
        706012,
        706013,
        706014,
        706015,
        706020,
        706021,
        706022,
        706023,
        706024,
        707100,
        707101,
    ];
}