<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;


/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDLicenceDirecte",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_LICENCE_DIRECTE",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CALicenceDirecteWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_LICENCE_DIRECTE';
    protected array $accounts = [706011];
}
