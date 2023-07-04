<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDTotalTPE",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_TOTAL_TPE",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CATotalTpeWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_TOTAL_TPE';
    protected array $accounts = [ 706022, 706024, 706023, 706013 ];
}
