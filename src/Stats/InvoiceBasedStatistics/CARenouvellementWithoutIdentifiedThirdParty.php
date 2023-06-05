<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDRenouvellement",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_RENOUVELLEMENT",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CARenouvellementWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_RENOUVELLEMENT';
    protected array $accounts = [706020, 706021];
}