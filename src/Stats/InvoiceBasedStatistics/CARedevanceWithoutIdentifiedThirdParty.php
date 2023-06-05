<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;


/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDRedevance",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_REDEVANCE",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CARedevanceWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_REDEVANCE';
    protected array $accounts = [ 706009, 706010, 706014, 706015 ];
}