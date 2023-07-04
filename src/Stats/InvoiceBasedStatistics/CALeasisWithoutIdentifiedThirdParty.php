<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;


/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDLeasis",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_LEASIS",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CALeasisWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_LEASIS';
    protected array $accounts = [ 706100, 707100, 707200 ];
}
