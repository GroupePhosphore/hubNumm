<?php

namespace App\Stats\InvoiceBasedStatistics;

use App\Stats\InvoiceBasedStatistics\AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty;


/**
 * @OA\Schema(
 *  schema="StatisticWithoutIDProgrammeCroissance",
 *          @OA\Property(
 *              property="NOM_DU_CONSEILLER",
 *              type="object",
 *              @OA\Property(
 *                  property="CA_PROGRAMME_CROISSANCE",
 *                  type="float",
 *                  example=12.2
 *              )
 *  )
 * )
 */
class CAProgrammeCroissanceWithoutIdentifiedThirdParty extends AbstractInvoiceBasedStatisticWithoutIdentifiedThirdParty
{
    protected string $slug = 'CA_PROGRAMME_CROISSANCE';
    protected array $accounts = [706013];
}