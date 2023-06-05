<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDCMCIC",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_CM_CIC",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CACMCICWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_CM_CIC';
    protected array $accounts = [706022];
}