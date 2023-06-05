<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDComnat",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_COMNAT",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CAComnatWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_COMNAT';
    protected array $accounts = [706012];
}