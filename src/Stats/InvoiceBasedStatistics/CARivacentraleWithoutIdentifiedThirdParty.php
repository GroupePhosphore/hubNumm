<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;


/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDRivacentrale",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_RIVACENTRALE",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CARivacentraleWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_RIVACENTRALE';
    protected array $accounts = [ 706017, 706018 ];
}
