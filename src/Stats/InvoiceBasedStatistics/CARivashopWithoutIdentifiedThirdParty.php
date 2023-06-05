<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;

/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDRivashop",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_RIVASHOP",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CARivashopWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_RIVASHOP';
    protected array $accounts = [707100, 707101];
}